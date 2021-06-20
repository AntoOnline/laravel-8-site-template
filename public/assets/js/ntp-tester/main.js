var dm = $('#domain');

$('.ntpLink').click(function () {
    dm.val($(this).text());
    return false;
});