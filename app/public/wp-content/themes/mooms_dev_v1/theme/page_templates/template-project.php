<?php
/**
 * Template Name: Project
 *
 * App Layout: layouts/app.php
 *
 * This is the template that is used for displaying 404 errors.
 *
 * @package WPEmergeTheme
 */
?>
<div class="page" data-aos="fade-in" data-aos-duration="2000">
    <div class="mm-container">
        <div class="head-child-page">
            <div class="page-thumbnail">
                <figure class="media border-radius-4">
                    <img src="<?php echo getPostThumbnailUrl(get_the_ID()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                </figure>
            </div>

            <div class="page-title-breadcrumb">
                <?php
                get_template_part('template-parts/breadcrumb');
                echo '<h1 class="page-title">' . get_the_title() . '</h1>';
                ?>
            </div>
        </div>

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
            $args = [
                'post_type' => 'project',
                'posts_per_page' => -1,
            ];
            $query = new WP_Query($args);
            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
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
