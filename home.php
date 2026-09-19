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
		'title'         => __( 'Live Feed', 'birds' ),
		'description'   => __( 'ordered by recent publication', 'birds' ),
		'show_composer' => true,
		'query'         => $GLOBALS['wp_query'],
	)
);
get_footer();
