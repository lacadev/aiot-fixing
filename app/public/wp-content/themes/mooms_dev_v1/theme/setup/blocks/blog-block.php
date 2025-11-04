<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;
$addLabels = array(
    'plural_name' => 'media',
    'singular_name' => 'media',
);

Block::make(__('Block blog', 'gaumap')) 
->add_fields([
    Field::make('separator', 'blog_spt', __('BLOCK BLOG', 'gaumap'))->set_width(70),
    Field::make('html', 'blog_guide') ->set_width(30)
        ->set_html('<button class="blog-guide button">Hướng dẫn nhập</button>'),
    //Title
    Field::make('text', 'blog_title', __('', 'gaumap')) ->set_width(60)
        ->set_attribute('placeholder', 'Enter block title')
        ->set_attribute('data-step', '1')
        ->set_attribute('data-intro', 'Nhập tiêu đề của block'),
    //URL
    Field::make('text', 'blog_page_url', __('', 'gaumap')) ->set_width(40)
        ->set_attribute('placeholder', 'Enter blog page URL ')
        ->set_attribute('data-step', '2')
        ->set_attribute('data-intro', 'Nhập URL của trang blog'),

    //Media
    Field::make('text', 'article_spt', __('', 'gaumap')) ->set_width(20)
        ->set_default_value('Choose article')
        ->set_attribute('readOnly', 'true')
        ->set_attribute('data-step', '3')
        ->set_attribute('data-intro', 'Chọn kiểu media (Slider, ảnh hoặc video)'),

    // type media
    Field::make('select', 'display_type', __('', 'gaumap')) ->set_width(20)
        ->set_default_value('auto')
        ->set_options([
            'auto' => __('Auto'),
            'manual' => __('Manual'),
        ]),

    Field::make('separator', 'auto_spt', __('Automatically display 3 latest posts', 'gaumap')) ->set_width(60)
        ->set_conditional_logic([
            'relation' => 'AND',
            ['field' => 'display_type', 'value' => 'auto', 'compare' => '='],
        ]),

    Field::make('separator', 'manual_spt', __('Select the posts to display', 'gaumap')) ->set_width(60)
        ->set_conditional_logic([
            'relation' => 'AND',
            ['field' => 'display_type', 'value' => 'manual', 'compare' => '='],
        ]),

    Field::make('association','manual_blog', __('','gaumap')) ->set_width(70)
        ->set_types([
            [
                'type'      => 'post',
                'post_type' => 'blog',
            ]
        ])
        ->set_conditional_logic([
            'relation' => 'AND',
            ['field' => 'display_type', 'value' => 'manual', 'compare' => '='],
        ]),
    
    
])
->set_render_callback(function ($fields, $attributes, $inner_blocks) {
    $title = !empty($fields['blog_title']) ? esc_html($fields['blog_title']) : '';
    $url = !empty($fields['blog_page_url']) ? esc_url($fields['blog_page_url']) : '';
    $type = !empty($fields['display_type']) ? $fields['display_type'] : '';
    $blogs = !empty($fields['manual_blog']) ? $fields['manual_blog'] : '';
?>
    <section class="block-blog full-width">
        <div class="mm-container">
                <?php if ($title): ?>
                    <h2 class="block-title"><?= $title; ?></h2>
                <?php endif; ?>

                <div class="list-items">
                <?php
                        if ($type == 'auto') :
                            $post_query = new WP_Query([
                                'post_type' => 'blog',
                                'posts_per_page' => 3,
                                'post_status' => 'publish',
                                'orderby' => 'date',
                                'order' => 'DESC',
                            ]);
                            if ($post_query->have_posts()) :
                                while ($post_query->have_posts()) : $post_query->the_post();
                                    get_template_part('template-parts/loop','post');
                                endwhile;
                            endif;
                            wp_reset_postdata();
                            wp_reset_query();
                        elseif ($type == 'manual') :
                            foreach ($blogs as $blog) :
                                get_template_part('template-parts/loop','post');
                            endforeach;
                        endif;
					?>
                </div>
                
                <?php if ($url): ?>
                    <a class="btn-url" href="<?= $url; ?>">
                        <span class="_circle"></span>
                        <span class="_label"><?= _e('View More','gaumap');?></span>
                        <span class="_icon">
                            <div class="mm-svg"></div>
                        </span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php
});
