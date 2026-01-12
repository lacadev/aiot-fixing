<?php

namespace App\PostTypes;

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field;
use mms\Abstracts\AbstractPostType;

class project extends \App\Abstracts\AbstractPostType
{

    public function __construct() {
        $this->showThumbnailOnList = true;
        $this->supports            = [
            'title',
            'editor',
            'thumbnail',
            'page-attributes',
        ];

        $this->menuIcon         = 'dashicons-admin-site-alt3'; //https://developer.wordpress.org/resource/dashicons/
        // $this->menuIcon = get_template_directory_uri() . '/images/custom-icon.png';
        $this->post_type        = 'project';
        $this->singularName     = $this->pluralName = __('Project', 'mms'); 
        $this->titlePlaceHolder = __('Project', 'mms');
        $this->slug             = 'project';
        add_action( 'carbon_fields_register_fields', [ $this, 'metaFields' ] );
        parent::__construct();
    }

    /**
     * Document: https://docs.carbonfields.net/#/containers/post-meta
     */
    public function metaFields()
    {
        Container::make('post_meta', __('Description | Mô tả', 'mms'))
            ->set_context('advanced') // Sử dụng context 'normal' để hiển thị trong Gutenberg
            ->set_priority('high')
            ->where('post_type', 'IN', [$this->post_type])
            ->add_fields([
                Field::make('color', 'color', __('Màu đại diện', 'mms'))
                    ->set_width(30),
                
                Field::make('complex', 'system_destination', __('システム導入先 | Danh sách hệ thống và địa điểm', 'mms'))
                    ->set_layout('tabbed-horizontal')
                    ->set_width(70)
                    ->add_fields([
                        Field::make('text', 'content', __('Content | Nội dung', 'mms')),
                    ])->set_header_template('<% if (content) { %><%- content %><% } %>'),
                
                Field::make('rich_text', 'description', __('', 'mms')),

            ]);
    }
}
