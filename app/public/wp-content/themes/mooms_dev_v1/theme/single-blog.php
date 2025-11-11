<?php
/**
 * App Layout: layouts/app.php
 *
 * This is the template that is used for displaying all posts by default.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPEmergeTheme
 */

 $postID = get_the_ID();
 $category = get_the_terms($postID, 'blog_cat');
?>

<div class="page aiot-blog" data-aos="fade-in" data-aos-duration="2000">
	<div class="mm-container-single">
		<?php theBreadcrumb() ?>
		<div class="aiot-header border-line-bottom">
			<div class="top-date-category">
				<div class="date"><?= get_the_date('Y.m.d', $postID); ?></div>
				<?php if ($category): ?>
					<div class="category"><?php echo $category[0]->name; ?></div>
				<?php endif; ?>
			</div>

			<h1 class="aiot-header__title">
				<?php the_title(); ?>		
			</h1>
		</div>
		<div class="aiot-header__content">
			<?php the_content(); ?>
		</div>
	</div>
</div>