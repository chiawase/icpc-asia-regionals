<?php

class FMControllerFormMakerSQLMapping {
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
    $task = ((isset($_GET['task'])) ? esc_html($_GET['task']) : 0);
    $id = ((isset($_GET['id'])) ? (int) $_GET['id'] : 0);
    $form_id = ((isset($_GET['form_id'])) ? (int) $_GET['form_id'] : 0);
    if ($task && method_exists($this, $task)) {
      $this->$task($form_id);
    }
    else {
      if ($id) {
        $this->edit_query($id, $form_id);
      }
      else {
        $this->add_query($form_id);
      }
    }
  }

  public function add_query($form_id) {
    require_once WD_FM_DIR . "/admin/models/FMModelFormMakerSQLMapping.php";
    $model = new FMModelFormMakerSQLMapping();

    require_once WD_FM_DIR . "/admin/views/FMViewFormMakerSQLMapping.php";
    $view = new FMViewFormMakerSQLMapping($model);
    $view->add_query($form_id);
  }

  public function edit_query($id, $form_id) {
    require_once WD_FM_DIR . "/admin/models/FMModelFormMakerSQLMapping.php";
    $model = new FMModelFormMakerSQLMapping();

    require_once WD_FM_DIR . "/admin/views/FMViewFormMakerSQLMapping.php";
    $view = new FMViewFormMakerSQLMapping($model);
    $view->edit_query($id, $form_id);
  }
  
  public function db_tables($form_id) {
    require_once WD_FM_DIR . "/admin/models/FMModelFormMakerSQLMapping.php";
    $model = new FMModelFormMakerSQLMapping();

    require_once WD_FM_DIR . "/admin/views/FMViewFormMakerSQLMapping.php";
    $view = new FMViewFormMakerSQLMapping($model);
    $view->db_tables($form_id);
  }
  
  public function db_table_struct($form_id) {
    require_once WD_FM_DIR . "/admin/models/FMModelFormMakerSQLMapping.php";
    $model = new FMModelFormMakerSQLMapping();

    require_once WD_FM_DIR . "/admin/views/FMViewFormMakerSQLMapping.php";
    $view = new FMViewFormMakerSQLMapping($model);
    $view->db_table_struct($form_id);
  }

  public function save_query() {
    global $wpdb;
    $form_id = ((isset($_GET['form_id'])) ? (int) $_GET['form_id'] : 0);
    $query = ((isset($_POST['query'])) ? stripslashes(wp_specialchars_decode($_POST['query'])) : "");
    $details = ((isset($_POST['details'])) ? esc_html($_POST['details']) : "");
    $save = $wpdb->insert($wpdb->prefix . 'formmaker_query', array(
      'form_id' => $form_id,                       
      'query' => $query,
      'details' => $details,
    ), array(
      '%d',
      '%s',
      '%s',
    ));
  }

  public function update_query() {
    global $wpdb;
    $id = ((isset($_GET['id'])) ? (int) $_GET['id'] : 0);
    $form_id = ((isset($_GET['form_id'])) ? (int) $_GET['form_id'] : 0);
    $query = ((isset($_POST['query'])) ? stripslashes(wp_specialchars_decode($_POST['query'])) : "");
    $details = ((isset($_POST['details'])) ? esc_html($_POST['details']) : "");
    $save = $wpdb->update($wpdb->prefix . 'formmaker_query', array(
      'form_id' => $form_id,
      'query' => $query,
      'details' => $details,
    ), array('id' => $id));
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