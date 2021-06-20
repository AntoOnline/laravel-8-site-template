$(document).ready(function () {
    var us = $('#urlString'), cl = $('#clear'), cp = $('#copy'), en = $('#encode'), dc = $('#decode');
    var clipboard = new ClipboardJS('.copy');

    en.click(function () {
        var value = us.val();
        us.val(encodeURIComponent(value).replace(/'/g, "%27").replace(/"/g, "%22"));
    });

    dc.click(function () {
        var value = us.val();
        us.val(decodeURIComponent(value.replace(/\+/g, " ")));
    });

    cl.click(function () {
        us.val('');
    });
});