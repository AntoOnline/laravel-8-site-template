

function getColorCode(status_code) {
    var color = "gray";

    if (status_code >= 200 && status_code < 300) {
        color = "green";
    } else if (status_code >= 400 && status_code < 500) {
        color = "red";
    }

    return color;
}

$("#dataForm").submit(function (event) {
    event.preventDefault();

    if ($("#http_url").val() === "") {
        $("#http_url").val("https://anto.online");
    }

    $(this).validate({
        rules: {
            http_url: {
                required: true,
                url: true
            }
        },
        messages: {
            http_url: {
                required: "Enter the URL you want to check.ss",
            }
        },
        errorElement: 'div',
        errorLabelContainer: '#http_url_error'
    });

    if ($(this).valid()) {
        $("#http_url-error").val("");

        json_obj = {
            url: $('#http_url').val()
        };

        $.ajax({
            type: 'GET',
            url: "/api/http-status?url=" + $('#http_url').val(),
            async: false,
            timeout: 5000,
            success: function (data, status, xhr) {
                if (data.code !== undefined) {
                    var color = getColorCode(data.code);

                    if (color === 'green') {
                        $.confirm({
                            title: 'HTTP Status Code: ' + data.code,
                            type: 'green',
                            icon: 'fa fa-check',
                            content: '<p>All is good! No problem was detected.</p>',
                            buttons: {
                                confirm: function () {
                                },
                            }
                        });
                    } else if (color === 'red') {
                        $.confirm({
                            title: 'HTTP Status Code: ' + data.code,
                            type: 'red',
                            icon: 'fa fa-exclamation',
                            content: '<p>There was a client or server problem.</p>',
                            buttons: {
                                confirm: function () {
                                },
                            }
                        });
                    } else {
                        $.confirm({
                            title: 'HTTP Status Code: ' + data.code,
                            type: 'gray',
                            icon: 'fa fa-info',
                            content: '<p>All is good. The server returned back some info.</p>',
                            buttons: {
                                confirm: function () {
                                },
                            }
                        });
                    }

                } else {

                    var error_msg = "Error when checking the HTTP status";
                    if (data.error !== undefined) {
                        error_msg = data.error;
                    }
                    $.confirm({
                        title: 'Error!',
                        type: 'red',
                        content: error_msg,
                        buttons: {
                            confirm: function () {
                            },
                        }
                    });

                }
            },
            error: function (jqXhr, textStatus, errorMessage) {
                $.confirm({
                    title: 'Error!',
                    type: 'red',
                    content: jqXhr.responseJSON.error,
                    buttons: {
                        confirm: function () {
                        },
                    }
                });
            }
        });

    }


});

$(document).ready(function () {
    jQuery.validator.setDefaults({
        debug: false,
        success: "valid"
    });
});
