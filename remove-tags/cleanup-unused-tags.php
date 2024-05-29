<?php
/**
 * Plugin Name: Cleanup Unused Tags
 * Plugin URI:  # (This plugin doesn't have a URI)
 * Description: Deletes unused tags (tags with 0 posts) upon activation. 
 * Version:     1.0
 * Author:      Dietrich Duke
 * Author URI:  # (Your website or contact info)
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Only run on plugin activation
if ( ! defined( 'ABSPATH' ) || ! function_exists( 'activate_plugin' ) ) {
  exit;
}

register_activation_hook( __FILE__, 'cleanup_unused_tags' );

function cleanup_unused_tags() {
  global $wpdb;

  // Get unused tags (count = 0)
  $unused_tags = $wpdb->get_results( "SELECT t.term_id, t.name FROM {$wpdb->terms} AS t LEFT JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id WHERE tt.count = 0" );

  // Check if any unused tags found
  if ( $unused_tags ) {
    foreach ( $unused_tags as $tag ) {
      // Delete the tag
      wp_delete_term( $tag->term_id, 'post_tag' );
    }
    // Success message (optional)
    echo '<div class="notice notice-success"><p>Successfully deleted unused tags.</p></div>';
  } else {
    // No unused tags message (optional)
    echo '<div class="notice notice-info"><p>No unused tags found.</p></div>';
  }
}

?>
