<?php 
if (isset($_GET['lang'])) {
	$_SESSION['lang'] = $_GET['lang'];
} else if (!isset($_SESSION['lang'])) {
	$_SESSION['lang'] = 'en';
}
$lang = $_SESSION['lang'];
$get_word = include "langs/{$lang}.php";

?>
<div class="top-menu">
	<div class="menu-options">
		<span class="language-toggle">
			<?php if ($lang === 'en'): ?>
				<a href="?lang=zh">中文</a>
			<?php elseif ($lang === 'zh'): ?>
				<a href="?lang=en">English</a>
			<?php endif ?>
			||
		</span>
		<?php if (isLoggedIn()): ?>
			<a href="index.php"><?php echo $get_word['home'] ?></a>
			|
			<a href="list-posts.php"><?php echo $get_word['all-posts'] ?></a>
			|
			<a href="edit-post.php"><?php echo $get_word['new-post'] ?></a>
			||
			<?php echo $get_word['greet'] . ' ' . htmlspecialchars(getAuthUser()) ?>
			<a href="logout.php"><?php echo $get_word['logout'] ?></a>
		<?php else: ?>
			<a href="login.php"><?php echo $get_word['login'] ?></a>
		<?php endif ?>
	</div>
</div>