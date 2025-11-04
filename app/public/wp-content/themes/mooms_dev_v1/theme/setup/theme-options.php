<?php

/**
 * Theme Options.
 *
 * Here, you can register Theme Options using the Carbon Fields library.
 *
 * @link    https://carbonfields.net/docs/containers-theme-options/
 *
 * @package WPEmergeCli
 */

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

$optionsPage = Container::make('theme_options', __('MMS Theme', 'mms'))
	->set_page_file('app-theme-options.php')
	->set_page_menu_position(3)
	->add_tab(__('Branding | Thương hiệu', 'mms'), [
		Field::make('color', 'primary_color', __('Primary Color', 'mms'))
		->set_width(50)
		->set_default_value('#010101'),
		Field::make('color', 'secondary_color', __('Secondary Color', 'mms'))
		->set_width(50)
		->set_default_value('#626262'),
		
		Field::make('image', 'logo' . currentLanguage(), __('Logo', 'mms'))
			->set_width(33.33),
		Field::make('image', 'footer_logo' . currentLanguage(), __('Footer Logo', 'mms'))
			->set_width(33.33),
		Field::make('image', 'hinh_anh_mac_dinh' . currentLanguage(), __('Default image | Hình ảnh mặc định', 'mms'))
			->set_width(33.33),
	])

	->add_tab(__('Contact | Liên hệ', 'mms'), [
		Field::make('html', 'info', __('', 'mms'))
			->set_html('----<i> Information | Thông tin </i>----'),
		Field::make('text', 'company' . currentLanguage(), __('', 'mms'))
			->set_width(50)
			->set_attribute('placeholder', 'Company | Công ty'),
		Field::make('text', 'address' . currentLanguage(), __('', 'mms'))
			->set_width(50)
			->set_attribute('placeholder', 'Address | Địa chỉ'),
		Field::make('textarea', 'googlemap' . currentLanguage(), __('', 'mms'))
			->set_attribute('placeholder', 'Google map'),
		Field::make('text', 'email' . currentLanguage(), __('', 'mms'))
			->set_width(33.33)
			->set_attribute('placeholder', 'Email'),
		Field::make('text', 'phone_number' . currentLanguage(), __('', 'mms'))
			->set_width(33.33)
			->set_attribute('placeholder', 'Phone number | Số điện thoại'),
		Field::make('text', 'hour_working' . currentLanguage(), __('', 'mms'))
			->set_width(33.33)
			->set_attribute('placeholder', 'Hour working | Giờ làm việc'),
		Field::make('html', 'socials', __('', 'mms'))
			->set_html('----<i> Socials | Mạng xã hội </i>----'),
		Field::make('text', 'facebook' . currentLanguage(), __('', 'mms'))
			->set_width(33.33)
			->set_attribute('placeholder', 'facebook'),
		Field::make('text', 'instagram' . currentLanguage(), __('', 'mms'))
			->set_width(33.33)
			->set_attribute('placeholder', 'instagram'),
		Field::make('text', 'twitter' . currentLanguage(), __('', 'mms'))
			->set_width(33.33)
			->set_attribute('placeholder', 'twitter'),
	])
	->add_tab(__('Footer', 'mms'), [
		Field::make('text', 'contact_label' . currentLanguage(), __('', 'mms'))
			->set_width(50)
			->set_attribute('placeholder', 'Contact label | Nhãn liên hệ'),
		Field::make('text', 'contact_url' . currentLanguage(), __('', 'mms'))
			->set_width(50)
			->set_attribute('placeholder', 'Contact URL | Liên kết liên hệ'),
		Field::make('textarea', 'contact_message' . currentLanguage(), __('', 'mms'))
			->set_attribute('placeholder', 'Contact description | Mô tả liên hệ'),
	])
	->add_tab(__('Scripts', 'mms'), [
		Field::make('header_scripts', 'crb_header_script', __('Header Script', 'app')),
		Field::make('footer_scripts', 'crb_footer_script', __('Footer Script', 'app')),
	]);

// Register post meta fields (load-time registration to avoid timing issues)
Container::make('post_meta', __('Trang default', 'mms'))
    ->set_context('normal') // normal, advanced, side or carbon_fields_after_title
    ->where('post_type', '=', 'page')
    ->add_fields([
		
        // Select and reorder child pages for custom display
        Field::make('complex', 'child_pages_order', __('Child pages order', 'mms'))
		->add_fields( array(
			Field::make('select', 'child_page', __('Child page', 'mms'))
			->set_options(function () {
				return getListChildPages();
			}),
		) )
		->set_header_template('<% var map = ' . json_encode(getListChildPages()) . '; %><% if (child_page) { %><%- (map[child_page] ? map[child_page] : child_page) %><% } %>'),
    ]);

// Limit the association options to direct children of the current page
// The filter in different Carbon Fields versions may pass 2-4 args, so keep params optional.
add_filter('carbon_fields_association_field_options', function ($options, $field = null, $value = null, $post_id = null) {
    if (!$post_id) {
        $post_id = isset($_GET['post']) ? absint($_GET['post']) : 0;
        if (!$post_id && isset($_POST['post_ID'])) {
            $post_id = absint($_POST['post_ID']);
        }
        if (!$post_id) {
            $post_id = get_the_ID();
        }
    }
    if (isset($options['field_name']) && $options['field_name'] === 'child_pages_order') {
        if (!isset($options['types'][0]['query'])) {
            $options['types'][0]['query'] = [];
        }
        $options['types'][0]['query']['post_parent'] = $post_id;
        $options['types'][0]['query']['post_status'] = 'publish';
        $options['types'][0]['query']['orderby'] = 'menu_order title';
        $options['types'][0]['query']['order'] = 'ASC';
    }
    return $options;
}, 10, 2);