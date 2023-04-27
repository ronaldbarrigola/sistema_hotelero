$(function() {
    $(document).on('click', function(e) {
        if ($('.context-menu').css('display') == 'block') {
            $('.context-menu').css('display', 'none');
        }
    });
});