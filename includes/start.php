<div class="SkySystemz-lower">
	<div class="SkySystemz-boxes">
		<div class="SkySystemz-box">
			<div class="SkySystemz-setup-instructions">
				<h4>Enter your SkySystemz Keys to enable Payment on Checkout Page</h4>
			</div>
		</div>
		<br>
		<div class="SkySystemz-box">
			<div class="SkySystemz-enter-api-key-box centered">
				<a href="#" style="display: none;">Manually enter an API key</a>
				<div class="enter-api-key" style="display: block;">
					<?php 
					global $wpdb;
					$table_keys_name = $wpdb->prefix . 'skysystemz_keys';
					if ($wpdb->get_var("SHOW TABLES LIKE '$table_keys_name'") == $table_keys_name) {
						$result = $wpdb->get_row("SELECT * FROM ".$table_keys_name." WHERE id = 1");
					} else {
						$result = "";
					}
					?>
					<form action="javascript:void(0);" method="post">
					<div class="SkySystemz-setup-instructions">
				<h4>SkySystemz </h4>
			</div>
						<input type="hidden" id="wpnonce" name="_wpnonce" value="<?php echo rand();?>">
						<p style="width: 100%; display: flex; flex-wrap: nowrap; box-sizing: border-box;">
							<input id="merchant_key" name="merchant_key" type="text" size="15" placeholder="Enter your Merchant key *" required class="regular-text code" style="flex-grow: 1; margin-right: 1rem;" value="<?php if(!empty($result)){ echo esc_html($result->merchant_keys); } else { } ?>">
						</p>
						<p style="width: 100%; display: flex; flex-wrap: nowrap; box-sizing: border-box; margin-top: 10px;">
							<input id="api_key" name="api_key" type="text" size="15" placeholder="Enter your API key *" class="regular-text code" required style="flex-grow: 1; margin-right: 1rem;" value="<?php if(!empty($result)){ echo esc_html($result->api_keys); } else { } ?>">
						</p>
						<p><small class="m_msg"></small></p>
						<p>
							<input type="submit" class="SkySystemz-button SkySystemz-is-primary" value="Pay with SkySystemz" style="margin-top:10px;">
						</p>
					</form>
				</div>
			</div>
		</div>		
	</div>
</div>
<script>
	jQuery(".SkySystemz-button").on("click", function()
	{
		var merchant_key = jQuery("#merchant_key").val();
		if(merchant_key == '') {
			jQuery(".m_msg").text("Merchant Key cannot be blank !!");
			return false;
		}
		var api_key = jQuery("#api_key").val();
		if(api_key == '') {
			jQuery(".m_msg").text("Api Key cannot be blank !!");
			return false;
		}
		var nonce = jQuery("#wpnonce").val();
		var ajax_url = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
		jQuery.ajax({
			type : "post",
			dataType : "json",
			url : ajax_url,
			data : {action: "save_keys", merchant_key : merchant_key, nonce: nonce, api_key: api_key},
			success: function(response) {
				if(response.status == "success") {
					toastr.success('Data saved successfully !!');
				}
				else if(response.status == "failure") {
					toastr.error('Data could not save !!');
				}
				else if(response.status == "keys_not_found") {
					toastr.error('Data not Found !!');
				}
				else if(response.status == "table_not_found") {
					toastr.error('Table not Found !!');
				}
				else {
					toastr.error('Something went wrong !!');
				}
			}
      	}) 
	});
</script>