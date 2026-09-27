<?php
/**
 * Posts page fallback.
 *
 * @package Birds
 */

get_header();
get_template_part(
	'template-parts/feed-page',
	null,
	array(
		'title'         => birds_theme_option( 'feed_title', __( 'Live Feed', 'birds' ) ),
		'description'   => birds_theme_option( 'feed_description', __( 'ordered by recent publication', 'birds' ) ),
		'show_composer' => birds_theme_option( 'show_composer', true ),
		'query'         => $GLOBALS['wp_query'],
	)
);
get_footer();
