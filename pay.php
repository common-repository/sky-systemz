<?php
echo '
<div>
    <div class="form-group input-field label-float col-sm-12 inp-fld">
        <div style="width: 100%;">
            <small>Card Holder Name <span class=" required">*</span></small>

            <input id="' . $this->id . '_card_holder" name="' . $this->id . '_card_holder" type="text" class="onlyText" autocomplete="off" placeholder="Card Holder Name">
        </div>
    </div>

    <div class="card-box-sec">
    <small>Card Information</small>  
    <div class="card-box-in">
    <div class="row">
    <div class="form-group input-field col-sm-12 card-no">
        <label for="password1">Card number:</label>
         <input type="text"
            class="form-control required numberonly inputCard"
            data-token="card_number"
            data-inputname="formcardnumber"
            data-cayan="cardnumber" size="20"
            autocomplete="off" placeholder="Card Number"
            required="required">

            <input id="'.$this->id.'_ccNo" name="'.$this->id.'_ccNo" type="hidden">

        <div class="input-highlight"></div>
    </div>
    </div>
    <div class="row pay-row">
    <div class="form-group col-md-4 input-field label-float month">
        <label>Month:</label>
        <input type="text"
        class="form-control required numberonly inputCard"
        data-token="exp_month"
        data-inputname="formexpiremonth"
        data-cayan="expirationmonth" size="2"
        title="Month" maxlength="2"
        autocomplete="off" placeholder=" Month"
        required="required" />

        <input id="'.$this->id.'_expdate_month" name="'.$this->id.'_expdate_month" type="hidden">


    </div>
    <div class="form-group col-md-4 input-field label-float year">
        <label>Year:</label>
        <input type="text"
        class="form-control required numberonly inputCard"
        data-token="exp_year"
        data-inputname="formexpireyear"
        data-cayan="expirationyear" size="4"
        title="Year" maxlength="4"
        autocomplete="off" placeholder="Year"
        required="required" />

    <input id="'.$this->id.'_expdate_year" name="'.$this->id.'_expdate_year" type="hidden">
    </div>
    <div class="form-group col-md-4 input-field label-float cvv">
        <label>CVV:</label>
        <input type="text"
        class="form-control numberonly required inputCard"
        data-token="cvv" data-inputname="formcvv"
        data-cayan="cvv" size="4" maxlength="4"
        autocomplete="off" placeholder="CVV"
        required="required" />

        <input id="'.$this->id.'_cvv" name="'.$this->id.'_cvv" type="hidden">

    </div>
    </div>
    </div>
    </div>
    <div class="pby">
        <img src="' . SKYSYSTEMZ__PLUGIN_DIR_URL . '/assets/images/footer-logo.png" style="height: 40px;width: auto;">
        
        
    </div>
</div>
<div class="clear"></div>';
?>

<script src="<?= DATACAP_JS_APIURL ?>"></script>
<script>



    // Refactored Tokenization Callback
    function handleResponse(response, isPaymentWallet) {
        if (response.Error) {
            console.log('', response.Error, 'error');
        } else {
            var token = response.Token;
            var jsonResponse = JSON.stringify(response, null, 2);
            console.log(jQuery("#datacap_textarea").length)
            if (jQuery("#datacap_textarea").length > 0) {
                jQuery("#datacap_textarea").val(jsonResponse);
            } else {
                jQuery("#checkout_form").append(`<textarea name="datacap_token_resp" id="datacap_textarea" style="display: none;">${jsonResponse}</textarea>`);
            }
        }
    }

    // Tokenization Callback
    var tokenCallback = function (response) {
        console.log(response)
        handleResponse(response, 0);
    }

    // Google Pay Tokenization Callback
    var tokenPayWalletCallback = function (response) {
        handleResponse(response, 1);
    }

    jQuery("#place_order").on('click', function () {

        jQuery('.inputCard').each(function(index, element) {
            var inputValue = jQuery(element).val();
            jQuery(element).next('input').val(inputValue);
        });

        DatacapWebToken.requestToken("<?= $datacapTokenKey ?>", "checkout_form", tokenCallback);
    });

</script>