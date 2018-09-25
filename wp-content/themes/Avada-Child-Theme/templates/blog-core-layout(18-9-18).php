<?php
/**
 * Blog-layout template.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      1.0.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

global $wp_query;

// Set the correct post container layout classes.
$blog_layout_custom = 'grid';
$blog_layout = avada_get_blog_layout();

$pagination_type = Avada()->settings->get( 'blog_pagination_type' );
$post_class  = 'fusion-post-' . $blog_layout;

// Masonry needs additional grid class.
if ( 'masonry' === $blog_layout ) {
	$post_class .= ' fusion-post-grid';
}

$container_class = 'fusion-posts-container ';
$wrapper_class = 'fusion-blog-layout-' . $blog_layout . '-wrapper ';

if ( 'grid' === $blog_layout || 'masonry' === $blog_layout ) {
	$container_class .= 'fusion-blog-layout-grid fusion-blog-layout-grid-' . Avada()->settings->get( 'blog_archive_grid_columns' ) . ' isotope ';

	if ( 'masonry' === $blog_layout ) {
		$container_class .= 'fusion-blog-layout-' . $blog_layout . ' ';
	}
} else if ( 'timeline' !== $blog_layout ) {
	$container_class .= 'fusion-blog-layout-' . $blog_layout . ' ';
}

if ( ! Avada()->settings->get( 'post_meta' ) || ( ! Avada()->settings->get( 'post_meta_author' ) && ! Avada()->settings->get( 'post_meta_date' ) && ! Avada()->settings->get( 'post_meta_cats' ) && ! Avada()->settings->get( 'post_meta_tags' ) && ! Avada()->settings->get( 'post_meta_comments' ) && ! Avada()->settings->get( 'post_meta_read' ) ) ) {
	$container_class .= 'fusion-no-meta-info ';
}

if ( Avada()->settings->get( 'blog_equal_heights' ) && 'grid' === $blog_layout ) {
	$container_class .= 'fusion-blog-equal-heights ';
}

// Set class for scrolling type.
if ( 'Infinite Scroll' === $pagination_type ) {
	$container_class .= 'fusion-posts-container-infinite ';
	$wrapper_class .= 'fusion-blog-infinite ';
} else if ( 'load_more_button' === $pagination_type ) {
	$container_class .= 'fusion-posts-container-infinite fusion-posts-container-load-more ';
} else {
	$container_class .= 'fusion-blog-pagination ';
}

if ( ! Avada()->settings->get( 'featured_images' ) ) {
	$container_class .= 'fusion-blog-no-images ';
}

// Add class if rollover is enabled.
if ( Avada()->settings->get( 'image_rollover' ) && Avada()->settings->get( 'featured_images' ) ) {
	$container_class .= ' fusion-blog-rollover';
}

$content_align = Avada()->settings->get( 'blog_layout_alignment' );
if ( $content_align && ( 'grid' === $blog_layout || 'masonry' === $blog_layout || 'timeline' === $blog_layout ) ) {
	$container_class .= ' fusion-blog-layout-' . $content_align;
}

$number_of_pages = $wp_query->max_num_pages;
if ( is_search() && Avada()->settings->get( 'search_results_per_page' ) ) {
	$number_of_pages = ceil( $wp_query->found_posts / Avada()->settings->get( 'search_results_per_page' ) );
}
?>
<div id="posts-container" class="fusion-blog-archive <?php echo esc_attr( $wrapper_class ); ?>fusion-clearfix">
    <div class="all-wraper">
        <h4 class="core-blog-title">Core Blog</h4>
    </div>

	<div class="<?php echo esc_attr( $container_class ); ?>" data-pages="<?php echo (int) $number_of_pages; ?>">
		<?php if ( 'timeline' === $blog_layout ) : ?>
			<?php // Add the timeline icon. ?>
			<div class="fusion-timeline-icon"><i class="fusion-icon-bubbles"></i></div>
			<div class="fusion-blog-layout-timeline fusion-clearfix">

			<?php
			// Initialize the time stamps for timeline month/year check.
			$post_count = 1;
			$prev_post_timestamp = null;
			$prev_post_month = null;
			$prev_post_year = null;
			$first_timeline_loop = false;
			?>

			<?php // Add the container that holds the actual timeline line. ?>
			<div class="fusion-timeline-line"></div>
		<?php endif; ?>

		<?php if ( 'masonry' === $blog_layout ) : ?>
			<article class="fusion-post-grid fusion-post-masonry post fusion-grid-sizer"></article>
		<?php endif; ?>

		<?php // Start the main loop. 
		$argsQuery =     array(
		  'post_type'   => 'core',  // only query post type
		  'tax_query'   => array(
		    array(
		        'taxonomy'  => 'qcore',
		        'field'     => 'slug',
		        'terms'     => 'our-values',
		        'operator'  => 'NOT IN') // seems that's what you're missing
		        ),
		   );

		$custom_query = new WP_Query( $argsQuery ); 
		?>
		<?php while ( $custom_query->have_posts() ) : ?>
			<?php $custom_query->the_post(); ?>
			<?php
			$thumbnail_img_url = get_the_post_thumbnail_url(get_the_ID(),'thumbnail');
			$permalink_url = get_permalink( $post->ID );
			// Set the time stamps for timeline month/year check.
			$alignment_class = '';
			if ( 'timeline' === $blog_layout ) {
				$post_timestamp = get_the_time( 'U' );
				$post_month     = date( 'n', $post_timestamp );
				$post_year      = get_the_date( 'Y' );
				$current_date   = get_the_date( 'Y-n' );

				// Set the correct column class for every post.
				if ( $post_count % 2 ) {
					$alignment_class = 'fusion-left-column';
				} else {
					$alignment_class = 'fusion-right-column';
				}

				// Set the timeline month label.
				if ( $prev_post_month != $post_month || $prev_post_year != $post_year ) {

					if ( $post_count > 1 ) {
						echo '</div>';
					}
					echo '<h3 class="fusion-timeline-date">' . get_the_date( Avada()->settings->get( 'timeline_date_format' ) ) . '</h3>';
					echo '<div class="fusion-collapse-month">';
				}
			}

			// Set the has-post-thumbnail if a video is used. This is needed if no featured image is present.
			$thumb_class = '';
			if ( get_post_meta( get_the_ID(), 'pyre_video', true ) ) {
				$thumb_class = ' has-post-thumbnail';
			}

			// Masonry layout, get the element orientation class.
			$element_orientation_class = '';
			if ( 'masonry' === $blog_layout ) {
				$masonry_cloumns = Avada()->settings->get( 'blog_archive_grid_columns' );
				$masonry_columns_spacing = Avada()->settings->get( 'blog_archive_grid_column_spacing' );
				$responsive_images_columns = $masonry_cloumns;
				$masonry_attributes = array();
				$element_base_padding = 0.8;

				// Set image or placeholder and correct corresponding styling.
				if ( has_post_thumbnail() ) {
					$post_thumbnail_attachment = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
					$masonry_attribute_style = 'background-image:url(' . $post_thumbnail_attachment[0] . ');';
				} else {
					$post_thumbnail_attachment = array();
					$masonry_attribute_style = 'background-color:#f6f6f6;';
				}

				// Get the correct image orientation class.
				$element_orientation_class = Avada()->images->get_element_orientation_class( get_post_thumbnail_id() );
				$element_base_padding  = Avada()->images->get_element_base_padding( $element_orientation_class );

				$masonry_column_offset = ' - ' . ( (int) $masonry_columns_spacing / 2 ) . 'px';
				if ( false !== strpos( $element_orientation_class, 'fusion-element-portrait' ) ) {
					$masonry_column_offset = '';
				}

				$masonry_column_spacing = ( (int) $masonry_columns_spacing ) . 'px';

				if ( class_exists( 'Fusion_Sanitize' ) && class_exists( 'Fusion_Color' ) &&
					'transparent' !== Fusion_Sanitize::color( Avada()->settings->get( 'timeline_color' ) ) &&
					'0' != Fusion_Color::new_color( Avada()->settings->get( 'timeline_color' ) )->alpha ) {

					$masonry_column_offset = ' - ' . ( (int) $masonry_columns_spacing / 2 ) . 'px';
					if ( false !== strpos( $element_orientation_class, 'fusion-element-portrait' ) ) {
						$masonry_column_offset = ' + 4px';
					}

					$masonry_column_spacing = ( (int) $masonry_columns_spacing - 2 ) . 'px';
					if ( false !== strpos( $element_orientation_class, 'fusion-element-landscape' ) ) {
						$masonry_column_spacing = ( (int) $masonry_columns_spacing - 6 ) . 'px';
					}
				}

				// Check if a featured image is set and also that not a video with no featured image.
				$post_video = get_post_meta( get_the_ID(), 'pyre_video', true );
				if ( ! empty( $post_thumbnail_attachment ) || ! $post_video ) {

					// Calculate the correct size of the image wrapper container, based on orientation and column spacing.
					$masonry_attribute_style .= 'padding-top:calc((100% + ' . $masonry_column_spacing . ') * ' . $element_base_padding . $masonry_column_offset . ');';
				}

				// Check if we have a landscape image, then it has to stretch over 2 cols.
				if ( false !== strpos( $element_orientation_class, 'fusion-element-landscape' ) ) {
					$responsive_images_columns = $masonry_cloumns / 2;
				}

				// Set the masonry attributes to use them in the first featured image function.
				$masonry_attributes = array(
					'class' => 'fusion-masonry-element-container',
					'style' => $masonry_attribute_style,
				);

				// Get the post image.
				Avada()->images->set_grid_image_meta(
					array(
						'layout' => 'portfolio_full',
						'columns' => $responsive_images_columns,
						'gutter_width' => $masonry_columns_spacing,
					)
				);

				$permalink = get_permalink( $post->ID );

				$image = fusion_render_first_featured_image_markup( $post->ID, 'full', $permalink, false, false, false, 'default', 'default', '', '', 'yes', false, $masonry_attributes );

				Avada()->images->set_grid_image_meta( array() );
			} // End if().

			$post_classes = array(
				$post_class,
				$alignment_class,
				$thumb_class,
				$element_orientation_class,
				'post',
				'fusion-clearfix',
			);
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
				<?php if ( 'grid' === $blog_layout_custom ) : ?>
					<?php // Add an additional wrapper for grid layout border. ?>
					<div class="fusion-post-wrapper archieve-page-div">
						<ul style="padding: 0;">
							<li style="background-image: url('<?php echo $thumbnail_img_url; ?>')">
								<a href="<?php echo $permalink_url; ?>">
										<h4><?php the_title();?></h4>
								</a>
							</li>
                            <div class="text">

<!--                                --><?php //if(has_excerpt()) : ?>
<!--                                    <p>--><?php //echo get_the_excerpt(); ?><!--</p>-->
<!--                                --><?php //endif; ?>

                                <?php if(has_excerpt()) { ?>
                                    <p><?php echo get_the_excerpt(); ?></p>
                                <?php }else{ ?>
                                    <p>
                                    <?php
                                    $content = get_the_content();
                                    echo mb_strimwidth($content, 0, 95, '...');
                                    ?>
                                    </p>
                                <?php } ?>

                            </div>
						</ul>
						</ul>
				<?php endif; ?>
			</article>

			<?php
			// Adjust the timestamp settings for next loop.
			if ( 'timeline' === $blog_layout ) {
				$prev_post_timestamp = $post_timestamp;
				$prev_post_month     = $post_month;
				$prev_post_year      = $post_year;
				$post_count++;
			}
			?>

		<?php endwhile; ?>

		<?php if ( 'timeline' === $blog_layout && 1 < $post_count ) : ?>
			</div>
		<?php endif; ?>

	</div>

	<?php // If infinite scroll with "load more" button is used. ?>
	<?php if ( 'load_more_button' === $pagination_type && 1 < $number_of_pages ) : ?>
		<div class="fusion-load-more-button fusion-blog-button fusion-clearfix">
			<?php echo esc_textarea( apply_filters( 'avada_load_more_posts_name', esc_attr__( 'Load More Posts', 'Avada' ) ) ); ?>
		</div>
	<?php endif; ?>
	<?php if ( 'timeline' === $blog_layout ) : ?>
	</div>
	<?php endif; ?>
<?php // Get the pagination. ?>
<?php echo fusion_pagination( '', apply_filters( 'fusion_pagination_size', 1 ) ); // WPCS: XSS ok. ?>
</div>
<?php

wp_reset_postdata();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
