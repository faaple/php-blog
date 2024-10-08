<?php

function addPost(PDO $pdo, $title, $body, $userId, $xltnId)
{
	// Prepare the insert query
	$sql = "
		INSERT INTO
			post
			(title, body, user_id, created_at, lang, xltn_post_id)
			VALUES
			(:title, :body, :user_id, :created_at, :lang, :xltn_post_id)
	";
	$stmt = $pdo->prepare($sql);
	if ($stmt === false)
	{
		throw new Exception('Could not prepare post insert query');
	}

	// Now run the query, with these parameters
	$result = $stmt->execute(
		array(
			'title' => $title,
			'body' => $body,
			'user_id' => $userId,
			'created_at' => getSqlDateForNow(),
			'lang' => get_lang(),
			'xltn_post_id' => $xltnId,
		)
	);
	if ($result === false)
	{
		throw new Exception('Could not run post insert query');
	}

	// Finally let's look up the automatically generated primary key
	$sqlSeq = "SELECT seq FROM SQLITE_SEQUENCE WHERE name = 'post'";
	$stmtSeq = $pdo->query($sqlSeq);

	return $stmtSeq->fetchColumn();
}

function addXltnPost(PDO $pdo, $postId, $xltnId) {
	// Prepare the update query
	$sql = "
		UPDATE
			post
		SET
			xltn_post_id = :xltn_post_id
		WHERE
			id = :post_id
	";
	$stmt = $pdo->prepare($sql);
	if ($stmt === false)
	{
		throw new Exception('Could not prepare post update query');
	}

	// Now run the query, with these parameters
	$result = $stmt->execute(
		array(
			'xltn_post_id' => $xltnId,
			'post_id' => $postId,
		)
	);
	if ($result === false)
	{
		throw new Exception('Could not run post update query');
	}

	return true;
}

function editPost(PDO $pdo, $title, $body, $postId)
{
	// Prepare the update query
	$sql = "
		UPDATE
			post
		SET
			title = :title,
			body = :body
		WHERE
			id = :post_id
	";
	$stmt = $pdo->prepare($sql);
	if ($stmt === false)
	{
		throw new Exception('Could not prepare post update query');
	}

	// Now run the query, with these parameters
	$result = $stmt->execute(
		array(
			'title' => $title,
			'body' => $body,
			'post_id' => $postId,
		)
	);
	if ($result === false)
	{
		throw new Exception('Could not run post update query');
	}

	return true;
}