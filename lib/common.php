<?php
require_once 'vendor/parsedown/Parsedown.php';

/**
 * Gets the root path of the project
 * 
 * @return string
 */
function getRootPath()
{
	return realpath(__DIR__ . '/..');
}

/**
 * Gets the full path for the database file
 * 
 * @return string
 */
function getDatabasePath()
{
	return getRootPath() . '/data/data.sqlite';
}

/**
 * Gets the DSN for the SQLite connection
 * 
 * @return string
 */
function getDsn()
{
	return 'sqlite:' . getDatabasePath();
}

/**
 * Gets the PDO object for database acccess
 * 
 * @return \PDO
 */
function getPDO()
{
	$pdo = new PDO(getDsn());

	// Foreign key constraints need to be enabled manually in SQLite
	$result = $pdo->query('PRAGMA foreign_keys = ON');
	if ($result === false)
	{
		throw new Exception('Could not turn on foreign key constraints');
	}

	return $pdo;
}

function convertSqlDate($sqlDate)
{
	/* @var $date DateTime */
	$date = DateTime::createFromFormat('Y-m-d H:i:s', $sqlDate);

	return $date->format('d M Y, H:i');
}

function getSqlDateForNow()
{
	return date('Y-m-d H:i:s');
}

/**
 * Gets a list of posts in reverse order
 * 
 * @param PDO $pdo
 * @param string $lang
 * @return array
 */
function getAllPosts(PDO $pdo, string $lang)
{
	$sql = "
		SELECT
			id, title, created_at, body, lang, xltn_post_id,
			(SELECT COUNT(*) FROM comment WHERE comment.post_id = post.id) comment_count
		FROM
			post
		WHERE
			lang = :lang
		ORDER BY
			created_at DESC
	";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['lang' => $lang]);
	if ($stmt === false)
	{
		throw new Exception('There was a problem running this query');
	}

	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Converts unsafe text to safe, paragraphed, HTML
 * 
 * @param string $text
 * @return string
 */
function convertNewlinesToParagraphs($text)
{
	$escaped = htmlspecialchars($text);

	return '<p>' . str_replace("\n", "</p><p>", $escaped) . '</p>';
}

function renderMarkdown($markdown) {
    $parsedown = new Parsedown();
	$parsedown->setSafeMode(true);
    return $parsedown->text($markdown);
}

function get_lang() {
	if (!isset($_SESSION['lang'])) {
		$_SESSION['lang'] = 'en';
	}
	return $_SESSION['lang'];
}

function getLanguageSwitcherLink($newLang) {
    $queryParams = $_GET;
    $queryParams['lang'] = $newLang; // Update or add the 'lang' parameter
    return '?' . http_build_query($queryParams);
}

function refresh() {
    $queryParams = $_GET;
	unset($queryParams['lang']);

	$relativeUrl = $_SERVER['PHP_SELF'];
	$host = $_SERVER['HTTP_HOST'];
	if ($queryParams) {
		header('Location: http://' . $host . $relativeUrl . '?' . http_build_query($queryParams));
		exit();
	} else {
		header('Location: http://' . $host . $relativeUrl);
		exit();
	}
	
}

function redirectAndExit($script)
{
	$relativeUrl = $_SERVER['PHP_SELF'];
    $urlFolder = substr($relativeUrl, 0, strrpos($relativeUrl, '/') + 1);

	$host = $_SERVER['HTTP_HOST'];
	header('Location: http://' . $host . $urlFolder . $script);
	exit();
}

/**
 * Returns all the comments for the specified post
 * 
 * @param PDO $pdo
 * @param integer $postId
 * return array
 */
function getCommentsForPost(PDO $pdo, $postId)
{
	$sql = "
		SELECT
			id, name, text, created_at, website
		FROM
			comment
		WHERE
			post_id = :post_id
		ORDER BY
			created_at DESC
	";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(
		array('post_id' => $postId, )
	);

	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function tryLogin(PDO $pdo, $username, $password)
{
	$sql = "
		SELECT
			password
		FROM
			user
		WHERE
			username = :username
	";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(
		array('username' => $username, )
	);

	// Get the hash from this row, and use the third-party hashing library to check it
	$hash = $stmt->fetchColumn();
	$success = password_verify($password, $hash);

	return $success;
}

function login($username)
{
	$_SESSION['logged_in_username'] = $username;
}

function logout()
{
	unset($_SESSION['logged_in_username']);
}

function getAuthUser()
{
	return isLoggedIn() ? $_SESSION['logged_in_username'] : null;
}

function isLoggedIn()
{
	return isset($_SESSION['logged_in_username']);
}

/**
 * Looks up the user_id for the current auth user
 */
function getAuthUserId(PDO $pdo)
{
	// Reply with null if there is no logged-in user
	if (!isLoggedIn())
	{
		return null;
	}

	$sql = "
		SELECT id FROM user WHERE username = :username
	";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(
		array(
			'username' => getAuthUser()
		)
	);

	return $stmt->fetchColumn();
}
