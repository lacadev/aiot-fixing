<?php

/**
 * Register custom taxonomies.
 *
 * @link https://developer.wordpress.org/reference/functions/register_taxonomy/
 *
 * @hook    init
 * @package WPEmergeTheme
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field\Field;

if (!defined('ABSPATH')) {
    exit;
}

// Custom hierarchical taxonomy (like categories).
// phpcs:disable

add_action('init', function () {
    register_taxonomy(
        'project_cat',
        array('project'),
        array(
            'labels'            => array(
                'name'              => __('Project Category', 'app'),
				'singular_name'     => __('Custom Taxonomy', 'app'),
				'search_items'      => __('Search Custom Taxonomies', 'app'),
				'all_items'         => __('All Custom Taxonomies', 'app'),
				'parent_item'       => __('Parent Taxonomy', 'app'),
				'parent_item_colon' => __('Parent Custom Taxonomy:', 'app'),
				'view_item'         => __('View Custom Taxonomy', 'app'),
				'edit_item'         => __('Edit', 'app'),
				'update_item'       => __('Update', 'app'),
				'add_new_item'      => __('Add new', 'app'),
				'new_item_name'     => __('New Custom Taxonomy Name', 'app'),
                'menu_name'         => __('Project Category', 'app'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'project-category'),
        )
    );
});

// phpcs:enable
