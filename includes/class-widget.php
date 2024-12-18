<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

class EPCW_Product_Count_Widget extends Widget_Base {

    public function get_name() {
        return 'product_count_widget';
    }

    public function get_title() {
        return __( 'Product Count Widget', 'epcw' );
    }

    public function get_icon() {
        return 'eicon-product';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Product Count Settings', 'epcw' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __( 'Select Category', 'epcw' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_product_categories(),
                'multiple' => false,
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    private function get_product_categories() {
        $terms = get_terms( [
            'taxonomy' => 'product_cat',
            'orderby'  => 'name',
            'hide_empty' => false,
        ] );
        
        $categories = [];
        foreach ( $terms as $term ) {
            $categories[ $term->term_id ] = $term->name;
        }
        return $categories;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $category_id = $settings['category'];

        if ( ! empty( $category_id ) ) {
            $args = [
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => [
                    [
                        'taxonomy' => 'product_cat',
                        'field'    => 'id',
                        'terms'    => $category_id,
                    ],
                ],
            ];

            $query = new \WP_Query( $args );
            $product_count = $query->found_posts;

            echo '<div class="epcw-product-count">';
            echo '<p>' . sprintf( __( '%d محصول ', 'epcw' ), $product_count ) . '</p>';
            echo '</div>';
        }
    }
}
