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
?>

<div class="page aiot-blog" data-aos="fade-in" data-aos-duration="2000">
	<div class="mm-container">
		<?php theBreadcrumb() ?>
		<div class="aiot-header">
			<div class="aiot-title">
				<?php the_title(); ?>		
			</div>
		</div>
		<div class="aiot-content">
			<?php the_content(); ?>
		</div>
	</div>
</div>