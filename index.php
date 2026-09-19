<?php
/**
 * Fallback template.
 *
 * @package Birds
 */

get_header();
get_template_part(
	'template-parts/feed-page',
	null,
	array(
		'title'       => __( 'Birds', 'birds' ),
		'description' => __( 'the WordPress content stream', 'birds' ),
		'query'       => $GLOBALS['wp_query'],
	)
);
get_footer();
