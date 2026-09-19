<?php
/**
 * Author stream.
 *
 * @package Birds
 */

get_header();

$author      = get_queried_object();
$author_name = isset( $author->display_name ) ? $author->display_name : get_the_author();
$description = isset( $author->description ) ? $author->description : '';

get_template_part(
	'template-parts/feed-page',
	null,
	array(
		'title'       => sprintf( __( 'Author — %s', 'birds' ), $author_name ),
		'description' => $description ? $description : __( 'the same post stream, viewed by author', 'birds' ),
		'query'       => $GLOBALS['wp_query'],
	)
);

get_footer();
