$(function() {
    $('.item').on('contextmenu', function(e) {
        e.preventDefault();
        var $menu = $('.context-menu');
        $menu.css({
            display: 'block',
            left: e.pageX,
            top: e.pageY
        });
        $menu.data('id', $(this).data('id'));
    });

    $(document).on('click', function(e) {
        if ($('.context-menu').css('display') == 'block') {
            $('.context-menu').css('display', 'none');
        }
    });
});