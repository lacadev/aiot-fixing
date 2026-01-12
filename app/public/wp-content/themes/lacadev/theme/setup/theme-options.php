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

$optionsPage = Container::make('theme_options', __('Laca Theme', 'laca'))
	->set_page_file('app-theme-options.php')
	->set_page_menu_position(3)
	->add_tab(__('Branding | Thương hiệu', 'mms'), [
		Field::make('color', 'primary_color' . currentLanguage(), __('Primary Color', 'mms'))
			->set_width(50)
			->set_default_value('#d85040'),
		Field::make('color', 'secondary_color' . currentLanguage(), __('Secondary Color', 'mms'))
			->set_width(50)
			->set_default_value('#5384ed'),

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
		Field::make('select', 'page_contact' . currentLanguage(), __('Contact page | Trang liên hệ', 'mms'))
			->set_options(function () {
				return getListAllPages();
			}),
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
			->set_width(30)
			->set_attribute('placeholder', 'Contact label | Nhãn liên hệ'),
		Field::make('textarea', 'contact_message' . currentLanguage(), __('', 'mms'))
			->set_width(70)
			->set_attribute('placeholder', 'Contact description | Mô tả liên hệ'),
	])

	->add_tab(__('Scripts', 'laca'), [
		Field::make('header_scripts', 'crb_header_script', __('Header Script', 'laca')),
		Field::make('footer_scripts', 'crb_footer_script', __('Footer Script', 'laca')),
	]);