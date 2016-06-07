<?php
class FMViewGoptions_fm {
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
		$fm_settings = get_option('fm_settings');
		$public_key = isset($fm_settings['public_key']) ? $fm_settings['public_key'] : '';
		$private_key = isset($fm_settings['private_key']) ? $fm_settings['private_key'] : '';
		$csv_delimiter = isset($fm_settings['csv_delimiter']) ? $fm_settings['csv_delimiter'] : ',';
		?>
		<div class="fm-user-manual">
			This section allows you to edit form settings.
			<a style="color: blue; text-decoration: none;" target="_blank" href="https://web-dorado.com/wordpress-form-maker-guide-2.html">Read More in User Manual</a>
		</div>
		<div class="fm-clear"></div>
		<form class="wrap" method="post" action="admin.php?page=goptions_fm" style="width:99%;">
			<?php wp_nonce_field('nonce_fm', 'nonce_fm'); ?>     
			<div class="fm-page-header">
				<div class="fm-page-title">
					Form Settings
				</div>
				<div class="fm-page-actions">
					<button class="fm-button save-button small" onclick="if (fm_check_required('title', 'Title')) {return false;}; fm_set_input_value('task', 'save');">
						<span></span>
						Save
					</button>
				</div>
			</div>

			<table style="clear:both;">
				<tbody>
					<tr>
						<td>
							<label for="public_key">Recaptcha Public Key:</label>
						</td>
						<td>
							<input type="text" id="public_key" name="public_key" value="<?php echo $public_key; ?>" style="width:250px;" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="private_key">Recaptcha Private Key:</label>
						</td>
						<td>
							<input type="text" id="private_key" name="private_key" value="<?php echo $private_key; ?>" style="width:250px;" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="csv_delimiter">CSV Delimiter:</label>
						</td>
						<td>
							<input type="text" id="csv_delimiter" name="csv_delimiter" value="<?php echo $csv_delimiter; ?>" style="width:50px;" />
						</td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" id="task" name="task" value=""/>
		</form>
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