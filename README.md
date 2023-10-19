# WordPress Kint Debugger

Dump variables and traces in an organized and interactive display. Integrates seamlessly with Debug Bar.

## Description

**WordPress Kint Debugger** is a simple wrapper for [Kint](https://github.com/kint-php/kint), a debugging tool to output information about variables and traces in a styled, collapsible format that makes understanding deep arrays and objects easier.

*No more adding PRE tags before print_r or var_dump!*

Kint Debugger works well with the [Debug Bar](https://wordpress.org/plugins/debug-bar/) plugin by creating its own panel to display your debug results.

## Basic Usage

```php
ddb( $var );
```

## Examples:

```php
global $post;
ddb( $post );
```

```php
global $post;
$term_list = wp_get_post_terms( $post->ID, 'my_taxonomy', array( 'fields' => 'all' ) );
ddb( $term_list );
```

Kint Debugger also provides some helper functions for dumping variables that are frequently needed.

* `dump_wp_query()`
* `dump_wp()`
* `dump_post()`

Examples:

```php
dump_post();
```

```php
add_action( 'wp_head', 'dump_post' );
```

Obviously, if this plugin is not active, calls to these functions will cause errors.

## Your Own Functions

If you are dumping the same information in different places, consider writing your own helper functions in your theme's functions file or an mu-plugin. For example:

```php
function my_dump_terms() {
  global $post;
  $term_list = wp_get_post_terms( $post->ID, 'my_taxonomy', array( 'fields' => 'all' ) );
  ddb( $term_list  );
}
```

Then at strategic points in your theme or plugin:

```php
my_dump_terms();
```

## With Debug Bar

When [Debug Bar](https://wordpress.org/plugins/debug-bar/) is installed and active, Kint Debugger will send ddb() output to its Debug Bar panel.

To print a specific dump inline, use Kint's native d() call:

```php
d( $var );
```

Helper functions take a boolean parameter `$inline`. Examples:

```php
dump_post( true );
```

```php
dump_wp( true );
```

## Restricting Output

To restrict visibility, use the `kint_debug_display` filter. For example, to prevent non-admins from seeing the debug output:

```php
add_filter( 'kint_debug_display', function( $allow ) { return is_super_admin(); } );
```

---

## Frequently Asked Questions

### I have called a debug function, but I can't find the output.

If Debug Bar is installed and active, your debug results when calling `ddb` will be displayed on the "Kint Debugger" panel.

Otherwise, your debug results will be inserted into the current page's HTML.

### Can I change the style of the output?

See Kint library documentation. This plugin does not theme the output, nor should it prevent Kint's theming options from being used.

---

## Credit

Credit goes to [Brian Fegter](https://profiles.wordpress.org/misternifty/) and [Chris Dillon](https://profiles.wordpress.org/cdillon27/) for their [plugin](https://wordpress.org/plugins/kint-debugger/). This was created from a fork since the old plugin was no longer being maintained, and offers to adopt weren't responded to.
