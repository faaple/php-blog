<?php
require_once 'lib/common.php';
require_once 'lib/list-posts.php';
require_once 'lib/view-post.php';

session_start();

// Don't let non-auth users see this screen
if (!isLoggedIn())
{
	redirectAndExit('index.php');
}

if ($_POST)
{
	$deleteResponse = $_POST['delete-post'];
	if ($deleteResponse)
	{
		$keys = array_keys($deleteResponse);
		$deletePostId = $keys[0];
		if ($deletePostId)
		{
			deletePost(getPDO(), $deletePostId);
			redirectAndExit('list-posts.php');
		}
	}
}

// Connect to the database, run a query
$pdo = getPDO();
$posts = getAllPosts($pdo, 'zh');

?>
<!DOCTYPE html>
<html>
	<head>
		<title>A blog application | Blog posts</title>
		<?php require 'templates/head.php' ?>
	</head>
	<body>
		<?php require 'templates/top-menu.php' ?>

		<h1><?php echo $get_word['post-list'] ?></h1>

		<p>You have <?php echo count($posts) ?> posts.

		<form method="post">
			<table id="post-list">
				<thead>
					<tr>
						<th><?php echo $get_word['post-title'] ?></th>
						<th><?php echo $get_word['creation-date'] ?></th>
						<th><?php echo $get_word['Comments'] ?></th>
						<th />
						<th />
					</tr>
				</thead>
				<tbody>
					<?php foreach ($posts as $post): ?>
						<tr>
							<?php $xltn_post = getPostRow($pdo, $post['xltn_post_id']) ?>
							<?php if ($_SESSION['lang'] == 'zh'): ?>
								<td><?php echo htmlspecialchars($post['title']) ?></td>
								<td><?php echo convertSqlDate($post['created_at']) ?></td>
								<td><?php echo $post['comment_count'] ?></td>
								<td><a href="edit-post.php?post_id=<?php echo $post['id'] ?>"><?php echo $get_word['edit'] ?></a></td>
							<?php else: ?>
								<td><?php echo htmlspecialchars($xltn_post['title']) ?></td>
								<td><?php echo convertSqlDate($xltn_post['created_at']) ?></td>
								<td><?php echo $xltn_post['comment_count'] ?></td>
								<td><a href="edit-post.php?post_id=<?php echo $post['xltn_post_id'] ?>"><?php echo $get_word['edit'] ?></a></td>
							<?php endif ?>
							<td>
								<button
									type="submit"
									name="delete-post[<?php echo $post['id']?>]"
									value="Delete"
								><?php echo $get_word['delete'] ?></button>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</form>
	</body>
</html>
