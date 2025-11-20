<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;
$addLabels = array(
    'plural_name' => 'media',
    'singular_name' => 'media',
);

Block::make(__('Block Intro', 'gaumap')) 
->add_fields([
    Field::make('separator', 'intro_spt', __('BLOCK INTRO', 'gaumap'))->set_width(70),
    Field::make('html', 'intro_guide')
        ->set_html('<button class="intro-guide button">Hướng dẫn nhập</button>')
        ->set_width(30),
    //Reversed
    Field::make('checkbox', 'reversed', __('REVERSED', 'gaumap'))->set_width(30)
        ->set_option_value('reversed')
        ->set_attribute('data-step', '1')
        ->set_attribute('data-intro', 'Click chọn để đảo ngược vị trí của Media (Slider, ảnh, video) và nội dung'),
    //Title
    Field::make('text', 'intro_title', __('', 'gaumap'))
        ->set_width(70)
        ->set_attribute('placeholder', 'Enter block title')
        ->set_attribute('data-step', '2')
        ->set_attribute('data-intro', 'Nhập tiêu đề của block'),
    //Description
    Field::make('textarea', 'intro_desc', __('', 'gaumap'))
        ->set_width(20)
        ->set_attribute('placeholder', 'Enter block descscription')
        ->set_attribute('data-step', '3')
        ->set_attribute('data-intro', 'Nhập mô tả của block'),
    //URL
    Field::make('text', 'intro_page_url', __('', 'gaumap'))
        ->set_attribute('placeholder', 'Enter button URL')
        ->set_attribute('data-step', '4')
        ->set_attribute('data-intro', 'Nhập button URL'),
    //Media
    Field::make('text', 'media_spt', __('', 'gaumap')) ->set_width(20)
        ->set_default_value('Media')
        ->set_attribute('placeholder', 'Media')
        ->set_attribute('readOnly', 'true')
        ->set_attribute('data-step', '5')
        ->set_attribute('data-intro', 'Chọn kiểu media (Slider, ảnh hoặc video)'),
    // type media
    Field::make('select', 'type_media', __('', 'gaumap')) ->set_width(20)
    ->set_default_value('image')
    ->add_options([
        'image' => __('Image'),
        'video' => __('Video'),
        'slider' => __('Slider'),
    ]),
    // Image
    Field::make('image', 'image', __('Image', 'gaumap')) ->set_width(60)
    ->set_conditional_logic([[
        'field' => 'type_media',
        'value' => 'image',
        'compare' => '=',
    ]]),
    // Video
    Field::make('text', 'video', __('', 'gaumap')) ->set_width(60)
    ->set_attribute('placeholder', 'Video from URL (youtube, vimeo)')
    ->set_conditional_logic([
        'relation' => 'AND',
        ['field' => 'type_media', 'value' => 'video', 'compare' => '='],
    ]),
    //slider
    Field::make('media_gallery', 'img_slider', __('Select slider image (Ctrl + image to select multiple images))', 'gaumap')) ->set_width(60)
        ->set_conditional_logic([
            'relation' => 'AND',
            ['field' => 'type_media', 'value' => 'slider', 'compare' => '='],
        ]),
])
->set_render_callback(function ($fields, $attributes, $inner_blocks) {
    $title = !empty($fields['intro_title']) ? esc_html($fields['intro_title']) : '';
    $desc = !empty($fields['intro_desc']) ? wp_kses_post($fields['intro_desc']) : '';
    $url = !empty($fields['intro_page_url']) ? esc_url($fields['intro_page_url']) : '';
    $reversed = !empty($fields['reversed']) ? 'reversed ' : '';
    $mediaType = !empty($fields['type_media']) ? esc_html($fields['type_media']) : '';
    $image = !empty($fields['image']) ? esc_url(getImageUrlById($fields['image'])) : '';
    $video = !empty($fields['video']) ? esc_url($fields['video']) : '';
    $sliders = !empty($fields['img_slider']) ? $fields['img_slider'] : '';
?>
    <section class="block-intro <?php echo $reversed;?>full-width">
        <div class="container">
            <div class="inner">
                <div class="intro-content">
                    <?php if ($title): ?>
                        <h2 class="block-title border-line-top"><?= $title; ?></h2>
                    <?php endif; ?>
                    
                    <div class="mb-media">
                        <?php 
                        if ($mediaType == 'image' && $image):
                            
                            echo '<figure class="media">
                                    <img src=" ' . $image . ' " alt="image" loading="lazy">
                                    </figure>';

                        elseif ($mediaType == 'video' && $video):
                            echo getVideoUrl($video);
                        elseif ($mediaType == 'slider' && $sliders): ?>
                            <div class="slider">
                                <div class="slider-track">
                                    <?php 
                                    $i = 1; 
                                    foreach ($sliders as $slider): 
                                        echo '<div class="item-slider"><div class="inner">
                                        <figure class="media-custom"><img src="' . getImageUrlById($slider) . '" alt="slider-'. $i .'" loading="lazy"></figure></div></div>';
                                        $i++;
                                    endforeach;
                                    
                                    foreach ($sliders as $slider): 
                                        echo '<div class="item-slider"><div class="inner">
                                        <figure class="media-custom"><img src="' . getImageUrlById($slider) . '" alt="slider-'. $i .'" loading="lazy"></figure></div></div>';
                                        $i++;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($desc): ?>
                        <p class="block-desc"><?= $desc; ?></p>
                    <?php endif; ?>
                    
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
                
                <div class="pc-media">
                    <?php 
                    if ($mediaType == 'image' && $image):
                        
                        echo '<figure class="media">
                            <img src=" ' . $image . ' " alt="image" loading="lazy">
                        </figure>';
                        
                    elseif ($mediaType == 'video' && $video):

                        echo getVideoUrl($video);
                        
                    elseif ($mediaType == 'slider' && $sliders): ?>
                        <div class="slider">
                            <div class="slider-track">
                                <?php 
                                $i = 1; 
                                foreach ($sliders as $slider): 
                                ?>
                                    <div class="item-slider">
                                        <div class="inner">
                                            <figure class="media-custom">
                                                <img src="<?= getImageUrlById($slider); ?>" alt="slider-<?= $i ?>" loading="lazy">
                                            </figure>
                                        </div>
                                    </div>
                                <?php
                                    $i++;
                                endforeach;
                                ?>
                                <!-- Copy các item để tạo hiệu ứng nối tiếp liên tục -->
                                <?php 
                                foreach ($sliders as $slider): 
                                ?>
                                    <div class="item-slider">
                                        <div class="inner">
                                            <figure class="media-custom">
                                                <img src="<?= getImageUrlById($slider); ?>" alt="slider-<?= $i ?>" loading="lazy">
                                            </figure>
                                        </div>
                                    </div>
                                <?php
                                    $i++;
                                endforeach;
                                ?>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php
});
