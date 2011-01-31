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
class ShoppProductOrganizerController {
  private $shopp;

  public $view;
  
  public function __construct(){
    global $Shopp;
    
    $this->shopp =& $Shopp;
    $this->view = new ShoppProductOrganizerView;
    
    add_action('shopp_init', array(&$this, 'init'));
  }
  
  public function init(){
    add_action('admin_menu', array(&$this, 'add_menu'));
  }
  
  public function add_menu(){
    add_submenu_page(
      $this->shopp->Flow->Admin->MainMenu,
      __('Product Organizer', 'shopp-product_organizer'),
      __('Product Organizer', 'shopp-product_organizer'),
      defined('SHOPP_USERLEVEL') ? SHOPP_USERLEVEL : 'manage_products', 'shopp-product_organizer',
      array(&$this->view, 'print_product_organizer_page'));
  }
}

// Define admin view
class ShoppProductOrganizerView {
  private $db;
  private $products_table;
  private $categories_table;

  public $page;
  
  public function __construct() {
    $this->db = DB::get();
    $this->products_table = DatabaseObject::tablename(Product::$table);
		$this->categories_table = DatabaseObject::tablename(Category::$table);

    if (isset($_GET['page'])) $this->page = strtolower($_GET['page']);
    
    date_default_timezone_set('US/Central');
  }
  
  public function print_product_organizer_page(){
    include_once('views/admin_product_organizer_page.phtml');
  }
}

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

function shopp_product_organizer_init_event() {
  
}

function load_shopp_product_organizer() {
	global $shopp_product_organizer;

	$shopp_product_organizer = new ShoppProductOrganizerController;
}

// Integration calls
register_activation_hook(__FILE__,'shopp_product_organizer_install');

add_action('init', 'shopp_product_organizer_init_event');
add_action('plugins_loaded', 'load_shopp_product_organizer');
