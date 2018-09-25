<?php

function theme_enqueue_styles() {
    //wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
    wp_enqueue_style( 'parent-style', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/css/custom.css', array( 'parent-style' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );




add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');



if ( ! function_exists( 'avada_main_menu' ) ) {
    /**
     * The main menu.
     *
     * @param bool $flyout_menu Whether we want the flyout menu or not.
     */
    function avada_main_menu($flyout_menu = false)
    {

        $menu_class = 'fusion-menu';
        if ('v7' === Avada()->settings->get('header_layout')) {
            $menu_class .= ' fusion-middle-logo-ul';
        }

        $main_menu_args = array(
            'theme_location' => 'main_navigation',
            'depth' => 5,
            'menu_class' => $menu_class,
            'items_wrap' => '<ul role="menubar" id="%1$s" class="%2$s">%3$s</ul>',
            'fallback_cb' => 'Avada_Nav_Walker::fallback',
            'walker' => new Avada_Nav_Walker(),
            'container' => false,
            'item_spacing' => 'discard',
            'echo' => false,
        );

        if ($flyout_menu) {
            $flyout_menu_args = array(
                'depth' => 2,
                'container' => false,
            );

            $main_menu_args = wp_parse_args($flyout_menu_args, $main_menu_args);

            $main_menu = wp_nav_menu($main_menu_args);

            if (has_nav_menu('sticky_navigation')) {
                $sticky_menu_args = array(
                    'theme_location' => 'sticky_navigation',
                    'menu_id' => 'menu-main-menu-1',
                    'items_wrap' => '<ul role="menubar" id="%1$s" class="%2$s">%3$s</ul>',
                    'walker' => new Avada_Nav_Walker(),
                    'item_spacing' => 'discard',
                );
                $sticky_menu_args = wp_parse_args($sticky_menu_args, $main_menu_args);
                $main_menu .= wp_nav_menu($sticky_menu_args);
            }

            return $main_menu;

        } else {
            $uber_menu_class = '';
            if (function_exists('ubermenu_get_menu_instance_by_theme_location')) {
                $uber_menu_class = ' fusion-ubermenu';
            }

            echo '<nav class="fusion-main-menu' . esc_attr($uber_menu_class) . '" aria-label="Main Menu">';
            echo wp_nav_menu($main_menu_args);
            echo '</nav>';

            if (has_nav_menu('sticky_navigation') && 'Top' === Avada()->settings->get('header_position') && (!function_exists('ubermenu_get_menu_instance_by_theme_location') || (function_exists('ubermenu_get_menu_instance_by_theme_location') && !ubermenu_get_menu_instance_by_theme_location('sticky_navigation')))) {

                $sticky_menu_args = array(
                    'theme_location' => 'sticky_navigation',
                    'menu_id' => 'menu-main-menu-1',
                    'walker' => new Avada_Nav_Walker(),
                    'item_spacing' => 'discard',
                );

                $sticky_menu_args = wp_parse_args($sticky_menu_args, $main_menu_args);

                echo '<nav class="fusion-main-menu fusion-sticky-menu" aria-label="Main Menu Sticky">';
                echo wp_nav_menu($sticky_menu_args);
                echo '</nav>';
            }

            // Make sure mobile menu is not loaded when we use slideout menu or ubermenu.
            if (!function_exists('ubermenu_get_menu_instance_by_theme_location') || (function_exists('ubermenu_get_menu_instance_by_theme_location') && !ubermenu_get_menu_instance_by_theme_location('main_navigation'))) {
                if (has_nav_menu('mobile_navigation')) {
                    $mobile_menu_args = array(
                        'theme_location' => 'mobile_navigation',
                        'menu_class' => 'fusion-mobile-menu',
                        'depth' => 5,
                        'walker' => new Avada_Nav_Walker(),
                        'item_spacing' => 'discard',
                        'container_class' => 'fusion-mobile-navigation',
                    );
                    echo wp_nav_menu($mobile_menu_args);
                }

                avada_mobile_main_menu();
            }
        } // End if().
    }

//} // End if().
}

//Core Post Type


function custom_post_for_core() {

    $labels = array(
        'name'                  => _x( 'Cores', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Core', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Core', 'text_domain' ),
        'name_admin_bar'        => __( 'Core', 'text_domain' ),
        'archives'              => __( 'Core Archives', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Core:', 'text_domain' ),
        'all_items'             => __( 'All Cores', 'text_domain' ),
        'add_new_item'          => __( 'Add New', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Core', 'text_domain' ),
        'edit_item'             => __( 'Edit Core', 'text_domain' ),
        'update_item'           => __( 'Update Core', 'text_domain' ),
        'view_item'             => __( 'View Core', 'text_domain' ),
        'search_items'          => __( 'Search Core', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
        'items_list'            => __( 'Items list', 'text_domain' ),
        'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Core', 'text_domain' ),
        'description'           => __( 'use for custom Core posts', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', ),

        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'core', $args );

    register_taxonomy(
        'qcore',
        'core',
        array(
            'label' => __( 'Core Categories' ),
            'public' => true,
            'query_var' => true,
            'rewrite' => true,
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'custom_post_for_core', 0 );


//short Code for Custom Post Type

//function hello() {
//    return 'Hello, World!';
//}
//add_shortcode('core', 'hello');


function core_shortcode($atts = []) {

    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    ob_start();
    //$param = 'post_type=core&showposts=3&orderby=desc';
    $param = array(
        'post_type' => 'core',
        'posts_per_page' => 3,
        'orderby' => 'title',
        'order'   => 'DESC',
    );
    if(isset($atts['catid'])){
        $param['tax_query']= array(
            array(
                'taxonomy' => 'qcore',
                'field'    => 'id',
                'terms'    => $atts['catid'],
        ));
        //array_push($tax_query,$param);
    }
//    echo '<pre>';
//    print_r($param);
//    echo '</pre>';
    $query = new WP_Query($param);
    $data = '';

    while( $query->have_posts() ):$query->the_post();
        $thumbnail_img_url = get_the_post_thumbnail_url(get_the_ID(),'thumbnail');
        $data .= '<div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_3  fusion-one-third front-core  id="'.get_the_ID().'">';
        $data .= '<ul>';
        $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
        $data .= '<li style="background-image: url('.$thumbnail_img_url.')">';
        $data .= '<a href="'.get_the_permalink().'">';
        $data .= '<span class="multiply-overlay"></span>';
        $data .= '<span class="darken-overlay"></span>';
        $data .= '<div class="text">';
        $data .= '<h4>'.get_the_title().'</h4>';
        $data .= '</div>';
        $data .= '</a>';
        $data .= '</li>';
        $data .= '</ul>';
        //$data .= '<div class="clientDetails">- '.get_the_title().'<span>('.get_the_excerpt().')</span></div>';
        $data .= '</div>';
    endwhile; wp_reset_postdata();
    return $data;
}
add_shortcode('core_p','core_shortcode');
