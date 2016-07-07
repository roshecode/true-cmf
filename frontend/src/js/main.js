+function($) {
// $(document).ready(function() {

    $('footer nav').click(function () {
        $('html, body').animate({
            scrollTop: $('.block_menu').offset().top
        }, 300);
    });

    $('.region_sidebar ul').click(function () {
        let top = $('.block_menu').offset().top;
        if ($(window).scrollTop() >= top) {
            $('html, body').animate({
                scrollTop: top
            }, 300);
        }
    });

    ajax.scripts = {
        callback: (node) => {
            let callback = $('.form_callback-wrapper');
            callback.click(function () {
                callback.remove();
            });
            callback.find('.form_callback').click(function (e) {
                e.stopPropagation();
            });
            callback.find('.form_callback > header > i').click(function () {
                callback.remove();
            });
        },
        cart: (node) => {
            let cartBody = node.find('tbody');
            cartBody.click((e) => {
                if (e.target.tagName == 'I' || e.target.tagName == 'A') {
                    $(e.target).closest('tr').remove();
                }
            });
            node.find('tfoot .remove').click(() => {
                cartBody.remove();
            });
        },
        vin: (node) => {
            // alert('hello');
            // $('#vin-form').validate();
            // $('input[type=submit]').click(function (e) {
            //     e.preventDefault();
            // });
            let count = 0;
            let details = $('.details');
            let detailRow = $('.detail-row').clone().addClass('os-m-5');
            node.find('.add-row').click(function (e) {
                details.append(detailRow.clone());
                ++count;
            });
            node.find('.remove-row').click(function (e) {
                if (count) {
                    details.find('.detail-row:last-child').remove();
                    --count;
                }
            });
        }
    };

    ajax.init();
// });
}(jQuery);
