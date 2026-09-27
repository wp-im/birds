<?php
/**
 * Native WordPress search form styled as a Birds strip.
 *
 * @package Birds
 */

$search_label = isset( $args['aria_label'] ) ? $args['aria_label'] : __( 'Search the stream', 'birds' );
?>
<form class="findstrip search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="sr-only" for="birds-search-field"><?php echo esc_html( $search_label ); ?></label>
	<input class="textfield" id="birds-search-field" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search the stream', 'birds' ); ?>">
	<button class="push small" type="submit"><?php esc_html_e( 'Search', 'birds' ); ?></button>
</form>
