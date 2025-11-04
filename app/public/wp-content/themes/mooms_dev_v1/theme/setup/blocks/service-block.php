<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;
$serviceLabels = array(
    'plural_name' => 'service',
    'singular_name' => 'service',
);

Block::make(__('Block Service', 'gaumap')) 
->add_fields([
    Field::make('separator', 'service_spt', __('BLOCK SERVICE', 'gaumap'))->set_width(70),
    Field::make('html', 'service_guide')
        ->set_html('<button class="service-guide button">Hướng dẫn nhập</button>')
        ->set_width(30),
    //Reversed
    Field::make('checkbox', 'reversed', __('REVERSED', 'gaumap'))->set_width(30)
        ->set_option_value('reversed')
        ->set_attribute('data-step', '1')
        ->set_attribute('data-intro', 'Click chọn để đảo ngược vị trí'),
    //Title
    Field::make('text', 'service_title', __('', 'gaumap'))
        ->set_width(70)
        ->set_attribute('placeholder', 'Enter block title')
        ->set_attribute('data-step', '2')
        ->set_attribute('data-intro', 'Nhập tiêu đề của block'),
    //Description
    Field::make('textarea', 'service_desc', __('', 'gaumap'))
        ->set_width(20)
        ->set_attribute('placeholder', 'Enter block descscription')
        ->set_attribute('data-step', '3')
        ->set_attribute('data-intro', 'Nhập mô tả của block'),
    //URL
    Field::make('text', 'service_page_url', __('', 'gaumap'))
        ->set_attribute('placeholder', 'Enter button URL')
        ->set_attribute('data-step', '4')
        ->set_attribute('data-intro', 'Nhập button URL'),
    //Services
    Field::make('complex', 'services', __('Service', 'gaumap')) ->set_width(20)
        ->setup_labels($serviceLabels)
        ->set_layout('tabbed-horizontal')
        ->add_fields([
            Field::make( 'text', 'name', __('','gaumap'))
            ->set_attribute('placeholder', 'Enter service name'),
            Field::make( 'text', 'url', __('','gaumap'))
            ->set_attribute('placeholder', 'Enter service url'),
            Field::make( 'textarea', 'desc', __('','gaumap'))
            ->set_attribute('placeholder', 'Enter service description'),
        ])->set_header_template('<% if (name) { %><%- name %><% } %>'),
    
])
->set_render_callback(function ($fields, $attributes, $inner_blocks) {
    $title = !empty($fields['service_title']) ? esc_html($fields['service_title']) : '';
    $desc = !empty($fields['service_desc']) ? wp_kses_post($fields['service_desc']) : '';
    $url = !empty($fields['service_page_url']) ? esc_url($fields['service_page_url']) : '';
    $reversed = !empty($fields['reversed']) ? 'reversed' : '';
    $services = !empty($fields['services']) ? $fields['services'] : '';
?>
    <section class="block-service <?= $reversed; ?>">
        <div class="inner">
            <div class="service-content">
                <?php if ($title): ?>
                    <h2 class="block-title border-line-top"><?= $title; ?></h2>
                <?php endif; ?>
                
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
            
            <div class="services-list">
                <div class="services">
                    <?php
                        foreach ($services as $service) :
                            echo '<div class="service-item border-line-top">
                                    <div class="content">
                                        <h4 class="name">' . $service['name'] . '</h4>
                                        <p class="desc">' . $service['desc'] . '</p>
                                    </div>
                                    <div class="link">
                                        <a class="btn-url not-label" href="' . $service['url'] . '">
                                            <span class="_icon">
                                                <div class="mm-svg"></div>
                                            </span>
                                        </a>
                                    </div>
                                </div>';
                        endforeach
                    ?>
                </div>
            </div>
        </div>
    </section>
<?php
});
