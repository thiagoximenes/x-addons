<?php
/**
 * Plugin Name: X Addons For Elementor
 * Description: One Elementor Widget.
 * Plugin URI:  https://yootiq.com/x-addon/
 * Version:     0.0.3
 * Author:      Thiago Ximenes
 * Author URI:  https://yootiq.com/contact/
 * Text Domain: x-first-widget
 *
 * Elementor tested up to: 3.16.0
 * Elementor Pro tested up to: 3.16.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function register_first_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/x-first-widget.php' );

	$widgets_manager->register( new \Elementor_xFirst_Widget() );

}
add_action( 'elementor/widgets/register', 'register_first_widget' );

?>