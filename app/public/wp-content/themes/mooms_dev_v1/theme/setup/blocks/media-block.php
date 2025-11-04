<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make(__('Block Media', 'gaumap'))
    ->add_fields([
        Field::make('separator', 'media_spt', __('BLOCK MEDIA', 'gaumap')),

        Field::make('select', 'type_media', __('Type media', 'gaumap'))
            ->set_width(20)
            ->set_default_value('image')
            ->add_options([
                'image' => __('Image'),
                'video' => __('Video'),
            ]),

        // Image field
        Field::make('image', 'image_media', __('Image', 'gaumap'))
            ->set_width(80)
            ->set_conditional_logic([[
                'field' => 'type_media',
                'value' => 'image',
                'compare' => '=',
            ]]),

        // Video type selection
        Field::make('select', 'type_video', __('Type video', 'gaumap'))
            ->set_width(20)
            ->set_options([
                'embed' => 'Embed link',
                'upload' => 'Upload',
            ])
            ->set_default_value('embed')
            ->set_conditional_logic([[
                'field' => 'type_media',
                'value' => 'video',
                'compare' => '=',
            ]]),

        // Video upload field
        Field::make('file', 'video_upload', __('Video upload', 'gaumap'))
            ->set_width(60)
            ->set_conditional_logic([
                'relation' => 'AND',
                ['field' => 'type_media', 'value' => 'video', 'compare' => '='],
                ['field' => 'type_video', 'value' => 'upload', 'compare' => '='],
            ]),

        // Video embed URL field
        Field::make('text', 'video_embed', __('Video from URL', 'gaumap'))
            ->set_width(60)
            ->set_conditional_logic([
                'relation' => 'AND',
                ['field' => 'type_media', 'value' => 'video', 'compare' => '='],
                ['field' => 'type_video', 'value' => 'embed', 'compare' => '='],
            ]),

        // Welcome text field
        Field::make('text', 'text_welcome', __('Welcome text', 'gaumap'))
            ->set_attribute('placeholder', 'Enter welcome text'),
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        $types = $fields['type_media'];
        $img = !empty($fields['image_media']) ? $fields['image_media'] : null;
        $type_video = $fields['type_video'];
        $videoUpload = !empty($fields['video_upload']) ? esc_url(wp_get_attachment_url($fields['video_upload'])) : null;
        $videoEmbed = !empty($fields['video_embed']) ? esc_url($fields['video_embed']) : null;
        $welcomeText = !empty($fields['text_welcome']) ? esc_html($fields['text_welcome']) : 'Welcome to AIOT-global';
        ?>
        
        <section class="block-media full-width">
            <?php 
            if ($types === 'image' && $img):
                
                echo '<figure class="media">
                    <img
                        src="' . esc_url(getImageUrlById($img, 1920, 1080)) . '"
                        srcset="' . esc_url(getImageUrlById($img, 400, 300)) . ' 400w,
                                ' . esc_url(getImageUrlById($img, 800, 600)) . ' 800w,
                                ' . esc_url(getImageUrlById($img, 1200, 900)) . ' 1200w"
                        sizes="(max-width: 600px) 400px, (max-width: 1200px) 800px, 1200px"
                        width="1920" height="1080"
                        alt="' . esc_attr(get_post_meta($img, '_wp_attachment_image_alt', true) ?: get_the_title($img)) . '"
                        loading="lazy">
                </figure>';
            
            elseif ($types === 'video' && $type_video === 'upload' && $videoUpload): 
                $filetype = wp_check_filetype($videoUpload);
                if (strpos($filetype['type'], 'video') !== false) {
                    echo '<figure class="media">
                        <video
                            src="' . $videoUpload . '"
                            autoplay
                            loop
                            muted
                            playsinline
                            preload="auto"
                            width="1920"
                            height="1080"
                        ></video>
                    </figure>';
                } else {
                    echo 'The file is not a video.';
                }
                
            elseif ($types === 'video' && $type_video === 'embed' && $videoEmbed):

                echo '<div class="embed-responsive">' . wp_oembed_get($videoEmbed) . '</div>';

            endif; ?>
        </section>

        <section class="block-welcome full-width">
            <div class="mm-container-fluid">
                <div class="scroll-circle">
                    <svg viewBox="0 0 200 200">
                        <path id="circlePath" d="M100,100 m-75,0 a75,75 0 1,1 150,0 a75,75 0 1,1 -150,0" fill="none"/>
                        <text>
                            <textPath href="#circlePath" startOffset="0">
                                Scroll down - Scroll down - Scroll down - Scroll down -
                            </textPath>
                        </text>
                    </svg>
                    <div class="arrow"></div>
                </div>
                <div class="welcome-aiot"><?= $welcomeText; ?></div>
            </div>
        </section>
        <?php
    });
