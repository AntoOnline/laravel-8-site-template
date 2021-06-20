var clone_num = 1;
var copy_num = 1;
var json_obj;

//
// Use this data for testing purposes - change in AJAX request json_obj_test -> json_obj to try your code.
//
var json_obj_test = {
    "base64Input": "PE15WE1MPg0KCTxJbnRlcm5hbENvZGU+e3tpbnRlcm5hbENvZGV9fTwvSW50ZXJuYWxDb2RlPg0KCTxDb21wYW55TmFtZT57e2NvbXBhbnlOYW1lfX08L0NvbXBhbnlOYW1lPg0KCTxEaXN0cmlidXRpb24+DQoJCTxQYXJ0bmVyQ29kZT57e3BhcnRuZXJDb2RlfX08L1BhcnRuZXJDb2RlPg0KCQk8U3lzdGVtQ29kZT57e3N5c3RlbUNvZGV9fTwvU3lzdGVtQ29kZT4NCgkJPFByb2R1Y3RDb2RlPnt7cHJvZHVjdENvZGV9fTwvUHJvZHVjdENvZGU+DQoJPC9EaXN0cmlidXRpb24+DQo8L015WE1MPg",
    "outputFileExt": ".xml",
    "clone": [
        {
            "findTag": "{{internalCode}}",
            "then": "replaceWithOneOf",
            "values": [
                "SSS",
                "AAA",
                "GGG",
                "MMM",
                "ZZZ"
            ]
        },
        {
            "findTag": "{{partnerCode}}",
            "then": "replaceWithOneOf",
            "values": [
                "Direct",
                "Outsourced"
            ]
        },
        {
            "findTag": "{{systemCode}}",
            "then": "replaceWithOneOf",
            "values": [
                "3001000159848",
                "3001000159928",
                "3001000159945"
            ]
        }
    ],
    "copies": [
        {
            "findTag": "{{companyName}}",
            "then": "replaceWith",
            "value": "My Company"
        },
        {
            "findTag": "{{productCode}}",
            "then": "replaceWith",
            "value": "Some Product"
        }
    ]
};

function add_clone() {
    var $div = $('div[id^="clone_box_"]:last');
    clone_num = clone_num + 1;
    var $clone = $div.clone().prop('id', 'clone_box_' + clone_num);
    $clone.appendTo("#clone_group");
}

function add_copy() {
    var $div = $('div[id^="copy_box_"]:last');
    copy_num = copy_num + 1;
    var $clone = $div.clone().prop('id', 'copy_box_' + copy_num);
    $clone.appendTo("#copy_group");
}

function build_json() {
    json_clone = [];
    json_copies = [];
    $(".clone_box").each(function () {
        var tag = $(this).find('input').val();
        var then = $(this).find("select").val();
        var values = [];

        $.each($(this).find("textarea").val().split(/\n/), function (i, line) {
            if (line) {
                values.push(line);
            }
        });

        item = {};
        item["findTag"] = "{{" + tag + "}}";
        item["then"] = then;
        item["values"] = values;
        json_clone.push(item);
    });
    $(".copy_box").each(function () {
        var tag = $(this).find('input').val();
        var then = $(this).find("select").val();
        var values = "";

        $.each($(this).find("textarea").val().split(/\n/), function (i, line) {
            if (line) {
                values = line;
                return true;
            }
        });

        item = {};
        item["findTag"] = "{{" + tag + "}}";
        item["then"] = then;
        item["value"] = values;
        json_copies.push(item);
    });

    json_obj = {
        base64Input: $("#base64Input").val().replace(/\n/g, ''),
        outputFileExt: $("#outputFileExt").val(),
        clone: json_clone,
        copies: json_copies,
        _token: "{{ csrf_token() }}"
    };
}

function display_json(json) {
    console.log(JSON.stringify(json, null, '\t'));
}

$("#dataForm").submit(function (event) {
    event.preventDefault();
    build_json();
    display_json(json_obj);
    $.ajax({
        type: 'POST',
        url: "api/data-multiplier",
        //
        // CHANGE THIS TO json_obj !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        //
        data: JSON.stringify(json_obj_test),
        async: false,
        dataType: 'JSON',
        timeout: 5000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data, status, xhr) {
            $.confirm({
                title: 'Success!',
                type: 'green',
                content: 'Click <a target="new" href="' + data.result.location + '">here</a> to download your sample data.',
                buttons: {
                    confirm: function () {
                    },
                }
            });
        },
        error: function (jqXhr, textStatus, errorMessage) {
            $.confirm({
                title: 'Error!',
                type: 'red',
                content: 'An error occured. ' + jqXhr.responseJSON.error,
                buttons: {
                    cancel: function () {
                    },
                }
            });
        }
    });


});

$(document).ready(function () {});
