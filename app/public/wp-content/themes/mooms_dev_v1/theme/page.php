<?php
/**
 * App Layout: layouts/app.php
 *
 * This is the template that is used for displaying all pages by default.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPEmergeTheme
 */

global $post;
$parentPage = get_post($post->post_parent);
$parentTitle = get_the_title($parentPage->ID);
$parentLink = get_the_permalink($parentPage->ID);
?>
<div class="page" data-aos="fade-in" data-aos-duration="2000">
	<?php
	if (!is_front_page() && is_page()):
		if ($post->post_parent):
			?>
			<!-- CHILD PAGE -->
			<div class="mm-container-fluid">
				<div class="head-child-page">
					<div class="page-thumbnail">
						<figure class="media border-radius-4">
							<img src="<?php echo getPostThumbnailUrl(get_the_ID()); ?>"
								alt="<?php echo get_the_title(); ?>"
								loading="lazy">
						</figure>
					</div>
					<div class="page-title-breadcrumb">
						<?php
						get_template_part('template-parts/breadcrumb');
						echo '<h1 class="page-title">' . get_the_title() . '</h1>';
						?>
					</div>
				</div>

				<div class="body-child-page">
					<!-- <button><?php // echo $parentTitle . ' ' . __('list','gaumap'); ?></button> -->
					<aside class="page-sidebar">
						<div class="parent-page">
							<?= '<a href="' . $parentLink . '" class="parent-link">' . $parentTitle . '</a>';
							?>
						</div>
						<div class="children-page">
							<!-- list children page and actived current page -->
							<ul>
								<?php
							// Build desired order from parent page meta (support both association and complex formats)
							$desiredOrderIds = [];
							if (function_exists('carbon_get_post_meta')) {
								$rows = carbon_get_post_meta($post->post_parent, 'child_pages_order');
								if (is_array($rows)) {
									foreach ($rows as $row) {
										if (is_array($row) && !empty($row['child_page'])) {
											$desiredOrderIds[] = (int) $row['child_page'];
										} elseif (is_array($row) && !empty($row['id'])) { // association format
											$desiredOrderIds[] = (int) $row['id'];
										} elseif (is_numeric($row)) { // legacy scalar
											$desiredOrderIds[] = (int) $row;
										}
									}
									$desiredOrderIds = array_values(array_unique(array_filter($desiredOrderIds)));
								}
							}

							$queryArgs = [
								'post_type'      => 'page',
								'posts_per_page' => -1,
								'post_parent'    => $post->post_parent,
							];

							// Ensure language matches current one (Polylang/WPML)
							if (function_exists('pll_current_language')) {
								$queryArgs['lang'] = pll_current_language('slug');
							} elseif (defined('ICL_LANGUAGE_CODE')) {
								$queryArgs['lang'] = ICL_LANGUAGE_CODE;
							}

							if (!empty($desiredOrderIds)) {
								$queryArgs['post__in'] = $desiredOrderIds;
								$queryArgs['orderby'] = 'post__in';
							} else {
								$queryArgs['orderby'] = 'date';
								$queryArgs['order'] = 'DESC';
							}

							$q = new WP_Query($queryArgs);
							// Lưu ID của page hiện tại trước khi vào loop (vì the_post() sẽ thay đổi global $post)
							$current_viewing_page_id = get_queried_object_id();
							if ($q->have_posts()):
								while ($q->have_posts()): $q->the_post();
									$loop_page_id = get_the_ID(); // ID của page trong loop
									$active = ($loop_page_id === $current_viewing_page_id) ? ' class="active"' : '';
									echo '<li' . $active . '><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
								endwhile;
								wp_reset_postdata();
							endif;
							?>
							</ul>
						</div>
					</aside>
					<div class="page-content">
						<?php the_content(); ?>
					</div>
				</div>
			</div>

			<?php
		else:
			// PARENT PAGE & PAGE
			?>
			<div class="page-header border-line-bottom">
				<div class="mm-container">
					<?php
					get_template_part('template-parts/breadcrumb');
					echo '<h1 class="page-title">' . get_the_title() . '</h1>';
					?>
				</div>
			</div>
			<!-- PARENT PAGE -->
			<div class="mm-container">
				<div class="head-parent-page">
					<?php
					if (has_post_thumbnail()):
						?>
						<div class="page-thumbnail">
							<figure class="media border-radius-4">
								<img src="<?php echo getPostThumbnailUrl(get_the_ID()); ?>"
									alt="<?php echo get_the_title(); ?>"
									loading="lazy">
							</figure>
						</div>
						<?php
					endif;
					?>
					<?php
					if (get_the_excerpt()):
						?>
						<div class="page-excerpt">
							<?php echo apply_filters('the_content', get_the_excerpt()); ?>
						</div>
						<?php
					endif;
					?>

					<div class="page-content">
						<?php the_content(); ?>
					</div>
				</div>
				<!--  List child pages -->
				<?php
				$selected = function_exists('carbon_get_post_meta') ? carbon_get_post_meta(get_the_ID(), 'child_pages_order') : [];
				$ordered_child_ids = [];
				if (!empty($selected) && is_array($selected)) {
					foreach ($selected as $item) {
						if (isset($item['id'])) {
							$ordered_child_ids[] = (int) $item['id'];
						}
					}
				}

				if (!empty($ordered_child_ids)) {
					$pages = get_posts([
						'post_type' => 'page',
						'post_status' => 'publish',
						'post__in' => $ordered_child_ids,
						'orderby' => 'post__in',
						'posts_per_page' => -1,
					]);
				} else {
					$pages = get_pages([
						'child_of' => $post->ID,
						'sort_column' => 'menu_order',
						'sort_order' => 'ASC'
					]);
				}

				if ($pages):
					?>
					<div class="child-pages">
						<ul>
							<?php
							foreach ($pages as $page):
								$pageThumbnail = getPostThumbnailUrl($page->ID);
								$pageTitle = get_the_title($page->ID);
								$pageLink = get_permalink($page->ID);
								$pageExcerpt = get_the_excerpt($page->ID);
								?>
								<li class="child-page">

									<div class="inner">
										<div class="inner-media">
											<figure class="media">
												<img src="<?= $pageThumbnail; ?>"
													alt="<?= $pageTitle; ?>"
													loading="lazy">
											</figure>
										</div>

										<div class="inner-content">
											<h3 class="title"><?= $pageTitle; ?></h3>
											<p class="excerpt"><?= $pageExcerpt; ?>
											</p>
											<a class="btn-url"
												href="<?= $pageLink; ?>">
												<span class="_circle"></span>
												<span
													class="_label"><?php _e('View More', 'gaumap'); ?></span>
												<span class="_icon">
													<div class="mm-svg"></div>
												</span>
											</a>
										</div>

									</div>

								</li>
								<?php
							endforeach;
							?>
						</ul>
					</div>
					<?php
				endif;
				?>
			</div>

			<?php
		endif;
	endif;
	?>

	<!-- Trang chủ -->
	<?php
	if (is_front_page()):
		?>
		<div class="mm-container">
			<div class="page-content">
				<?php the_content(); ?>
			</div>
		</div>
	<?php
	endif;
	?>
</div>