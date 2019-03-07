<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'lisa_fancy_implode') ) {

	function lisa_fancy_implode( $array, $word = false ) {
		if ( ! is_string( $word ) ) $word = __( 'and', 'lisa-templates' );

		$last  = array_slice( $array, -1 );
		$first = implode( ', ', array_slice( $array, 0, -1 ) );
		$both  = array_filter( array_merge( array( $first ), $last ), 'strlen' );

		return implode( sprintf( ' %s ', $word ), $both );
	}
}

if ( ! function_exists( 'lisa_allowed_hooks' ) ) {

	function lisa_allowed_hooks( $hook ) {
		$allowed_hooks = (array) apply_filters( 'lisa_data_sources', array(
			'the_content'	=> __( 'the_content', 'lisa-templates' ),
			'woocommerce_short_description'	=> __( 'woocommerce_short_description', 'Lisa_Templates' )
		) );

		if ( array_key_exists( $hook, $allowed_hooks ) ) {
			return $hook;
		} else {
			return 'the_content';
		}
	}

}

if ( ! function_exists( 'lisa_allowed_methods' ) ) {

	function lisa_allowed_methods( $method ) {
		$allowed_methods = (array) apply_filters( 'lisa_data_sources', array(
			'shortcode'	=> __( 'Shortcode', 'lisa-templates' ),
			'autoload'	=> __( 'Autoload', 'lisa-templates'),
			'prerender'	=> __( 'Prerender', 'lisa-templates' )
		) );

		if ( array_key_exists( $method, $allowed_methods ) ) {
			return $method;
		} else {
			return 'shortcode';
		}
	}

}

if ( ! function_exists( 'lisa_allowed_data_sources' ) ) {

	function lisa_allowed_data_sources( $data_source ) {
		$allowed_data_sources = (array) apply_filters( 'lisa_data_sources', array(
			'single'	=> __( 'Single', 'lisa' ),
			'query'		=> __( 'Query', 'lisa' )
		) );

		if ( array_key_exists( $data_source, $allowed_data_sources ) ) {
			return $data_source;
		} else {
			return 'single';
		}
	}

}

if ( ! function_exists( 'lisa_allowed_html' ) ) {

	function lisa_allowed_html() {
		$allowed = wp_kses_allowed_html( 'post' );

		// iframe
		$allowed['iframe'] = array(
			'src'             => array(),
			'height'          => array(),
			'width'           => array(),
			'frameborder'     => array(),
			'allowfullscreen' => array(),
		);

		// form fields - input
		$allowed['input'] = array(
			'class' => array(),
			'id'    => array(),
			'name'  => array(),
			'value' => array(),
			'type'  => array(),
		);

		// select
		$allowed['select'] = array(
			'class'  => array(),
			'id'     => array(),
			'name'   => array(),
			'value'  => array(),
			'type'   => array(),
		);

		// select options
		$allowed['option'] = array(
			'selected' => array(),
		);

		// style
		$allowed['style'] = array(
			'types' => array(),
		);

		return $allowed;
	}

}

if ( ! function_exists( 'lisa_kses' ) ) {
	function lisa_kses( $content ) {
		$content = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $content );
		$content = wp_kses( $content, lisa_allowed_html() );
		return trim( $content );
	}
}
