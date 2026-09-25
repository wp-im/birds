<?php
/**
 * Shared post renderer for feed, archive, author and search views.
 *
 * @package Birds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$author_id  = (int) get_the_author_meta( 'ID' );
$author_url = get_author_posts_url( $author_id );
$category   = birds_primary_category( get_the_ID() );
$latest     = birds_latest_comment( get_the_ID() );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
	<a class="avatar" href="<?php echo esc_url( $author_url ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Open %s profile', 'birds' ), get_the_author() ) ); ?>">
		<?php echo birds_avatar( $author_id, 38 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</a>
	<div class="post-main">
		<div class="meta">
			<a href="<?php echo esc_url( $author_url ); ?>"><strong><?php the_author(); ?></strong></a>
			<span class="handle">@<?php echo esc_html( get_the_author_meta( 'user_nicename' ) ); ?></span>
			·
			<a href="<?php the_permalink(); ?>"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ); ?></time></a>
		</div>
		<?php if ( get_the_title() ) : ?>
			<?php if ( is_singular( 'post' ) ) : ?>
				<h1 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<?php else : ?>
				<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php endif; ?>
		<?php endif; ?>
		<div class="bodytext">
			<?php the_content(); ?>
		</div>
		<?php wp_link_pages( array( 'before' => '<div class="post-pages">' . esc_html__( 'Pages:', 'birds' ), 'after' => '</div>' ) ); ?>
		<div class="post-tools">
			<a class="tool-link" href="<?php echo esc_url( get_comments_link() ); ?>">
				<?php esc_html_e( 'Reply', 'birds' ); ?>
				<span class="reply-count"><?php echo esc_html( number_format_i18n( get_comments_number() ) ); ?></span>
			</a>
			<a class="tool-link" href="<?php the_permalink(); ?>#comments"><?php esc_html_e( 'Thread', 'birds' ); ?></a>
			<a class="tool-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Permalink', 'birds' ); ?></a>
			<?php do_action( 'birds_post_actions', get_the_ID() ); ?>
			<?php if ( $category ) : ?>
				<a class="topic-tag" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
			<?php endif; ?>
		</div>
		<?php if ( $latest ) : ?>
			<div class="latest-reply">
				<strong><?php echo esc_html( $latest->comment_author ); ?></strong>
				<span><?php echo esc_html( birds_comment_preview( $latest ) ); ?></span>
			</div>
		<?php endif; ?>
	</div>
</article>
