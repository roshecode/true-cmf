+function($) {
    // let home = 'http://work/avtomagazin.dp.ua';
    // let home = 'http://avto.emis.net.ua';

    $.camelCase = (str) => {
        return str.replace(/(?:^\w|[A-Z]|\b\w)/g, function(letter, index) {
            return index == 0 ? letter.toLowerCase() : letter.toUpperCase();
        }).replace(/[\s-]+/g, '');
    };

    $.ucFirst = (str) => {
        return str.charAt(0).toUpperCase() + str.substr(1, str.length - 1);
    };

    let body = $(document.body), placeholder_content = $('.block__content'),
        ajax = {
            init: () => {
                ajax.type = 'html';
                ajax.mods = 'html';
                ajax.currentLink = ajax.lastLink = $('a[href$="' + window.location.href + '"]').addClass('active');
                ajax.loader.exist = false;
                for (let init in ajax.init) {
                    if (ajax.init.hasOwnProperty(init)) {
                        ajax.init[init]();
                    }
                }
                // ajax.init.load('load');
            },
            prepare: () => {
                ajax.putItems = ajax.data('to'  ).split(' ') || null;
                ajax.putNodes = $.each(ajax.putItems, (index, value) => {
                    ajax.mods[index] = ajax.mods;
                    return '[data-ajax-put*="' + value + '"]';
                });
                ajax.type = ajax.data('type');
                ajax.mods = ajax.data('mod' ).split(' ') || ajax.mods;
            },
            put: (msg) => {
                if (ajax.type == 'json') {
                    $.each(ajax.putNodes, (index) => {
                        $(this)[ajax.mods[index]](msg[$.camelCase(ajax.putItems(index))]);
                    });
                } else {
                    $.each(ajax.putNodes, (index) => {
                        $(this)[ajax.mods[index]](msg);
                    });
                }
            },
            ref: (href) => {
                return href + '?part=1';
            },
            data: (attr) => {
                return ajax.currentLink.data('ajax-' + attr);
            },
            loader: $(document.createElement('div')).addClass('ajax-loader'),
            toggleLoader: () => {
                ajax.loader.exist ?
                ajax.loader.remove() && (ajax.loader.exist = false) :
                body.append(ajax.loader) && (ajax.loader.exist = true);
            }
        };

    ajax.save = function () {

    };

    $.extend(ajax.init, {
        load: () => {
            $('a[data-ajax="load"]').click(function (e) {
                e.preventDefault();
                ajax.currentLink = $(this);
                ajax.toggleLoader();
                $.ajax(ajax.ref(ajax.currentLink.attr('href')), {
                    success: (msg) => {
                        ajax.toggleLoader();
                        body.append(msg);
                        // sendCallback();
                    }
                });
            });
        },
        loadGroup: () => {
            $('a[data-ajax="load-group"]').click(function (e) {
                e.preventDefault();
                // ajax.currentLink = $(e.target.tagName == 'A' ? e.target : $(e.target).parent('a'));
                ajax.currentLink = $(e.target).closest('a');
                ajax.toggleLoader();
                $.ajax(ajax.ref(ajax.currentLink.attr('href')), {
                    success: (msg) => {
                        ajax.toggleLoader();
                        body.append(msg);
                        // sendCallback();
                    }
                });
            });
        },
        link: () => {
            $('[data-ajax="link"]').click(function (e) {
                e.preventDefault();
                // ajax.currentLink = $(this);
                ajax.currentLink = $(e.target).closest('a');
                ajax.currentLink.attr('href') ?
                    History.pushState(null, ajax.currentLink.attr('data-title') || ajax.currentLink.text(),
                    ajax.currentLink.attr('href')) : 0;
            });
        },
        // linkGroup: () => {
        //     $('[data-ajax="link-group"]').click(function (e) {
        //         e.preventDefault();
        //         ajax.currentLink = $(e.target).closest('a');
        //         ajax.currentLink.attr('href') ?
        //             History.pushState(null, ajax.currentLink.attr('data-title') || ajax.currentLink.text(),
        //                 ajax.currentLink.attr('href')) : 0;
        //     });
        // },
        form: () => {
            $('form[data-ajax="form"]').submit(function (e) {
                e.preventDefault();
            });
        }
    });

    ajax.init();

    History.Adapter.bind(window, 'statechange', function(){ // Note: We are using statechange instead of popstate
        ajax.lastLink.removeClass('active');
        ajax.lastLink = ajax.currentLink.addClass('active');
        ajax.toggleLoader();
        $.ajax(ajax.ref(History.getState().url), {
            success: function(msg) {
                ajax.toggleLoader();
                placeholder_content.html(msg);
                // addToCart();
                // removeFromCart();
                // sendCallback();
            }
        })
    });

    // let removeProductFromCart
}(jQuery);
