# WordPress Kint Debugger

Dump variables and traces in an organized and interactive display. Integrates seamlessly with Debug Bar.

## Description

**WordPress Kint Debugger** is a simple wrapper for [Kint](https://github.com/kint-php/kint), a debugging tool to output information about variables and traces in a styled, collapsible format that makes understanding deep arrays and objects easier.

*No more adding PRE tags before print_r or var_dump!*

Kint Debugger works well with the [Debug Bar](https://wordpress.org/plugins/debug-bar/) plugin by creating its own panel to display your debug results.

## Basic Usage

```php
<?php d( $var ); ?>
```

## Examples:

```php
<?php global $post; d( $post ); ?>
```

```php
<?php
    global $post;
    $term_list = wp_get_post_terms( $post->ID, 'my_taxonomy', array( 'fields' => 'all' ) );
    d( $term_list );
?>
```

Kint Debugger also provides some helper functions for dumping variables that are frequently needed.

* `dump_wp_query()`
* `dump_wp()`
* `dump_post()`
* `dump_this( $var, $inline = false )` - explained below

Examples:

```php
<?php dump_post(); ?>
```

```php
<?php add_action( 'wp_head', 'dump_post' ); ?>
```

Obviously, if this plugin is not active, calls to the helper functions will cause errors.

## Your Own Functions

If you are dumping the same information in different places, consider writing your own helper functions in your theme's functions file or an mu-plugin. For example:

```php
<?php
function my_dump_terms() {
     global $post;
     $term_list = wp_get_post_terms( $post->ID, 'my_taxonomy', array( 'fields' => 'all' ) );
     d( $term_list  );
}
?>
```

Then at strategic points in your theme or plugin:

```php
<?php my_dump_terms(); ?>
```

## With Debug Bar

By default, when [Debug Bar](https://wordpress.org/plugins/debug-bar/) is installed and active, Kint Debugger will send d() output to its Debug Bar panel.

To print debug output inline instead, as if Debug Bar was not active, declare the constant KINT_TO_DEBUG_BAR in your config.php (or really anywhere before your d() call):

```php
define( 'KINT_TO_DEBUG_BAR', false );
```

Or to print a specific dump inline, use a helper function with the parameter `$inline`. The generic `dump_this()` takes `$inline` as the second parameter.

Examples:

```php
<?php dump_post( true ); ?>
```

```php
<?php
    global $post;
    $term_list = wp_get_post_terms( $post->ID, 'my_taxonomy', array( 'fields' => 'all' ) );
    dump_this( $term_list , true );
?>
```

Kint Debugger overrides Kint's d() function in order to buffer its output for Debug Bar. If you already have a modified d() function, you need to prevent the override in one of two ways.

1. Move your modified d() function to an mu-plugin. Kint Debugger checks if the function exists before declaring it so putting yours in an mu-plugin is the only way to ensure it exists first.
1. Declare KINT_TO_DEBUG_BAR as described above.

## Restricting Output

To restrict visibility, use the `kint_debug_display` filter. For example, to prevent non-admins from seeing the debug output:

```php
add_filter( 'kint_debug_display', function( $allow ) { return is_super_admin(); } );
```

---

## Frequently Asked Questions

### I have called a debug function, but I can't find the output.

If Debug Bar is installed and active, your debug results will be displayed on the "Kint Debugger" panel.

Otherwise, your debug results will be inserted into the current page's HTML.

### Can I change the style of the output?

Currently, the Kint library includes some themes and a config file. Feel free to configure as you see fit. In order to leave the Kint library intact, the plugin does not provide additional configuration.

Fortunately, the developers of Kint are working on version 2 which will make it easier to configure and extend it.

---

## Credit

Credit goes to [Brian Fegter](https://profiles.wordpress.org/misternifty/) and [Chris Dillon](https://profiles.wordpress.org/cdillon27/) for their [plugin](https://wordpress.org/plugins/kint-debugger/). This was created from a fork since the old plugin was no longer being maintained, and offers to adopt weren't responded to.
