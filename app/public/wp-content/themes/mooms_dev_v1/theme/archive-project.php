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
$term = get_queried_object();
?>

<div class="page-listing">
	<div class="page-header border-line-bottom">
		<div class="mm-container">
			<?php
			get_template_part('template-parts/breadcrumb');
			echo '<h1 class="page-title">' . get_the_title() . '</h1>';
			?>
		</div>
	</div>

	<div class="mm-container">
		<div class="select-box">
			<select class="js-example-basic-multiple" name="states[]" multiple="multiple">
				<?php
				$terms = get_terms([
					'taxonomy' => 'project_cat',
					'hide_empty' => false,
				]);
				foreach ($terms as $term) {
					echo '<option value="' . $term->slug . '">' . $term->name . '</option>';
				}
				?>
			</select>
		</div>

		<div class="list-project">
			<?php
			if (have_posts()) :
				while (have_posts()) : the_post();
					?>
					<?php
						// Prepare category slugs for filtering via JS
						$project_terms = get_the_terms(get_the_ID(), 'project_cat');
						$term_slugs = (!empty($project_terms) && !is_wp_error($project_terms)) ? wp_list_pluck($project_terms, 'slug') : [];
						$data_value = esc_attr(implode(' ', array_map('sanitize_title', $term_slugs)));
					?>
					<div class="project-item" data-value="<?php echo $data_value; ?>">
						<div class="project-item__content">
							<div class="project-item__tags">
								<?php $system_destination = getPostMeta('system_destination'); ?>
								<?php foreach ($system_destination as $item) { ?>
									<span class="project-item__tag"><?php echo $item['content']; ?></span>
								<?php } ?>
							</div>

							<h2 class="project-item__title">
								<a href="<?php the_permalink(); ?>" class="project-item__link"><?php the_title(); ?></a>
							</h2>

							<div class="project-item__description">
								<?php echo apply_filters('the_content', getPostMeta('description')); ?>
							</div>
						</div>

						<?php
						$color = getPostMeta('color');
						?>
						<div class="project-item__image" style="--bg-color: <?php echo $color ? $color : ''; ?>;">
							<a href="<?php the_permalink(); ?>" class="project-item__image-link">
								<figure class="project-item__figure">
									<img src="<?php echo getPostThumbnailUrl(get_the_ID()) ?>" alt="<?php the_title(); ?>" loading="lazy">
								</figure>
							</a>
						</div>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
			endif;
			thePagination();
			?>
		</div>
	</div>
</div>

