jQuery(document).on('click', '.SS_pay_checkout', function(event) 
{
	jQuery(".SS_Popups").show();
});
jQuery(document).on('click', '.SS_Close', function(event) 
{
	jQuery(".SS_Popups").hide();
});
function addHyphen (element) {
	let ele = document.getElementById(element.id);
    ele = ele.value.split('-').join('');    // Remove dash (-) if mistakenly entered.

    let finalVal = ele.match(/.{1,4}/g).join('-');
    document.getElementById(element.id).value = finalVal;
}

