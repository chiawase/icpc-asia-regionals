<?php

class FMViewUninstall_fm {
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
	public function display() {
		global $wpdb;
		$prefix = $wpdb->prefix;
		?>
		<form method="post" action="admin.php?page=uninstall_fm" style="width:95%;">
			<?php wp_nonce_field('nonce_fm', 'nonce_fm'); ?>
			<div class="wrap">
				<div class="fm-page-banner uninstall-banner">
					<div class="uninstall_icon">
					</div>
					<div class="fm-logo-title">Uninstall Form Maker</div>
				</div>	
				<br />
				<div class="goodbye-text">
				Before uninstalling the plugin, please Contact our <a href="https://web-dorado.com/support/contact-us.html?source=formmaker" target= '_blank'>support team</a>. We'll do our best to help you out with your issue. We value each and every user and value what’s right for our users in everything we do.<br>
				However, if anyway you have made a decision to uninstall the plugin, please take a minute to <a href="https://web-dorado.com/support/contact-us.html?source=formmaker" target= '_blank'>Contact us</a> and tell what you didn't like for our plugins further improvement and development. Thank you !!!
				</div>				
				<p>
					Deactivating Form Maker plugin does not remove any data that may have been created, such as the Forms and the Submissions. To completely remove this plugin, you can uninstall it here.
				</p>
				<p style="color: red;">
					<strong>WARNING:</strong>
					Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.
				</p>
				<p style="color: red">
					<strong>The following WordPress Options/Tables will be DELETED:</strong>
				</p>
				<table class="widefat">
					<thead>
						<tr>
							<th>Database Tables</th>
						</tr>
					</thead>
					<tr>
						<td valign="top">
							<ol>
								<li><?php echo $prefix; ?>formmaker</li>
								<li><?php echo $prefix; ?>formmaker_backup</li>
								<li><?php echo $prefix; ?>formmaker_blocked</li>
								<li><?php echo $prefix; ?>formmaker_submits</li>
								<li><?php echo $prefix; ?>formmaker_views</li>
								<li><?php echo $prefix; ?>formmaker_themes</li>
								<li><?php echo $prefix; ?>formmaker_sessions</li>
								<li><?php echo $prefix; ?>formmaker_query</li>

							</ol>
						</td>
					</tr>
				</table>
				<p style="text-align: center;">
					Do you really want to uninstall Form Maker?
				</p>
				<p style="text-align: center;">
					<input type="checkbox" name="Form Maker" id="check_yes" value="yes" />&nbsp;<label for="check_yes">Yes</label>
				</p>
				<p style="text-align: center;">
					<input type="submit" value="UNINSTALL" class="button-primary" onclick="if (check_yes.checked) {  if (confirm('You are About to Uninstall Form Maker from WordPress.\nThis Action Is Not Reversible.')) { fm_set_input_value('task', 'uninstall'); } else { return false; } } else { return false; }" />
				</p>
			</div>
			<input id="task" name="task" type="hidden" value="" />
		</form>
		<?php
	}

  public function uninstall() {
    $this->model->delete_db_tables();
    global $wpdb;
    $prefix = $wpdb->prefix;
    $deactivate_url = add_query_arg(array('action' => 'deactivate', 'plugin' => 'form-maker/form-maker.php'), admin_url('plugins.php'));
    $deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_form-maker/form-maker.php');
    ?>
    <div id="message" class="updated fade">
        <p>The following Database Tables succesfully deleted:</p>
        <p><?php echo $prefix; ?>formmaker,</p>
        <p><?php echo $prefix; ?>formmaker_backup,</p>
        <p><?php echo $prefix; ?>formmaker_blocked,</p>
        <p><?php echo $prefix; ?>formmaker_sessions,</p>
        <p><?php echo $prefix; ?>formmaker_submits,</p>
        <p><?php echo $prefix; ?>formmaker_themes,</p>
        <p><?php echo $prefix; ?>formmaker_views.</p>
        <p><?php echo $prefix; ?>formmaker_query.</p>
    </div>
    <div class="wrap">
      <h2>Uninstall Form Maker</h2>
      <p><strong><a href="<?php echo $deactivate_url; ?>">Click Here</a> To Finish the Uninstallation and Form Maker will be Deactivated Automatically.</strong></p>
      <input id="task" name="task" type="hidden" value="" />
    </div>
  <?php
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