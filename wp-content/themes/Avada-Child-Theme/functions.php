<?php
//includ file for resize of images// override
require_once('extra_class.php' );


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

//added by Fawad
// Changing excerpt more
function new_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');


//Added By Fawad custom field for our people
function ourpeople_add_meta_box() {
    $screens = array( 'our_people' );

    foreach ( $screens as $screen ) {

            add_meta_box(
                'our_people_designationid',
                __( 'Our People Details', 'our_people_designation' ),
                'our_people_meta_box_callback',
                $screen
            );
    }
}
add_action( 'add_meta_boxes', 'ourpeople_add_meta_box' );




function our_people_meta_box_callback( $post ) {

// Add a nonce field so we can check for it later.
    wp_nonce_field( 'our_people_save_meta_box_data', 'our_people_meta_box_nonce' );

    $value = get_post_meta( $post->ID, '_my_meta_value_key', true );
    echo '<label for="our_people_new_field">';
    _e( 'Designation', 'our_people_designation' );
    echo '</label> ';
    echo '<input type="text" id="our_people_new_field" name="our_people_new_field" value="' . esc_attr( $value ) . '" size="25" />';
    echo "<br/>";

    $value2 = get_post_meta( $post->ID, '_our_people_pnumber', true );
    echo '<label for="our_people_pnumber">';
    _e( 'Phone Number', 'our_people_pnumber' );
    echo '</label> ';
    echo '<input type="text" id="our_people_pnumber" name="our_people_pnumber" value="' . esc_attr( $value2 ) . '" size="25" />';
    echo "<br/>";

    $value3 = get_post_meta( $post->ID, '_our_people_email', true );
    echo '<label for="our_people_email">';
    _e( 'Email', 'our_people_email' );
    echo '</label> ';
    echo '<input type="text" id="our_people_email" name="our_people_email" value="' . esc_attr( $value3 ) . '" size="25" />';
}


/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function our_people_save_meta_box_data( $post_id ) {

    if ( ! isset( $_POST['our_people_meta_box_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['our_people_meta_box_nonce'], 'our_people_save_meta_box_data' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    if (isset( $_POST['our_people_new_field'] ) ) {
        $my_data = sanitize_text_field( $_POST['our_people_new_field'] );
        update_post_meta( $post_id, '_my_meta_value_key', $my_data );
    }
    if (isset( $_POST['our_people_pnumber'] ) ) {
        $my_data = sanitize_text_field( $_POST['our_people_pnumber'] );
        update_post_meta( $post_id, '_our_people_pnumber', $my_data );
    }
    if (isset( $_POST['our_people_email'] ) ) {
        $my_data = sanitize_text_field( $_POST['our_people_email'] );
        update_post_meta( $post_id, '_our_people_email', $my_data );
    }




}
add_action( 'save_post', 'our_people_save_meta_box_data' );


//Blog Post Type
function custom_post_for_core() {

    $labels = array(
        'name'                  => _x( 'Blogs', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Blog', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Blog', 'text_domain' ),
        'name_admin_bar'        => __( 'Blog', 'text_domain' ),
        'archives'              => __( 'Blog Archives', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Blog:', 'text_domain' ),
        'all_items'             => __( 'All Blogs', 'text_domain' ),
        'add_new_item'          => __( 'Add New', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Blog', 'text_domain' ),
        'edit_item'             => __( 'Edit Blog', 'text_domain' ),
        'update_item'           => __( 'Update Blog', 'text_domain' ),
        'view_item'             => __( 'View Blog', 'text_domain' ),
        'search_items'          => __( 'Search Blog', 'text_domain' ),
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
        'label'                 => __( 'Blog', 'text_domain' ),
        'description'           => __( 'use for custom Blog posts', 'text_domain' ),
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
    register_post_type( 'blog', $args );

    register_taxonomy(
        'qcore',
        'blog',
        array(
            'label' => __( 'Blog Categories' ),
            'public' => true,
            'query_var' => true,
            'rewrite' => true,
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'custom_post_for_core', 0 );


function core_shortcode($atts = []) {

    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    ob_start();
    //$param = 'post_type=core&showposts=3&orderby=desc';
    $param = array(
        'post_type' => 'blog',
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
    $count = 0;
    while( $query->have_posts() ):$query->the_post();
        $count++;
        $thumbnail_img_url = get_the_post_thumbnail_url(get_the_ID(),'portfolio-three');
        $data .= '<div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_3 fusion-one-third front-core count_'.$count.'  id="'.get_the_ID().'">';
        $data .= '<ul>';

        global $_wp_additional_image_sizes;
        print '<pre style="display: none">';
        print_r( $_wp_additional_image_sizes );
        print '</pre>';

        $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
        $data .= '<li style="background-image: url('.$thumbnail_img_url.')">';
        $data .= '<a href="'.get_the_permalink().'">';
        //$data .= '<span class="multiply-overlay"></span>';
        //$data .= '<span class="darken-overlay"></span>';
        $data .= '<h4>'.get_the_title().'</h4>';
        $data .= '</a>';
        $data .= '</li>';
        $data .= '<div class="text">';
        //$data .= '<P>'.get_the_excerpt().'</P>';

        if(has_excerpt()) {
        $data .= '<p>'.get_the_excerpt();'</p>';
        }else{
        $content = get_the_content();
        $data .= '<p>'.mb_strimwidth($content, 0, 95, '...').'</p>';
        }

        $data .= '</div>';
        $data .= '</ul>';
        //$data .= '<div class="clientDetails">- '.get_the_title().'<span>('.get_the_excerpt().')</span></div>';
        $data .= '</div>';
    endwhile; wp_reset_postdata();
    return $data;
}
add_shortcode('core_p','core_shortcode');





//Our people Post Type


function custom_post_for_our_people() {

    $labels = array(
        'name'                  => _x( 'Our people', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Our people', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Our people', 'text_domain' ),
        'name_admin_bar'        => __( 'Our people', 'text_domain' ),
        'archives'              => __( 'Our people Archives', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Our people:', 'text_domain' ),
        'all_items'             => __( 'Our people', 'text_domain' ),
        'add_new_item'          => __( 'Add New', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New People', 'text_domain' ),
        'edit_item'             => __( 'Edit People', 'text_domain' ),
        'update_item'           => __( 'Update People', 'text_domain' ),
        'view_item'             => __( 'View People', 'text_domain' ),
        'search_items'          => __( 'Search People', 'text_domain' ),
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
        'label'                 => __( 'Our people', 'text_domain' ),
        'description'           => __( 'use for custom Our people posts', 'text_domain' ),
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
    register_post_type( 'our_people', $args );

    register_taxonomy(
        'qourpeople',
        'our_people',
        array(
            'label' => __( 'Our people Categories' ),
            'public' => true,
            'query_var' => true,
            'rewrite' => true,
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'custom_post_for_our_people', 0 );


function ourpeople_shortcode($atts = []) {

    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    ob_start();
    //$param = 'post_type=core&showposts=3&orderby=desc';
    $param = array(
        'post_type' => 'our_people',
        'posts_per_page' => 15,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );
    if(isset($atts['catid'])){
        $param['tax_query']= array(
            array(
                'taxonomy' => 'qourpeople',
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
        $thumbnail_img_url = get_the_post_thumbnail_url(get_the_ID(),'portfolio-three');
        $data .= '<div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_4  fusion-one-fourth front-core our-people-main  id="'.get_the_ID().'">';
        $data .= '<ul>';
        $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');

//        global $_wp_additional_image_sizes;
//        print '<pre>';
//        print_r( $_wp_additional_image_sizes );
//        print '</pre>';
        //$data .= '<a href="'.get_the_permalink().'">';
        $data .= '<a class="fusion-modal-text-link" href="#" rel="noopener noreferrer" data-toggle="modal" data-target=".fusion-modal.image_content_'.get_the_ID().'">';
        $data .= '<li style="background-image: url('.$featured_img_url.')">';




        //$data .= '<div class="text">';
        //$data .= '<h4>'.get_the_title().'</h4>';
        //$data .= '</div>';
        $data .= '</li>';
        $data .= '</a>';

        $data .= '<div class="fusion-modal modal fade modal-8 image_content_'.get_the_ID().' in" tabindex="-1" role="dialog" aria-labelledby="modal-heading-8" aria-hidden="false"><style type="text/css">.modal-8 .modal-header, .modal-8 .modal-footer{border-color:#ebebeb;}.modal-footer{text-align: center;margin: 0 auto;width: 25%;}</style>

            <div class="modal-dialog modal-lg">
                <div class="modal-content fusion-modal-content" style="background-color:#f6f6f6">
                    <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body fusion-clearfix">
                        <h4 style="text-align: center;" data-fontsize="32" data-lineheight="46">
                            <span style="color: #314756;">'.get_the_title().'</span><br/>
                            <span style="color: #314756; font-size: 18px;">'.get_post_meta(get_the_ID(),'_my_meta_value_key',true).'</span>
                        </h4>
                        <p style="color: #314756; font-size: 14px; text-align: center">'.get_the_content().'</p>
                    </div>
                    <div class="modal-footer">
                        <a style="background: #203e4a;" class="fusion-button button-default button-medium button default medium color-green" data-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>
        </div>';

        $data .= '<div class="fusion-portfolio-content">';
        $data .= '<div class="name">';
        $data .= '<h4>'.get_the_title().'</h4>';
        $data .= '</div>';
        $data .= '<div class="designation">';
        $data .= '<span>'.get_post_meta(get_the_ID(),'_my_meta_value_key',true).'</span>';
        $data .= '</div>';
        $data .= '<div class="phone">';
        $data .= '<p>'.get_post_meta(get_the_ID(),'_our_people_pnumber',true).'</p>';
        $data .= '</div>';
        $data .= '<div class="email">';
        $data .= '<p>'.get_post_meta(get_the_ID(),'_our_people_email',true).'</p>';
        $data .= '</div>';
        $data .= '</div>';

        //$data .='<p>'.get_the_content().'</p>';


        $data .= '</ul>';
        //$data .= '<div class="clientDetails">- '.get_the_title().'<span>('.get_the_excerpt().')</span></div>';
        $data .= '</div>';

    endwhile; wp_reset_postdata();
    return $data;
}
add_shortcode('qourpeople_p','ourpeople_shortcode');



// add to your theme's functions.php file
add_filter('upload_mimes', 'add_custom_upload_mimes');
function add_custom_upload_mimes($existing_mimes) {
    $existing_mimes['otf'] = 'application/x-font-otf';
    $existing_mimes['woff'] = 'application/x-font-woff';
    $existing_mimes['ttf'] = 'application/x-font-ttf';
    $existing_mimes['svg'] = 'image/svg+xml';
    $existing_mimes['eot'] = 'application/vnd.ms-fontobject';
    return $existing_mimes;
}


//adding widget area into menu

function wpb_widgets_init() {

 register_sidebar( array(

 'name' => 'Header Menu Text Widget Area',

 'id' => 'custom-header-widget',

 'before_widget' => '<div class="adress-widget">',

 'after_widget' => '</div>',

 'before_title' => '<h2 class="chw-title">',

 'after_title' => '</h2>',

 ) );

}

add_action( 'widgets_init', 'wpb_widgets_init' );



//custom contact widget area into menu

function wpc_widgets_init() {

    register_sidebar( array(

        'name' => 'Header Menu Contact Widget Area',

        'id' => 'custom-contact-header-widget',

        'before_widget' => '<div class="contact-widget">',

        'after_widget' => '</div>',

        'before_title' => '<h2 class="contact-title">',

        'after_title' => '</h2>',

    ) );

}

add_action( 'widgets_init', 'wpc_widgets_init' );