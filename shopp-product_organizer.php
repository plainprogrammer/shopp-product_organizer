<?php
/* shopp-product_organizer
 *
 * This extension adds a means for managing the default ordering of Shopp
 * products on their category display pages.
 *
 * Author: Plain Programs LLC (James Thompson)
 * Author URL: http://plainprograms.com/
 * Author Email: james@plainprograms.com
 * License: MIT licensed, Refer to LICENSE file
 *
 */

// Define Controller

// Define admin view

// Housekeeping functions
function shopp_product_organizer_install() {
  global $wpdb;

  $table_name = DatabaseObject::tablename('product');
  
  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    $sql = "ALTER TABLE $table_name ADD prodorgnzr_order INT;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
}

// Integration calls
register_activation_hook(__FILE__,'shopp_product_organizer_install');
