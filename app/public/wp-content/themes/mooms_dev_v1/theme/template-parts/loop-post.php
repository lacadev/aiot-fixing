<?php
global $post;
$postID = $post->ID;
$thumbnail = getPostThumbnailUrl($postID);
$title = get_the_title($postID);
$category = get_the_terms($postID, 'blog_cat');
?>
<div class="item-post">
	<a href="<?= get_the_permalink($postID); ?>">
		<div class="inner">
			<figure class="media">
				<img src="<?=$thumbnail;?>" alt="<?= $title; ?>" loading="lazy">
			</figure>
			<div class="content">
				<div class="top">
					<div class="date"><?= get_the_date('Y.m.d', $postID); ?></div>
					<?php if ($category): ?>
						<div class="category"><?php echo $category[0]->name; ?></div>
					<?php endif; ?>
				</div>
				<?php
				if ($title):
					echo '<h4 class="title-post">' . $title . '</h4>';
				endif;
				?>
			</div>
		</div>
	</a>
</div>
