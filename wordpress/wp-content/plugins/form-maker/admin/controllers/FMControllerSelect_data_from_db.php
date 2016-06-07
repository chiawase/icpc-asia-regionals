<?php

class FMControllerSelect_data_from_db {
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
    $id = ((isset($_GET['id'])) ? (int)esc_html($_GET['id']) : 0);
    $form_id = ((isset($_GET['form_id'])) ? (int)esc_html($_GET['form_id']) : 0);
    $field_id = ((isset($_GET['field_id'])) ? esc_html($_GET['field_id']) : 0);
	//var_dump($form_id);
    $field_type = ((isset($_GET['field_type'])) ? esc_html($_GET['field_type']) : 0);
    $value_disabled = ((isset($_GET['value_disabled'])) ? esc_html($_GET['value_disabled']) : 0);
	if ($task && method_exists($this, $task)) {
        $this->$task($form_id,$field_type="");
    }
    else
		$this->display($id, $field_id, $field_type, $value_disabled,$form_id);
  }

  public function display($id, $field_id, $field_type, $value_disabled,$form_id) {
    require_once WD_FM_DIR . "/admin/models/FMModelSelect_data_from_db.php";
    $model = new FMModelSelect_data_from_db();

    require_once WD_FM_DIR . "/admin/views/FMViewSelect_data_from_db.php";
    $view = new FMViewSelect_data_from_db($model);
    $view->display($id, $field_id, $field_type, $value_disabled,$form_id);
  }
  
  public function db_tables($form_id,$field_type) {
    require_once WD_FM_DIR . "/admin/models/FMModelSelect_data_from_db.php";
    $model = new FMModelSelect_data_from_db();

    require_once WD_FM_DIR . "/admin/views/FMViewSelect_data_from_db.php";
    $view = new FMViewSelect_data_from_db($model);
    $view->db_tables((int)$form_id, $field_type);
  }
   public function db_table_struct_select($form_id,$field_type) {
    require_once WD_FM_DIR . "/admin/models/FMModelSelect_data_from_db.php";
    $model = new FMModelSelect_data_from_db();

    require_once WD_FM_DIR . "/admin/views/FMViewSelect_data_from_db.php";
    $view = new FMViewSelect_data_from_db($model);
    $view->db_table_struct_select((int)$form_id,$field_type);
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