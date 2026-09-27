<?php
/**
 * WordPress-native Context Rail widget areas.
 *
 * @package Birds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the two Context Rail widget areas.
 *
 * @return void
 */
function birds_widgets_init() {
	$areas = array(
		'birds-rail-primary'   => __( 'Rail — Primary', 'birds' ),
		'birds-rail-secondary' => __( 'Rail — Secondary', 'birds' ),
	);

	foreach ( $areas as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'description'   => __( 'Shown as a Birds window on the right of the feed and below it on small screens.', 'birds' ),
				'before_widget' => '<section id="%1$s" class="window widget-window %2$s"><div class="titlebar"><span class="control" aria-hidden="true"></span><span class="window-title">' . esc_html__( 'Widget', 'birds' ) . '</span><span class="zoom" aria-hidden="true"></span></div><div class="window-body"><div class="widget-content">',
				'after_widget'  => '</div></div></section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'birds_widgets_init' );
