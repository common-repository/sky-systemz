<?php

/*
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
add_filter( 'woocommerce_payment_gateways', 'SkySystemz_add_gateway_class' );
function SkySystemz_add_gateway_class( $gateways ) {
	$gateways[] = 'WC_SkySystemz_Gateway'; // your class name is here
	return $gateways;
}

/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action( 'plugins_loaded', 'SkySystemz_init_gateway_class' );
function SkySystemz_init_gateway_class() {
	class WC_SkySystemz_Gateway extends WC_Payment_Gateway {
 		/**
 		 * Class constructor, more about it in Step 3
 		 */
 		public function __construct() {
			$this->id = 'skysystemz'; // payment gateway plugin ID
			$this->icon = ''; // URL of the icon that will be displayed on checkout page near your gateway name
			$this->has_fields = true; // in case you need a custom credit card form
			$this->method_title = 'SkySystemz Gateway';
			$this->method_description = 'Description of SkySystemz payment gateway'; // will be displayed on the options page

			// gateways can support subscriptions, refunds, saved payment methods,
			// but in this tutorial we begin with simple payments
			$this->supports = array(
				'products'
			);

			// Method with all the options fields
			$this->init_form_fields();

			// Load the settings.
			$this->init_settings();
			$this->title = $this->get_option( 'title' );
			$this->description = $this->get_option( 'description' );
			$this->enabled = $this->get_option( 'enabled' );
			// This action hook saves the settings
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
 		}

		/**
 		 * Plugin options, we deal with it in Step 3 too
 		 */
 		public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title'       => 'Enable/Disable',
					'label'       => 'Enable SkySystemz Gateway',
					'type'        => 'checkbox',
					'description' => '',
					'default'     => 'no'
				),
				'title' => array(
					'title'       => 'Title',
					'type'        => 'text',
					'description' => 'This controls the title which the user sees during checkout.',
					'default'     => 'Credit Card',
					'desc_tip'    => true,
				),
				'description' => array(
					'title'       => 'Description',
					'type'        => 'textarea',
					'description' => 'This controls the description which the user sees during checkout.',
					'default'     => 'Pay with your credit card via our super-cool payment gateway.',
				),
			);
	 	}

		/**
		 * You will need it if you want your custom credit card form, Step 4 is about it
		 */
		public function payment_fields() {
			// ok, let's display some description before the payment form
			if ( $this->description ) {
				// you can instructions for test mode, I mean test card numbers etc.
				// if ( $this->testmode ) {
				// 	$this->description .= ' TEST MODE ENABLED. In test mode, you can use the card numbers listed in <a href="#">documentation</a>.';
				// 	$this->description  = trim( $this->description );
				// }
				// display the description with <p> tags etc.
				echo wpautop( wp_kses_post( $this->description ) );
			}
		 	$cur_year = date('Y');
		 	for ($i = 0; $i <= 50; $i++) {
			    $year[] = $cur_year++;
			}


			// echo '<div class="pop_btn" style="text-align: center;">
			// 		<span class="btn toggle button SS_pay_checkout" data-target="myPopup" style="cursor:pointer;">Pay with sky systemz</span>
			// 	</div>';
			// I will echo() the form, but you can close PHP tags and print it directly in HTML
			echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form wc_cus_fform payment-m-box">';
		 
			// Add this action hook if you want your custom payment gateway to support it
			do_action( 'woocommerce_credit_card_form_start', $this->id );

			echo '<style>
					.pby{width:100%; text-align:right;}
					.pby img{width:80px !important; height:auto!important;}
					.payment-m-box{margin: 20px 0 0 0; padding:5px 2% !important; width: 91%; background: #fff; border: #ecf1f7 10px solid; border-radius: 20px;}
					.card-box-sec{width: 100%; margin:8px 0 0 0;}
					.inp-fld small{display: block; font-size: 14px; font-weight: 500; color: #000000;	margin-bottom: 4px;}
					.card-box-sec small{display: block; font-size: 14px; font-weight: 500; color: #000000;	margin-bottom: 4px;}
					.card-box-in{border: #dfe0e5 1px solid;  border-radius: 5px; background: #fff; display: block; box-shadow: 0px 1px 2.88px 0.12px rgba(0, 0, 0, 0.1); margin-bottom: 10px;}
					.card-box-in label{display: none;}
					.card-box-in .form-group {border-radius: 0; outline: 0;}
					.card-box-in .card-no {margin: 0;}
					.card-box-in .card-no input{border: 0; border-bottom: #dfe0e5 1px solid; background: url(https://staging.skysystemz.com/assets/paymentpage/images/card-ico01.jpg); background-repeat: no-repeat; background-position: right 10px center; width: 96%; padding:8px 2%;} 
					#skysystemz_card_holder {
						padding: 2%; 
					}
					#skysystemz_card_holder:focus {
						outline: 0!important; box-shadow: none!important; 
					}

					.woocommerce form .form-row select, .woocommerce-page form .form-row select {padding: 10px 1.1rem;}
					
					.card-logo{padding:10px 0;}
					.card-box-in .card-no input:focus{border-bottom: #dfe0e5 1px solid; outline: 0!important; box-shadow: none!important;}
					.card-box-in .month{border-right: #dfe0e5 1px solid; width: 32%!important; display: inline-block; margin: 0;  }
					// .card-box-in .month select{border: 0; border-right: #dfe0e5 1px solid; padding: 8px; background: #fff;  width: 100%;}
					.card-box-in .month select{border: 0; padding:8px 2%; background: #fff; width: 96%; color:#757575; }

					.card-box-in .year{width: 32%!important; display: inline-block; margin: 0; }
					.card-box-in .year select{border: 0; border-right: #dfe0e5 1px solid; color:#757575; padding: 8px; background: #fff; width: 100%;}
					.card-box-in .cvv{width: 32%!important; display: inline-block; margin: 0; padding-left: 0;}
					.card-box-in .cvv input{border: 0; padding: 8px;  background: url(https://staging.skysystemz.com/assets/paymentpage/images/cvv.jpg); background-repeat: no-repeat; background-position: right 10px center; width: 87%;}
					.card-box-in .cvv input:focus {
						outline: 0!important; box-shadow: none!important;
					}
					.card-box-in input{padding:.375rem .75rem;}
					.card-box-in input:focus{box-shadow: none;}
					.mytitl{text-align: center;}
					.payment-m-box .card-wrapper{display: none;}
					.payment-m-box .separtor{display: none;}
					.p-dt{position: relative;}
					.p-dt:before{width: 100%; height: 1px; background: #ddd; top: 56%; transform: translateY(-56%); position: absolute; content: ""; left: 0;}
					.p-dt span{background: #fff; padding: 0 12px; font-size: 16px; position: relative; z-index: 5;}
					.payment-m-box .mybtnp{width: 100%!important; display: block; text-align: center; background: #000; border: #000 1px solid; padding: 8px 28px !important;}
					.payment-m-box .mybtnp:hover{background:#212223; border: #212223 1px solid; }
					.mytitl{text-transform: inherit!important;}
					.inp-fld input {
						border: #dfe0e5 1px solid;
						padding: 0.375rem 0.75rem;
						border-radius: 5px;
						background: #fff;
						display: block;
						box-shadow: 0px 1px 2.88px 0.12px rgb(0 0 0 / 10%);
						margin-bottom: 10px;
						width: 96%;
					}
					#payment button, .woocommerce-input-wrapper button{
					background: #2979ff !important; border: 0 !important;
						border-radius: 4px !important;
					}
					
					
					#payment button:hover, .woocommerce-input-wrapper button:hover{
						opacity: 0.8; text-decoration:  none !important;
					}
					
					.woocommerce .woocommerce-error, .woocommerce .woocommerce-info, .woocommerce .woocommerce-message{border-top-color: #2979ff !important;}
					
					.woocommerce-page input[type=radio][name=payment_method]~.payment_box {
						padding-left: 0;
					}
					.woocommerce-page form label {
						font-weight:600;
					}
					@media screen and (max-width: 400px){
					.card-box-in .month{width:31.5% !important;}
					.woocommerce-page form .input-text {
						padding: 8px !important;
					}
					}
					.hdn-input{display:none}
					.pay-row{display:flex; align-items:center;}
					.card-box-in .year, .card-box-in .month {border-right: #dfe0e5 1px solid; overflow:hidden;}
					.card-box-in .year input, .card-box-in .month input {border:0 !important; outline:none; width:100%;}
					</style>';


			$payMethodNameId = 0;
			
				
			global $woocommerce, $wpdb;
			$table_name = $wpdb->prefix . 'skysystemz_keys';
			$result = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE id = 1");
			$args = array(
				"merchant_key" => $result->merchant_keys,
			);
			$environment_url = SKYSYSTEMZ_API_URL."/get-payment-method";
			$response = wp_remote_post( $environment_url, array(
				'method'    => 'POST',
				'body'      => http_build_query( $args ),
				'timeout'   => 90,
				'sslverify' => false,
				'headers' => array("Content-type" => "application/x-www-form-urlencoded;charset=UTF-8", "Authorization" => "15dd23483ac659d638c892123761786erg4GG6ff3218c25eb0a475edcbaaeb86"),
			) );
			
			
			if( !is_wp_error( $response ) ) {
				$body = json_decode( $response['body'], true );
				
				if ( $body['status_code'] == '200' ) {

					$data = $body['data'];
					$payMethodNameId = $data['pay_method_name_id'];

					if($payMethodNameId == 31){
						$merchantname = $data['merchant_name'];
						$datacapMid = $data['pay_method_id'];
						$datacapTokenKey = explode(":",$data['pay_method_name_address'])[3];
						require_once('pay.php');
					}
				}
			}

			if($payMethodNameId != 31){

				// I recommend to use inique IDs, because other gateways could already use #ccNo, #expdate, #cvc
				echo '
					<div>
						<div class="form-group input-field label-float col-sm-12 inp-fld">
							<div style="width: 100%;">
								<small>Card Holder Name <span class=" required">*</span></small>
					
								<input id="'.$this->id.'_card_holder" name="'.$this->id.'_card_holder" type="text" class="onlyText" autocomplete="off" placeholder="Card Holder Name">
							</div>
						</div>

						<div class="card-box-sec">
						<small>Card Information</small>  
						<div class="card-box-in">
						<div class="row">
						<div class="form-group input-field col-sm-12 card-no">
							<label for="password1">Card number:</label>
							<input id="'.$this->id.'_ccNo" name="'.$this->id.'_ccNo" type="text" autocomplete="off" placeholder="4242-4242-4242-4242" onkeyup="addHyphen(this)">
							<div class="input-highlight"></div>
						</div>
						</div>
						<div class="row">
						<div class="form-group col-md-4 input-field label-float month">
							<label>Month:</label>
							<select id="'.$this->id.'_expdate_month" name="'.$this->id.'_expdate_month" style="width: 100%;">
									<option value="0">Month</option>
									<option value="01">(01) January</option>
									<option value="02">(02) February</option>
									<option value="03">(03) March</option>
									<option value="04">(04) April</option>
									<option value="05">(05) May</option>
									<option value="06">(06) June</option>
									<option value="07">(07) July</option>
									<option value="08">(08) August</option>
									<option value="09">(09) September</option>
									<option value="10">(10) October</option>
									<option value="11">(11) November</option>
									<option value="12">(12) December</option>
								</select>
						</div>
						<div class="form-group col-md-4 input-field label-float year">
							<label>Year:</label>
							<select id="'.$this->id.'_expdate_year" name="'.$this->id.'_expdate_year" style="width: 100%;">
									<option value="0">Year</option>';
									foreach ($year as $key => $yr) {
										$short = substr($yr, 2);
										echo '<option value="'.esc_attr($short).'">'.esc_attr($yr).'</option>';
									}
								echo '</select>
						</div>
						<div class="form-group col-md-4 input-field label-float cvv">
							<label>CVV:</label>
							<input id="'.$this->id.'_cvv" name="'.$this->id.'_cvv" type="password" autocomplete="off" placeholder="CVC">
						</div>
						</div>
						</div>
						</div>
						<div class="pby">
							<img src="'.SKYSYSTEMZ__PLUGIN_DIR_URL.'/assets/images/footer-logo.png" style="height: 40px;width: auto;">
							
							
						</div>
					</div>
					<div class="clear"></div>';
			}

			do_action( 'woocommerce_credit_card_form_end', $this->id );
			echo '<div class="clear"></div></fieldset>';
			// padding: 0.9rem 1.1rem;
		}

		/*
		 * Custom CSS and JS, in most cases required only when you decided to go with a custom credit card form
		 */
	 	public function payment_scripts() {
			// we need JavaScript to process a token only on cart/checkout pages, right?
			if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) ) {
				return;
			}
		}

		/*
 		 * Fields validation, more in Step 5
		 */
		public function validate_fields() {
			
			if( empty( sanitize_text_field($_POST[ 'billing_first_name' ]) )) {
				wc_add_notice( 'First name is required!', 'error' );
				return false;
			}
			if( empty( sanitize_text_field($_POST[ 'skysystemz_ccNo' ])) ) {
				wc_add_notice( 'Card Number is required!', 'error' );
				return false;
			} else {
				$ccc_no = str_replace("-", "", sanitize_text_field($_POST['skysystemz_ccNo']));
				$type = validatecard($ccc_no);
				if(empty($type)) {
					wc_add_notice( 'Enter valid Card Number!', 'error' );
					return false;
				}
			}
			if( empty( sanitize_text_field($_POST[ 'skysystemz_cvv' ]) )) {
				wc_add_notice( 'CVV is required!', 'error' );
				return false;
			} else {
				$len = strlen(sanitize_text_field($_POST[ 'skysystemz_cvv' ]));
				if($len > 4) {
					if(isNumeric(sanitize_text_field($_POST[ 'skysystemz_cvv' ]))) {
						wc_add_notice( 'CVV number cannot be more than 4 digits', 'error' );
						return false;
					}
				}
			}
			if( empty( sanitize_text_field($_POST[ 'skysystemz_expdate_month' ]) )) {
				wc_add_notice( 'Expiry Month is required!', 'error' );
				return false;
			}
			if( empty( sanitize_text_field($_POST[ 'skysystemz_expdate_year' ]) )) {
				wc_add_notice( 'Expiry Year is required!', 'error' );
				return false;
			}
			if( empty( sanitize_text_field($_POST[ 'skysystemz_card_holder' ]) )) {
				wc_add_notice( 'Card Holder Name is required!', 'error' );
				return false;
			}
			return true;
		}

		/*
		 * We're processing the payments here, everything about it is in Step 5
		 */
		public function process_payment( $order_id ) {
			
			global $woocommerce, $wpdb;
			$items = $woocommerce->cart->get_cart();
			foreach($items as $item => $values) {
				$_product =  wc_get_product( $values['data']->get_id());
				$title[] = $_product->get_title();
			}
			if(!empty($title)) {
				$titles = implode(', ', $title);
			} else {
				$titles = "";
			}
			// we need it to get any order detailes
			$order = wc_get_order( $order_id );
			/*
		 	 * Array with parameters for API interaction
			*/
			$table_name = $wpdb->prefix . 'skysystemz_keys';
			if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
				$result = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE id = 1");
			} else {
				$result = "";
			}
			if(empty($result->merchant_keys)) {
				wc_add_notice( 'Sky Systemz Merchant Key not Found !!', 'error' );
				return;
			}
			$ccc_no = str_replace("-", "", $_POST['skysystemz_ccNo']);
			$args = array(
 				"number" => $ccc_no,
				"cvv" => sanitize_text_field($_POST['skysystemz_cvv']),
				"amount" => round($order->get_total() * 100),
				"merchant_key" => $result->merchant_keys,
				"orderId" => $order->get_id(),
				"desc" => $titles,
				"mm" => sanitize_text_field($_POST['skysystemz_expdate_month']),
				"yy" => sanitize_text_field($_POST['skysystemz_expdate_year']),
				"card_holder_name" => sanitize_text_field($_POST['skysystemz_card_holder']),
				"datacap_token_resp" => isset($_POST['datacap_token_resp']) ? sanitize_text_field($_POST['datacap_token_resp']) : '',
			);
			$environment_url = SKYSYSTEMZ_API_URL."/api-charge";

			/*
			 * Your API interaction could be built with wp_remote_post()
		 	 */
			$response = wp_remote_post( $environment_url, array(
		      'method'    => 'POST',
		      'body'      => http_build_query( $args ),
		      'timeout'   => 90,
		      'sslverify' => false,
		      'headers' => array("Content-type" => "application/x-www-form-urlencoded;charset=UTF-8", "Authorization" => "15dd23483ac659d638c892123761786erg4GG6ff3218c25eb0a475edcbaaeb86"),
		    ));
			
			//$response = wp_remote_post( '{payment processor endpoint}', $args );
			if( !is_wp_error( $response ) ) {
				$body = json_decode( $response['body'], true );

		 		if ( $body['status_code'] == '200' ) {
					$table_keys_name = $wpdb->prefix . 'skysystemz_payment';
		 			if ($wpdb->get_var("SHOW TABLES LIKE '$table_keys_name'") == $table_keys_name) {
						$result = $wpdb->get_row("SELECT * FROM ".$table_keys_name." WHERE id = 1");
						if(!empty($result)) {
						} else {
							$user_id = get_current_user_id();
						    $save = $wpdb->insert($table_keys_name, array(
							    'user_id' => $user_id,
							    'cust_name' => sanitize_text_field($_POST['skysystemz_card_holder']),
							    'card_no' => $ccc_no,
							    'expiration_month' => sanitize_text_field($_POST['skysystemz_expdate_month']),
							    'expiration_year' => sanitize_text_field($_POST['skysystemz_expdate_year']),
							    'security_code' => sanitize_text_field($_POST['skysystemz_cvv']),
							    'status' => 1,
							));
							if($save) {
								$json['status'] = "success";
							} else {
								wc_add_notice( 'Could not save card', 'error' );
								return;
							}
						}
					}
					else {
						wc_add_notice( 'Could not save card', 'error' );
						return;
					}
					// we received the payment
					$order->payment_complete();
					$order->reduce_order_stock();
					// some notes to customer (replace true with false to make it private)
					$order->add_order_note( 'Hey, your order is paid! Thank you!', true );
					// Empty cart
					$woocommerce->cart->empty_cart();
					// Redirect to the thank you page
					return array(
						'result' => 'success',
						'redirect' => $this->get_return_url( $order )
					);
				} else {
					wc_add_notice( 'Cannot process payment at this time, Please try again.', 'error' );
					return;
				}
			} else {
				wc_add_notice( 'Connection error.', 'error' );
				return;
			}
	 	}

		/*
		 * In case you need a webhook, like PayPal IPN etc
		 */
		public function webhook() {
			$order = wc_get_order( $_GET['id'] );
			$order->payment_complete();
			$order->reduce_order_stock();
			update_option('webhook_debug', $_GET);	
	 	}
 	}

 	function validatecard($number)
	{
		global $type;
		$cardtype = array(
		"visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
		"mastercard" => "/^5[1-5][0-9]{14}$/",
		"amex"       => "/^3[47][0-9]{13}$/",
		"discover"   => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
		);

		if (preg_match($cardtype['visa'], $number))
		{
			$type = "visa";
			return 'visa';
		}
		else if (preg_match($cardtype['mastercard'], $number))
		{
			$type = "mastercard";
			return 'mastercard';
		}
		else if (preg_match($cardtype['amex'], $number))
		{
			$type = "amex";
			return 'amex';
		}
		else if (preg_match($cardtype['discover'], $number))
		{
			$type = "discover";
			return 'discover';
		}
		else
		{
			return false;
		} 
	}


}

