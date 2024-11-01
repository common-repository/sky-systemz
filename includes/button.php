<div class="">
    <input type="button" class="btn SS_Btn" value="SkySystemz Payment">
    <div class="SS_paymentForm">
        <form action="javascript:void(0);" class="payment__body" method="post">
            <section class="payment__card">
                <div class="SS_Close">X</div>
                <div class="box">
                    <label>Card Number</label>
                    <input class="credit-card-number" type="text" name="number" inputmode="numeric">
                </div>
                <div class="box">
                    <label>CVV</label>
                    <input class="security-code" inputmode="numeric" type="text" name="security-code">
                </div>
                <div class="box">
                    <label>Name on Card</label>
                    <input class="billing-address-name" type="text" name="name">
                </div>
                <div class="box">
                    <label>Expiration</label>
                    <input class="expiration-month-and-year" type="text" name="expiration-month-and-year" placeholder="MM / YY">
                </div>
                <input type="hidden" name="nonce" class="nonce" value="<?php echo rand();?>">
            </section>
            <span class="help-info"></span>
            <input type="submit" class="payment_confirm" value="Submit your SkySystemz Keys">
        </form>
    </div>
</div>
<script>
    jQuery(".payment_confirm").on("click", function()
    {
        var card_no = jQuery(".credit-card-number").val();
        if(card_no == '') {
            jQuery(".help-info").text("Card Number cannot be blank !!");
            return false;
        }
        var security_code = jQuery(".security-code").val();
        if(security_code == '') {
            jQuery(".help-info").text("Security code cannot be blank !!");
            return false;
        }
        var bill_address = jQuery(".billing-address-name").val();
        if(bill_address == '') {
            jQuery(".help-info").text("Billing Address cannot be blank !!");
            return false;
        }
        var expiration = jQuery(".expiration-month-and-year").val();
        if(expiration == '') {
            jQuery(".help-info").text("Expiration Date cannot be blank !!");
            return false;
        }
        var nonce = jQuery(".nonce").val();
        var ajax_url = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : ajax_url,
            data : {action: "SS_save_card", card_no : card_no, nonce: nonce, security_code: security_code, bill_address : bill_address, expiration : expiration},
            success: function(response) {
                if(response.status == "success") {
                    jQuery(".help-info").text("Data saved successfully !!");
                }
                else if(response.status == "failure") {
                    jQuery(".help-info").text("Data could not save !!");
                }
                else if(response.status == "keys_not_found") {
                    jQuery(".help-info").text("Data not Found !!");
                }
                else if(response.status == "table_not_found") {
                    jQuery(".help-info").text("Table not Found !!");
                }
                else {
                    jQuery(".help-info").text("Something went wrong !!");
                }
            }
        });
    });
    jQuery(".SS_paymentForm").hide();
    jQuery(".SS_Btn").on("click", function()
    {
        jQuery(".SS_paymentForm").show();
    });
    jQuery(".SS_Close").on("click", function()
    {
        jQuery(".SS_paymentForm").hide();
    });
</script>