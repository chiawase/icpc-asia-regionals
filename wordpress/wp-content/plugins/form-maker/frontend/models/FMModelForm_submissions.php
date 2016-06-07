<?php

class FMModelForm_submissions {
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
  function showsubmissions($form_id, $startdate, $enddate, $submit_date, $submitter_ip, $username, $useremail, $form_fields, $csv, $xml, $title, $search, $ordering, $entries, $views, $conversion_rate, $pagination, $stats) {
		global $wpdb;
	
		$user = wp_get_current_user();
		$userGroups = $wpdb->get_var($wpdb->prepare("SELECT `user_id_wd` FROM " . $wpdb->prefix . "formmaker WHERE id='%d'", $form_id));    
		$users = explode(',', $userGroups);
		$users = array_slice($users, 0, count($users) - 1); 
		$show_submits = false;
		if(!is_user_logged_in()) {
			if(!in_array('guest', $users))
				return false;
		}
		else {
			foreach($user->roles as $user_role) {
				if(in_array($user_role, $users))
					$show_submits = true;
			}
			if(!$show_submits)
				return false;
		}
		$from = $startdate;
		$to = $enddate;
		
		$filter_order = ((isset($_POST['order_by']) && esc_html(stripslashes($_POST['order_by'])) != '') ? esc_html(stripslashes($_POST['order_by'])) : 'group_id');
		$filter_order_Dir = ((isset($_POST['asc_or_desc']) && ($_POST['asc_or_desc'] == 'asc' || $_POST['asc_or_desc'] == 'desc')) ? esc_html($_POST['asc_or_desc']) : 'asc');
		
		$ip_search = $submitter_ip;
		$username_search = strtolower($username);
		$useremail_search = strtolower($useremail);

		$where = array();
		$lists['startdate'] = ((isset($_POST['startdate'])) ? esc_html(stripslashes($_POST['startdate'])) : '');
		$lists['enddate'] = ((isset($_POST['enddate'])) ? esc_html(stripslashes($_POST['enddate'])) : '');
		$lists['hide_label_list'] = ((isset($_POST['hide_label_list'])) ? esc_html(stripslashes($_POST['hide_label_list'])) : '');
    
		$lists['ip_search'] = ((isset($_POST['ip_search'])) ? esc_html(stripslashes($_POST['ip_search'])) : '');
		$lists['username_search'] = ((isset($_POST['username_search'])) ? esc_html(stripslashes($_POST['username_search'])) : '');
		$lists['useremail_search'] = ((isset($_POST['useremail_search'])) ? esc_html(stripslashes($_POST['useremail_search'])) : '');
    $limit = ((isset($_POST['page_number'])) ? ((int) $_POST['page_number'] - 1) * 20 : 0);
		
		if ( $lists['ip_search'] ) {
			$where[] = 'ip LIKE "%' . esc_sql($lists['ip_search']) . '%"';
		}		
		if ( $lists['username_search'] ) {
			$where[] = 'user_id_wd IN (SELECT `id` FROM `' . $wpdb->prefix . 'users` WHERE `display_name` LIKE "%' . esc_sql($lists['username_search']) . '%")';
		}
		if ( $lists['useremail_search'] ) {
			$where[] = 'user_id_wd IN (SELECT `id` FROM `' . $wpdb->prefix . 'users` WHERE `user_email` LIKE "%' . esc_sql($lists['useremail_search']) . '%")';
		}
		
		if($from) {
			if($lists['startdate'] != '') {
				if(strtotime($from) > strtotime($lists['startdate']))
					$where[] = "`date` >= '" . $from . " 00:00:00'";
				else
					$where[] ="`date`>='" . $lists['startdate'] . " 00:00:00' ";
			}
			else
				$where[] = "`date` >= '" . $from . " 00:00:00'";
		}
		else {
			if($lists['startdate']!='')
        $where[] ="  `date`>='".$lists['startdate']." 00:00:00' ";
		}
		if($to) {
			if($lists['enddate']!='') {
				if(strtotime($to) < strtotime($lists['enddate']))
					$where[] = "`date` <= '".$to." 23:59:59'";
				else
					$where[] ="`date`<='".$lists['enddate']." 23:59:59' ";
			}
			else
				$where[] = "`date` <= '".$to." 23:59:59'";
		}
		else {
			if($lists['enddate']!='')
        $where[] ="`date`<='".$lists['enddate']." 23:59:59' ";
		}
					
		$form_title = $wpdb->get_var($wpdb->prepare("SELECT `title` FROM " . $wpdb->prefix . "formmaker WHERE id='%d'", $form_id));

		$where[] = 'form_id="' . (int)$form_id . '"';
		$where 		= ( count( $where ) ? '  ' . implode( ' AND ', $where ) : '' );
		$orderby 	= ' ';
		if ($filter_order == 'id' or $filter_order == 'title' or $filter_order == 'mail') {
			$orderby 	= ' ORDER BY `date` desc';
		}
		else {
			if ($filter_order == 'group_id' or $filter_order == 'date' or $filter_order == 'ip') {
					$orderby 	= ' ORDER BY '.$filter_order .' '. $filter_order_Dir .'';
			} 
			else {
				if ($filter_order == 'display_name' or $filter_order == 'user_email') {
					$orderby 	= ' ORDER BY (SELECT `'.$filter_order.'` FROM `' . $wpdb->prefix . 'users` WHERE id=user_id_wd) '. $filter_order_Dir .'';
				}
      }
		}
		$query = "SELECT distinct element_label FROM " . $wpdb->prefix . "formmaker_submits WHERE ". $where;
		$labels = $wpdb->get_col($query);

		$query = $wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "formmaker_submits WHERE form_id='%d' and element_label=0 limit 0, 1", $form_id);    
		$ispaypal = $wpdb->get_var($query);

		$query = $wpdb->prepare('SELECT count(distinct group_id) FROM ' . $wpdb->prefix . 'formmaker_submits where form_id ="%d"', $form_id);    
		$total_entries = $wpdb->get_var($query);
	
		$sorted_labels_type= array();
		$sorted_labels_id= array();
		$sorted_labels= array();
		$label_titles=array();
		$rows_ord = array();
		$rows = array();
		$total = 0;	
		$join_count='';
    $checked_ids = '';
    $stats_fields = '';
    
		if($labels) {
			$label_id= array();
			$label_order= array();
			$label_order_original= array();
			$label_type= array();			
			$this_form = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "formmaker WHERE id='%d'", $form_id));
      $checked_ids = $this_form->frontend_submit_fields;
      $stats_fields = $this_form->frontend_submit_stat_fields;
			
			if(strpos($this_form->label_order, 'type_paypal_')) {
				$this_form->label_order=$this_form->label_order."item_total#**id**#Item Total#**label**#type_paypal_payment_total#****#total#**id**#Total#**label**#type_paypal_payment_total#****#0#**id**#Payment Status#**label**#type_paypal_payment_status#****#";
			}
			$label_all	= explode('#****#',$this_form->label_order);
			$label_all 	= array_slice($label_all,0, count($label_all)-1);
			foreach($label_all as $key => $label_each) {
				$label_id_each=explode('#**id**#',$label_each);
				array_push($label_id, $label_id_each[0]);
				
				$label_order_each=explode('#**label**#', $label_id_each[1]);	
				array_push($label_order_original, $label_order_each[0]);
				
				$ptn = "/[^a-zA-Z0-9_]/";
				$rpltxt = "";
				$label_temp=preg_replace($ptn, $rpltxt, $label_order_each[0]);
				array_push($label_order, $label_temp);		
				array_push($label_type, $label_order_each[1]);
			}
			
			$join_query=array();
			$join_where=array();
			$join='';
			$is_first=true;
			
			foreach($label_id as $key => $label) {
				if(in_array($label, $labels)) {
					array_push($sorted_labels_type, $label_type[$key]);
					array_push($sorted_labels, $label_order[$key]);
					array_push($sorted_labels_id, $label);
					array_push($label_titles, $label_order_original[$key]);
					$search_temp = isset($_POST[$form_id.'_'.$label.'_search']) ? $_POST[$form_id.'_'.$label.'_search'] : '';
					$search_temp = strtolower( $search_temp );
					$lists[$form_id.'_'.$label.'_search']	 = $search_temp;					
					if ( $search_temp ) {
						$join_query[]	='search';
						$join_where[]	=array('label'=>$label, 'search'=>esc_sql($search_temp));
					}
				}
      }
			if(strpos($filter_order,"_field")) {
				if (in_array(str_replace("_field", "", $filter_order), $labels)) {
					$join_query[]	='sort';
					$join_where[]	=array('label'=>str_replace("_field", "", $filter_order));
				}
			}
			$cols 	= 'group_id';
			if ($filter_order == 'date' or $filter_order == 'ip') {
				$cols 	= 'group_id, date, ip';
			}
		
			switch(count($join_query)) {
				case 0:
					$join='SELECT distinct group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE '. $where;
          break;
				case 1:
					if($join_query[0]=='sort') {
						$join		=	'SELECT group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE '.$where.' AND element_label="'.$join_where[0]['label'].'" ';
						$join_count	=	'SELECT count(group_id) FROM ' . $wpdb->prefix . 'formmaker_submits WHERE form_id="'.esc_sql((int)$form_id).'" AND element_label="'.$join_where[0]['label'].'" ';
						$orderby 	= 	' ORDER BY `element_value` '. $filter_order_Dir .'';
					}
					else
						$join='SELECT group_id FROM ' . $wpdb->prefix . 'formmaker_submits WHERE element_label="'.$join_where[0]['label'].'" AND  element_value LIKE "%'.$join_where[0]['search'].'%" AND '. $where;
          break;						
				default:
					$join='SELECT t.group_id FROM (SELECT '.$cols.'  FROM ' . $wpdb->prefix . 'formmaker_submits WHERE '.$where.' AND element_label="'.$join_where[0]['label'].'" AND  element_value LIKE "%'.$join_where[0]['search'].'%" ) as t ';					
					for($key=1; $key< count($join_query); $key++) {
						if($join_query[$key]=='sort') {
							$join.='LEFT JOIN (SELECT group_id as group_id'.$key.', element_value   FROM ' . $wpdb->prefix . 'formmaker_submits WHERE '.$where.' AND element_label="'.$join_where[$key]['label'].'") as t'.$key.' ON t'.$key.'.group_id'.$key.'=t.group_id ';
							$orderby 	= 	' ORDER BY t'.$key.'.`element_value` '. $filter_order_Dir .'';
						}
						else
							$join.='INNER JOIN (SELECT group_id as group_id'.$key.' FROM ' . $wpdb->prefix . 'formmaker_submits WHERE '.$where.' AND element_label="'.$join_where[$key]['label'].'" AND  element_value LIKE "%'.$join_where[$key]['search'].'%" ) as t'.$key.' ON t'.$key.'.group_id'.$key.'=t.group_id ';
          }
          break;
			}
			
			$pos = strpos($join, 'SELECT t.group_id');		
			if ($pos === false) 
				$query = str_replace(array('SELECT group_id','SELECT distinct group_id'), array('SELECT count(distinct group_id)','SELECT count(distinct group_id)'),  $join);
			else
				$query = str_replace('SELECT t.group_id', 'SELECT count(t.group_id)',  $join);
			$total = $wpdb->get_var($query);
			
			$query = $join.' '.$orderby . ($pagination ? ' limit ' . $limit . ', 20 ' : '') . ' ';
			
			$rows_ord = $wpdb->get_col($query);
			
			$where2 = array();
				$where2 [] ="group_id='0'";			
			foreach($rows_ord as $rows_ordd) {
				$where2 [] ="group_id='".esc_sql($rows_ordd)."'";
			}
			$where2 = ( count( $where2 ) ? ' WHERE ' . implode( ' OR ', $where2 ).'' : '' );
			$query = "SELECT * FROM " . $wpdb->prefix . "formmaker_submits ".$where2.'';

			$rows = $wpdb->get_results($query);
			
			if($join_count) {
				$total_sort = $wpdb->get_var($join_count);
				if($total_sort != $total_entries)
					$join_count = $total_sort;
				else
					$join_count = '';
			}
		}
		
		$query = $wpdb->prepare('SELECT views FROM ' . $wpdb->prefix . 'formmaker_views WHERE form_id="%d"', $form_id);    
		$total_views = $wpdb->get_var($query);

		$pageNav = "";// $pageNav = new JPagination( $total, $limitstart, $limit );	

		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
    $lists['total'] = $total;
    $lists['limit'] = (int) ($limit / 20 + 1);
		return array("rows" => $rows, "lists" => $lists, "pageNav" => $pageNav, "sorted_labels" => $sorted_labels, "label_titles" => $label_titles, "rows_ord" => $rows_ord, "sorted_labels_id" => $sorted_labels_id, "sorted_labels_type" => $sorted_labels_type, "total_entries" => $total_entries, "total_views" => $total_views, "join_count" => $join_count, "form_title" => $form_title, "checked_ids" => $checked_ids, "stats_fields" => $stats_fields);
	}
  
  function show_stats() {
		global $wpdb;
		$form_id = (isset($_POST['form_id']) ? esc_html(stripslashes($_POST['form_id'])) : 0);
		$id = (isset($_POST['stat_id']) ? esc_html(stripslashes($_POST['stat_id'])) : 0);
		$from = (isset($_POST['from']) ? esc_html(stripslashes($_POST['from'])) : 0);
		$to = (isset($_POST['to']) ? esc_html(stripslashes($_POST['to'])) : 0);
		$where = ' AND form_id=' . $form_id;		
		if($from != '')
			$where .= " AND `date`>='".$from." 00:00:00' ";
		if($to != '')
			$where .= " AND `date`<='".$to." 23:59:59' ";
		$query = "SELECT element_value FROM " . $wpdb->prefix . "formmaker_submits WHERE element_label='" . $id . "'" . $where;
		$choices = $wpdb->get_col($query);
		return $choices;
	}
  
  function paypal_info($id) {
    global $wpdb;
		$query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "formmaker_sessions where group_id='%d'", $id);    
		return $wpdb->get_row($query);
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