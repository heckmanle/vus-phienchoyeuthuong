const convertStringToMoney =  (val, decimal = REP_NUM_DECIMAL) => {
    return formatMoney( val,  decimal, REP_DECIMAL_SEP, REP_THOUNSAND_SEP);
}
const convertMoneyToNumber = (val) => {
    if ( typeof val !== "string" ) {
        val = val.toString();
    }

    val = val.replaceAll(REP_THOUNSAND_SEP, '').replace(REP_DECIMAL_SEP, '.');

    return Number(val);
}
const formatMoney = (n, c, d, t) => {
    c = !isNaN(Math.abs(c)) ? c : 2;
    d = d == undefined ? REP_THOUNSAND_SEP : d; d = c == 0 ? ' ' : d;
    t = t == undefined ? REP_DECIMAL_SEP : t;
    let s = n < 0 ? "-" : "", j,
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c)));

    j = (j = i.length) > 3 ? j % 3 : 0;

    let val = s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    return val;
}
jQuery(function ($){
    let currentValue;
    $(document)
        .off('input', "input[data-type='currency']")
        .on('input', "input[data-type='currency']", function(ev) {
            let input = this;
            let cursorPosition = getCaretPosition(input);
            let valueBefore = input.value;
            let lengthBefore = input.value.length;
            let specialCharsBefore = getSpecialCharsOnSides(input.value);
            let number = removeThousandSeparators(input.value);

            if (input.value == '') {
                return;
            }

            input.value = formatNumber(number.replace(getCommaSeparator(), '.'));

            // if deleting the comma, delete it correctly
            if (currentValue == input.value && currentValue == valueBefore.substr(0, cursorPosition) + getThousandSeparator() + valueBefore.substr(cursorPosition)) {
                input.value = formatNumber(removeThousandSeparators(valueBefore.substr(0, cursorPosition-1) + valueBefore.substr(cursorPosition)));
                cursorPosition--;
            }

            // if entering comma for separation, leave it in there (as well support .000)
            let commaSeparator = getCommaSeparator();
            if (valueBefore.endsWith(commaSeparator) || valueBefore.endsWith(commaSeparator+'0') || valueBefore.endsWith(commaSeparator+'00') || valueBefore.endsWith(commaSeparator+'000')) {
                input.value = input.value + valueBefore.substring(valueBefore.indexOf(commaSeparator));
            }
            // move cursor correctly if thousand separator got added or removed
            let specialCharsAfter = getSpecialCharsOnSides(input.value);
            if (specialCharsBefore[0] < specialCharsAfter[0]) {
                cursorPosition += specialCharsAfter[0] - specialCharsBefore[0];
            } else if (specialCharsBefore[0] > specialCharsAfter[0]) {
                cursorPosition -= specialCharsBefore[0] - specialCharsAfter[0];
            }
            setCaretPosition(input, cursorPosition);

            currentValue = convertMoneyToNumber(input.value);
            currentValue = formatMoney(currentValue);
        });
        $("input[data-type='currency']").trigger('input');

    function getSpecialCharsOnSides(x, cursorPosition) {
        let specialCharsLeft = x.substring(0, cursorPosition).replace(/[0-9]/g, '').length;
        let specialCharsRight = x.substring(cursorPosition).replace(/[0-9]/g, '').length;
        return [specialCharsLeft, specialCharsRight]
    }

    function formatNumber(x) {
        return getNumberFormat().format(x);
    }

    function removeThousandSeparators(x) {
        return x.toString().replace(new RegExp(escapeRegExp(getThousandSeparator()), 'g'), "");
    }

    function getThousandSeparator() {
        return getNumberFormat().format('1000').replace(/[0-9]/g, '')[0];
    }

    function getCommaSeparator() {
        return getNumberFormat().format('0.01').replace(/[0-9]/g, '')[0];
    }

    function getNumberFormat() {
        return new Intl.NumberFormat('en-US');
    }

    function escapeRegExp(str) {
        return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
    }

    function getCaretPosition (oField) {
        // Initialize
        let iCaretPos = 0;

        // IE Support
        if (document.selection) {

            // Set focus on the element
            oField.focus();

            // To get cursor position, get empty selection range
            let oSel = document.selection.createRange();

            // Move selection start to 0 position
            oSel.moveStart('character', -oField.value.length);

            // The caret position is selection length
            iCaretPos = oSel.text.length;
        }

        // Firefox support
        else if (oField.selectionStart || oField.selectionStart == '0')
            iCaretPos = oField.selectionStart;

        // Return results
        return iCaretPos;
    }

    function setCaretPosition(elem, caretPos) {
        if(elem != null) {
            if(elem.createTextRange) {
                var range = elem.createTextRange();
                range.move('character', caretPos);
                range.select();
            }
            else {
                if(elem.selectionStart) {
                    elem.focus();
                    elem.setSelectionRange(caretPos, caretPos);
                }
                else
                    elem.focus();
            }
        }
    }
});