<?php
/**
 * @var $pdo PDO
 * @var $postId integer
 * @var $commentCount integer
 */
?>
<form
	action="view-post.php?action=delete-comment&amp;post_id=<?php echo $postId?>&amp;"
	method="post"
	class="comment-list"
>
	<h3><?php echo $commentCount . ' ' . $get_word['comments'] ?></h3>

	<?php foreach (getCommentsForPost($pdo, $postId) as $comment): ?>
		<div class="comment">
			<div class="comment-meta">
				Comment from
				<a href="https://<?php echo htmlspecialchars($comment['website']) ?>">
					<?php echo htmlspecialchars($comment['name']) ?>
				</a>
				on
				<?php echo convertSqlDate($comment['created_at']) ?>
				<?php if (isLoggedIn()): ?>
					<input
						type="submit"
						name="delete-comment[<?php echo $comment['id'] ?>]"
						value="Delete"
					/>
				<?php endif ?>
			</div>
			<div class="comment-body">
				<?php // This is already escaped ?>
				<?php echo convertNewlinesToParagraphs($comment['text']) ?>
			</div>
		</div>
	<?php endforeach ?>
</form>
