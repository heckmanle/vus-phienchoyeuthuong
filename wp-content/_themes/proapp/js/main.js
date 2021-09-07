
// Call the dataTables jQuery plugin
jQuery(function($) {
    const formatCurrency = ($input) => {
        let input_val = $input.val();
        if( input_val !== '' ) {
            let regex = new RegExp('\\' + DECIMAL_SEP);
            if (!regex.test(input_val)) {
                input_val = Applications.helpers.convertMoneyToNumber(input_val);
                input_val = Applications.helpers.convertStringToMoney(input_val);
            }
        }
        $input.val(input_val);
    }

    $(document)
        .off('input', "input[data-type='currency']")
        .on('input', "input[data-type='currency']", function() {
            formatCurrency($(this));
        });

    $("input[data-type='currency']").trigger('input');
});
