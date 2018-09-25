<?php
/**
 * Header-v6 template.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

$content_2 = avada_secondary_header_content( 'header_right_content' );
?>
<div class="fusion-header-sticky-height"></div>
<div class="fusion-header">
	<div class="fusion-row">
		<div class="fusion-header-v6-content fusion-header-has-flyout-menu-content">

            <div class="menu-icon-lag-switch">
                <?php do_action('wpml_add_language_selector'); ?>
            </div>
			<?php
			avada_logo();
			$menu = avada_main_menu( true );
			?>

			<div class="fusion-flyout-menu-icons">
				<?php if ( class_exists( 'WooCommerce' ) && Avada()->settings->get( 'woocommerce_cart_link_main_nav' ) ) : ?>
					<?php
					global $woocommerce;

					$cart_link_text  = '';
					$cart_link_class = '';
					if ( Avada()->settings->get( 'woocommerce_cart_counter' ) && $woocommerce->cart->get_cart_contents_count() ) {
						$cart_link_text  = '<span class="fusion-widget-cart-number">' . $woocommerce->cart->get_cart_contents_count() . '</span>';
						$cart_link_class = ' fusion-widget-cart-counter';
					}
				?>
					<div class="fusion-flyout-cart-wrapper">
						<a href="<?php echo esc_attr( get_permalink( get_option( 'woocommerce_cart_page_id' ) ) ); ?>" class="fusion-icon fusion-icon-shopping-cart<?php echo esc_attr( $cart_link_class ); ?>" aria-hidden="true" aria-label="<?php esc_attr_e( 'Toggle Shopping Cart', 'Avada' ); ?>"><?php echo $cart_link_text; // WPCS: XSS ok. ?></a>
					</div>
				<?php endif; ?>

				<?php if ( 'menu' === Avada()->settings->get( 'slidingbar_toggle_style' ) && Avada()->settings->get( 'slidingbar_widgets' ) ) : ?>
					<?php $sliding_bar_label = esc_attr__( 'Toggle Sliding Bar', 'Avada' ); ?>
					<div class="fusion-flyout-sliding-bar-toggle">
						<a href="#" class="fusion-toggle-icon fusion-icon fusion-icon-sliding-bar" aria-label="<?php echo esc_attr( $sliding_bar_label ); ?>"></a>
					</div>
				<?php endif; ?>

				<?php if ( Avada()->settings->get( 'main_nav_search_icon' ) ) : ?>
					<div class="fusion-flyout-search-toggle">
						<div class="fusion-toggle-icon">
							<div class="fusion-toggle-icon-line"></div>
							<div class="fusion-toggle-icon-line"></div>
							<div class="fusion-toggle-icon-line"></div>
						</div>
						<a class="fusion-icon fusion-icon-search" aria-hidden="true" aria-label="<?php esc_attr_e( 'Toggle Search', 'Avada' ); ?>" href="#"></a>
					</div>
				<?php endif; ?>

                <?php if ( $content_2 ) : ?>
                    <div class="social-top-bar">
                        <?php echo $content_2; // WPCS: XSS ok. ?>
                    </div>
                <?php endif; ?>

				<a class="fusion-flyout-menu-toggle" aria-hidden="true" aria-label="<?php esc_attr_e( 'Toggle Menu', 'Avada' ); ?>" href="#">
					<div class="fusion-toggle-icon-line"></div>
					<div class="fusion-toggle-icon-line"></div>
					<div class="fusion-toggle-icon-line"></div>
				</a>
			</div>
		</div>

		<div class="fusion-main-menu fusion-flyout-menu" role="navigation" aria-label="Main Menu">
            <div class="left-container" style="float:left;min-width: 250px; vertical-align: top;">
                <?php echo $menu; // WPCS: XSS ok. ?>
                <!--Menu adress widget-->
                <?php if ( is_active_sidebar( 'custom-header-widget' ) ) : ?>
                    <div id="header-widget-area" class="menu-text-widget-area" role="complementary">
                        <?php dynamic_sidebar( 'custom-header-widget' ); ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="right-container" style="float:left;width: 300px;vertical-align: top;">
                <!--Menu contact widget-->
                <?php if ( is_active_sidebar( 'custom-contact-header-widget' ) ) : ?>
                    <div id="header-widget-contact-area" class="menu-contact-widget-area" role="complementary">
                        <?php dynamic_sidebar( 'custom-contact-header-widget' ); ?>
                    </div>
                <?php endif; ?>
            </div>
		</div>

		<?php if ( Avada()->settings->get( 'main_nav_search_icon' ) ) : ?>
			<div class="fusion-flyout-search">
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>

		<div class="fusion-flyout-menu-bg"></div>
	</div>
</div>
