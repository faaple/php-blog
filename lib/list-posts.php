<?php
require_once 'lib/view-post.php';

/**
 * Tries to delete the specified post
 * 
 * We first delete the comments attached to the post, and then delete the post itself
 * 
 * @param PDO $pdo
 * @param integer $postId
 * @return boolean Returns true on successful deletion
 * @throws Exception
 */
function deletePost(PDO $pdo, $postId)
{
	$post = getPostRow($pdo, $postId);

	$sqls = array(
		// Remove xltn link
		"UPDATE
			post
		SET 
			xltn_post_id = NULL
		WHERE
			id = :id OR id = :xltn_id",
		// Delete comments first, to remove the foreign key objection
		"DELETE FROM
			comment
		WHERE
			post_id = :id OR post_id = :xltn_id",
		// Now we can delete the post
		"DELETE FROM
			post
		WHERE
			id = :id OR id = :xltn_id",
	);

	foreach ($sqls as $sql)
	{
		$stmt = $pdo->prepare($sql);
		if ($stmt === false)
		{
			throw new Exception('There was a problem preparing this query');
		}

		$result = $stmt->execute(
			array('id' => $postId, 'xltn_id' => $post['xltn_post_id'], )
		);

		// Don't continue if something went wrong
		if ($result === false)
		{
			break;
		}
	}

	return $result !== false;
}
