<?php
/**
 * Generate the portable Birds social-card fallback image.
 *
 * Usage: php scripts/generate-social-card-default.php
 */

$font_candidates = array(
	'/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
	'/usr/share/fonts/truetype/liberation2/LiberationSans-Regular.ttf',
	'/System/Library/Fonts/Supplemental/Arial.ttf',
);
$font = '';
foreach ( $font_candidates as $candidate ) {
	if ( is_readable( $candidate ) ) {
		$font = $candidate;
		break;
	}
}

if ( ! $font || ! function_exists( 'imagecreatetruecolor' ) ) {
	fwrite( STDERR, "Birds fallback generation requires GD and a TrueType font.\n" );
	exit( 1 );
}

$output = dirname( __DIR__ ) . '/assets/images/birds-social-card.png';
$image  = imagecreatetruecolor( 1200, 630 );
$colors = array(
	'desktop' => imagecolorallocate( $image, 183, 183, 177 ),
	'chrome'  => imagecolorallocate( $image, 214, 214, 209 ),
	'dark'    => imagecolorallocate( $image, 18, 18, 18 ),
	'paper'   => imagecolorallocate( $image, 255, 255, 255 ),
	'muted'   => imagecolorallocate( $image, 98, 98, 95 ),
	'white'   => imagecolorallocate( $image, 255, 255, 255 ),
	'shadow'  => imagecolorallocate( $image, 80, 80, 77 ),
);

imagefill( $image, 0, 0, $colors['desktop'] );
for ( $y = 0; $y < 630; $y += 4 ) {
	for ( $x = 0; $x < 1200; $x += 4 ) {
		$pixel = ( ( $x / 4 + $y / 4 ) % 2 ) ? $colors['white'] : $colors['chrome'];
		imagefilledrectangle( $image, $x, $y, $x + 1, $y + 1, $pixel );
	}
}

$left = 72;
$top = 42;
$right = 1128;
$bottom = 588;
$bar_top = 50;
imagefilledrectangle( $image, $left + 7, $top + 7, $right + 7, $bottom + 7, $colors['shadow'] );
imagefilledrectangle( $image, $left, $top, $right, $bottom, $colors['dark'] );
imagefilledrectangle( $image, $left + 3, $top + 3, $right - 3, $bottom - 3, $colors['chrome'] );
imagefilledrectangle( $image, $left + 8, $bar_top, $right - 8, $bar_top + 34, $colors['chrome'] );
$label = 'Birds  ·  Write. Read. Reply.';
$label_x = 390;
$label_box = imagettfbbox( 23, 0, $font, $label );
$label_width = abs( $label_box[2] - $label_box[0] );
$stripe_left = $left + 42;
$stripe_right = $right - 42;
$label_clear_left = max( $stripe_left, $label_x - 12 );
$label_clear_right = min( $stripe_right, $label_x + $label_width + 12 );
for ( $y = $bar_top + 7; $y < $bar_top + 28; $y += 4 ) {
	imageline( $image, $stripe_left, $y, $label_clear_left - 1, $y, $colors['muted'] );
	imageline( $image, $stripe_left, $y + 1, $label_clear_left - 1, $y + 1, $colors['white'] );
	imageline( $image, $label_clear_right + 1, $y, $stripe_right, $y, $colors['muted'] );
	imageline( $image, $label_clear_right + 1, $y + 1, $stripe_right, $y + 1, $colors['white'] );
}
imagerectangle( $image, $left + 8, $bar_top, $right - 8, $bar_top + 34, $colors['dark'] );

foreach ( array( $left + 15, $right - 30 ) as $control_left ) {
	imagefilledrectangle( $image, $control_left, $bar_top + 7, $control_left + 20, $bar_top + 27, $colors['chrome'] );
	imagerectangle( $image, $control_left, $bar_top + 7, $control_left + 20, $bar_top + 27, $colors['dark'] );
}

imagettftext( $image, 23, 0, $label_x, $bar_top + 24, $colors['dark'], $font, $label );

$body_left = $left + 14;
$body_top = $bar_top + 42;
$body_right = $right - 14;
$body_bottom = $bottom - 14;
imagefilledrectangle( $image, $body_left, $body_top, $body_right, $body_bottom, $colors['paper'] );
imagerectangle( $image, $body_left, $body_top, $body_right, $body_bottom, $colors['dark'] );
imagettftext( $image, 34, 0, $body_left + 34, $body_top + 150, $colors['dark'], $font, 'A small WordPress feed' );
imagettftext( $image, 28, 0, $body_left + 34, $body_top + 205, $colors['dark'], $font, 'for immediate publishing, reading, and replies.' );
imageline( $image, $body_left + 1, $body_bottom - 38, $body_right - 1, $body_bottom - 38, $colors['dark'] );
imagettftext( $image, 18, 0, $body_left + 14, $body_bottom - 13, $colors['muted'], $font, 'Birds  ·  WordPress Classic Theme' );

if ( ! imagepng( $image, $output, 7 ) ) {
	fwrite( STDERR, "Could not write {$output}.\n" );
	exit( 1 );
}

echo "Generated {$output}\n";
