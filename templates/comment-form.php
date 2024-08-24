<?php
/**
 * @var $errors string
 * @var $commentData array
 */
?>

<?php // Report any errors in a bullet-point list ?>
<?php if ($errors): ?>
	<div class="error box comment-margin">
		<ul>
			<?php foreach ($errors as $error): ?>
				<li><?php echo $error ?></li>
			<?php endforeach ?>
		</ul>
	</div>
<?php endif ?>

<h3><?php echo $get_word['add-comment'] ?></h3>

<form
	action="view-post.php?action=add-comment&amp;post_id=<?php echo $postId?>"
	method="post"
	class="comment-form user-form"
>
	<div>
		<label for="comment-name">
		<?php echo $get_word['name'] ?>:
		</label>
		<input
			type="text"
			id="comment-name"
			name="comment-name"
			value="<?php echo htmlspecialchars($commentData['name']) ?>"
		/>
	</div>
	<div>
		<label for="comment-website">
		<?php echo $get_word['website'] ?>:
		</label>
		<input
			type="text"
			id="comment-website"
			name="comment-website"
			value="<?php echo htmlspecialchars($commentData['website']) ?>"
		/>
	</div>
	<div>
		<label for="comment-text">
		<?php echo $get_word['comment'] ?>:
		</label>
		<textarea
			id="comment-text"
			name="comment-text"
			rows="8"
			cols="70"
		><?php echo htmlspecialchars($commentData['text']) ?></textarea>
	</div>

	<div>
		<button type="submit" value="Submit comment"><?php echo $get_word['submit-comment'] ?></button>
	</div>
</form>
