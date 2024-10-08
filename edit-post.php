<?php
require_once 'lib/common.php';
require_once 'lib/edit-post.php';
require_once 'lib/view-post.php';

session_start();

// Don't let non-auth users see this screen
if (!isLoggedIn())
{
	redirectAndExit('index.php');
}

// Empty defaults
$title = $body = '';

// Init database and get handle
$pdo = getPDO();

$postId = null;
if (isset($_GET['post_id']))
{
	$post = getPostRow($pdo, $_GET['post_id']);
	if ($post)
	{
		$postId = $_GET['post_id'];
		$title = $post['title'];
		$body = $post['body'];
	}
}

// Handle the post operation here
$errors = array();
if ($_POST && $_POST['action'] === 'Submit')
{
	// Validate these first
	$title = $_POST['post-title'];
	if (!$title)
	{
		$errors[] = 'The post must have a title';
	}
	$body = $_POST['post-body'];
	if (!$body)
	{
		$errors[] = 'The post must have a body';
	}

	if (!$errors)
	{
		$pdo = getPDO();
		// Decide if we are editing or adding
		if ($postId)
		{
			editPost($pdo, $title, $body, $postId);
		}
		else
		{
			if (isset($_GET['xltn_id'])) {
				$xltnId = $_GET['xltn_id'];
			} else {
				$xltnId = NULL;
			}
			
			$userId = getAuthUserId($pdo);
			$postId = addPost($pdo, $title, $body, $userId, $xltnId);

			if ($postId === false)
			{
				$errors[] = 'Post operation failed';
			}
			else if ($xltnId != NULL)
			{
				addXltnPost($pdo, $xltnId, $postId);
			}
		}
	}

	if (!$errors)
	{
		redirectAndExit('edit-post.php?post_id=' . $postId);
	}
}

?>
<html>
	<head>
		<title>A blog application | New post</title>
		<?php require 'templates/head.php' ?>
	</head>
	<body>
		<?php require 'templates/top-menu.php' ?>

		<?php if (isset($_GET['post_id'])): ?>
			<h1><?php echo $get_word['edit-post'] ?></h1>
		<?php else: ?>
			<h1><?php echo $get_word['new-post'] ?></h1>
		<?php endif ?>

		<?php if ($errors): ?>
			<div class="error box">
				<ul>
					<?php foreach ($errors as $error): ?>
						<li><?php echo $error ?></li>
					<?php endforeach ?>
				</ul>
			</div>
		<?php endif ?>

		<form method="post" class="post-form user-form">
			<div>
				<label for="post-title"><?php echo $get_word['post-title'] ?>:</label>
				<input
					id="post-title"
					name="post-title"
					type="text"
					value="<?php echo htmlspecialchars($title) ?>"
				/>
			</div>
			<div>
				<label for="post-body"><?php echo $get_word['post-body'] ?>:</label>
				<textarea
					id="post-body"
					name="post-body"
					rows="12"
					cols="70"
					><?php echo htmlspecialchars($body) ?></textarea>
			</div>
			<div>
				<label for="image-input"><?php echo $get_word['image'] ?>:</label>
				<input type="file" id="image-input" accept="image/*">
			</div>
			<div>
				<button type="submit" name="action" value="Submit"><?php echo $get_word['submit'] ?></button>
				<a href="index.php"><?php echo $get_word['cancel'] ?></a>
			</div>
		</form>
		<div id="preview">
			<h2><?php echo $get_word['rendered-markdown'] ?></h2>
			<div id="post-preview"></div>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    	<script src="assets/markdown.js"></script>
		<script src="assets/upload.js"></script>
	</body>
</html>