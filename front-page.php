<?php
/**
 * The live feed front page.
 *
 * @package Birds
 */

get_header();

$front_page_id     = 'page' === get_option( 'show_on_front' ) ? (int) get_option( 'page_on_front' ) : 0;
$front_page        = $front_page_id ? get_post( $front_page_id ) : null;
$front_intro       = '';
$front_intro_title = get_bloginfo( 'name' );

if ( $front_page && 'publish' === $front_page->post_status && ! empty( $front_page->post_content ) ) {
	$front_intro = wp_trim_words( wp_strip_all_tags( $front_page->post_content ), 42, '…' );
}

$paged      = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$feed_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 20,
		'paged'               => $paged,
		'ignore_sticky_posts' => false,
	)
);

get_template_part(
	'template-parts/feed-page',
	null,
	array(
		'intro'         => $front_intro,
		'intro_title'   => $front_intro_title,
		'title'         => __( 'Live Feed', 'birds' ),
		'description'   => __( 'ordered by recent publication', 'birds' ),
		'show_composer' => true,
		'query'         => $feed_query,
	)
);

get_footer();
