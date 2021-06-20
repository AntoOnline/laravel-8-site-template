var path_test_num = 1;
var json_obj;


function add_path_test() {
    var $div = $('div[id^="path_test_box_"]:last');
    path_test_num = path_test_num + 1;
    var $clone = $div.clone().prop('id', 'path_test_box_' + path_test_num);
    $clone.find('input').val("");
    $clone.appendTo("#path_test_group");
}

function fill_and_add(path, status) {

    var $div = $('div[id^="path_test_box_"]:last');
    $div.find('input').val(path);
    $div.find('select').val(status);

    add_path_test();
}

function set_host(scheme, host) {
    $("#scheme").val(scheme);
    $("#host").val(host);
}

function remove_all_path_tests() {
    var cursor = 1;
    var canDelete = false;
    while (cursor <= path_test_num) {
        if ($("#path_test_box_" + cursor).length) {
            if (canDelete) {
                $("#path_test_box_" + cursor).remove();
            }
            else {
                $("#path_test_box_" + cursor).find('input').removeClass("is-valid is_invalid");
                $("#path_test_box_" + cursor).find('input').val("");
            }
            canDelete = true;
        }
        cursor = cursor + 1;
    }
}

function build_json() {
    json_path = [];
    $(".path_test_box").each(function () {
        var path = $(this).find('input').val();

        if (path != "") {
            var status = $(this).find("select").val();

            item = [path, status];
            json_path.push(item);

        }
    });

    json_obj = {
        scheme: $("#scheme").val(),
        host: $("#host").val(),
        pathTest: json_path
    };
}

function display_json(json) {
    console.log(JSON.stringify(json, null, '\t'));
}

function remove_path(elem) {
    if ($('div[id^="path_test_box_"]:first').is($('div[id^="path_test_box_"]:last'))) {
        $(elem).closest('.path_test_box').find('input').removeClass("is-valid is_invalid");;
        $(elem).closest('.path_test_box').find('input').val("");
    }
    else {
        $(elem).closest('.path_test_box').remove();
    }
}


function reset_default_path_tests() {
    remove_all_path_tests();
    set_host('https', 'app.dev-dash.io');
    fill_and_add('/robots.txt', '200');
    fill_and_add('/.htaccess', '!200');
    fill_and_add('/.git', '!200');
    fill_and_add('/.gitignore', '!200');
    fill_and_add('/127.0.0.1', '!200');
    fill_and_add('/readme.html', '!200');
    fill_and_add('/readme.md', '!200');
    fill_and_add('/readme.txt', '200');
    fill_and_add('/vendor', '!200');
    fill_and_add('/wp-admin/admin-ajax.php', '!200');

}

$("#dataForm").submit(function (event) {
    event.preventDefault();
    build_json();
    display_json(json_obj);

        if (json_obj.host === undefined || json_obj.host == "") {

        $.confirm({
            title: 'Error!',
            type: 'red',
            content: "You need to specify the host...",
            buttons: {
                confirm: function () {
                },
            },
            onDestroy: function () {
                $("#host").focus();
            },
        });

        return false;
    }

    if (json_obj.pathTest === undefined || json_obj.pathTest.length <= 0) {

        $.confirm({
            title: 'Error!',
            type: 'red',
            content: "You need at least one path test...",
            buttons: {
                confirm: function () {
                },
            },
            onDestroy: function () {
                $('div[id^="path_test_box_"]:first').find('input').focus();
            },
        });

        return false;
    }
    
    $.ajax({
        type: 'POST',
        url: "/api/site-file-checker",
        async: false,
        dataType: 'JSON',
        data: JSON.stringify(json_obj),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        timeout: 5000,
        success: function (data, status, xhr) {
            var cursor = 1;
            if (data.urls !== undefined) {
                $.each(data.urls, function (i, obj) {
                    while ($("#path_test_box_" + cursor).length <= 0) {
                        cursor = cursor + 1;
                        if (cursor > path_test_num) {
                            cursor = 1;
                            return;
                        }
                    }

                    var $div = $("#path_test_box_" + cursor);
                    $div.find('span').html("Returned: "+obj.statusReturned);
                    if (obj.path == $div.find('input').val()) {
                        var color = 'red';
                        if (obj.pass !== undefined && obj.pass == true) {
                            color = 'lightgreen';
                        }
                        $div.find('input').css("background-color", color);
                    }
                    cursor = cursor + 1;
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


});

$(document).ready(function () {
    reset_default_path_tests();
});
