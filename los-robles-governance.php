<?php
/*
Plugin Name:       Los Robles Governance
Plugin URI:        https://github.com/afragen/losrobles-governance
Description:       This plugin adds registration, custom user meta and other things to the Los Robles HOA website for web-based governance.
Version:           0.1
Author:            Andy Fragen
License:           GNU General Public License v2
License URI:       http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
GitHub Plugin URI: https://github.com/afragen/los-robles-governance
GitHub Branch:     master
Requires WP:       3.8
Requires PHP:      5.3
*/

// Plugin namespace root
$root = array( 'Fragen\LosRobles' => __DIR__ . '/src/LosRobles' );

// Add extra classes
$extra_classes = array();

// Load Autoloader
require_once( __DIR__ . '/src/LosRobles/Autoloader.php' );
$class_loader = 'Fragen\\LosRobles\\Autoloader';
new $class_loader( $root, $extra_classes );

// Launch
$launch_method = array( 'Fragen\\LosRobles\\Base', 'instance' );
$lrhoa = call_user_func( $launch_method );

register_activation_hook( __FILE__, array( &$lrhoa, 'activate' ) );

// add shortcode for [voting]
add_shortcode( 'voting', 'lrhoa_voting_check_shortcode' );
function lrhoa_voting_check_shortcode( $attr, $content = null ) {
	$atts = shortcode_atts( array( 'capability' => 'can_vote' ), $attr, 'voting' );
	if ( current_user_can( $atts['capability'] ) && ! is_null( $content ) && ! is_feed() ) {
		return do_shortcode( $content );
	}
	return 'You do not have sufficient privileges to vote for this matter.';
}

// secret ballots in wp-polls
//add_filter( 'poll_log_show_log_filter', '__return_false' );
//add_filter( 'poll_log_secret_ballot', '__return_empty_string' );