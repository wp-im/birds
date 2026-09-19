<?php
/**
 * Feed shell used by the home, archive, author and search templates.
 *
 * @package Birds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$feed_args = wp_parse_args(
	isset( $args ) && is_array( $args ) ? $args : array(),
	array(
		'intro'         => '',
		'intro_title'   => '',
		'title'         => __( 'Live Feed', 'birds' ),
		'description'   => __( 'ordered by recent publication', 'birds' ),
		'show_composer' => false,
		'query'         => $GLOBALS['wp_query'],
	)
);

$feed_query = $feed_args['query'] instanceof WP_Query ? $feed_args['query'] : $GLOBALS['wp_query'];
$paged      = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$status     = '';

if ( isset( $_GET['birds_core_status'] ) && is_string( $_GET['birds_core_status'] ) ) {
	$status = sanitize_key( wp_unslash( $_GET['birds_core_status'] ) );
} elseif ( isset( $_GET['birds_status'] ) && is_string( $_GET['birds_status'] ) ) {
	$status = sanitize_key( wp_unslash( $_GET['birds_status'] ) );
}

$pagination = paginate_links(
	array(
		'current'   => $paged,
		'total'     => max( 1, (int) $feed_query->max_num_pages ),
		'type'      => 'array',
		'prev_text' => __( '← Newer', 'birds' ),
		'next_text' => __( 'Older →', 'birds' ),
	)
);
$previous_link = '';
$next_link     = '';

if ( is_array( $pagination ) ) {
	foreach ( $pagination as $pagination_link ) {
		if ( false !== strpos( $pagination_link, 'next' ) ) {
			$next_link = $pagination_link;
		} elseif ( false !== strpos( $pagination_link, 'prev' ) ) {
			$previous_link = $pagination_link;
		}
	}
}
?>
<main class="workspace" id="top">
	<div class="main-stack">
		<?php if ( ! empty( $feed_args['intro'] ) ) : ?>
			<section class="window intro-window" aria-labelledby="intro-title">
				<div class="titlebar">
					<span class="control" aria-hidden="true"></span>
					<h1 class="window-title" id="intro-title"><?php echo esc_html( $feed_args['intro_title'] ? $feed_args['intro_title'] : get_bloginfo( 'name' ) ); ?></h1>
					<span class="zoom" aria-hidden="true"></span>
				</div>
				<div class="window-body">
					<article class="intro-copy bodytext">
						<p><?php echo esc_html( $feed_args['intro'] ); ?></p>
					</article>
					<div class="statusbar">
						<span class="grow"><?php esc_html_e( 'A feed-first WordPress experience', 'birds' ); ?></span>
						<span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					</div>
				</div>
			</section>
		<?php endif; ?>
		<?php if ( $feed_args['show_composer'] ) : ?>
			<?php get_template_part( 'template-parts/composer' ); ?>
		<?php endif; ?>

		<section class="window feed-window" aria-labelledby="feed-title">
			<div class="titlebar">
				<span class="control" aria-hidden="true"></span>
				<?php if ( ! empty( $feed_args['intro'] ) ) : ?>
					<h2 class="window-title" id="feed-title"><?php echo esc_html( get_bloginfo( 'name' ) . ' — ' . $feed_args['title'] ); ?></h2>
				<?php else : ?>
					<h1 class="window-title" id="feed-title"><?php echo esc_html( get_bloginfo( 'name' ) . ' — ' . $feed_args['title'] ); ?></h1>
				<?php endif; ?>
				<span class="zoom" aria-hidden="true"></span>
			</div>
			<div class="window-body">
					<?php if ( 'published' === $status || '1' === $status ) : ?>
						<div class="live-banner" role="status"><?php esc_html_e( 'Your post is now in the Live Feed.', 'birds' ); ?></div>
					<?php elseif ( 'post_updated' === $status ) : ?>
						<div class="live-banner" role="status"><?php esc_html_e( 'Your post was updated.', 'birds' ); ?></div>
					<?php elseif ( 'post_deleted' === $status ) : ?>
						<div class="live-banner" role="status"><?php esc_html_e( 'Your post was moved to the Trash.', 'birds' ); ?></div>
					<?php elseif ( 'comment_updated' === $status ) : ?>
						<div class="live-banner" role="status"><?php esc_html_e( 'Your reply was updated.', 'birds' ); ?></div>
					<?php elseif ( 'comment_deleted' === $status ) : ?>
						<div class="live-banner" role="status"><?php esc_html_e( 'Your reply was moved to the Trash.', 'birds' ); ?></div>
					<?php elseif ( 'comments_open' === $status ) : ?>
						<div class="live-banner" role="status"><?php esc_html_e( 'Replies are open again.', 'birds' ); ?></div>
					<?php elseif ( 'comments_closed' === $status ) : ?>
						<div class="live-banner" role="status"><?php esc_html_e( 'Replies are now closed.', 'birds' ); ?></div>
					<?php elseif ( 'empty' === $status ) : ?>
						<div class="live-banner" role="status"><?php esc_html_e( 'Write something before publishing.', 'birds' ); ?></div>
					<?php elseif ( 'forbidden' === $status ) : ?>
						<div class="live-banner" role="status"><?php esc_html_e( 'You do not have permission to perform that action.', 'birds' ); ?></div>
					<?php elseif ( 'error' === $status ) : ?>
						<div class="live-banner" role="status"><?php esc_html_e( 'That action could not be completed. Please try again.', 'birds' ); ?></div>
				<?php endif; ?>
				<form class="findstrip" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="sr-only" for="feed-search"><?php esc_html_e( 'Search the stream', 'birds' ); ?></label>
					<input class="textfield" id="feed-search" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search the stream', 'birds' ); ?>">
					<button class="push small" type="submit"><?php esc_html_e( 'Search', 'birds' ); ?></button>
				</form>

				<div class="feed-head">
					<span><strong><?php echo esc_html( $feed_args['title'] ); ?></strong> · <?php echo esc_html( $feed_args['description'] ); ?></span>
					<span><?php echo esc_html( number_format_i18n( (int) $feed_query->found_posts ) ); ?> <?php esc_html_e( 'posts', 'birds' ); ?></span>
				</div>

				<div class="post-list">
					<?php if ( $feed_query->have_posts() ) : ?>
						<?php while ( $feed_query->have_posts() ) : ?>
							<?php $feed_query->the_post(); ?>
							<?php get_template_part( 'template-parts/post' ); ?>
						<?php endwhile; ?>
					<?php else : ?>
						<div class="empty-state"><?php esc_html_e( 'No posts match this view.', 'birds' ); ?></div>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $pagination ) ) : ?>
					<nav class="pager" aria-label="<?php esc_attr_e( 'Posts navigation', 'birds' ); ?>">
						<span class="pager-side"><?php echo wp_kses_post( $previous_link ); ?></span>
						<span class="stats"><?php printf( esc_html__( 'Page %1$d of %2$d', 'birds' ), $paged, max( 1, (int) $feed_query->max_num_pages ) ); ?></span>
						<span class="pager-side pager-next"><?php echo wp_kses_post( $next_link ); ?></span>
					</nav>
				<?php endif; ?>

				<div class="statusbar">
					<span class="grow"><?php bloginfo( 'name' ); ?> · <?php echo esc_html( number_format_i18n( (int) $feed_query->found_posts ) ); ?> <?php esc_html_e( 'posts', 'birds' ); ?></span>
					<span><?php esc_html_e( 'WordPress Core', 'birds' ); ?></span>
				</div>
			</div>
		</section>
	</div>

	<?php get_template_part( 'template-parts/context-rail' ); ?>
</main>
<?php wp_reset_postdata(); ?>
