function chmod() {
    value = "";

    $.each(["owner", "group", "other"], function (index, val) {
        subval = 0;
        for (var i = 1; i <= 3; ++i) {
            var check_id = '#' + val + i;
            if ($(check_id).is(':checked')) {
                subval = subval + Math.pow(2, i - 1);
            }
        }

        value = value + subval.toString();
    });
    $("#chmod_result").text(value);

}

$('.user_check').click(function () {
    chmod();
});

$(document).ready(function () {
    chmod();
});