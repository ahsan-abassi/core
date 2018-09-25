<?php
/**
 * Archives template.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}
?>
<?php get_header('core'); ?>
    <section id="content" <?php Avada()->layout->add_class( 'content_class' ); ?> <?php Avada()->layout->add_style( 'content_style' ); ?>>
        <?php if ( category_description() ) : ?>
            <div id="post-<?php the_ID(); ?>" <?php post_class( 'fusion-archive-description' ); ?>>
                <div class="post-content">
                    <?php //echo category_description(); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php //get_template_part( 'templates/blog', 'layout' ); ?>
        <?php get_template_part( 'templates/blog-core', 'layout' ); ?>
    </section>
<?php do_action( 'avada_after_content' ); ?>
<?php
get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */







//&autoplay=1&controls=0&disablekb=1&fs=0&iv_load_policy=3&loop=1&modestbranding=1&rel=0
//
//version=3&enablejsapi=1&html5=1&hd=1&wmode=opaque&showinfo=0&ref=0&&rel=0&iv_load_policy=3&controls=0&modestbranding=1
//
//
//&version=3&enablejsapi=1&html5=1&hd=1&wmode=opaque&showinfo=0&ref=0&&rel=0&iv_load_policy=3&controls=0&modestbranding=1
