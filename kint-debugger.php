<?php
/**
 * Plugin Name: WordPress Kint Debugger
 * Plugin URI: https://github.com/jameelmoses/wordpress-kint-debugger
 * Description: Dump variables and traces in an organized and interactive display. Works with Debug Bar.
 * Version: 2.0.0
 * Author: Jameel Moses
 * Author URI: https://github.com/jameelmoses
 * Requires: 2.5 or higher
 * Requires PHP: 7.1
 * License: Dual license GPL-3.0 & MIT (Kint is licensed MIT)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

use Kint\Kint;

/**
 * Load Kint via plugin-specific autoloader, only if the class is not already present.
 */
if ( !class_exists( Kint::class ) ) {
	require_once( __DIR__ . '/vendor/autoload.php' );
}

/**
 * Helper functions.
 */

if ( ! function_exists( 'dump_this' ) ) {
	/**
	 * Generic data dump.
	 *
	 * @since 1.0
	 */
	function dump_this( $var, $inline = false ) {
		/**
		 * Some hooks send WP objects which then get passed as $inline
		 * so check type too.
		 */
		if ( true === $inline ) {
			Kint::dump( $var );
		}
		else {
			ddb( $var );
		}
	}
}
Kint::$aliases[] = 'dump_this';

if ( ! function_exists( 'dump_wp_query' ) ) {
	function dump_wp_query( bool $inline = false ) {
		global $wp_query;
		dump_this( $wp_query, $inline );
	}
}
Kint::$aliases[] = 'dump_wp_query';

if ( ! function_exists( 'dump_wp' ) ) {
	function dump_wp( bool $inline = false ) {
		global $wp;
		dump_this( $wp, $inline );
	}
}
Kint::$aliases[] = 'dump_wp';

if ( ! function_exists( 'dump_post' ) ) {
	function dump_post( bool $inline = false ) {
		global $post;
		dump_this( $post, $inline );
	}
}
Kint::$aliases[] = 'dump_post';

/**
 * Alias of Kint::dump() similar to d().
 *
 * Unlike d(), this sends Kint output to Debug Bar if active.
 */
function ddb( ...$args ): int|string {
	if ( class_exists( 'Debug_Bar' ) ) {
		ob_start( 'kint_debug_ob' );
		Kint::dump( ...$args );
		ob_end_flush();
		return '';
	}

	return Kint::dump( ...$args );
}
Kint::$aliases[] = 'ddb';

/**
 * Output buffer callback.
 *
 * @param $buffer
 */
function kint_debug_ob( $buffer ): string {
	global $kint_debug;
	$kint_debug[] = $buffer;
	if ( class_exists( 'Debug_Bar' ) ) {
		return '';
	}

	return $buffer;
}

/**
 * Add our Debug Bar panel.
 *
 * @param $panels
 *
 * @return array
 */
function kint_debug_bar_panel( $panels ) {

	if ( ! class_exists( 'Kint_Debug_Bar_Panel' ) ) {
		require_once 'includes/class-kint-debug-bar-panel.php';
	}

	$panels[] = new Kint_Debug_Bar_Panel;

	return $panels;
}
add_filter( 'debug_bar_panels', 'kint_debug_bar_panel' );
