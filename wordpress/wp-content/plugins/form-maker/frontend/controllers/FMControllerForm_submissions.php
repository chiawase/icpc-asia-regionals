<?php

class FMControllerForm_submissions {
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
  public function execute($id = '', $startdate = '', $enddate = '', $submit_date = '', $submitter_ip = '', $username = '', $useremail = '', $form_fields = '', $show = '') {
    require_once(WD_FM_DIR . '/framework/WDW_FM_Library.php');
    $task = WDW_FM_Library::get('action');
    if (method_exists($this, $task) && $task != 'display') {
      return $this->$task();
    }
    else {
      return $this->display((int)$id, $startdate, $enddate, $submit_date, $submitter_ip, $username, $useremail, $form_fields, $show);
    }
  }

  public function display($id, $startdate, $enddate, $submit_date, $submitter_ip, $username, $useremail, $form_fields, $show) {
    if (session_id() == '' || (function_exists('session_status') && (session_status() == PHP_SESSION_NONE))) {
      @session_start();
    }
    require_once WD_FM_DIR . "/frontend/models/FMModelForm_submissions.php";
    $model = new FMModelForm_submissions();

    require_once WD_FM_DIR . "/frontend/views/FMViewForm_submissions.php";
    $view = new FMViewForm_submissions($model);
    
    $show = explode(",", $show);
    $csv = isset($show[0]) ? $show[0] : 0;
    $xml = isset($show[1]) ? $show[1] : 0;
    $title = isset($show[2]) ? $show[2] : 0;
    $search = isset($show[3]) ? $show[3] : 0;
    $ordering = isset($show[4]) ? $show[4] : 0;
    $entries = isset($show[5]) ? $show[5] : 0;
    $views = isset($show[6]) ? $show[6] : 0;
    $conversion_rate = isset($show[7]) ? $show[7] : 0;
    $pagination = isset($show[8]) ? $show[8] : 0;
    $stats = isset($show[9]) ? $show[9] : 0;

    return $view->display($id, $startdate, $enddate, $submit_date, $submitter_ip, $username, $useremail, $form_fields, $csv, $xml, $title, $search, $ordering, $entries, $views, $conversion_rate, $pagination, $stats);
  }

  public function get_frontend_stats() {  
    require_once WD_FM_DIR . "/frontend/models/FMModelForm_submissions.php";
    $model = new FMModelForm_submissions();

    require_once WD_FM_DIR . "/frontend/views/FMViewForm_submissions.php";
    $view = new FMViewForm_submissions($model);
    $view->show_stats();
  }

  public function frontend_show_map() {
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);
    require_once WD_FM_DIR . "/frontend/models/FMModelForm_submissions.php";
    $model = new FMModelForm_submissions();

    require_once WD_FM_DIR . "/frontend/views/FMViewForm_submissions.php";
    $view = new FMViewForm_submissions($model);
    $view->show_map($form_id);
  }

  public function frontend_show_matrix() {
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);
    require_once WD_FM_DIR . "/frontend/models/FMModelForm_submissions.php";
    $model = new FMModelForm_submissions();

    require_once WD_FM_DIR . "/frontend/views/FMViewForm_submissions.php";
    $view = new FMViewForm_submissions($model);
    $view->show_matrix($form_id);
  }

  public function frontend_paypal_info() {
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);
    require_once WD_FM_DIR . "/frontend/models/FMModelForm_submissions.php";
    $model = new FMModelForm_submissions();

    require_once WD_FM_DIR . "/frontend/views/FMViewForm_submissions.php";
    $view = new FMViewForm_submissions($model);
    $view->paypal_info($form_id);
  }

  public function frontend_generate_csv() {
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);
    require_once WD_FM_DIR . "/frontend/models/FMModelForm_submissions.php";
    $model = new FMModelForm_submissions();

    require_once WD_FM_DIR . "/frontend/views/FMViewForm_submissions.php";
    $view = new FMViewForm_submissions($model);
    $view->generate_csv($form_id);
  }

  public function frontend_generate_xml() {
    $form_id = ((isset($_POST['form_id']) && esc_html($_POST['form_id']) != '') ? (int)esc_html($_POST['form_id']) : 0);
    require_once WD_FM_DIR . "/frontend/models/FMModelForm_submissions.php";
    $model = new FMModelForm_submissions();

    require_once WD_FM_DIR . "/frontend/views/FMViewForm_submissions.php";
    $view = new FMViewForm_submissions($model);
    $view->generate_xml($form_id);
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