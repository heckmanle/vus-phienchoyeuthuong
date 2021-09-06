const pbbConvertStringToMoney =  (val, decimal = PBB_NUM_DECIMAL) => {
    return pbbFormatMoney( val,  decimal, PBB_DECIMAL_SEP, PBB_THOUNSAND_SEP);
}
const pbbConvertMoneyToNumber = (val) => {
    if ( typeof val !== "string" ) {
        val = val.toString();
    }

    val = val.replaceAll(PBB_THOUNSAND_SEP, '').replace(PBB_DECIMAL_SEP, '.');

    return Number(val);
}
const pbbFormatMoney = (n, c, d, t) => {
    c = !isNaN(Math.abs(c)) ? c : 2;
    d = d == undefined ? PBB_THOUNSAND_SEP : d; d = c == 0 ? ' ' : d;
    t = t == undefined ? PBB_DECIMAL_SEP : t;
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
            let cursorPosition = pbbGetCaretPosition(input);
            let valueBefore = input.value;
            let lengthBefore = input.value.length;
            let specialCharsBefore = pbbGetSpecialCharsOnSides(input.value);
            let number = pbbRemoveThousandSeparators(input.value);

            if (input.value == '') {
                return;
            }

            input.value = pbbFormatNumber(number.replace(pbbGetCommaSeparator(), '.'));

            // if deleting the comma, delete it correctly
            if (currentValue == input.value && currentValue == valueBefore.substr(0, cursorPosition) + pbbGetThousandSeparator() + valueBefore.substr(cursorPosition)) {
                input.value = pbbFormatNumber(pbbRemoveThousandSeparators(valueBefore.substr(0, cursorPosition-1) + valueBefore.substr(cursorPosition)));
                cursorPosition--;
            }

            // if entering comma for separation, leave it in there (as well support .000)
            let commaSeparator = pbbGetCommaSeparator();
            if (valueBefore.endsWith(commaSeparator) || valueBefore.endsWith(commaSeparator+'0') || valueBefore.endsWith(commaSeparator+'00') || valueBefore.endsWith(commaSeparator+'000')) {
                input.value = input.value + valueBefore.substring(valueBefore.indexOf(commaSeparator));
            }
            // move cursor correctly if thousand separator got added or removed
            let specialCharsAfter = pbbGetSpecialCharsOnSides(input.value);
            if (specialCharsBefore[0] < specialCharsAfter[0]) {
                cursorPosition += specialCharsAfter[0] - specialCharsBefore[0];
            } else if (specialCharsBefore[0] > specialCharsAfter[0]) {
                cursorPosition -= specialCharsBefore[0] - specialCharsAfter[0];
            }
            pbbSetCaretPosition(input, cursorPosition);

            currentValue = pbbConvertMoneyToNumber(input.value);
            currentValue = pbbFormatMoney(currentValue);
        });
        $("input[data-type='currency']").trigger('input');

    function getSpecialCharsOnSides(x, cursorPosition) {
        let specialCharsLeft = x.substring(0, cursorPosition).replace(/[0-9]/g, '').length;
        let specialCharsRight = x.substring(cursorPosition).replace(/[0-9]/g, '').length;
        return [specialCharsLeft, specialCharsRight]
    }

    function pbbFormatNumber(x) {
        return pbbGetNumberFormat().format(x);
    }

    function pbbRemoveThousandSeparators(x) {
        return x.toString().replace(new RegExp(pbbRscapeRegExp(pbbGetThousandSeparator()), 'g'), "");
    }

    function pbbGetThousandSeparator() {
        return pbbGetNumberFormat().format('1000').replace(/[0-9]/g, '')[0];
    }

    function pbbGetCommaSeparator() {
        return pbbGetNumberFormat().format('0.01').replace(/[0-9]/g, '')[0];
    }

    function pbbGetNumberFormat() {
        return new Intl.NumberFormat('en-US');
    }

    function pbbEscapeRegExp(str) {
        return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
    }

    function pbbGetCaretPosition (oField) {
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

    function pbbSetCaretPosition(elem, caretPos) {
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