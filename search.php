<?php
/**
 * Search results.
 *
 * @package Birds
 */

get_header();

$search_query = get_search_query();

get_template_part(
	'template-parts/feed-page',
	null,
	array(
		'title'       => sprintf( __( 'Search — %s', 'birds' ), $search_query ),
		'description' => __( 'the same post renderer, filtered by WordPress search', 'birds' ),
		'query'       => $GLOBALS['wp_query'],
	)
);

get_footer();
