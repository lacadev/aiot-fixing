<?php
/**
 * get resources image uri
 *
 * @param string $path
 *
 * @return string
 */
function getImageAsset($path) {
    $my_theme   = wp_get_theme();
    $theme_name = str_replace('/theme', '', $my_theme->get_stylesheet());
    $theme_path = str_replace('wp-content/themes/'. $theme_name .'/theme', 'wp-content/themes/' . $theme_name . '/', $my_theme->get_template_directory_uri());

    if (carbon_get_theme_option('use_short_url') !== true) {
        $siteUrl = $theme_path . "resources/images/";
    } else {
        $siteUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . "/img/";
    }

    return $siteUrl . $path;
}

function template($name) {
    get_template_part('templates/' . $name);
}

/**
 * Get theme option dạng image
 *
 * @param string $name
 * @param int    $w
 * @param int    $h
 *
 * @return false|string
 */
function getOptionImageUrl($name, $w, $h) {
    return getImageUrlById(getOption($name), $w, $h);
}

function getFieldImageUrl($name, $w, $h) {
    return getImageUrlById(get_field($name,'option'), $w, $h);
}

function getPostThumbnailUrl($postId, $width = null, $height = null) {
    $defaultImage = getImageUrlById(getOption('default_image'), $width, $height);
    try {
        $imageId = get_post_thumbnail_id($postId);
        if (empty($imageId)) {
            return $defaultImage;
        }

        if ($width === null && $height === null) {
            return wp_get_attachment_image_url($imageId, 'full');
        }

        return getImageUrlById($imageId, $width, $height);
    } catch (\Exception $ex) {
        return $defaultImage;
    }
}

function getPostMetaImageUrl($name, $id = null, $w = null, $h = null) {
    $id = empty($id) ? get_the_ID() : $id;
    return getImageUrlById(carbon_get_post_meta($id, $name), $w, $h);
}

function getPostMeta($name, $id = null) {
	$id = empty($id) ? get_the_ID() : $id;
	return carbon_get_post_meta($id, $name);
}
function thePostMeta($name) {
    echo getPostMeta($name, get_the_ID());
}

function thePostMetaImageUrl($name = '', $w = null, $h = null) {
    echo getPostMetaImageUrl($name, get_the_ID(), $w, $h);
}

/**
 * Echo view count of post
 *
 * @param null $postId
 */
function getViewCount($postId = null) {
    $postId = empty($postId) ? get_the_ID() : $postId;
    $cache_key = "post_{$postId}_view_count";
    $view_count = get_transient($cache_key);

    if ($view_count === false) {
        $count_key = '_gm_view_count';
        $view_count = get_post_meta($postId, $count_key, true);
        if (empty($view_count)) {
            $view_count = 0;
        }
        set_transient($cache_key, $view_count, 12 * HOUR_IN_SECONDS); // Cache for 12 hours
    }

    return $view_count;
}

function updateViewCount($postId = null) {
	$postId = empty($postId) ? get_the_ID() : $postId;

	$count_key = '_gm_view_count';
	$count     = (int)get_post_meta($postId, $count_key, true);
	if (empty($count)) {
		$count = 1;
		delete_post_meta($postId, $count_key);
		add_post_meta($postId, $count_key, $count);
	} else {
		$count++;
		update_post_meta($postId, $count_key, $count);
	}

	return $count;
}
function theViewCount($postId = null) {
    echo getViewCount($postId);
}

function thePostThumbnailUrl($width = null, $height = null) {
    echo getPostThumbnailUrl(get_the_ID(), $width, $height);
}

function theTitle($limit = 999) {
    echo subString(get_the_title(), $limit);
}

function getExcerpt($postId, $limit) {
	return subString(get_the_excerpt($postId), $limit);
}
function theExcerpt($limit = 9999) {
    echo '<p>' . getExcerpt(get_the_ID(), $limit) . '</p>';
}

function theContent() {
    $content = get_the_content();
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    echo !empty($content) ? wp_kses_post($content) : __('Dữ liệu đang được cập nhật', 'mms');
}

function getOption($name) {
	return carbon_get_theme_option($name . currentLanguage());
}
function theOption($name) {
    echo getOption($name);
}

function theOptionImage($name, $width = null, $height = null) {
    $imageId = getOption($name);
    if (!empty($imageId)) {
        echo getImageUrlById($imageId, $width, $height);
    }
}

/**
 * Load resource
 *
 * @param string $path
 */
function theAsset($path) {
    echo getImageAsset($path);
}

/**
 * Tạo phân trang sử dụng Bootstrap 5
 *
 * @param mixed|\WP_Query $query
 */
function thePagination($query = null) {
    if (empty($query)) {
        global $wp_query;
        $query = $wp_query;
    }

    $paged = (get_query_var('paged') === 0) ? 1 : get_query_var('paged');
    $pages = paginate_links([
        'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format'    => '?paged=%#%',
        'current'   => $paged,
        'total'     => $query->max_num_pages,
        'mid_size'  => 2, // Hiển thị 2 trang trước và 2 trang sau trang hiện tại
        'type'      => 'array',
        'prev_next' => true,
        'prev_text' => '<svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M5.9212 0.395035C6.14968 3.07539 7.27113 4.44006 8.36293 5.13692L0.348819 5.13692C0.156968 5.13692 -2.49144e-07 5.30024 -2.40419e-07 5.49986C-2.31693e-07 5.69948 0.156968 5.86281 0.348819 5.86281L8.36467 5.86281C7.27113 6.55966 6.15142 7.92434 5.92294 10.6047C5.90725 10.8061 6.04852 10.9803 6.24037 10.9985C6.43222 11.0166 6.6014 10.8696 6.61884 10.6682C7.01649 6.0225 10.2657 5.86462 10.6163 5.86281L10.6494 5.86281L10.6564 5.86281C10.6564 5.86281 10.6721 5.86099 10.6808 5.85918C10.693 5.85918 10.7052 5.85918 10.7157 5.85555L10.7227 5.85555C10.7314 5.85192 10.7401 5.84829 10.7489 5.84648C10.7593 5.84285 10.7715 5.84103 10.782 5.83559L10.789 5.83196C10.789 5.83196 10.8029 5.82288 10.8116 5.81925C10.8221 5.81381 10.8326 5.80837 10.843 5.80111L10.8483 5.79748C10.8483 5.79748 10.857 5.7884 10.8622 5.78477L10.8674 5.78114C10.8762 5.77389 10.8866 5.76663 10.8954 5.75755L10.9006 5.75211C10.9006 5.75211 10.9023 5.74848 10.9041 5.74666C10.9076 5.74122 10.9128 5.73578 10.9163 5.73215C10.9233 5.72307 10.932 5.714 10.939 5.70311C10.9442 5.69404 10.9494 5.68496 10.9547 5.67408C10.9599 5.66319 10.9669 5.6523 10.9721 5.64141C10.9756 5.63052 10.9791 5.61963 10.9826 5.60875C10.986 5.59604 10.9913 5.58515 10.993 5.57245C10.9948 5.56156 10.9965 5.55067 10.9965 5.53979C10.9965 5.52708 11 5.51619 11 5.50349L11 5.49623C11 5.47083 10.9983 5.44724 10.993 5.42364C10.993 5.42183 10.993 5.42001 10.9913 5.41638C10.9913 5.41638 10.9895 5.40913 10.9878 5.40731C10.9843 5.39098 10.9791 5.37283 10.9721 5.3565C10.9721 5.35468 10.9704 5.35287 10.9686 5.34924L10.9686 5.34742C10.9686 5.34742 10.9634 5.34017 10.9616 5.33654C10.9547 5.32202 10.9494 5.30932 10.9407 5.29661C10.9407 5.2948 10.9372 5.29298 10.9355 5.29117C10.9355 5.29117 10.9337 5.28754 10.932 5.28754C10.9302 5.28391 10.9267 5.28209 10.925 5.28028C10.9163 5.26758 10.9076 5.25669 10.8971 5.2458C10.8971 5.24399 10.8936 5.24217 10.8919 5.24036C10.8901 5.23854 10.8866 5.23673 10.8849 5.23491C10.8849 5.23491 10.8814 5.23128 10.8797 5.22947C10.8692 5.22039 10.8587 5.20951 10.8465 5.20043C10.8448 5.20043 10.843 5.19862 10.8413 5.1968C10.8361 5.19317 10.8308 5.19136 10.8273 5.18773C10.8151 5.18047 10.8029 5.17321 10.789 5.16595L10.782 5.16595C10.782 5.16595 10.7663 5.15869 10.7593 5.15688C10.7471 5.15325 10.7366 5.14781 10.7227 5.14599L10.7175 5.14599C10.707 5.14236 10.6965 5.14236 10.6861 5.14055C10.6773 5.14055 10.6669 5.13692 10.6582 5.13692L10.646 5.13692C10.496 5.13873 7.03219 5.13692 6.62058 0.33152C6.60314 0.1319 6.43396 -0.0150931 6.24211 0.0012395C6.05026 0.019387 5.91073 0.19723 5.92469 0.395035L5.9212 0.395035Z" fill="white"></path>
        </svg>',
                'next_text' => '<svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M5.9212 0.395035C6.14968 3.07539 7.27113 4.44006 8.36293 5.13692L0.348819 5.13692C0.156968 5.13692 -2.49144e-07 5.30024 -2.40419e-07 5.49986C-2.31693e-07 5.69948 0.156968 5.86281 0.348819 5.86281L8.36467 5.86281C7.27113 6.55966 6.15142 7.92434 5.92294 10.6047C5.90725 10.8061 6.04852 10.9803 6.24037 10.9985C6.43222 11.0166 6.6014 10.8696 6.61884 10.6682C7.01649 6.0225 10.2657 5.86462 10.6163 5.86281L10.6494 5.86281L10.6564 5.86281C10.6564 5.86281 10.6721 5.86099 10.6808 5.85918C10.693 5.85918 10.7052 5.85918 10.7157 5.85555L10.7227 5.85555C10.7314 5.85192 10.7401 5.84829 10.7489 5.84648C10.7593 5.84285 10.7715 5.84103 10.782 5.83559L10.789 5.83196C10.789 5.83196 10.8029 5.82288 10.8116 5.81925C10.8221 5.81381 10.8326 5.80837 10.843 5.80111L10.8483 5.79748C10.8483 5.79748 10.857 5.7884 10.8622 5.78477L10.8674 5.78114C10.8762 5.77389 10.8866 5.76663 10.8954 5.75755L10.9006 5.75211C10.9006 5.75211 10.9023 5.74848 10.9041 5.74666C10.9076 5.74122 10.9128 5.73578 10.9163 5.73215C10.9233 5.72307 10.932 5.714 10.939 5.70311C10.9442 5.69404 10.9494 5.68496 10.9547 5.67408C10.9599 5.66319 10.9669 5.6523 10.9721 5.64141C10.9756 5.63052 10.9791 5.61963 10.9826 5.60875C10.986 5.59604 10.9913 5.58515 10.993 5.57245C10.9948 5.56156 10.9965 5.55067 10.9965 5.53979C10.9965 5.52708 11 5.51619 11 5.50349L11 5.49623C11 5.47083 10.9983 5.44724 10.993 5.42364C10.993 5.42183 10.993 5.42001 10.9913 5.41638C10.9913 5.41638 10.9895 5.40913 10.9878 5.40731C10.9843 5.39098 10.9791 5.37283 10.9721 5.3565C10.9721 5.35468 10.9704 5.35287 10.9686 5.34924L10.9686 5.34742C10.9686 5.34742 10.9634 5.34017 10.9616 5.33654C10.9547 5.32202 10.9494 5.30932 10.9407 5.29661C10.9407 5.2948 10.9372 5.29298 10.9355 5.29117C10.9355 5.29117 10.9337 5.28754 10.932 5.28754C10.9302 5.28391 10.9267 5.28209 10.925 5.28028C10.9163 5.26758 10.9076 5.25669 10.8971 5.2458C10.8971 5.24399 10.8936 5.24217 10.8919 5.24036C10.8901 5.23854 10.8866 5.23673 10.8849 5.23491C10.8849 5.23491 10.8814 5.23128 10.8797 5.22947C10.8692 5.22039 10.8587 5.20951 10.8465 5.20043C10.8448 5.20043 10.843 5.19862 10.8413 5.1968C10.8361 5.19317 10.8308 5.19136 10.8273 5.18773C10.8151 5.18047 10.8029 5.17321 10.789 5.16595L10.782 5.16595C10.782 5.16595 10.7663 5.15869 10.7593 5.15688C10.7471 5.15325 10.7366 5.14781 10.7227 5.14599L10.7175 5.14599C10.707 5.14236 10.6965 5.14236 10.6861 5.14055C10.6773 5.14055 10.6669 5.13692 10.6582 5.13692L10.646 5.13692C10.496 5.13873 7.03219 5.13692 6.62058 0.33152C6.60314 0.1319 6.43396 -0.0150931 6.24211 0.0012395C6.05026 0.019387 5.91073 0.19723 5.92469 0.395035L5.9212 0.395035Z" fill="white"></path>
        </svg>',
    ]);
    

    if (is_array($pages)) {
        $pagination = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">'; // Added 'justify-content-center' to center pagination if needed

        foreach ($pages as $page) {
            // Add active class to the current page
            if (strpos($page, 'current') !== false) {
                $pagination .= '<li class="page-item active" aria-current="page"><span class="page-link">' . strip_tags($page) . '</span></li>';
            } else {
                $pagination .= '<li class="page-item"> ' . str_replace('page-numbers', 'page-link', $page) . '</li>';
            }
        }

        $pagination .= '</ul></nav>';

        echo $pagination;
    }
}

/**
 * Tạo breadcrumb
 */
function theBreadcrumb() {
    get_template_part('template-parts/breadcrumb');
}
function theShareSocials() {
    get_template_part('template-parts/share_box');
}

function getPageTitle() {
    $obj   = get_queried_object();
    $title = get_bloginfo('name');
    if (is_single() || is_page()) {
        $title = get_the_title();
    } elseif (is_search()) {
        /* translators: search results page title */
        $title = sprintf(__('Kết quả tìm kiếm cho từ khóa: %s', 'mms'), get_search_query());
    } elseif (is_category()) {
        /* translators: category post listing page title */
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        /* translators: tag post listing page title */
        $title = sprintf(__('Tag: %s', 'mms'), single_tag_title('', false));
    } elseif (is_day()) {
        /* translators: day archive post listing page title */
        $title = sprintf(__('Daily Archives: %s', 'mms'), get_the_time('F jS, Y'));
    } elseif (is_month()) {
        /* translators: month archive post listing page title */
        $title = sprintf(__('Monthly Archives: %s', 'mms'), get_the_time('F, Y'));
    } elseif (is_year()) {
        /* translators: year archive post listing page title */
        $title = sprintf(__('Yearly Archives: %s', 'mms'), get_the_time('Y'));
    } elseif (is_author()) {
        /* translators: author archive post listing page title */
        $title = sprintf(__('Posts by %s', 'mms'), get_the_author());
    } elseif (class_exists('WooCommerce') && is_woocommerce()) {
        $title = woocommerce_page_title(false);
    } elseif (is_archive()) {
        if ($obj instanceof WP_Term) {
            $title = $obj->name;
        } elseif ($obj instanceof WP_Post_Type) {
            $title = $obj->label;
        }
    } elseif (is_404()) {
        $title = __('Lỗi 404 - Không tìm thấy trang bạn yêu cầu', 'mms');
    }
    return $title;
}
function thePageTitle() {
	echo getPageTitle();
}

function theLanguageSwitcher($showName = true, $showFlag = false) {
  if (function_exists('pll_the_languages')) {
      $languages = pll_the_languages([
          'show_names'    => $showName,
          'show_flags'    => $showFlag,
          'hide_if_empty' => false,
          'hide_current'  => true,
          'raw'           => true,
      ]);

      echo '<ul class="language-switcher">';
      foreach ($languages as $lang) {
          $icon_html = '<span class="iconify" data-icon="ant-design:global-outlined"></span>';
          echo '<li><a href="'. esc_url($lang['url']) .'" hreflang="'. esc_attr($lang['slug']) .'">' . $icon_html . ' ' . esc_html($lang['name']) . '</a></li>';
      }
      echo '</ul>';
  }
}
