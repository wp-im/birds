<?php
/**
 * Birds theme functions.
 *
 * @package Birds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BIRDS_VERSION', '0.5.0' );

/**
 * Set up the theme.
 *
 * @return void
 */
function birds_setup() {
	load_theme_textdomain( 'birds', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'b7b7b1',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 64,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'birds' ),
			'footer'  => __( 'Footer Menu', 'birds' ),
		)
	);
}
add_action( 'after_setup_theme', 'birds_setup' );

/**
 * Return the Birds theme defaults.
 *
 * These options affect presentation only. Palette values remain part of the
 * selected fixed-palette package and are not Customizer settings.
 *
 * @return array<string,mixed>
 */
function birds_theme_defaults() {
	return array(
		'show_composer'      => true,
		'show_rail'          => true,
		'show_clock'         => true,
		'show_footer_version' => true,
		'feed_per_page'      => 20,
		'feed_title'         => __( 'Live Feed', 'birds' ),
		'feed_description'   => __( 'ordered by recent publication', 'birds' ),
	);
}

/**
 * Return one sanitized Birds presentation option.
 *
 * @param string $key Option key.
 * @param mixed  $fallback Fallback value.
 * @return mixed
 */
function birds_theme_option( $key, $fallback = null ) {
	$defaults = birds_theme_defaults();
	$default  = array_key_exists( $key, $defaults ) ? $defaults[ $key ] : $fallback;
	$value    = get_theme_mod( $key, $default );

	if ( in_array( $key, array( 'show_composer', 'show_rail', 'show_clock', 'show_footer_version' ), true ) ) {
		return (bool) $value;
	}

	if ( 'feed_per_page' === $key ) {
		return max( 5, min( 50, absint( $value ) ) );
	}

	return is_string( $value ) ? sanitize_text_field( $value ) : $default;
}

/**
 * Enqueue the single theme stylesheet.
 *
 * @return void
 */
function birds_enqueue_assets() {
	wp_enqueue_style(
		'birds-style',
		get_stylesheet_uri(),
		array(),
		BIRDS_VERSION
	);
	wp_enqueue_script(
		'birds-interactions',
		get_template_directory_uri() . '/assets/js/interactions.js',
		array(),
		BIRDS_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'birds_enqueue_assets' );

require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/widgets.php';
require_once get_template_directory() . '/inc/social-preview.php';

$birds_demo_palette = get_template_directory() . '/inc/demo-palette.php';
if ( is_readable( $birds_demo_palette ) ) {
	require_once $birds_demo_palette;
}

/**
 * Add a small theme class to the body.
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function birds_body_class( $classes ) {
	$classes[] = 'birds-theme';

	return $classes;
}
add_filter( 'body_class', 'birds_body_class' );

/**
 * Set the feed size for native archive, author and search queries.
 *
 * @param WP_Query $query WordPress query.
 * @return void
 */
function birds_pre_get_posts( $query ) {
	if ( is_admin() || ! $query instanceof WP_Query || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_home() || $query->is_archive() || $query->is_search() ) {
		$query->set( 'posts_per_page', birds_theme_option( 'feed_per_page', 20 ) );
	}
}
add_action( 'pre_get_posts', 'birds_pre_get_posts' );

/**
 * Return the site's login URL while allowing an integration layer to replace it.
 *
 * @param string $redirect_to Optional post-login destination.
 * @return string
 */
function birds_login_url( $redirect_to = '' ) {
	$url = wp_login_url( $redirect_to );

	return apply_filters( 'birds_login_url', $url, $redirect_to );
}

/**
 * Return the site's public account URL while allowing an integration layer to
 * replace it.
 *
 * @param string $redirect_to Optional post-account destination.
 * @return string
 */
function birds_account_url( $redirect_to = '' ) {
	$page = get_page_by_path( 'account' );
	$user = wp_get_current_user();
	$url  = $page instanceof WP_Post ? get_permalink( $page ) : ( $user->exists() ? get_author_posts_url( $user->ID ) : home_url( '/' ) );

	if ( $redirect_to ) {
		$url = add_query_arg( 'redirect_to', $redirect_to, $url );
	}

	return apply_filters( 'birds_account_url', $url, $redirect_to );
}

/**
 * Whether the Context Rail should be rendered.
 *
 * @return bool
 */
function birds_show_context_rail() {
	return birds_theme_option( 'show_rail', true );
}

/**
 * Return the first category attached to a post.
 *
 * @param int $post_id Post ID.
 * @return WP_Term|null
 */
function birds_primary_category( $post_id = 0 ) {
	$categories = get_the_category( $post_id );

	return ! empty( $categories ) ? $categories[0] : null;
}

/**
 * Return a short, safe latest-comment preview for a post.
 *
 * This intentionally uses the normal comments table and does not cache or
 * reorder activity in V1.
 *
 * @param int $post_id Post ID.
 * @return WP_Comment|null
 */
function birds_latest_comment( $post_id ) {
	$comments = get_comments(
		array(
			'post_id' => absint( $post_id ),
			'status'  => 'approve',
			'type'    => 'comment',
			'number'  => 1,
			'orderby' => 'comment_date_gmt',
			'order'   => 'DESC',
		)
	);

	return ! empty( $comments ) ? $comments[0] : null;
}

/**
 * Format a comment preview without rendering arbitrary markup in the feed.
 *
 * @param WP_Comment $comment Comment object.
 * @return string
 */
function birds_comment_preview( $comment ) {
	return wp_trim_words( wp_strip_all_tags( $comment->comment_content ), 26, '…' );
}

/**
 * Render a post author avatar using the normal WordPress avatar system.
 *
 * @param int $user_id User ID.
 * @param int $size Avatar size.
 * @return string
 */
function birds_avatar( $user_id, $size = 38 ) {
	$name = get_the_author_meta( 'display_name', $user_id );

	return get_avatar(
		$user_id,
		$size,
		'',
		$name,
		array(
			'class' => 'avatar-image',
		)
	);
}

/**
 * Render the shared comment item used by the native WordPress comment tree.
 *
 * Birds Core adds front-end operations through the action hook inside this
 * renderer; the Theme remains responsible for the markup and typography.
 *
 * @param WP_Comment $comment Comment object.
 * @param array      $args    Comment list arguments.
 * @param int        $depth   Comment depth.
 * @return void
 */
function birds_comment( $comment, $args, $depth ) {
	$tag             = ( 'div' === $args['style'] ) ? 'div' : 'li';
	$commenter       = wp_get_current_commenter();
	$show_pending    = ! empty( $commenter['comment_author'] );
	$comment_author  = get_comment_author_link( $comment );
	$moderation_note = __( 'Your reply is awaiting moderation.', 'birds' );

	if ( '0' === $comment->comment_approved && ! $show_pending ) {
		$comment_author = get_comment_author( $comment );
	}
	?>
	<<?php echo esc_html( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( '', $comment ); ?>>
		<div class="comment-body">
			<div class="avatar" aria-hidden="true">
				<?php echo get_avatar( $comment, $args['avatar_size'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="comment-main">
				<div class="comment-meta">
					<strong class="comment-author"><?php echo wp_kses_post( $comment_author ); ?></strong>
					<span class="comment-metadata"><a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>"><time datetime="<?php echo esc_attr( get_comment_time( 'c', true, true, $comment ) ); ?>"><?php echo esc_html( get_comment_date( '', $comment ) . ' ' . get_comment_time( '', false, true, $comment ) ); ?></time></a></span>
				</div>
				<?php if ( '0' === $comment->comment_approved ) : ?>
					<em class="comment-awaiting-moderation"><?php echo esc_html( $moderation_note ); ?></em>
				<?php endif; ?>
				<div class="comment-content">
					<?php comment_text(); ?>
				</div>
				<?php do_action( 'birds_comment_actions', $comment, $depth ); ?>
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply">',
							'after'     => '</div>',
						)
					)
				);
				?>
			</div>
		</div>
	<?php
}
