<?php 
require_once 'lib/common.php';

if (isset($_GET['lang'])) {
	$_SESSION['lang'] = $_GET['lang'];
	if (isset($_GET['post_id']) && $post['lang'] != $_SESSION['lang']) {
		$_GET['post_id'] = $post['xltn_post_id'];
	}
	refresh();
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
				<a href="<?php echo getLanguageSwitcherLink('zh') ?>">中文</a>
			<?php elseif ($lang === 'zh'): ?>
				<a href="<?php echo getLanguageSwitcherLink('en') ?>">English</a>
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