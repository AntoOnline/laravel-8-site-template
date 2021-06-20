var jsonEncode = $('#jsonEncode'), meta = $('#meta'), linearize = $('#linearize'), txt = $('#text'), clr = $('#clear'), jsonPretty = $('#jsonPretty'), xmlPretty = $('#xmlPretty'), chars = $('#chars'), words = $('#words'), lines = $('#lines');

$(document).ready(function () {
    $('#text').keyup(function (e) {
        var v = txt.val().toString();
        var charsCount = v.length;
        var wordCount = v.split(/\s+/);
        var lineCount = v.split(/\r\n|\r|\n/).length;

        var wordCountFilter = wordCount.filter(function (el) {
            if ((el !== null) && (el !== "")) {
                return el;
            }
        });

        var wordCount = wordCountFilter.length;

        chars.text('Characters: ' + charsCount + '');
        words.text('Words: ' + wordCount + '');
        lines.text('Lines ' + lineCount + '');


        e.stopPropagation();
        e.preventDefault();
        return false;
    });

    jsonPretty.click(function () {
        try {
            var ugly = txt.val();
            var obj = JSON.parse(ugly);
            var pretty = JSON.stringify(obj, undefined, 4);
            txt.val(pretty);
        } catch (err) {
            alert("Could not beatify string. Is it valid JSON?");
        }
    });

    jsonEncode.click(function () {
        meta.val('jsonEncode');
        $("#frm").submit();
    });

    xmlPretty.click(function () {
        v = txt.val().toString();
        txt.val(formatXml(v));
    });

    linearize.click(function () {
        v = txt.val();

        /*remove spaces between tags*/
        v = v.replace(/\>\s+\</g, '\>\<');

        /*remove new lines and tabs*/
        v = v.replace(/\n|\t/g, ' ');

        txt.val(v);
    });

    clr.click(function () {
        txt.val('');
        chars.text('Characters: 0');
        words.text('Words: 0');
        words.text('Lines: 0');
    });
});

function formatXml(xml) {
    var formatted = '';
    var reg = /(>)(<)(\/*)/g;
    xml = xml.replace(reg, '$1\r\n$2$3');
    var pad = 0;
    jQuery.each(xml.split('\r\n'), function (index, node) {
        var indent = 0;
        if (node.match(/.+<\/\w[^>]*>$/)) {
            indent = 0;
        } else if (node.match(/^<\/\w/)) {
            if (pad != 0) {
                pad -= 1;
            }
        } else if (node.match(/^<\w[^>]*[^\/]>.*$/)) {
            indent = 1;
        } else {
            indent = 0;
        }

        var padding = '';
        for (var i = 0; i < pad; i++) {
            padding += '  ';
        }

        formatted += padding + node + '\r\n';
        pad += indent;
    });

    return formatted;
}
