$(function () {
    $('[data-toggle="popover"]').popover({trigger: 'hover click'});

    $(document).on('pjax:end', function() {
        $('[data-toggle="popover"]').popover({trigger: 'hover click'});
    });
});
