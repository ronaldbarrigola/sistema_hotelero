(function($) {
    var defaults = {
        sm: 540,
        md: 720,
        lg: 768, //960
        xl: 1140,
        navbar_expand: 'lg',
        animation: true,
        animateIn: 'fadeIn',
    };
    $.fn.bootnavbar = function(options) {
        var screen_width = $(document).width();
        settings = $.extend(defaults, options);
        if (screen_width >= settings.lg) {
            //$(this).find('.dropdown').hover(function() {  //fue sustituido por on.(mouseover) y on.(mouseleave) porque on se aplica aun si los elementos fueron creados posteriormente mientras que hover necesita que los elementos se cargen primero.
            $(this).on('mouseover', '.dropdown', function() {
                //$(this).on('mouseover', '.dropdown:has(>a.dropdown-item)', function() {
                //$(this).on('mouseover', '.dropdown', function() {
                //if ($(this).has('>a.dropdown-toggle.oculto').length = 0) {
                $(this).addClass('show');
                $(this).find('.dropdown-menu').first().addClass('show');
                if (settings.animation) {
                    $(this).find('.dropdown-menu').first().addClass('animated ' + settings.animateIn);
                }
                //}
            });
            $(this).on('mouseleave', '.dropdown', function() {
                //$(this).on('mouseleave', '.dropdown:has(>a.dropdown-item)', function() {
                //if ($(this).has('>a.dropdown-toggle.oculto').length = 0) {
                $(this).removeClass('show');
                $(this).find('.dropdown-menu').first().removeClass('show');
                //}

            });
        }

        //$('.dropdown-menu').on("click", function(e) { alert("holaasd"); });

        $(this).on('click', '.dropdown-menu a.dropdown-toggle', function(e) {
            // $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
                //$(this).closest("li").addClass('show'); //para el signo - en menu anidado
            }
            var $subMenu = $(this).next(".dropdown-menu");
            $subMenu.toggleClass('show');
            $(this).closest("li").toggleClass('show'); //para el signo - en menu anidado
            // alert("quitar");
            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                $('.dropdown-submenu .show').removeClass("show");

            });
            return false;
        });
    };
})(jQuery);
