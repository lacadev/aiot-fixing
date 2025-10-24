<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;
$addLabels = array(
    'plural_name' => 'media',
    'singular_name' => 'media',
);

Block::make(__('Block table', 'gaumap')) 
->add_fields([
    // Field::make('separator', 'blog_spt', __('BLOCK BLOG', 'gaumap'))->set_width(70),
    // Field::make('html', 'blog_guide') ->set_width(30)
    //     ->set_html('<button class="blog-guide button">Hướng dẫn nhập</button>'),
    // //Title
    // Field::make('text', 'blog_title', __('', 'gaumap')) ->set_width(60)
    //     ->set_attribute('placeholder', 'Enter block title')
    //     ->set_attribute('data-step', '1')
    //     ->set_attribute('data-intro', 'Nhập tiêu đề của block'),
    // //URL
    // Field::make('text', 'blog_page_url', __('', 'gaumap')) ->set_width(40)
    //     ->set_attribute('placeholder', 'Enter blog page URL ')
    //     ->set_attribute('data-step', '2')
    //     ->set_attribute('data-intro', 'Nhập URL của trang blog'),

    // //Media
    // Field::make('text', 'article_spt', __('', 'gaumap')) ->set_width(20)
    //     ->set_default_value('Choose article')
    //     ->set_attribute('readOnly', 'true')
    //     ->set_attribute('data-step', '3')
    //     ->set_attribute('data-intro', 'Chọn kiểu media (Slider, ảnh hoặc video)'),

    // // type media
    // Field::make('select', 'display_type', __('', 'gaumap')) ->set_width(20)
    //     ->set_default_value('auto')
    //     ->set_options([
    //         'auto' => __('Auto'),
    //         'manual' => __('Manual'),
    //     ]),

    // Field::make('separator', 'auto_spt', __('Automatically display 3 latest posts', 'gaumap')) ->set_width(60)
    //     ->set_conditional_logic([
    //         'relation' => 'AND',
    //         ['field' => 'display_type', 'value' => 'auto', 'compare' => '='],
    //     ]),

    // Field::make('separator', 'manual_spt', __('Select the posts to display', 'gaumap')) ->set_width(60)
    //     ->set_conditional_logic([
    //         'relation' => 'AND',
    //         ['field' => 'display_type', 'value' => 'manual', 'compare' => '='],
    //     ]),

    // Field::make('association','manual_blog', __('','gaumap')) ->set_width(70)
    //     ->set_types([
    //         [
    //             'type'      => 'post',
    //             'post_type' => 'blog',
    //         ]
    //     ])
    //     ->set_conditional_logic([
    //         'relation' => 'AND',
    //         ['field' => 'display_type', 'value' => 'manual', 'compare' => '='],
    //     ]),
    
    
])
->set_render_callback(function ($fields, $attributes, $inner_blocks) {
    // $title = !empty($fields['blog_title']) ? esc_html($fields['blog_title']) : '';
    // $url = !empty($fields['blog_page_url']) ? esc_url($fields['blog_page_url']) : '';
    // $type = !empty($fields['display_type']) ? $fields['display_type'] : '';
    // $blogs = !empty($fields['manual_blog']) ? $fields['manual_blog'] : '';
?>
    <section class="block-table">        
        <div class="inner">
            <div class="list-items" style="--bg-color:#f2f2f2">
                <div class="item" >
                    <!-- Left -->
                    <div class="item__left">
                        <p class="item__title">Address</p>
                    </div>
                    <!-- Right -->
                    <div class="item__right">
                        <ul class="item__desc">
                            <li>東京都中央区京橋1-1-5 セントラルビル 2階</li>
                            <li>5th Floor, 50 Cuu Long Street, Tan Son Hoa Ward, Ho Chi Minh City, Vietnam</li>
                            <li>6th Floor, 132-136 Le Dinh Ly Street, Thanh Khe Ward, Da Nang City, Vietnam</li>
                        </ul>
                    </div>
                </div>

                <div class="item">
                    <!-- Left -->
                    <div class="item__left">
                        <p class="item__title">Phone</p>
                    </div>
                    <!-- Right -->
                    <div class="item__right">
                        <ul class="item__desc">
                            <li>(Tokyo) (+81)3-4500-6968</li>
                            <li>(Da Nang) (+84)78-979-6559</li>
                            <li>(Ho Chi Minh) (+84)90-688-9060</li>
                        </ul>
                    </div>
                </div>

                <div class="item">
                    <!-- Left -->
                    <div class="item__left">
                        <p class="item__title">Email</p>
                    </div>
                    <!-- Right -->
                    <div class="item__right">
                        <ul class="item__desc">
                            <li>info@aiot-fixing.local</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
});

