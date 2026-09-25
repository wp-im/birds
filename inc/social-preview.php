<?php
/**
 * Birds social preview cards.
 *
 * The card is a server-rendered image because social crawlers do not render
 * the site's CSS or JavaScript. Its geometry follows the Birds window system.
 *
 * @package Birds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return a readable font suitable for the supplied text.
 *
 * @param string $sample Text that will be drawn on the card.
 * @return string
 */
function birds_social_card_font_path( $sample = '' ) {
	$font = apply_filters( 'birds_social_card_font_path', '', $sample );

	if ( is_string( $font ) && $font && is_readable( $font ) ) {
		return $font;
	}

	$requires_cjk = preg_match( '/[\x{3040}-\x{30FF}\x{3400}-\x{9FFF}\x{AC00}-\x{D7AF}]/u', $sample );
	$fonts        = array();

	if ( $requires_cjk ) {
		$locale = strtolower( get_locale() );
		if ( preg_match( '/[\x{3040}-\x{30FF}]/u', $sample ) || 0 === strpos( $locale, 'ja' ) ) {
			$fonts = array(
				'/usr/share/fonts/opentype/noto/NotoSansCJKjp-Regular.otf',
				'/usr/share/fonts/truetype/noto/NotoSansJP-Regular.ttf',
				'/usr/share/fonts/opentype/ipafont-gothic/ipag.ttf',
				'/System/Library/Fonts/ヒラギノ角ゴシック W4.ttc',
			);
		} elseif ( preg_match( '/[\x{AC00}-\x{D7AF}]/u', $sample ) || 0 === strpos( $locale, 'ko' ) ) {
			$fonts = array(
				'/usr/share/fonts/opentype/noto/NotoSansCJKkr-Regular.otf',
				'/System/Library/Fonts/AppleSDGothicNeo.ttc',
			);
		} else {
			$fonts = array(
				'/usr/share/fonts/opentype/noto/NotoSansCJKsc-Regular.otf',
				'/usr/share/fonts/truetype/wqy/wqy-zenhei.ttc',
				'/System/Library/Fonts/STHeiti Light.ttc',
				'/System/Library/Fonts/Supplemental/Songti.ttc',
			);
		}
	}

	$fonts = array_merge(
		$fonts,
		array(
			'/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
			'/usr/share/fonts/dejavu/DejaVuSans.ttf',
			'/usr/share/fonts/truetype/liberation2/LiberationSans-Regular.ttf',
			'/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
			'/usr/share/fonts/opentype/noto/NotoSans-Regular.ttf',
			'/System/Library/Fonts/Supplemental/Arial.ttf',
		)
	);

	foreach ( $fonts as $candidate ) {
		if ( is_readable( $candidate ) ) {
			return $candidate;
		}
	}

	return '';
}

/**
 * Return plain text appropriate for a social description or image.
 *
 * @param string $text Source text.
 * @param int    $length Maximum UTF-8 character count.
 * @return string
 */
function birds_social_plain_text( $text, $length = 300 ) {
	$text = strip_shortcodes( (string) $text );
	$text = html_entity_decode( wp_strip_all_tags( $text, true ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$text = preg_replace( '/\s+/u', ' ', trim( $text ) );

	return wp_html_excerpt( $text, $length, '…' );
}

/**
 * Build the one content context shared by metadata and image rendering.
 *
 * @param WP_Post $post Public post or page.
 * @return array
 */
function birds_social_card_context( $post ) {
	$title   = birds_social_plain_text( get_the_title( $post ), 160 );
	$content = has_excerpt( $post ) ? get_the_excerpt( $post ) : $post->post_content;
	$author  = get_the_author_meta( 'display_name', (int) $post->post_author );
	$host    = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

	return array(
		'title'       => $title ? $title : get_bloginfo( 'name' ),
		'description' => birds_social_plain_text( $content, 260 ),
		'author'      => $author ? birds_social_plain_text( $author, 80 ) : get_bloginfo( 'name' ),
		'handle'      => get_the_author_meta( 'user_nicename', (int) $post->post_author ),
		'date'        => get_the_date( 'Y-m-d H:i', $post ),
		'replies'     => (int) get_comments_number( $post ),
		'site_name'   => birds_social_plain_text( get_bloginfo( 'name' ), 80 ),
		'host'        => $host ? $host : get_bloginfo( 'name' ),
		'url'         => get_permalink( $post ),
	);
}

/**
 * Wrap UTF-8 text to a pixel width and a maximum number of lines.
 *
 * @param string $text Text to wrap.
 * @param string $font Font path.
 * @param int    $size Point size.
 * @param int    $width Available width.
 * @param int    $max_lines Maximum lines.
 * @return array
 */
function birds_social_card_wrap_text( $text, $font, $size, $width, $max_lines ) {
	$characters = preg_split( '//u', (string) $text, -1, PREG_SPLIT_NO_EMPTY );
	$lines      = array();
	$line       = '';
	$truncated  = false;

	foreach ( $characters as $character ) {
		if ( "\n" === $character || "\r" === $character ) {
			if ( '' !== trim( $line ) ) {
				$lines[] = trim( $line );
			}
			$line = '';
			continue;
		}

		$candidate = $line . $character;
		$box       = imagettfbbox( $size, 0, $font, $candidate );
		$line_width = abs( $box[2] - $box[0] );

		if ( $line && $line_width > $width ) {
			$break_at = strrpos( $line, ' ' );
			if ( false !== $break_at ) {
				$next_line = trim( substr( $line, $break_at + 1 ) . $character );
				$line      = substr( $line, 0, $break_at );
				$lines[]   = trim( $line );
				$line      = $next_line;
			} else {
				$lines[] = trim( $line );
				$line    = ltrim( $character );
			}
			if ( count( $lines ) >= $max_lines ) {
				$truncated = true;
				break;
			}
		} else {
			$line = $candidate;
		}
	}

	if ( $line && count( $lines ) < $max_lines ) {
		$lines[] = trim( $line );
	} elseif ( $line ) {
		$truncated = true;
	}

	if ( $truncated && $lines ) {
		$last = array_pop( $lines );
		while ( $last ) {
			$box = imagettfbbox( $size, 0, $font, $last . '…' );
			if ( $box && abs( $box[2] - $box[0] ) <= $width ) {
				break;
			}
			$last_characters = preg_split( '//u', $last, -1, PREG_SPLIT_NO_EMPTY );
			array_pop( $last_characters );
			$last = implode( '', $last_characters );
		}
		$lines[] = rtrim( $last ) . '…';
	}

	return array_slice( array_filter( $lines, 'strlen' ), 0, $max_lines );
}

/**
 * Draw a Birds Classic Mac OS 8/9 window into a PNG.
 *
 * @param WP_Post $post Public post or page.
 * @param string  $destination Destination PNG path.
 * @param string  $font Font path.
 * @return bool
 */
function birds_social_card_render( $post, $destination, $font ) {
	if ( ! function_exists( 'imagecreatetruecolor' ) || ! function_exists( 'imagettftext' ) || ! function_exists( 'imagettfbbox' ) ) {
		return false;
	}

	$card = imagecreatetruecolor( 1200, 630 );
	if ( ! $card ) {
		return false;
	}

	$desktop     = imagecolorallocate( $card, 183, 183, 177 );
	$chrome      = imagecolorallocate( $card, 214, 214, 209 );
	$chrome_dark = imagecolorallocate( $card, 155, 155, 150 );
	$paper       = imagecolorallocate( $card, 255, 255, 255 );
	$paper_alt   = imagecolorallocate( $card, 243, 243, 240 );
	$ink         = imagecolorallocate( $card, 18, 18, 18 );
	$muted       = imagecolorallocate( $card, 98, 98, 95 );
	$line        = imagecolorallocate( $card, 200, 200, 195 );
	$blue        = imagecolorallocate( $card, 47, 55, 144 );
	$white       = imagecolorallocate( $card, 255, 255, 255 );
	$shadow      = imagecolorallocate( $card, 80, 80, 77 );
	$avatar_bg   = imagecolorallocate( $card, 231, 232, 244 );

	imagefill( $card, 0, 0, $desktop );
	for ( $y = 0; $y < 630; $y += 4 ) {
		for ( $x = 0; $x < 1200; $x += 4 ) {
			$pixel = ( ( $x / 4 + $y / 4 ) % 2 ) ? $white : $chrome_dark;
			imagefilledrectangle( $card, $x, $y, $x + 1, $y + 1, $pixel );
		}
	}

	$left   = 72;
	$top    = 42;
	$right  = 1128;
	$bottom = 588;
	$bar_y  = 50;

	/* Window shadow and classic chrome. */
	imagefilledrectangle( $card, $left + 7, $top + 7, $right + 7, $bottom + 7, $shadow );
	imagefilledrectangle( $card, $left, $top, $right, $bottom, $ink );
	imagefilledrectangle( $card, $left + 3, $top + 3, $right - 3, $bottom - 3, $chrome );
	imageline( $card, $left + 4, $top + 4, $right - 4, $top + 4, $white );
	imageline( $card, $left + 4, $top + 4, $left + 4, $bottom - 4, $white );
	imageline( $card, $left + 4, $bottom - 4, $right - 4, $bottom - 4, $chrome_dark );
	imageline( $card, $right - 4, $top + 4, $right - 4, $bottom - 4, $chrome_dark );

	/* Title bar with Birds' striped Classic Mac treatment. */
	imagefilledrectangle( $card, $left + 8, $bar_y, $right - 8, $bar_y + 34, $chrome );
	$context           = birds_social_card_context( $post );
	$title_lines       = birds_social_card_wrap_text( $context['site_name'], $font, 23, 520, 1 );
	$title             = ! empty( $title_lines ) ? $title_lines[0] : 'Birds';
	$title_box         = imagettfbbox( 23, 0, $font, $title );
	$title_width       = abs( $title_box[2] - $title_box[0] );
	$title_x           = 600 - ( $title_width / 2 );
	$stripe_left       = $left + 42;
	$stripe_right      = $right - 42;
	$title_clear_left  = max( $stripe_left, (int) floor( $title_x ) - 12 );
	$title_clear_right = min( $stripe_right, (int) ceil( $title_x + $title_width ) + 12 );
	for ( $y = $bar_y + 7; $y < $bar_y + 28; $y += 4 ) {
		imageline( $card, $stripe_left, $y, $title_clear_left - 1, $y, $muted );
		imageline( $card, $stripe_left, $y + 1, $title_clear_left - 1, $y + 1, $white );
		imageline( $card, $title_clear_right + 1, $y, $stripe_right, $y, $muted );
		imageline( $card, $title_clear_right + 1, $y + 1, $stripe_right, $y + 1, $white );
	}
	imagerectangle( $card, $left + 8, $bar_y, $right - 8, $bar_y + 34, $ink );

	/* Close and zoom boxes. */
	foreach ( array( $left + 15, $right - 30 ) as $control_left ) {
		imagefilledrectangle( $card, $control_left, $bar_y + 7, $control_left + 20, $bar_y + 27, $chrome );
		imagerectangle( $card, $control_left, $bar_y + 7, $control_left + 20, $bar_y + 27, $ink );
		imageline( $card, $control_left + 2, $bar_y + 9, $control_left + 18, $bar_y + 9, $white );
		imageline( $card, $control_left + 2, $bar_y + 25, $control_left + 18, $bar_y + 25, $chrome_dark );
	}

	imagettftext( $card, 23, 0, (int) $title_x, $bar_y + 24, $ink, $font, $title );

	/* Inner paper area. */
	$body_left   = $left + 14;
	$body_top    = $bar_y + 42;
	$body_right  = $right - 14;
	$body_bottom = $bottom - 14;
	imagefilledrectangle( $card, $body_left, $body_top, $body_right, $body_bottom, $paper );
	imagerectangle( $card, $body_left, $body_top, $body_right, $body_bottom, $ink );

	/* Author block. */
	$avatar_left = $body_left + 34;
	$avatar_top  = $body_top + 28;
	imagefilledrectangle( $card, $avatar_left, $avatar_top, $avatar_left + 66, $avatar_top + 66, $avatar_bg );
	imagerectangle( $card, $avatar_left, $avatar_top, $avatar_left + 66, $avatar_top + 66, $ink );
	$initial = function_exists( 'mb_substr' ) ? mb_substr( $context['author'], 0, 1, 'UTF-8' ) : substr( $context['author'], 0, 1 );
	$initial_box = imagettfbbox( 30, 0, $font, $initial );
	$initial_x   = $avatar_left + 33 - ( abs( $initial_box[2] - $initial_box[0] ) / 2 );
	imagettftext( $card, 30, 0, (int) $initial_x, $avatar_top + 45, $blue, $font, $initial );

	$author_x = $avatar_left + 86;
	$author   = $context['author'];
	if ( $context['handle'] ) {
		$author .= '  @' . $context['handle'];
	}
	$author .= '  ·  ' . $context['date'];
	$author_lines = birds_social_card_wrap_text( $author, $font, 23, 840, 1 );
	imagettftext( $card, 23, 0, $author_x, $avatar_top + 27, $ink, $font, ! empty( $author_lines ) ? $author_lines[0] : $context['author'] );

	/* Core post content. */
	$title_lines = birds_social_card_wrap_text( $context['title'], $font, 34, 940, 2 );
	$baseline    = $body_top + 142;
	foreach ( $title_lines as $title_line ) {
		imagettftext( $card, 34, 0, $body_left + 34, $baseline, $ink, $font, $title_line );
		$baseline += 43;
	}

	$description_lines = birds_social_card_wrap_text( $context['description'], $font, 24, 940, 4 );
	$baseline          = max( $baseline + 12, $body_top + 205 );
	foreach ( $description_lines as $description_line ) {
		imagettftext( $card, 24, 0, $body_left + 34, $baseline, $ink, $font, $description_line );
		$baseline += 32;
	}

	/* Status bar echoes the thread window without pretending replies are post text. */
	$status_top = $body_bottom - 38;
	imagefilledrectangle( $card, $body_left + 1, $status_top, $body_right - 1, $body_bottom - 1, $paper_alt );
	imageline( $card, $body_left + 1, $status_top, $body_right - 1, $status_top, $ink );
	$reply_text = sprintf( _n( '%s reply', '%s replies', $context['replies'], 'birds' ), number_format_i18n( $context['replies'] ) );
	$reply_text = wp_strip_all_tags( $reply_text );
	imagettftext( $card, 18, 0, $body_left + 14, $status_top + 25, $muted, $font, $reply_text );
	$host_box = imagettfbbox( 18, 0, $font, $context['host'] );
	$host_x   = $body_right - 18 - abs( $host_box[2] - $host_box[0] );
	imagettftext( $card, 18, 0, (int) $host_x, $status_top + 25, $muted, $font, $context['host'] );

	$written = imagepng( $card, $destination, 7 );

	return (bool) $written;
}

/**
 * Return a cached Birds social card URL.
 *
 * @param WP_Post $post Public post or page.
 * @return string
 */
function birds_social_card_image_url( $post ) {
	$context = birds_social_card_context( $post );
	$font    = birds_social_card_font_path( $context['title'] . ' ' . $context['description'] . ' ' . $context['author'] );

	if ( ! $font ) {
		return '';
	}

	$upload = wp_upload_dir();
	if ( ! empty( $upload['error'] ) || empty( $upload['basedir'] ) || empty( $upload['baseurl'] ) ) {
		return '';
	}

	$directory = trailingslashit( $upload['basedir'] ) . 'birds-social-cards';
	if ( ! wp_mkdir_p( $directory ) ) {
		return '';
	}

	$file = trailingslashit( $directory ) . absint( $post->ID ) . '.png';
	$hash = hash(
		'sha256',
		implode(
			"\0",
			array(
				BIRDS_VERSION,
				$post->post_title,
				$post->post_content,
				$post->post_modified_gmt,
				get_comments_number( $post ),
				$context['author'],
				get_bloginfo( 'name' ),
				home_url( '/' ),
				$font,
			)
		)
	);
	$stored = get_post_meta( $post->ID, '_birds_social_card_hash', true );

	if ( $stored !== $hash || ! is_readable( $file ) ) {
		$temporary = tempnam( $directory, 'birds-' );
		if ( ! $temporary || ! birds_social_card_render( $post, $temporary, $font ) ) {
			if ( $temporary ) {
				@unlink( $temporary ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			}
			return '';
		}

		if ( ! chmod( $temporary, 0644 ) || ! rename( $temporary, $file ) ) {
			@unlink( $temporary ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			return '';
		}

		update_post_meta( $post->ID, '_birds_social_card_hash', $hash );
	}

	return add_query_arg( 'v', substr( $hash, 0, 12 ), trailingslashit( $upload['baseurl'] ) . 'birds-social-cards/' . absint( $post->ID ) . '.png' );
}

/**
 * Return the best public image for a social preview.
 *
 * The generated Birds window is primary. A featured image is only a safe
 * fallback when the server cannot render a card.
 *
 * @param WP_Post $post Public post or page.
 * @return array{url:string,alt:string,width:int,height:int}
 */
function birds_social_preview_image( $post ) {
	$generated = birds_social_card_image_url( $post );
	if ( $generated ) {
		return array(
			'url'    => $generated,
			'alt'    => sprintf( __( 'Birds preview of %s', 'birds' ), birds_social_plain_text( get_the_title( $post ), 120 ) ),
			'width'  => 1200,
			'height' => 630,
		);
	}

	if ( has_post_thumbnail( $post ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'full' );
		if ( $image ) {
			return array(
				'url'    => $image[0],
				'alt'    => get_post_meta( get_post_thumbnail_id( $post ), '_wp_attachment_image_alt', true ),
				'width'  => (int) $image[1],
				'height' => (int) $image[2],
			);
		}
	}

	$default = get_template_directory_uri() . '/assets/images/birds-social-card.png';
	$site_icon = get_site_icon_url( 1200 );
	return array(
		'url'    => file_exists( get_template_directory() . '/assets/images/birds-social-card.png' ) ? $default : $site_icon,
		'alt'    => get_bloginfo( 'name' ),
		'width'  => 1200,
		'height' => 630,
	);
}

/**
 * Detect common SEO plugins that already own social metadata.
 *
 * @return bool
 */
function birds_social_meta_has_provider() {
	$constants = array( 'WPSEO_VERSION', 'RANK_MATH_VERSION', 'SEOPRESS_VERSION', 'AIOSEO_VERSION' );
	foreach ( $constants as $constant ) {
		if ( defined( $constant ) ) {
			return true;
		}
	}

	return (bool) has_action( 'wp_head', 'wpseo_head' );
}

/**
 * Print social metadata for public singular content.
 *
 * @return void
 */
function birds_output_social_preview_meta() {
	if ( ! is_singular( array( 'post', 'page' ) ) ) {
		return;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post || 'publish' !== $post->post_status || $post->post_password ) {
		return;
	}

	$enabled = apply_filters( 'birds_output_social_preview_meta', ! birds_social_meta_has_provider(), $post );
	if ( ! $enabled ) {
		return;
	}

	$context = birds_social_card_context( $post );
	$image   = birds_social_preview_image( $post );
	$tags    = array(
		'og:type'        => 'post' === $post->post_type ? 'article' : 'website',
		'og:title'       => $context['title'],
		'og:description' => $context['description'],
		'og:url'         => $context['url'],
		'og:site_name'   => $context['site_name'],
		'og:locale'      => str_replace( '-', '_', get_locale() ),
		'twitter:card'   => 'summary_large_image',
		'twitter:title'  => $context['title'],
		'twitter:description' => $context['description'],
	);

	if ( $image['url'] ) {
		$tags['og:image']          = $image['url'];
		$tags['og:image:alt']      = $image['alt'];
		$tags['twitter:image']     = $image['url'];
		$tags['twitter:image:alt'] = $image['alt'];
		if ( $image['width'] && $image['height'] ) {
			$tags['og:image:width']  = $image['width'];
			$tags['og:image:height'] = $image['height'];
		}
	}

	foreach ( $tags as $name => $value ) {
		if ( '' === (string) $value ) {
			continue;
		}
		$attribute = 0 === strpos( $name, 'og:' ) ? 'property' : 'name';
		printf( "\t<meta %s=\"%s\" content=\"%s\">\n", esc_attr( $attribute ), esc_attr( $name ), esc_attr( $value ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( 'post' === $post->post_type ) {
		printf( "\t<meta property=\"article:published_time\" content=\"%s\">\n", esc_attr( get_post_time( DATE_W3C, true, $post ) ) );
		printf( "\t<meta property=\"article:modified_time\" content=\"%s\">\n", esc_attr( get_post_modified_time( DATE_W3C, true, $post ) ) );
	}
}
add_action( 'wp_head', 'birds_output_social_preview_meta', 5 );
