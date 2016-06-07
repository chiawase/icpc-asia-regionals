<?php

class FMControllerGoptions_fm {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function execute() {
    $task = WDW_FM_Library::get('task');
    $id = (int)WDW_FM_Library::get('current_id', 0);
    $message = WDW_FM_Library::get('message');
    echo WDW_FM_Library::message_id($message);
    if (method_exists($this, $task)) {
		check_admin_referer('nonce_fm', 'nonce_fm');
		$this->$task($id);
    }
    else {
		$this->display();
    }
  }

  public function display() {
    require_once WD_FM_DIR . "/admin/models/FMModelGoptions_fm.php";
    $model = new FMModelGoptions_fm();

    require_once WD_FM_DIR . "/admin/views/FMViewGoptions_fm.php";
    $view = new FMViewGoptions_fm($model);
    $view->display();
  }

  public function save() {
    $message = $this->save_db();
    $page = WDW_FM_Library::get('page');
    WDW_FM_Library::fm_redirect(add_query_arg(array('page' => $page, 'task' => 'display', 'message' => $message), admin_url('admin.php')));
  }
  
  public function save_db() {
	global $wpdb;
    $public_key = (isset($_POST['public_key']) ? esc_html(stripslashes( $_POST['public_key'])) : '');
    $private_key = (isset($_POST['private_key']) ?  esc_html(stripslashes( $_POST['private_key'])) : '');
    $csv_delimiter = (isset($_POST['csv_delimiter']) && $_POST['csv_delimiter']!='' ? esc_html(stripslashes( $_POST['csv_delimiter'])) : ',');
    update_option('fm_settings', array('public_key' => $public_key, 'private_key' => $private_key, 'csv_delimiter' => $csv_delimiter));	
  }



  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}