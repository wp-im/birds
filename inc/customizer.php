<?php
/**
 * Small Birds presentation settings.
 *
 * @package Birds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Birds presentation controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 * @return void
 */
function birds_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'birds_presentation',
		array(
			'title'       => __( 'Birds Presentation', 'birds' ),
			'description' => __( 'Control the feed shell without changing the fixed palette or WordPress content model.', 'birds' ),
			'priority'    => 35,
		)
	);

	$checkboxes = array(
		'show_composer'       => __( 'Show the front-page Composer', 'birds' ),
		'show_rail'           => __( 'Show the Context Rail', 'birds' ),
		'show_clock'          => __( 'Show the menu-bar clock', 'birds' ),
		'show_footer_version' => __( 'Show the Theme version in the Footer', 'birds' ),
	);

	foreach ( $checkboxes as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => birds_theme_defaults()[ $key ],
				'sanitize_callback' => 'rest_sanitize_boolean',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $label,
				'section' => 'birds_presentation',
				'type'    => 'checkbox',
			)
		);
	}

	$wp_customize->add_setting(
		'feed_per_page',
		array(
			'default'           => birds_theme_defaults()['feed_per_page'],
			'sanitize_callback' => 'birds_customize_feed_per_page',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'feed_per_page',
		array(
			'label'       => __( 'Feed posts per page', 'birds' ),
			'description' => __( 'Choose between 5 and 50 posts.', 'birds' ),
			'section'     => 'birds_presentation',
			'type'        => 'number',
			'input_attrs' => array(
				'min' => 5,
				'max' => 50,
			),
		)
	);

	$text_fields = array(
		'feed_title'       => __( 'Feed title', 'birds' ),
		'feed_description' => __( 'Feed description', 'birds' ),
	);

	foreach ( $text_fields as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => birds_theme_defaults()[ $key ],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $label,
				'section' => 'birds_presentation',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'birds_customize_register' );

/**
 * Keep the feed size inside the supported range.
 *
 * @param mixed $value Submitted value.
 * @return int
 */
function birds_customize_feed_per_page( $value ) {
	return max( 5, min( 50, absint( $value ) ) );
}
