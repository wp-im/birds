<?php
/**
 * Date, category and tag archives.
 *
 * @package Birds
 */

get_header();

$archive_title = get_the_archive_title();
$description   = get_the_archive_description();

get_template_part(
	'template-parts/feed-page',
	null,
	array(
		'title'       => wp_strip_all_tags( $archive_title ),
		'description' => $description ? wp_strip_all_tags( $description ) : __( 'ordered by recent publication', 'birds' ),
		'query'       => $GLOBALS['wp_query'],
	)
);

get_footer();
