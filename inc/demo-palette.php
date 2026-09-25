<?php
/**
 * Showcase-only palette switcher.
 *
 * This file is intentionally excluded from fixed release packages. It exists
 * so the Birds demonstration site can show the available palette choices.
 *
 * @package Birds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function birds_demo_palette_body_attribute() {
	return ' data-palette="classic"';
}

function birds_demo_palette_control() {
	$palettes = array(
		'classic'       => __( 'Classic', 'birds' ),
		'sunlit-yellow' => __( 'Sunlit Yellow', 'birds' ),
		'mist-green'    => __( 'Mist Green', 'birds' ),
		'mist-blue'     => __( 'Mist Blue', 'birds' ),
		'mist-red'      => __( 'Mist Red', 'birds' ),
		'mist-gray'     => __( 'Mist Gray', 'birds' ),
	);

	wp_enqueue_style(
		'birds-demo-palettes',
		get_template_directory_uri() . '/assets/demo/palettes.css',
		array( 'birds-style' ),
		BIRDS_VERSION
	);
	wp_enqueue_script(
		'birds-demo-palettes',
		get_template_directory_uri() . '/assets/demo/palettes.js',
		array(),
		BIRDS_VERSION,
		true
	);
	?>
	<label class="palette-picker" for="birds-palette-select">
		<span><?php esc_html_e( 'Palette', 'birds' ); ?></span>
		<select id="birds-palette-select" name="birds_palette">
			<?php foreach ( $palettes as $slug => $label ) : ?>
				<option value="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</label>
	<?php
}
