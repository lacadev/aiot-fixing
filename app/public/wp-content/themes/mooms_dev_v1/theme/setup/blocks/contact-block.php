<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make(__('Block Contact', 'gaumap'))
->add_fields([
    Field::make('separator', 'contact_spt', __('BLOCK CONTACT', 'gaumap'))->set_width(70),

    //Title
    Field::make('text', 'contact_title', __('', 'gaumap'))
        ->set_attribute('placeholder', 'Enter block title'),
        
    //Description
    Field::make('textarea', 'contact_desc', __('', 'gaumap'))
        ->set_attribute('placeholder', 'Enter block description'),

    //Google map
    Field::make('textarea', 'google_map', __('', 'gaumap'))
    ->set_attribute('placeholder', 'Iframe google map'),

    //Policy
    Field::make('rich_text', 'policy', __('', 'gaumap'))
    ->set_attribute('placeholder', 'Enter policy'),

    //Agree checkbox
    Field::make('text', 'agree_text', __('', 'gaumap'))
    ->set_attribute('placeholder', 'Enter agree text'),

    //Other contact methods
    // Field::make('text', 'contact_method_spt', __('', 'gaumap')) ->set_width(20)
    // ->set_default_value('Other contact methods')
    // ->set_attribute('readOnly', 'true'),

    // Field::make('complex', 'contact_method', __('', 'gaumap')) ->set_width(80)
    // ->set_layout('tabbed-horizontal')
    // ->add_fields([
    //     Field::make( 'text', 'name', __('','gaumap'))
    //     ->set_attribute('placeholder', 'Enter contact name'),
    //     Field::make( 'text', 'url', __('','gaumap'))
    //     ->set_attribute('placeholder', 'Enter contact url'),
    // ])->set_header_template('<% if (name) { %><%- name %><% } %>'),
])
->set_render_callback(function ($fields, $attributes, $inner_blocks) {
    $title = !empty($fields['contact_title']) ? esc_html($fields['contact_title']) : '';
    $desc = !empty($fields['contact_desc']) ? wp_kses_post($fields['contact_desc']) : '';
    $googMap = !empty($fields['google_map']) ? $fields['google_map'] : '';
    // $otherContact = !empty($fields['contact_method']) ? $fields['contact_method'] : '';
    $policy = !empty($fields['policy']) ? $fields['policy'] : '';
    $agree_text = !empty($fields['agree_text']) ? esc_html($fields['agree_text']) : '';
?>
    <section class="block-contact">
        <div class="google-map">
            <?php echo $googMap; ?>
        </div>
        <div class="info-contact">
            <?php
            if ($title):
                echo '<h2 class="block-title">' . esc_html($title) . '</h2>';
            endif;

            if ($desc):
                echo '<div class="block-desc">' .  wp_kses_post(apply_filters('the_content', $desc)) . '</div>';
            endif;

            // if ($otherContact):
            //     echo '<div class="other-contact">';
            //         foreach ($otherContact as $contact):
            //             $name = !empty($contact['name']) ? esc_html($contact['name']) : '';
            //             $url = !empty($contact['url']) ? esc_url($contact['url']) : '';
            //             if ($name && $url):
            //                 echo '<a href="' . $url . '" target="_blank" class="contact-method">' . $name . '</a>';
            //             endif;
            //         endforeach;
            //     echo '</div>';
            // endif;
            ?>

            <form id="contactForm" action="#">
                <div class="form-row">
                    <div class="input-data">
                        <input type="text" name="first_name" required>
                        <div class="underline"></div>
                        <label for="first_name">First Name</label>
                    </div>
                    <div class="input-data">
                        <input type="text" name="last_name" required>
                        <div class="underline"></div>
                        <label for="last_name">Last Name</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-data">
                        <input type="text" name="email" required>
                        <div class="underline"></div>
                        <label for="email">Email</label>
                    </div>
                    <div class="input-data">
                        <input type="tel" name="phone_number" required>
                        <div class="underline"></div>
                        <label for="phone_number">Phone</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-data textarea">
                        <textarea rows="8" cols="80" name="message" required></textarea>
                        <br />
                        <div class="underline"></div>
                        <label for="message">Write your message</label>
                        <br />
                    </div>
                </div>

                <?php
                if ($policy):
                    echo '<div class="policy-scroll">' . wp_kses_post(apply_filters('the_content', $policy)) . '</div>';
                endif;
                ?>

				<div class="form-row">
                    <span>
                        <input style="height: 2rem; width: 2rem;" type="checkbox" id="agree" name="agree" required>
                        <span for="agree"><?php echo $agree_text;?></span>
                    </span>
                </div>

                <div class="form-row submit-btn">
                    <div class="input-data">
                        <div class="inner">
                            <button type="submit">
                                <svg viewBox="0 0 28 19" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.89153 9.60178C5.80696 9.60178 5.72285 9.57795 5.65139 9.5316L0.264398 6.04566C0.123825 5.95471 0.0561156 5.79378 0.0928988 5.63766C0.129448 5.48199 0.263461 5.36216 0.43168 5.33462L27.4215 0.910455C27.6351 0.875253 27.842 0.997259 27.9006 1.19186C27.9592 1.38624 27.85 1.59089 27.6485 1.66458L6.0457 9.57467C5.99603 9.59282 5.94355 9.60178 5.89177 9.60178H5.89153ZM1.58766 5.94443L5.94706 8.76523L23.4285 2.36448L1.58766 5.94443Z"></path>
                                    <path d="M15.9787 16.8496C15.8938 16.8496 15.8096 16.8269 15.739 16.7806L7.78951 11.5661C7.66748 11.4858 7.59802 11.3514 7.6061 11.2109C7.61443 11.0704 7.69959 10.9443 7.83019 10.8773L27.2847 0.952809C27.4617 0.863338 27.6786 0.900655 27.8104 1.04228C27.9419 1.18435 27.9519 1.39364 27.8347 1.54651C23.373 7.36008 16.6355 16.1768 16.361 16.6383C16.302 16.7376 16.199 16.8127 16.0815 16.8381C16.0477 16.8455 16.0132 16.8491 15.979 16.8491L15.9787 16.8496ZM8.85045 11.2768L15.8688 15.8807C17.2362 14.0016 23.3989 5.95283 26.0399 2.50776L8.85045 11.2768Z"></path>
                                    <path d="M8.62085 18.2992C8.49498 18.2992 8.39703 18.2321 8.2354 17.8804H8.22854C8.22854 17.8767 8.2283 17.8713 8.2283 17.8642C7.98757 17.3339 7.60506 16.1797 6.78591 13.7085C6.07328 11.5576 5.36801 9.40557 5.36801 9.40557C5.29674 9.18946 5.42825 8.96061 5.66138 8.89486C5.89353 8.82912 6.14063 8.9506 6.21115 9.16694C6.77783 10.8952 7.50539 13.1025 8.08504 14.842C8.02602 13.6248 7.95794 12.2473 7.90186 11.1185C7.8906 10.8924 8.07867 10.7011 8.3216 10.6906C8.566 10.6813 8.77072 10.8549 8.78223 11.0806C8.81578 11.7562 9.10989 17.6868 9.10989 17.8804C9.10989 18.0739 8.94925 18.2587 8.73301 18.2885C8.69187 18.2942 8.65514 18.2992 8.62061 18.2992H8.62085Z"></path>
                                    <path d="M8.75414 18.3008C8.65363 18.3008 8.55312 18.2587 8.47597 18.1744C8.31969 18.0043 8.3177 17.7257 8.47134 17.5525L12.1914 13.3571C12.3448 13.1836 12.5961 13.1819 12.7524 13.352C12.9087 13.5221 12.9107 13.8007 12.757 13.974L9.03694 18.1693C8.95935 18.257 8.85664 18.3008 8.75414 18.3008Z"></path>
                                </svg>
                                <?= _e('Send','gaumap');?>
                            </button>
                            <input type="hidden" name="_token" value="<?php echo wp_create_nonce('send_contact_form'); ?>">
                        </div>
                    </div>
                </div>
            </form>

            <div id="formMessage"></div>

            <div class="info-company">
                <!-- Phone -->
                <div class="info-item phone">
                    <a href="tel:<?= str_replace(['.', ',','-', ' '], '', getOption('phone_number'));?>">
                        <div class="icon-phone"></div>
                        <?php echo esc_html(getOption('phone_number'));?>
                    </a>
                </div>

                <!-- Email -->
                <div class="info-item email">
                    <a href="mailto:<?php echo esc_html(getOption('email'));?>">
                        <div class="icon-mail"></div>
                        <?php echo esc_html(getOption('email'));?>
                    </a>
                </div>

                <!-- Hour -->
                <div class="info-item hour">
                    <div class="icon-hour"></div>
                    <?php echo esc_html(getOption('hour_working'));?>
                </div>

                <!-- Address -->
                <div class="info-item address">
                    <div class="icon-address"></div>
                    <?php echo esc_html(getOption('address'));?>
                </div>
            </div>
        </div>
    </section>
<?php
});
