<?php

class FMViewForm_submissions {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display($form_id, $startdate, $enddate, $submit_date, $submitter_ip, $username, $useremail, $form_fields, $csv, $xml, $title, $search, $ordering, $entries, $views, $conversion_rate, $pagination, $stats) {
    require_once(WD_FM_DIR . '/framework/WDW_FM_Library.php');
    $get_data = $this->model->showsubmissions($form_id, $startdate, $enddate, $submit_date, $submitter_ip, $username, $useremail, $form_fields, $csv, $xml, $title, $search, $ordering, $entries, $views, $conversion_rate, $pagination, $stats);
    if ($get_data === false) {
      echo WDW_FM_Library::message(__('You have no permission to view submissions.', 'form_maker'), 'warning');
      return;
    }
    $rows	= $get_data["rows"];
    $lists = $get_data["lists"];
    $pageNav = $get_data["pageNav"];
    $labels = $get_data["sorted_labels"];
    $label_titles = $get_data["label_titles"];
    $group_id_s	= $get_data["rows_ord"];
    $labels_id = $get_data["sorted_labels_id"];
    $sorted_labels_type = $get_data["sorted_labels_type"];
    $total_entries = $get_data["total_entries"];
    $total_views = $get_data["total_views"];
    $join_count = $get_data["join_count"];
    $form_title = $get_data["form_title"];
    $checked_ids = $get_data["checked_ids"];
    $stats_fields = $get_data["stats_fields"];
	$current_url=htmlentities($_SERVER['REQUEST_URI']);

    $order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'group_id');
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'desc');
    $oder_class_default = "manage-column column-autor sortable desc";
    $oder_class = "manage-column column-title sorted " . $asc_or_desc; 

    $other_fileds = array('submit_id', 'payment_info');
    foreach($other_fileds as $other_filed)
      $$other_filed = false;
    if($checked_ids) {
      $checked_ids = explode(',',$checked_ids);
      $checked_ids 	= array_slice($checked_ids,0, count($checked_ids)-1);
    }
    else
      $checked_ids = Array();
      
    foreach($other_fileds as $other_filed) {
      if(!in_array($other_filed, $checked_ids))
        $$other_filed = true;
    }
    
    $is_paypal = $this->model->paypal_info($form_id);
    if (!$is_paypal)
      $payment_info = false;
    
    $label_titles_copy=$label_titles;
    
    $export_ids = "";
    if ($form_fields && $submit_id) $export_ids .= "submit_id,";
    if ($submit_date) $export_ids .= "submit_date,";
    if ($submitter_ip) $export_ids .= "submitter_ip,";
    if ($username) $export_ids .= "username,";
    if ($useremail) $export_ids .= "useremail,";
    for($i=0; $i < count($labels) ; $i++) {
      if($form_fields && !in_array($labels_id[$i], $checked_ids)) {
        $export_ids .= $labels_id[$i] . ",";
      }
    }
    
    add_thickbox();
  ?>

  <script type="text/javascript">
    function fm_form_submit(event, form_id, task, id) {
      if (document.getElementById(form_id)) {
        document.getElementById(form_id).submit();
      }
      if (event.preventDefault) {
        event.preventDefault();
      }
      else {
        event.returnValue = false;
      }
    }
    function remove_all() {
      if(document.getElementById('startdate'))
        document.getElementById('startdate').value='';
      if(document.getElementById('enddate'))
        document.getElementById('enddate').value='';
      if(document.getElementById('ip_search'))
        document.getElementById('ip_search').value='';
      if(document.getElementById('username_search'))
        document.getElementById('username_search').value='';
      if(document.getElementById('useremail_search'))
        document.getElementById('useremail_search').value='';
      <?php
      $n=count($rows);
      for($i=0; $i < count($labels) ; $i++) {
        echo "if(document.getElementById('".$form_id.'_'.$labels_id[$i]."_search'))
          document.getElementById('".$form_id.'_'.$labels_id[$i]."_search').value='';";
      }
      ?>
    }
    function show_hide_filter() {
      if (document.getElementById('fields_filter').style.display == "none") {
        document.getElementById('fields_filter').style.display = '';
        document.getElementById('filter_img').src = '<?php echo WD_FM_URL . '/images/filter_hide.png'; ?>';
      }
      else {
        document.getElementById('fields_filter').style.display = "none";
        document.getElementById('filter_img').src = '<?php echo WD_FM_URL . '/images/filter_show.png'; ?>';
      }
    }
  </script>
    
    <form action="<?php echo $current_url; ?>" method="post" name="adminForm" id="adminForm">
      <input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php echo $asc_or_desc; ?>" />
      <input type="hidden" name="order_by" id="order_by" value="<?php echo $order_by; ?>" />
      <input type="hidden" id="task" name="task" value="" />
      <div class="submission_params"> 
      <?php if(isset($form_id) and $form_id>0):?>	
      <?php if($title): ?>
      <div class="form_title"><strong><?php echo $form_title; ?></strong></div>
      <?php endif; ?>
      <div>
        <?php if($entries):?>
          <div class="reports" style="width: 80px;"><strong><?php echo __('Entries', 'form_maker'); ?></strong><br /><?php echo $total_entries; ?></div>
        <?php endif; if($views): ?>
          <div class="reports" style="width: 80px;"><strong><?php echo __('Views', 'form_maker'); ?></strong><br /><?php echo $total_views; ?></div>
        <?php endif; if($conversion_rate): ?>
          <div class="reports" style="width: 130px;"><strong><?php echo __('Conversion Rate', 'form_maker'); ?></strong><br /><?php  if($total_views) echo round((($total_entries/$total_views)*100),2).'%'; else echo '0%' ?></div>
        <?php endif; if($csv || $xml):?>
          <div <?php echo (($entries || $views || $conversion_rate) ? 'class="csv_xml"' : '') ?>>
          <?php echo __('Export to', 'form_maker'); ?>
          <?php if($csv): ?>
          <input type="button" value="CSV" onclick="window.location='<?php echo add_query_arg(array('action' => 'frontend_generate_csv', 'page' => 'form_submissions', 'id' => $form_id, 'checked_ids' => $export_ids, 'from' => $startdate, 'to' => $enddate), admin_url('admin-ajax.php')) ?>'" />&nbsp;
          <?php endif; ?>
          <?php if($xml): ?>
          <input type="button" value="XML" onclick="window.location='<?php echo add_query_arg(array('action' => 'frontend_generate_xml', 'page' => 'form_submissions', 'id' => $form_id, 'checked_ids' => $export_ids, 'from' => $startdate, 'to' => $enddate), admin_url('admin-ajax.php')) ?>'" />&nbsp;
          <?php endif; ?>
          </div>
        <?php endif; ?>	
      </div>
      <?php if($search || $pagination): ?>
      <div class="search_and_pagination">
        <?php if($search): ?>
        <div>
          <input type="hidden" name="hide_label_list" value="<?php  echo $lists['hide_label_list']; ?>" /> 
          <img id="filter_img" src="<?php echo WD_FM_URL . '/images/filter_show.png'; ?>" width="40" style="vertical-align:middle; cursor:pointer" onclick="show_hide_filter()"  title="Search by fields" />
          <input type="button" onclick="this.form.submit();" style="vertical-align:middle; cursor:pointer" value="<?php echo __('GO', 'form_maker'); ?>" />	
          <input type="button" onclick="remove_all();this.form.submit();" style="vertical-align:middle; cursor:pointer" value="<?php echo __('Reset', 'form_maker'); ?>" />
        </div>
        <div>
        <?php if($join_count) echo ($total_entries-$join_count) . ' ' . __('of', 'form_maker') . ' ' . $total_entries . ' ' . __('submissions are not shown, as the field you sorted by is missing in those submissions.', 'form_maker'); ?>
        </div>
        <?php endif; ?>
        <?php if($pagination): ?>
        <div class="tablenav top">
          <?php WDW_FM_Library::html_page_nav($lists['total'], $lists['limit'], 'adminForm'); ?>
        </div>  
        <?php endif; ?>
      </div>
      <?php endif; ?>
      <?php endif; ?>
      </div>

      <div style="overflow-x:scroll;">
        <table class="submissions" width="100%">
        <thead>
          <tr>
            <th width="3%"><?php echo '#'; ?></th>
            <?php
            if($form_fields && $submit_id) {
              echo '<th width="4%" class="submitid_fc"';
              if(!(strpos($lists['hide_label_list'],'@submitid@')===false)) 
              echo 'style="display:none;"';
              echo '>';
              if($ordering)
              echo '<a href="" onclick="document.getElementById(\'order_by\').value = \'group_id\'; document.getElementById(\'asc_or_desc\').value = \'' . (($order_by == "group_id" && $asc_or_desc == 'asc') ? 'desc' : 'asc') . '\'; fm_form_submit(event, \'adminForm\');">
                  <span>Id</span>
                  <span>' . ($order_by == "group_id" ? ($asc_or_desc == "asc" ? "&#x25B2;" : "&#x25BC;") : "") . '</span>
                </a>';
              else
              echo 'Id';
              echo '</th>';
            }
            if($submit_date) {
              echo '<th width="150" align="center" class="submitdate_fc"';
              if(!(strpos($lists['hide_label_list'],'@submitdate@')===false)) 
              echo 'style="display:none;"';
              echo '>';
              if($ordering)
              echo '<a href="" onclick="document.getElementById(\'order_by\').value = \'date\'; document.getElementById(\'asc_or_desc\').value = \'' . (($order_by == "date" && $asc_or_desc == 'asc') ? 'desc' : 'asc') . '\'; fm_form_submit(event, \'adminForm\');">
                  <span>Submit date</span>
                  <span>' . ($order_by == "date" ? ($asc_or_desc == "asc" ? "&#x25B2;" : "&#x25BC;") : "") . '</span>
                </a>';
              else
              echo 'Submit Date';
              echo '</th>';
            } 
            if($submitter_ip) {
              echo '<th width="100" align="center" class="submitterip_fc"';
              if(!(strpos($lists['hide_label_list'],'@submitterip@')===false)) 
              echo 'style="display:none;"';
              echo '>';
              if($ordering)
              echo '<a href="" onclick="document.getElementById(\'order_by\').value = \'ip\'; document.getElementById(\'asc_or_desc\').value = \'' . (($order_by == "ip" && $asc_or_desc == 'asc') ? 'desc' : 'asc') . '\'; fm_form_submit(event, \'adminForm\');">
                  <span>Submitter\'s IP Address</span>
                  <span>' . ($order_by == "ip" ? ($asc_or_desc == "asc" ? "&#x25B2;" : "&#x25BC;") : "") . '</span>
                </a>';
              else
              echo 'Submitter\'s IP Address';
              echo '</th>';
            }
            if($username) {
              echo '<th width="100" class="submitterusername_fc"';
              if(!(strpos($lists['hide_label_list'],'@submitterusername@')===false)) 
              echo 'style="display:none;"';
              echo '>';
              if($ordering)
              echo '<a href="" onclick="document.getElementById(\'order_by\').value = \'display_name\'; document.getElementById(\'asc_or_desc\').value = \'' . (($order_by == "display_name" && $asc_or_desc == 'asc') ? 'desc' : 'asc') . '\'; fm_form_submit(event, \'adminForm\');">
                  <span>Submitter\'s Username</span>
                  <span>' . ($order_by == "display_name" ? ($asc_or_desc == "asc" ? "&#x25B2;" : "&#x25BC;") : "") . '</span>
                </a>';
              else
              echo 'Submitter\'s Username';
              echo '</th>';
            }            
            if($useremail) {
              echo '<th width="100" class="submitteremail_fc"';
              if(!(strpos($lists['hide_label_list'],'@submitteremail@')===false)) 
              echo 'style="display:none;"';
              echo '>';
              if($ordering)
              echo '<a href="" onclick="document.getElementById(\'order_by\').value = \'user_email\'; document.getElementById(\'asc_or_desc\').value = \'' . (($order_by == "user_email" && $asc_or_desc == 'asc') ? 'desc' : 'asc') . '\'; fm_form_submit(event, \'adminForm\');">
                  <span>Submitter\'s Email Address</span>
                  <span>' . ($order_by == "user_email" ? ($asc_or_desc == "asc" ? "&#x25B2;" : "&#x25BC;") : "") . '</span>
                </a>';
              else
              echo 'Submitter\'s Email Address';
              echo '</th>';
            }
            $n=count($rows);
            $ispaypal=false;
            for($i=0; $i < count($labels) ; $i++) {
              if($form_fields && !in_array($labels_id[$i], $checked_ids)) {
                if(strpos($lists['hide_label_list'],'@'.$labels_id[$i].'@')===false)  $styleStr='';
                else $styleStr='style="display:none;"';                
                $field_title=$label_titles_copy[$i];                  
                if($sorted_labels_type[$i]=='type_paypal_payment_status') {
                  $ispaypal=true;
                }
                echo '<th align="center" class="'.$labels_id[$i].'_fc" '.$styleStr.'>';
                if($ordering)
                echo '<a href="" onclick="document.getElementById(\'order_by\').value = \'' . $labels_id[$i] . '_field\'; document.getElementById(\'asc_or_desc\').value = \'' . (($order_by == $labels_id[$i] . '_field' && $asc_or_desc == 'asc') ? 'desc' : 'asc') . '\'; fm_form_submit(event, \'adminForm\');">
                    <span>' . $field_title . '</span>
                    <span>' . ($order_by == $labels_id[$i]."_field" ? ($asc_or_desc == "asc" ? "&#x25B2;" : "&#x25BC;") : "") . '</span>
                  </a>';
                else
                echo $field_title;
                echo '</th>';
              }
            }
            if($form_fields && $payment_info) {
              if(strpos($lists['hide_label_list'],'@payment_info@')===false)  
                $styleStr2='aa';
              else 
                $styleStr2='style="display:none;"';
              echo '<th class="payment_info_fc" '.$styleStr2.'>Payment Info</th>';
            }	
      ?>

          </tr>
          <tr id="fields_filter" style="display:none">
            <th width="3%"></th>
            <?php if($form_fields && $submit_id): ?>
            <th width="4%" class="submitid_fc" <?php if(!(strpos($lists['hide_label_list'],'@submitid@')===false)) echo 'style="display:none;"';?> ></th>
            <?php endif;
            
            if($submit_date): ?>
            <th class="submitdate_fc" style="text-align:left; <?php if(!(strpos($lists['hide_label_list'],'@submitdate@')===false)) echo 'display:none;';?>" align="center"> 
            <table class="simple_table">
              <tr>
                <td><?php echo __('From', 'form_maker'); ?>:</td>
                <td><input class="inputbox" type="text" name="startdate" id="startdate" size="10" maxlength="10" value="<?php echo $lists['startdate'];?>" /> </td>
                <td><input type="reset" class="button" value="..." name="startdate_but" id="startdate_but" onclick="return showCalendar('startdate','%Y-%m-%d');"/> </td>
              </tr>
              <tr>
                <td><?php echo __('To', 'form_maker'); ?>:</td>
                <td><input class="inputbox" type="text" name="enddate" id="enddate" size="10" maxlength="10" value="<?php echo $lists['enddate'];?>" /> </td>
                <td><input type="reset" class="button" value="..." name="enddate_but" id="enddate_but" onclick="return showCalendar('enddate','%Y-%m-%d');"/></td>
              </tr>
            </table>
            
            </th>
            <?php endif; 

            if($submitter_ip): ?>
            <th class="submitterip_fc"  <?php if(!(strpos($lists['hide_label_list'],'@submitterip@')===false)) echo 'style="display:none;"';?>>
             <input class="inputbox" type="text" name="ip_search" id="ip_search" value="<?php echo $lists['ip_search'] ?>" onChange="this.form.submit();" style="width:96%"/>
            </th>
            <?php endif; 
            if($username): ?>		
            <th width="100"class="submitterusername_fc"  <?php if(!(strpos($lists['hide_label_list'],'@submitterusername@')===false)) echo 'style="display:none;"';?>>
               <input class="inputbox" type="text" name="username_search" id="username_search" value="<?php echo $lists['username_search'] ?>" onChange="this.form.submit();" style="width:150px"/>
              </th>
            <?php endif; 	
            if($useremail): ?>		
              <th width="100"class="submitteremail_fc"  <?php if(!(strpos($lists['hide_label_list'],'@submitteremail@')===false)) echo 'style="display:none;"';?>>
               <input class="inputbox" type="text" name="useremail_search" id="useremail_search" value="<?php echo $lists['useremail_search'] ?>" onChange="this.form.submit();" style="width:150px"/>
              </th>
            <?php endif; 	
              $ka_fielderov_search=false;
              
              if($lists['ip_search'] || $lists['startdate'] || $lists['enddate'] || $lists['username_search'] || $lists['useremail_search']){
                $ka_fielderov_search=true;
              }
              
              for($i=0; $i < count($labels) ; $i++)
              {
                if(strpos($lists['hide_label_list'],'@'.$labels_id[$i].'@')===false)  
                  $styleStr='';
                else 
                  $styleStr='style="display:none;"';
                
                if(!$ka_fielderov_search)
                  if($lists[$form_id.'_'.$labels_id[$i].'_search'])
                  {
                    $ka_fielderov_search=true;
                  } 
          
                if($form_fields && !in_array($labels_id[$i], $checked_ids))		
                switch($sorted_labels_type[$i])
                {
                  case 'type_mark_map': echo '<th class="'.$labels_id[$i].'_fc" '.$styleStr.'>'.'</th>'; break;
                  case 'type_paypal_payment_status':
                  echo '<th class="'.$labels_id[$i].'_fc" '.$styleStr.'>';
                  ?>
                  <select name="<?php echo $form_id.'_'.$labels_id[$i]; ?>_search" id="<?php echo $form_id.'_'.$labels_id[$i]; ?>_search" onChange="this.form.submit();" value="<?php echo $lists[$form_id.'_'.$labels_id[$i].'_search']; ?>" >
                    <option value="" ></option>
                    <option value="canceled" ><?php echo __('Canceled', 'form_maker'); ?></option>
                    <option value="cleared" ><?php echo __('Cleared', 'form_maker'); ?></option>
                    <option value="cleared by payment review" ><?php echo __('Cleared by payment review', 'form_maker'); ?></option>
                    <option value="completed" ><?php echo __('Completed', 'form_maker'); ?></option>
                    <option value="denied" ><?php echo __('Denied', 'form_maker'); ?></option>
                    <option value="failed" ><?php echo __('Failed', 'form_maker'); ?></option>
                    <option value="held" ><?php echo __('Held', 'form_maker'); ?></option>
                    <option value="in progress" ><?php echo __('In progress', 'form_maker'); ?></option>
                    <option value="on hold" ><?php echo __('On hold', 'form_maker'); ?></option>
                    <option value="paid" ><?php echo __('Paid', 'form_maker'); ?></option>
                    <option value="partially refunded" ><?php echo __('Partially refunded', 'form_maker'); ?></option>
                    <option value="pending verification" ><?php echo __('Pending verification', 'form_maker'); ?></option>
                    <option value="placed" ><?php echo __('Placed', 'form_maker'); ?></option>
                    <option value="processing" ><?php echo __('Processing', 'form_maker'); ?></option>
                    <option value="refunded" ><?php echo __('Refunded', 'form_maker'); ?></option>
                    <option value="refused" ><?php echo __('Refused', 'form_maker'); ?></option>
                    <option value="removed" ><?php echo __('Removed', 'form_maker'); ?></option>
                    <option value="returned" ><?php echo __('Returned', 'form_maker'); ?></option>
                    <option value="reversed" ><?php echo __('Reversed', 'form_maker'); ?></option>
                    <option value="temporary hold" ><?php echo __('Temporary hold', 'form_maker'); ?></option>
                    <option value="unclaimed" ><?php echo __('Unclaimed', 'form_maker'); ?></option>
                  </select>	
                  <script> 
                    var element = document.getElementById('<?php echo $form_id.'_'.$labels_id[$i]; ?>_search');
                    element.value = '<?php echo $lists[$form_id.'_'.$labels_id[$i].'_search']; ?>';
                  </script>
                  <?php				
                  echo '</th>';

                  break;
                    
                  default : 		
                  echo '<th class="'.$labels_id[$i].'_fc" '.$styleStr.'>'.'<input name="'.$form_id.'_'.$labels_id[$i].'_search" id="'.$form_id.'_'.$labels_id[$i].'_search" class="inputbox" type="text" value="'.$lists[$form_id.'_'.$labels_id[$i].'_search'].'"  onChange="this.form.submit();" style="width:96%">'.'</th>';
                  break;			
                
                }	
              }
              if($form_fields && $payment_info)
              {
                if(strpos($lists['hide_label_list'],'@payment_info@')===false)  
                  $styleStr2='';
                else 
                  $styleStr2='style="display:none;"';
                echo '<th class="payment_info_fc" '.$styleStr2.'></th>';
              }	
            ?>
          </tr>
        </thead>
        <?php 
        $k = 0;
        $m=count($labels);
        for($www=0, $qqq=count($group_id_s); $www < $qqq ; $www++) {
          $i=$group_id_s[$www];
          $temp= array();
          for($j=0; $j < $n ; $j++) {
            $row = &$rows[$j];
            if($row->group_id==$i) {
              array_push($temp, $row);
            }
          }
          $f=$temp[0];
          $date=$f->date;
          $ip = $f->ip;
          $user_id = get_userdata($f->user_id_wd);
          $user_name = $user_id ? $user_id->display_name : "";
          $user_email= $user_id ? $user_id->user_email : "";
          ?>        
          <tr class="<?php echo "row$k"; ?>">
            <td align="center"><?php echo $www + 1 + ($lists['limit'] - 1) * 20;?></td>		 
            <?php	
            if($form_fields && $submit_id) {
              if(strpos($lists['hide_label_list'],'@submitid@')===false)
              echo '<td align="center" class="submitid_fc">'.$f->group_id.'</td>';
              else 
              echo '<td align="center" class="submitid_fc" style="display:none;">'.$f->group_id.'</td>';
            }            
            if($submit_date) {
              if(strpos($lists['hide_label_list'],'@submitdate@')===false)
                echo '<td align="center" class="submitdate_fc">'.$date.'</td>';
              else 
                echo '<td align="center" class="submitdate_fc" style="display:none;">'.$date.'</td>'; 
            }
            if($submitter_ip) {
              if(strpos($lists['hide_label_list'],'@submitterip@')===false)
                echo '<td align="center" class="submitterip_fc">'.$ip.'</td>';
              else 
                echo '<td align="center" class="submitterip_fc" style="display:none;">'.$ip.'</td>';
            }
            if($username) {
              if(strpos($lists['hide_label_list'],'@submitterusername@')===false)
              echo '<td align="center" class="submitterusername_fc" style="display:table-cell;">'.$user_name.'</td>';
              else 
              echo '<td align="center" class="submitterusername_fc" style="display:none;">'.$user_name.'</td>';
            }
            if($useremail) {
              if(strpos($lists['hide_label_list'],'@submitteremail@')===false)
              echo '<td align="center" class="submitteremail_fc" style="display:table-cell;">'.$user_email.'</td>';
              else 
              echo '<td align="center" class="submitteremail_fc" style="display:none;">'.$user_email.'</td>';
            }
            $ttt=count($temp);
            for($h=0; $h < $m ; $h++) {
              if($form_fields && !in_array($labels_id[$h], $checked_ids)) {
                $not_label=true;
                for($g=0; $g < $ttt ; $g++) {
                  $t = $temp[$g];
                  if(strpos($lists['hide_label_list'],'@'.$labels_id[$h].'@')===false)
                    $styleStr='';
                  else 
                    $styleStr='style="display:none;"';
                  if($t->element_label==$labels_id[$h]) {
                    if(strpos($t->element_value,"***map***")) {
                      $map_params=explode('***map***',$t->element_value);                      
                      $longit	= $map_params[0];
                      $latit = $map_params[1];                      
                      echo  '<td align="center" class="'.$labels_id[$h].'_fc" '.$styleStr.'><a class="thickbox-preview" href="' . add_query_arg(array('action' => 'frontend_show_map', 'page' => 'form_submissions', 'long' => $longit, 'lat' => $latit, 'width' => '620', 'height' => '550', 'TB_iframe' => '1'), admin_url('admin-ajax.php')) . '" title="' . __('Show on Map', 'form_maker') . '">' . __('Show on Map', 'form_maker') . '</a></td>';
                    }
                    else {
                      if(strpos($t->element_value,"*@@url@@*")) {
                        echo  '<td align="center" class="'.$labels_id[$h].'_fc" '.$styleStr.'>'; 
                        $new_files=explode("*@@url@@*", $t->element_value);
                        foreach($new_files as $new_file) {
                          if($new_file) {
                            $new_filename=explode('/', $new_file);
                            $new_filename=$new_filename[count($new_filename)-1];
                            if(strpos(strtolower($new_filename), 'jpg')!== false or strpos(strtolower($new_filename), 'png')!== false or strpos(strtolower($new_filename), 'gif')!== false or strpos(strtolower($new_filename), 'jpeg')!== false)
                              echo  '<a href="'.$new_file.'" class="modal">'.$new_filename."</a></br>";
                            else
                              echo  '<a target="_blank" href="'.$new_file.'">'.$new_filename."</a></br>";
                          }
                        }
                        echo "</td>";                        
                      }
                      else {
                        if(strpos($t->element_value,"@@@")>-1 || $t->element_value=="@@@" || $t->element_value=="@@@@@@@@@" || $t->element_value=="::" || $t->element_value==":" || $t->element_value=="--") {
                          echo  '<td align="center" class="'.$labels_id[$h].'_fc" '.$styleStr.'><pre style="font-family:inherit">'.str_replace(array("@@@",":","-")," ",$t->element_value).'</pre></td>';
                        }	
                        else {
                          if(strpos($t->element_value,"***grading***")) {
                            $new_filename= str_replace("***grading***",'', $t->element_value);	
                            $grading = explode(":",$new_filename);                        								
                            $items_count = sizeof($grading)-1;
                            $items = "";
                            $total = "";	
                            for($k=0;$k<$items_count/2;$k++) {
                              $items .= $grading[$items_count/2+$k].": ".$grading[$k]."</br>";
                              $total += $grading[$k];
                            }
                            $items .=  __('Total', 'form_maker') . ": ".$total;                          
                            echo  '<td align="center" class="'.$labels_id[$h].'_fc" '.$styleStr.'><pre style="font-family:inherit">'.$items.'</pre></td>';
                          }
                          else {
                            if(strpos($t->element_value,"***matrix***")) {
                              echo  '<td align="center" class="'.$labels_id[$h].'_fc" '.$styleStr.'><a class="thickbox-preview" href="' . add_query_arg(array('action' => 'frontend_show_matrix', 'page' => 'form_submissions', 'matrix_params' => $t->element_value, 'width' => '620', 'height' => '550', 'TB_iframe' => '1'), admin_url('admin-ajax.php')) . '" title="' . __('Show Matrix', 'form_maker') . '">' . __('Show Matrix', 'form_maker') . '</a></td>';
                            }
                            else {
                              if(strpos($t->element_value, "***quantity***"))
                                $t->element_value = str_replace("***quantity***"," ",$t->element_value);
                              if(strpos($t->element_value,"***property***"))
                                $t->element_value = str_replace("***property***"," ",$t->element_value);
                              echo  '<td align="center" class="'.$labels_id[$h].'_fc" '.$styleStr.'><pre style="font-family:inherit; white-space: pre;">'.str_replace("***br***",'<br>', $t->element_value).'</pre></td>';
                            }
                          }
                        }
                      }
                    }
                      $not_label=false;
                  }
                }
                if($not_label)
                  echo  '<td align="center" class="'.$labels_id[$h].'_fc" '.$styleStr.'></td>';
              }
            }
            if($form_fields && $payment_info) {
              if(strpos($lists['hide_label_list'],'@payment_info@')===false) 
                $styleStr='';
              else 
                $styleStr='style="display:none;"';
              echo  '<td align="center" class="payment_info_fc" '.$styleStr.'>		
              <a class="thickbox-preview" href="' . add_query_arg(array('action' => 'frontend_paypal_info', 'page' => 'form_submissions', 'id' => $i, 'width' => '600', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')) . '">
                  <img src="' . WD_FM_URL . '/images/info.png" />
                </a></td>';
            }
            ?>
          </tr>
          <?php
          $k = 1 - $k;
        }
        ?>
        </table>
      </div>
      <?php
      $is_stats=false;
      if($stats) {
        foreach($sorted_labels_type as $key => $label_type) {
          if($label_type=="type_checkbox" || $label_type=="type_radio" || $label_type=="type_own_select" || $label_type=="type_country" || $label_type=="type_paypal_select" || $label_type=="type_paypal_radio" || $label_type=="type_paypal_checkbox" || $label_type=="type_paypal_shipping") {
            $is_stats=true;
            break;
          }
        }      
        if($is_stats) {
          ?>
          <br><br>
          <h1 style="border-bottom: 1px solid; color:#000 !important;"><?php echo __('Statistics', 'form_maker'); ?></h1>
          <table class="stats">
            <tr valign="top">
              <td class="key" style="vertical-align: middle;">
                <label> <?php echo __('Select a Field', 'form_maker'); ?>: </label>
              </td>
              <td>
                <select id="stat_id">
                <option value=""><?php echo __('Select a Field', 'form_maker'); ?></option>;
                <?php 
                  $stats_fields = explode(',',$stats_fields);	
                  $stats_fields 	= array_slice($stats_fields,0, count($stats_fields)-1);                  
                  foreach($sorted_labels_type as $key => $label_type) {
                    if(($label_type=="type_checkbox" || $label_type=="type_radio" || $label_type=="type_own_select" || $label_type=="type_country" || $label_type=="type_paypal_select" || $label_type=="type_paypal_radio" || $label_type=="type_paypal_checkbox" || $label_type=="type_paypal_shipping") && !in_array($labels_id[$key], $stats_fields)) {
                      echo '<option value="'.$labels_id[$key].'">'.$label_titles_copy[$key].'</option>';
                    }
                  }
                ?>
                </select>
              </td>
            </tr>
            <tr valign="middle">
              <td class="key" style="vertical-align: middle;">
                <label> <?php echo __('Select a Date', 'form_maker'); ?>: </label>
              </td>
              <td>
                <?php echo __('From', 'form_maker'); ?>:<input class="inputbox" type="text" name="startstats" id="startstats" size="10" maxlength="10" />
                  <input type="reset" class="button" value="..." name="startstats_but" id="startstats_but" onclick="return showCalendar('startstats','%Y-%m-%d');"/> 
                <?php echo __('To', 'form_maker'); ?>: <input class="inputbox" type="text" name="endstats" id="endstats" size="10" maxlength="10" />
                  <input type="reset" class="button" value="..." name="endstats_but" id="endstats_but" onclick="return showCalendar('endstats','%Y-%m-%d');"/>
              </td>
            </tr>
            <tr valign="top">
              <td class="key" style="vertical-align: middle;" colspan="2">
              <input type="button" onclick="show_stats()" style="vertical-align:middle; cursor:pointer" value="<?php echo __('Show', 'form_maker'); ?>">
              </td>
            </tr>			
          </table>
          <div id="div_stats"></div>
          <?php
        }
      }
      ?>
    </form>    
    
    <script> 
      jQuery(window).load(function() {
        fm_popup();
        if (typeof jQuery().fancybox !== 'undefined' && jQuery.isFunction(jQuery().fancybox)) {
          jQuery(".fm_fancybox").fancybox({
            'maxWidth ' : 600,
            'maxHeight' : 500
          });
        }
      });
      function show_stats() {
        jQuery('#div_stats').html('<div id="saving"><div id="saving_text">Loading</div><div id="fadingBarsG"><div id="fadingBarsG_1" class="fadingBarsG"></div><div id="fadingBarsG_2" class="fadingBarsG"></div><div id="fadingBarsG_3" class="fadingBarsG"></div><div id="fadingBarsG_4" class="fadingBarsG"></div><div id="fadingBarsG_5" class="fadingBarsG"></div><div id="fadingBarsG_6" class="fadingBarsG"></div><div id="fadingBarsG_7" class="fadingBarsG"></div><div id="fadingBarsG_8" class="fadingBarsG"></div></div></div>');
        if(jQuery('#stat_id').val() != "") {
          jQuery('#div_stats').load('<?php echo add_query_arg(array('action' => 'get_frontend_stats', 'page' => 'form_submissions'), admin_url('admin-ajax.php')); ?>', {
            'form_id' : '<?php echo $form_id; ?>',
            'stat_id' : jQuery('#stat_id').val(),
            'startdate' : jQuery('#startstats').val(), 
            'enddate' : jQuery('#endstats').val()
          });
        }		
        else
          jQuery('#div_stats').html("<?php echo __('Please select the field!', 'form_maker'); ?>")
      }
      function fm_popup(id) {
        if (typeof id === 'undefined') {
          var id = '';
        }
        var thickDims, tbWidth, tbHeight;
            thickDims = function() {
              var tbWindow = jQuery('#TB_window'), H = jQuery(window).height(), W = jQuery(window).width(), w, h;
              w = (tbWidth && tbWidth < W - 90) ? tbWidth : W - 40;
              h = (tbHeight && tbHeight < H - 60) ? tbHeight : H - 40;
              if (tbWindow.size()) {
                tbWindow.width(w).height(h);
                jQuery('#TB_iframeContent').width(w).height(h - 27);
                tbWindow.css({'margin-left': '-' + parseInt((w / 2),10) + 'px'});
                if (typeof document.body.style.maxWidth != 'undefined') {
                  tbWindow.css({'top':(H-h)/2,'margin-top':'0'});
                }
              }
            };
        thickDims();
        jQuery(window).resize(function() { thickDims() });
        jQuery('a.thickbox-preview' + id).click( function() {
          tb_click.call(this);
          var alink = jQuery(this).parents('.available-theme').find('.activatelink'), link = '', href = jQuery(this).attr('href'), url, text;
          if (tbWidth = href.match(/&width=[0-9]+/)) {
            tbWidth = parseInt(tbWidth[0].replace(/[^0-9]+/g, ''), 10);
          }
          else {
            tbWidth = jQuery(window).width() - 120;
          }
          
          if (tbHeight = href.match(/&height=[0-9]+/)) {
            tbHeight = parseInt(tbHeight[0].replace(/[^0-9]+/g, ''), 10);
          }
          else {
            tbHeight = jQuery(window).height() - 120;
          }
          if (alink.length) {
            url = alink.attr('href') || '';
            text = alink.attr('title') || '';
            link = '&nbsp; <a href="' + url + '" target="_top" class="tb-theme-preview-link">' + text + '</a>';
          }
          else {
            text = jQuery(this).attr('title') || '';
            link = '&nbsp; <span class="tb-theme-preview-link">' + text + '</span>';
          }
          jQuery('#TB_title').css({'background-color':'#222','color':'#dfdfdf'});
          jQuery('#TB_closeAjaxWindow').css({'float':'right'});
          jQuery('#TB_ajaxWindowTitle').css({'float':'left'}).html(link);
          jQuery('#TB_iframeContent').width('100%');
          thickDims();
          return false;
        });

        jQuery('.theme-detail').click(function () {
          jQuery(this).siblings('.themedetaildiv').toggle();
          return false;
        });
      }
      <?php
      if ($ka_fielderov_search) {
        ?> 
        document.getElementById('fields_filter').style.display = '';
        document.getElementById('filter_img').src = '<?php echo WD_FM_URL . '/images/filter_hide.png'; ?>';
        <?php
      }
      ?>
    </script>
    <?php
  }

  public function show_stats() {
    $choices = $this->model->show_stats();
    $colors = array('#D6DEE9', '#F5DFCA');
    $choices_colors = array('#6095CB', '#FF9630');
    $choices_labels = array();
    $choices_count = array();
    $all = count($choices);
    $unanswered=0;
    foreach($choices as $key => $choice) {
      if($choice == '') {
        $unanswered++;
      }
      else {
        if(!in_array( $choice, $choices_labels)) {
          array_push($choices_labels, $choice);
          array_push($choices_count, 0);
        }
        $choices_count[array_search($choice, $choices_labels)]++;
      }
    }
    array_multisort($choices_count, SORT_DESC, $choices_labels);
    ?>
    <table  class="adminlist">
      <thead>
        <tr>
          <th width="20%"><?php echo __('Choices', 'form_maker'); ?></th>
          <th><?php echo __('Percentage', 'form_maker'); ?></th>
          <th width="10%"><?php echo __('Count', 'form_maker'); ?></th>
        </tr>
      </thead>
    <?php
    $k=0;
    foreach($choices_labels as $key => $choices_label) {
      ?>
      <tr>
        <td class="label<?php echo $k; ?>"><?php echo str_replace("***br***",'<br>', $choices_label)?></td>
        <td><div class="bordered" style="width:<?php echo ($choices_count[$key]/($all-$unanswered))*100; ?>%; height:28px; background-color:<?php echo $colors[$key % 2]; ?>; display:inline-block;"></div><div <?php echo ($choices_count[$key]/($all-$unanswered)!=1 ? 'class="bordered'.$k.'"' : "") ?> style="width:<?php echo 100-($choices_count[$key]/($all-$unanswered))*100; ?>%; height:28px; background-color:#F2F0F1; display:inline-block;"></div></td>
        <td><div><div style="width: 0; height: 0; border-top: 14px solid transparent;border-bottom: 14px solid transparent; border-right:14px solid <?php echo $choices_colors[$key % 2]; ?>; float:left;"></div><div style="background-color:<?php echo $choices_colors[$key % 2]; ?>; height:28px; width:28px; text-align: center; margin-left:14px; color: #fff;"><?php echo $choices_count[$key]?></div></div></td>
      </tr>
      <tr>
        <td colspan="3">
        </td>
      </tr>
      <?php 
      $k = 1 - $k;
    }    
    if($unanswered) {
      ?>
      <tr>
      <td colspan="2" style="text-align:right; color: #000;"><?php echo __('Unanswered', 'form_maker'); ?></th>
      <td><strong style="margin-left:10px;"><?php echo $unanswered;?></strong></th>
      </tr>
      <?php	
    }
    ?>
      <tr>
      <td colspan="2" style="text-align:right; color: #000;"><strong><?php echo __('Total', 'form_maker'); ?></strong></th>
      <td><strong style="margin-left:10px;"><?php echo $all;?></strong></th>
      </tr>
    </table>
    <?php
    die();
  }

  public function show_map() {
    $long = (isset($_GET['long']) ? esc_html(stripslashes($_GET['long'])) : 0);
		$lat = (isset($_GET['lat']) ? esc_html(stripslashes($_GET['lat'])) : 0);
		?>
    <script src="<?php echo WD_FM_URL . '/js/if_gmap_back_end.js'; ?>"></script>
    <script src="http://maps.google.com/maps/api/js?v=3"></script>
		<table style="margin:0px; padding:0px">
			<tr>
				<td>
					<b><?php echo __('Address', 'form_maker'); ?>:</b>
        </td>
        <td>
          <input type="text" id="addrval0" style="border:0px; background:none" size="100" readonly />
        </td>
			</tr>
			<tr>
				<td>
					<b><?php echo __('Longitude', 'form_maker'); ?>:</b>
        </td>
        <td>
          <input type="text" id="longval0" style="border:0px; background:none" size="100" readonly /> 
				</td>
			</tr>
			<tr>
				<td>
					<b><?php echo __('Latitude', 'form_maker'); ?>:</b>
        </td>
        <td>
          <input type="text" id="latval0" style="border:0px; background:none" size="100" readonly /> 
				</td>
			</tr>
		</table>			
		<div id="0_elementform_id_temp" long="<?php echo $long ?>" center_x="<?php echo $long ?>" center_y="<?php echo $lat ?>" lat="<?php echo $lat ?>" zoom="8" info="" style="width:600px; height:500px; ">
		</div>		
		<script>
			if_gmap_init("0");
			add_marker_on_map(0, 0, "<?php echo $long ?>", "<?php echo $lat ?>", '');
		</script>
		<?php
    die();
  }

  public function show_matrix() {
    $matrix_params = (isset($_GET['matrix_params']) ? esc_html(stripslashes($_GET['matrix_params'])) : '');
		$new_filename= str_replace("***matrix***", '', $matrix_params);	

		$new_filename=explode('***', $matrix_params);
		$mat_params=array_slice($new_filename,0, count($new_filename)-1);
		$mat_rows=$mat_params[0];
		$mat_columns=$mat_params[$mat_rows+1];
		$matrix="<table >";					
		$matrix .='<tr><td></td>';
			
		for( $k=1;$k<=$mat_columns;$k++)
			$matrix .='<td style="background-color:#BBBBBB; padding:5px;">'.$mat_params[$mat_rows+1+$k].'</td>';
	
		$matrix .='</tr>';
				
		$aaa=Array();
		$var_checkbox=1;
		for( $k=1;$k<=$mat_rows;$k++) {
			$matrix .='<tr><td style="background-color:#BBBBBB; padding:5px; ">'.$mat_params[$k].'</td>';
			if($mat_params[$mat_rows+$mat_columns+2]=="radio") {
				if($mat_params[$mat_rows+$mat_columns+2+$k]==0) {
					$checked=0;
					$aaa[1]="";	
				}
				else
					$aaa=explode("_",$mat_params[$mat_rows+$mat_columns+2+$k]);				
				for( $l=1;$l<=$mat_columns;$l++) {
					if($aaa[1]==$l)
						$checked="checked";
					else
						$checked="";
					$matrix .='<td style="text-align:center"><input  type="radio" '.$checked.' disabled /></td>';	
				}
			}
			else {
				if($mat_params[$mat_rows+$mat_columns+2]=="checkbox") {
					for( $l=1;$l<=$mat_columns;$l++) {
						if($mat_params[$mat_rows+$mat_columns+2+$var_checkbox]=="1")
							$checked="checked";
						else
							$checked="";	
						$matrix .='<td style="text-align:center"><input  type="checkbox" '.$checked.' disabled /></td>';
						$var_checkbox++;
					}
				}
				else {
					if($mat_params[$mat_rows+$mat_columns+2]=="text") {
						for( $l=1;$l<=$mat_columns;$l++) {
							$checked = $mat_params[$mat_rows+$mat_columns+2+$var_checkbox];
							$matrix .='<td style="text-align:center"><input  type="text" value="'.$checked.'" disabled /></td>';
							$var_checkbox++;
						}
					}
					else
					{
						for( $l=1;$l<=$mat_columns;$l++) {
							$checked = $mat_params[$mat_rows+$mat_columns+2+$var_checkbox];		
							$matrix .='<td style="text-align:center">'.$checked.'</td>';
							$var_checkbox++;
						}
					}			
				}					
			}		
			$matrix .='</tr>';
		}		
		$matrix .='</table>';
		echo $matrix;
    die();
  }

  public function paypal_info() {
    $id = (isset($_GET['id']) ? esc_html(stripslashes($_GET['id'])) : '');
		$row = $this->model->paypal_info($id);
		
		if(!isset($row->ipn)) {
      echo "<div style='width:100%; text-align:center; height: 100%; vertical-align:middle'><h1 style='top: 44%;position: absolute;left:38%; color:#000'>No information yet<p></h1>";
      return;
		}
	
		$paypal_info ='
			<h2>Payment Info</h2>
			<table class="admintable">
				<tr>
					<td class="key">Currency</td>
					<td>'.$row->currency.'</td>
				</tr>
				<tr>
					<td class="key">Last modified</td>
					<td>'.$row->ord_last_modified.'</td>
				</tr>
				<tr>
					<td class="key">Status</td>
					<td>'.$row->status.'</td>
				</tr>
				<tr>
					<td class="key">Full name</td>
					<td>'.$row->full_name.'</td>
				</tr>
				<tr>
					<td class="key">Email</td>
					<td>'.$row->email.'</td>
				</tr>
				<tr>
					<td class="key">Phone</td>
					<td>'.$row->phone.'</td>
				</tr>
				<tr>
					<td class="key">Mobile phone</td>
					<td>'.$row->mobile_phone.'</td>
				</tr>
				<tr>
					<td class="key">Fax</td>
					<td>'.$row->fax.'</td>
				</tr>
				<tr>
					<td class="key">Address</td>
					<td>'.$row->address.'</td>
				</tr>
				<tr>
					<td class="key">PayPal info</td>
					<td>'.$row->paypal_info.'</td>
				</tr>	
				<tr>
					<td class="key">IPN</td>
					<td>'.$row->ipn.'</td>
				</tr>
				<tr>
					<td class="key">Tax</td>
					<td>'.$row->tax.'%</td>
				</tr>
				<tr>
					<td class="key">Shipping</td>
					<td>'.$row->shipping.'</td>
				</tr>
				<tr>
					<td class="key">Total</td>
					<td><b>'.$row->total.'</b></td>
				</tr>
			</table>';
		echo $paypal_info;
    die();
  }
  
  public function generate_csv() {
		$user = wp_get_current_user();
		global $wpdb;
		$id = (isset($_GET['id']) ? esc_html(stripslashes($_GET['id'])) : '');
		
		$userGroups = $wpdb->get_var($wpdb->prepare("SELECT `user_id_wd` FROM " . $wpdb->prefix . "formmaker WHERE id='%d'", $id));    
		$users = explode(',', $userGroups);
		$users = array_slice($users, 0, count($users) - 1);

		$allow_export = false;
		$msg = 'You have no permissions to download csv';
		if(!is_user_logged_in()) {
			if(!in_array('guest',$users)) {
				wp_redirect($_SERVER["HTTP_REFERER"], $msg, 'error');
        die($msg);
			}
		}
		else {
			foreach($user->roles as $user_role) {
				if(in_array($user_role, $users))
					$allow_export = true;
			}
			if(!$allow_export) {
				wp_redirect($_SERVER["HTTP_REFERER"], $msg, 'error');
        die($msg);
			}
		}
	
		$checked_ids = (isset($_GET['checked_ids']) ? esc_html(stripslashes($_GET['checked_ids'])) : '');
    $from = (isset($_GET['from']) ? esc_html(stripslashes($_GET['from'])) : '');
    $to = (isset($_GET['to']) ? esc_html(stripslashes($_GET['to'])) : '');
		
		$form_id = $id;
		$paypal_info_fields = array('currency', 'ord_last_modified', 'status', 'full_name', 'fax', 'mobile_phone', 'email', 'phone', 'address', 'paypal_info',  'ipn', 'tax', 'shipping');
		$paypal_info_labels = array( 'Currency', 'Last modified', 'Status', 'Full Name', 'Fax', 'Mobile phone', 'Email', 'Phone', 'Address', 'Paypal info', 'IPN', 'Tax', 'Shipping');

		$where_range = ' ';
		$labels= array();
		
		if($from)
			$where_range .= "AND DATE_FORMAT(date,'%Y-%m-%d') >= '".$from."'";
		if($to)
			$where_range .= "AND DATE_FORMAT(date,'%Y-%m-%d') <= '".$to."'";
		if($checked_ids) {
			$labels = explode(',',$checked_ids);
			$labels = array_slice($labels,0, count($labels)-1);   

			$query = "SELECT id FROM " . $wpdb->prefix . "formmaker_submits where form_id=" . $form_id . $where_range;
			$rows = $wpdb->get_col($query);
		}
		else
      $rows = '';

		$query_lable = $wpdb->prepare("SELECT label_order,title FROM " . $wpdb->prefix . "formmaker where id='%d'", $form_id);    
		$rows_lable = $wpdb->get_row($query_lable);
		
		$ptn = "/[^a-zA-Z0-9_]/";
    $rpltxt = "";
		$title = preg_replace($ptn, $rpltxt, $rows_lable->title);

		$sorted_labels_id= array();
		$sorted_labels= array();
		$label_titles=array();
		if($labels) {
			$label_id= array();
			$label_order= array();
			$label_order_original= array();
			$label_type= array();
		
			$label_all	= explode('#****#',$rows_lable->label_order);
			$label_all 	= array_slice($label_all,0, count($label_all)-1);   
		
			foreach($label_all as $key => $label_each) {
				$label_id_each=explode('#**id**#',$label_each);
				array_push($label_id, $label_id_each[0]);
				$label_oder_each=explode('#**label**#', $label_id_each[1]);
				array_push($label_order_original, $label_oder_each[0]);
				$ptn = "/[^a-zA-Z0-9_]/";
				$rpltxt = "";
				$label_temp=preg_replace($ptn, $rpltxt, $label_oder_each[0]);
				array_push($label_order, $label_temp);
				array_push($label_type, $label_oder_each[1]);
			}

			foreach($label_id as $key => $label) {
				if(in_array($label, $labels)) {
					array_push($sorted_labels, $label_order[$key]);
					array_push($sorted_labels_id, $label);
					array_push($label_titles, $label_order_original[$key]);
				}
      }
		}

		$m=count($sorted_labels);
		$group_id_s= array();
		
		if(count($rows)>0 and $checked_ids) {
			$query = $wpdb->prepare("SELECT distinct group_id FROM " . $wpdb->prefix . "formmaker_submits where form_id='%d'", $form_id);      
			$group_id_s = $wpdb->get_col($query);
		}

		$data=array();
		for($www=0; $www < count($group_id_s); $www++) {
			$data_temp=array();
			$i=$group_id_s[$www];
			
			$query = "SELECT `date`, `ip`, `user_id_wd` FROM " . $wpdb->prefix . "formmaker_submits where group_id=$i".$where_range;
			$f = $wpdb->get_row($query);
			
			$date = $f->date;
			$ip = $f->ip;
			$user_id = get_userdata($f->user_id_wd);
			$user_name = $user_id ? $user_id->display_name : "";
      $user_email= $user_id ? $user_id->user_email : "";
			if(in_array('submit_id', $labels))
				$data_temp['ID'] = $i;
			if(in_array('submit_date', $labels))
				$data_temp['Submit date'] = $date;
			if(in_array('submitter_ip', $labels))
				$data_temp['Ip'] = $ip;
			if(in_array('username', $labels))
				$data_temp['Submitter\'s Username'] = $user_name;
			if(in_array('useremail', $labels))
				$data_temp['Submitter\'s Email Address'] = $user_email;
				
			for($h=0; $h < $m ; $h++) {
				if(isset($data_temp[$label_titles[$h]])) {
					$label_titles[$h] .= '(1)';
        }
				$query = "SELECT * FROM " . $wpdb->prefix . "formmaker_submits where group_id=$i AND element_label=".$sorted_labels_id[$h].$where_range;
				$t = $wpdb->get_row($query);				
				if($t) {
					if(strpos($t->element_value,"*@@url@@*")) {
						$new_file=str_replace("*@@url@@*",'', $t->element_value);
						$new_filename=explode('/', $new_file);
						$data_temp[$label_titles[$h]]=$new_file;
					}
					else {
						if(strpos($t->element_value,"***br***")) {
							$element_value = str_replace("***br***",', ', $t->element_value);							
							if(strpos($element_value,"***quantity***"))
                $element_value = str_replace("***quantity***",'', $element_value);							
							if(strpos($element_value,"***property***"))
                $element_value = str_replace("***property***",'', $element_value);
							if(substr($element_value, -2) == ', ')
								$data_temp[$label_titles[$h]]= substr($element_value, 0, -2);
							else
								$data_temp[$label_titles[$h]]= $element_value;		
						}					
						else {
							if(strpos($t->element_value,"***map***")) {
								$data_temp[$label_titles[$h]]= 'Longitude:'.substr(str_replace("***map***",', Latitude:', $t->element_value), 0, -2);
							}
							else {
								if(strpos($t->element_value,"@@@")>-1 || $t->element_value=="@@@" || $t->element_value=="@@@@@@@@@") {
									$data_temp[$label_titles[$h]]= str_replace("@@@",' ', $t->element_value);
								}
								else {
									if($t->element_value=="::" || $t->element_value==":" || $t->element_value=="--") {
										$data_temp[$label_titles[$h]]= str_replace(array(":","-"),"",$t->element_value);
									}
									else {
										if(strpos($t->element_value,"***grading***")) {
											$element = str_replace("***grading***",'', $t->element_value);											
											$grading = explode(":",$element);                        
											$items_count = sizeof($grading)-1;
											$items = "";
											$total = "";
											for($k=0;$k<$items_count/2;$k++) {
                        $items .= $grading[$items_count/2+$k].": ".$grading[$k].", ";
                        $total += $grading[$k];
											}
											$items .="Total: ".$total;
											$data_temp[$label_titles[$h]]= $items;
										}
										else {
											if(strpos($t->element_value,"***matrix***")) {
												$element = str_replace("***matrix***",'', $t->element_value);
												$matrix_value=explode('***', $element);
												$matrix_value = array_slice($matrix_value,0, count($matrix_value)-1);
												$mat_rows=$matrix_value[0];
												$mat_columns=$matrix_value[$mat_rows+1];
												$matrix="";
												$aaa=Array();
												$var_checkbox=1;
												$selected_value="";
												$selected_value_yes="";
												$selected_value_no="";
												for( $k=1;$k<=$mat_rows;$k++) {
													if($matrix_value[$mat_rows+$mat_columns+2]=="radio") {
														if($matrix_value[$mat_rows+$mat_columns+2+$k]==0) {
															$checked="0";
															$aaa[1]="";
														}
														else
															$aaa=explode("_",$matrix_value[$mat_rows+$mat_columns+2+$k]);
														for( $l=1;$l<=$mat_columns;$l++) {
															if($aaa[1]==$l)
																$checked='1';
															else
																$checked="0";											
															$matrix .= '['.$matrix_value[$k].','.$matrix_value[$mat_rows+1+$l].']='.$checked."; ";
														}
													}
													else {
														if($matrix_value[$mat_rows+$mat_columns+2]=="checkbox") {
															for( $l=1;$l<=$mat_columns;$l++) {
																if( $matrix_value[$mat_rows+$mat_columns+2+$var_checkbox]==1)
																	$checked ='1';
																else
																	$checked ='0';
																$matrix .= '['.$matrix_value[$k].','.$matrix_value[$mat_rows+1+$l].']='.$checked."; ";															
																$var_checkbox++;
															}
														}
														else {
															if($matrix_value[$mat_rows+$mat_columns+2]=="text") {
																for( $l=1;$l<=$mat_columns;$l++) {
																	$text_value = $matrix_value[$mat_rows+$mat_columns+2+$var_checkbox];								
																	$matrix .='['.$matrix_value[$k].','.$matrix_value[$mat_rows+1+$l].']='.$text_value."; ";
																	$var_checkbox++;
																}
															}
															else {
																for( $l=1;$l<=$mat_columns;$l++) {
																	$selected_text = $matrix_value[$mat_rows+$mat_columns+2+$var_checkbox];
																	$matrix .='['.$matrix_value[$k].','.$matrix_value[$mat_rows+1+$l].']='.$selected_text."; ";
																	$var_checkbox++;
																}
															}								
														}
													}
												}
												$data_temp[$label_titles[$h]]= $matrix;
											}
											else {
												$data_temp[$label_titles[$h]]= ' '.$t->element_value;
                      }
                    }
                  }
                }
              }
            }
          }
				}
				else {
					$data_temp[$label_titles[$h]]= '';
        }
			}
			
			if(in_array('item_total', $labels)) {
				$query = "SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits where group_id=$i AND element_label='item_total'".$where_range;
				$item_total = $wpdb->get_var($query);
				$data_temp['Item Total'] = $item_total;
			}
			if(in_array('total', $labels)) {
				$query = "SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits where group_id=$i AND element_label='total'".$where_range;
				$total = $wpdb->get_var($query);
				$data_temp['Total'] = $total;
			}
			if(in_array('0', $labels)) {
				$query = "SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits where group_id=$i AND element_label='0'".$where_range;
				$payment_status = $wpdb->get_var($query);
				$data_temp['Payment Status'] = $payment_status;
			}
			if(in_array('payment_info', $labels)) {
				$query = "SELECT * FROM " . $wpdb->prefix . "formmaker_sessions where group_id=".$i.$where_range;
				$paypal_info = $wpdb->get_row($query);
				foreach($paypal_info_fields as $key=>$paypal_info_field) {
					if($paypal_info)
						$data_temp['PAYPAL_'.$paypal_info_labels[$key]]=$paypal_info->$paypal_info_field;
					else
						$data_temp['PAYPAL_'.$paypal_info_labels[$key]]='';
				}
			}
			$data[]=$data_temp;
		}

		$filename = $title."_" . date('Ymd') . ".csv";
	 
		header('Content-Encoding: Windows-1252');
		header('Content-type: text/csv; charset=Windows-1252');
		header("Content-Disposition: attachment; filename=\"$filename\"");

    $flag = false;
    $text = '';
    foreach($data as $row) {
			if(!$flag) {
				$text .= '"'.implode('","', str_replace('PAYPAL_', '', array_keys($row)));
				$text .= "\"\r\n";
				$flag = true;
			}
			array_walk($row, array($this, 'cleanData'));
			$text .= '"'.implode('","',array_values($row))."\"\r\n";
		}
		echo $text;
		exit;
    die();
  }
  
  function cleanData(&$str) {
		$str = preg_replace("/\t/", "\\t", $str);
		$str = preg_replace("/\r?\n/", "\\n", $str);
		if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	}
  
  public function generate_xml() {
    $user = wp_get_current_user();
		global $wpdb;
		$id = (isset($_GET['id']) ? esc_html(stripslashes($_GET['id'])) : '');
		
		$userGroups = $wpdb->get_var($wpdb->prepare("SELECT `user_id_wd` FROM " . $wpdb->prefix . "formmaker WHERE id='%d'", $id));    
		$users = explode(',', $userGroups);
		$users 	= array_slice($users,0, count($users)-1); 

		$allow_export = false;
		$msg = 'You have no permissions to download xml';
		if(!is_user_logged_in()) {
			if(!in_array('guest',$users)) {
				wp_redirect($_SERVER["HTTP_REFERER"], $msg, 'error');
        die($msg);
			}
		}
		else {
			foreach($user->roles as $user_role) {
				if(in_array($user_role, $users))
					$allow_export = true;
			}
			if(!$allow_export) {
				wp_redirect($_SERVER["HTTP_REFERER"], $msg, 'error');
        die($msg);
			}
		}
	
		$checked_ids = (isset($_GET['checked_ids']) ? esc_html(stripslashes($_GET['checked_ids'])) : '');
    $from = (isset($_GET['from']) ? esc_html(stripslashes($_GET['from'])) : '');
    $to = (isset($_GET['to']) ? esc_html(stripslashes($_GET['to'])) : '');
		
		$form_id=$id;
		$paypal_info_fields = array('currency', 'ord_last_modified', 'status', 'full_name', 'fax', 'mobile_phone', 'email', 'phone', 'address', 'paypal_info',  'ipn', 'tax', 'shipping');
		$paypal_info_labels = array( 'Currency', 'Last modified', 'Status', 'Full Name', 'Fax', 'Mobile phone', 'Email', 'Phone', 'Address', 'Paypal info', 'IPN', 'Tax', 'Shipping');

		$where_range = ' ';
		$labels= array();
		
		if($from)
			$where_range .= "AND DATE_FORMAT(date,'%Y-%m-%d') >= '".$from."'";
		if($to)
			$where_range .= "AND DATE_FORMAT(date,'%Y-%m-%d') <= '".$to."'";
		if($checked_ids) {
			$labels = explode(',',$checked_ids);
			$labels = array_slice($labels,0, count($labels)-1);   

			$query = "SELECT id FROM " . $wpdb->prefix . "formmaker_submits where form_id=" . $form_id . $where_range;
			$rows = $wpdb->get_col($query);
		}
		else
		$rows = '';

		$query_lable = $wpdb->prepare("SELECT label_order,title FROM " . $wpdb->prefix . "formmaker where id='%d'", $form_id);    
		$rows_lable = $wpdb->get_row($query_lable);
		
		$ptn = "/[^a-zA-Z0-9_]/";
				$rpltxt = "";
				
		$form_title	= $rows_lable->title;	
		$title = preg_replace($ptn, $rpltxt, $rows_lable->title);
		
		$sorted_labels_id= array();
		$sorted_labels= array();
		$label_titles=array();
		if($labels) {
			$label_id= array();
			$label_order= array();
			$label_order_original= array();
			$label_type= array();
		
			$label_all	= explode('#****#',$rows_lable->label_order);
			$label_all 	= array_slice($label_all,0, count($label_all)-1);   
		
			foreach($label_all as $key => $label_each) {
				$label_id_each=explode('#**id**#',$label_each);
				array_push($label_id, $label_id_each[0]);
				$label_oder_each=explode('#**label**#', $label_id_each[1]);
				array_push($label_order_original, $label_oder_each[0]);
				$ptn = "/[^a-zA-Z0-9_]/";
				$rpltxt = "";
				$label_temp=preg_replace($ptn, $rpltxt, $label_oder_each[0]);
				array_push($label_order, $label_temp);
				array_push($label_type, $label_oder_each[1]);
			}

			foreach($label_id as $key => $label) {
				if(in_array($label, $labels)) {
					array_push($sorted_labels, $label_order[$key]);
					array_push($sorted_labels_id, $label);
					array_push($label_titles, $label_order_original[$key]);
				}
      }
		}
		
		$m=count($sorted_labels);
		$group_id_s= array();
		
		if(count($rows)>0 and $checked_ids) {
			$query = $wpdb->prepare("SELECT distinct group_id FROM " . $wpdb->prefix . "formmaker_submits where form_id='%d'", $form_id);      
			$group_id_s = $wpdb->get_col($query);
		}

		$data=array();
		for($www=0; $www < count($group_id_s); $www++) {
			$data_temp=array();
			$i=$group_id_s[$www];
			
			$query = "SELECT `date`, `ip`, `user_id_wd` FROM " . $wpdb->prefix . "formmaker_submits where group_id=$i".$where_range;
			$f = $wpdb->get_row($query);
			
			$date=$f->date;
			$ip = $f->ip;
			$user_id = get_userdata($f->user_id_wd);
			$user_name = $user_id ? $user_id->display_name : "";
      $user_email= $user_id ? $user_id->user_email : "";
			if(in_array('submit_id', $labels))
				$data_temp['ID']=$i;
			if(in_array('submit_date', $labels))
				$data_temp['Submit date']=$date;
			if(in_array('submitter_ip', $labels))
				$data_temp['Ip']=$ip;
			if(in_array('username', $labels))
				$data_temp['Submitter\'s Username']=$user_name;
			if(in_array('useremail', $labels))
				$data_temp['Submitter\'s Email Address']=$user_email;
				
			for($h=0; $h < $m ; $h++) {
				if(isset($data_temp[$label_titles[$h]])) {
					$label_titles[$h] .= '(1)';
        }
				$query = "SELECT * FROM " . $wpdb->prefix . "formmaker_submits where group_id=$i AND element_label=".$sorted_labels_id[$h].$where_range;
				$t = $wpdb->get_row($query);				
				if($t) {
					if(strpos($t->element_value,"*@@url@@*")) {
						$new_file=str_replace("*@@url@@*",'', $t->element_value);
						$new_filename=explode('/', $new_file);
						$data_temp[$label_titles[$h]]=$new_file;
					}
					else {
						if(strpos($t->element_value,"***br***")) {
							$element_value = str_replace("***br***",', ', $t->element_value);							
							if(strpos($element_value,"***quantity***"))
							$element_value = str_replace("***quantity***",'', $element_value);
							
							if(strpos($element_value,"***property***"))
							$element_value = str_replace("***property***",'', $element_value);

							if(substr($element_value, -2) == ', ')
								$data_temp[$label_titles[$h]]= substr($element_value, 0, -2);
							else
								$data_temp[$label_titles[$h]]= $element_value;		
						}					
						else {
							if(strpos($t->element_value,"***map***")) {
								$data_temp[$label_titles[$h]]= 'Longitude:'.substr(str_replace("***map***",', Latitude:', $t->element_value), 0, -2);
							}
							else {
								if(strpos($t->element_value,"@@@")>-1 || $t->element_value=="@@@" || $t->element_value=="@@@@@@@@@") {
									$data_temp[$label_titles[$h]]= str_replace("@@@",' ', $t->element_value);
								}
								else {
									if($t->element_value=="::" || $t->element_value==":" || $t->element_value=="--") {
										$data_temp[$label_titles[$h]]= str_replace(array(":","-"),"",$t->element_value);
									}
									else {
										if(strpos($t->element_value,"***grading***")) {
											$element = str_replace("***grading***",'', $t->element_value);											
											$grading = explode(":",$element);                        
											$items_count = sizeof($grading)-1;
											$items = "";
											$total = "";
											for($k=0;$k<$items_count/2;$k++) {
                        $items .= $grading[$items_count/2+$k].": ".$grading[$k].", ";
                        $total += $grading[$k];
											}
											$items .="Total: ".$total;
											$data_temp[$label_titles[$h]]= $items;
										}
										else {
											if(strpos($t->element_value,"***matrix***")) {
												$element = str_replace("***matrix***",'', $t->element_value);
												$matrix_value=explode('***', $element);
												$matrix_value = array_slice($matrix_value,0, count($matrix_value)-1);
												$mat_rows=$matrix_value[0];
												$mat_columns=$matrix_value[$mat_rows+1];
												$matrix="";
												$aaa=Array();
												$var_checkbox=1;
												$selected_value="";
												$selected_value_yes="";
												$selected_value_no="";
												for( $k=1;$k<=$mat_rows;$k++) {
													if($matrix_value[$mat_rows+$mat_columns+2]=="radio") {
														if($matrix_value[$mat_rows+$mat_columns+2+$k]==0) {
															$checked="0";
															$aaa[1]="";
														}
														else
															$aaa=explode("_",$matrix_value[$mat_rows+$mat_columns+2+$k]);
														for( $l=1;$l<=$mat_columns;$l++) {
															if($aaa[1]==$l)
																$checked='1';
															else
																$checked="0";											
															$matrix .= '['.$matrix_value[$k].','.$matrix_value[$mat_rows+1+$l].']='.$checked."; ";
														}
													}
													else {
														if($matrix_value[$mat_rows+$mat_columns+2]=="checkbox") {
															for( $l=1;$l<=$mat_columns;$l++) {
																if( $matrix_value[$mat_rows+$mat_columns+2+$var_checkbox]==1)
																	$checked ='1';
																else
																	$checked ='0';
																$matrix .= '['.$matrix_value[$k].','.$matrix_value[$mat_rows+1+$l].']='.$checked."; ";															
																$var_checkbox++;
															}
														}
														else {
															if($matrix_value[$mat_rows+$mat_columns+2]=="text") {
																for( $l=1;$l<=$mat_columns;$l++) {
																	$text_value = $matrix_value[$mat_rows+$mat_columns+2+$var_checkbox];								
																	$matrix .='['.$matrix_value[$k].','.$matrix_value[$mat_rows+1+$l].']='.$text_value."; ";
																	$var_checkbox++;
																}
															}
															else {
																for( $l=1;$l<=$mat_columns;$l++) {
																	$selected_text = $matrix_value[$mat_rows+$mat_columns+2+$var_checkbox];
																	$matrix .='['.$matrix_value[$k].','.$matrix_value[$mat_rows+1+$l].']='.$selected_text."; ";
																	$var_checkbox++;
																}
															}								
														}
													}
												}
												$data_temp[$label_titles[$h]]= $matrix;
											}
											else {
												$data_temp[$label_titles[$h]]= ' '.$t->element_value;
                      }
                    }
                  }
                }
              }
            }
          }
				}
				else {
					$data_temp[$label_titles[$h]]= '';
        }
			}
			
			if(in_array('item_total', $labels)) {
				$query = "SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits where group_id=$i AND element_label='item_total'".$where_range;
				$item_total = $wpdb->get_var($query);
				$data_temp['Item Total'] = $item_total;
			}
			if(in_array('total', $labels)) {
				$query = "SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits where group_id=$i AND element_label='total'".$where_range;
				$total = $wpdb->get_var($query);
				$data_temp['Total'] = $total;
			}
			if(in_array('0', $labels)) {
				$query = "SELECT `element_value` FROM " . $wpdb->prefix . "formmaker_submits where group_id=$i AND element_label='0'".$where_range;
				$payment_status = $wpdb->get_var($query);
				$data_temp['Payment Status'] = $payment_status;
			}
			if(in_array('payment_info', $labels)) {
				$query = "SELECT * FROM " . $wpdb->prefix . "formmaker_sessions where group_id=".$i.$where_range;
				$paypal_info = $wpdb->get_row($query);
				foreach($paypal_info_fields as $key=>$paypal_info_field) {
					if($paypal_info)
						$data_temp['PAYPAL_'.$paypal_info_labels[$key]]=$paypal_info->$paypal_info_field;
				}  
			}
			$data[$i]=$data_temp;
		}
		define('PHP_TAB', "\t");
		$filename = $title."_" . date('Ymd') . ".xml";
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type:text/xml,  charset=utf-8");
		$text = '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL;
		$text .= '<form id="'.$form_id.'" title="'.$form_title.'">'.PHP_EOL;
		foreach ($data as $key1 => $value1) {
			$text .=PHP_TAB.'<submission id="'.$key1.'">'.PHP_EOL; 
			foreach ($value1 as $key => $value) {
				$text .=PHP_TAB.PHP_TAB.'<field title="'.str_replace('PAYPAL_', '', $key).'">'.PHP_EOL;
				$text .=PHP_TAB.PHP_TAB.PHP_TAB.'<![CDATA['.$value.']]>'.PHP_EOL;
				$text .=PHP_TAB.PHP_TAB.'</field>'.PHP_EOL;
			}  
			$text .=PHP_TAB.'</submission>'.PHP_EOL;
		}
		$text .='</form>';
		echo $text;
		exit;
    die();
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