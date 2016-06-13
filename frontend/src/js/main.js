+function($) {
    
    let ajaxFun = {
        link: [
            { sel: '.block_menu', to: 'content' },
            { sel: '.block_categories', to: 'content', init: 'form' },
            { sel: '.block_cart', to: 'content' }
        ],
        load: [
            { sel: '.btn_callback', to: 'content', init: 'form' },
            { sel: '.btn_product-rm', to: 'content', init: 'form' }
        ],
        send: [
            { sel: '.product-send-form', to: 'products-count total-cost', type: 'json' }
        ]
    };

    var pos = $('.block__menu').offset().top;
    $('footer nav').click(function (e) {
        ajax.globalLink = true;
        $('html, body').animate({
            scrollTop: pos
        }, 300);
    });

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

    function globalInit() {
        $('.table > tbody').click(function (e) {
            if (e.target.tagName == 'I' || e.target.tagName == 'A') {
                e.target.parent('tr').remove();
            }
        });
    }

    // body.click(globalInit);

    let logNode = $(document.createElement('div')).addClass('log');
    $(document.body).prepend(logNode);
    function log(txt) {
        logNode.append('<p>' + txt + '</p>');
    }

    let body = $(document.body),
        ajax = {
            init: () => {

                log('INIT');

                // ajax.globalLink = true;
                // ajax.nodes = [];
                // ajax.currentNode
                ajax.loader.exist = false;
                for (let init in ajax.init) {
                    if (ajax.init.hasOwnProperty(init)) {
                        ajax.init[init](body);
                    }
                }
                ajax.lastLink = $('a[href$="' + window.location.href + '"]').addClass('active');
                // ajax.init.load('load');
            },
            createCache: (ctx, index) => { log('create cache'); return ajax.nodes[index] = $('[data-ajax-put*="' + ctx + '"]'); },
            prepare: (ctx, event, tag) => {

                // log('PREPARE');

                // ajax.toggleLoader();
                event.preventDefault();
                ajax.type = 'html';
                ajax.mods = 'html';
                ajax.currentNode = $(ctx);
                ajax.currentLink = $(event.target).closest(tag);
                // ajax.globalLink = false;
                ajax.type = ajax.data('type') || ajax.type;
                ajax.lazy = ajax.data('init');
                ajax.placeholders = ajax.data('to') || 'body';


                if (ajax.placeholders && (ajax.placeholders !== ajax.lastPlaceholders)) {
                    ajax.lastPlaceholders = ajax.placeholders;
                    // ajax.placeholders = ajax.placeholders.split(' ');
                    ajax.nodes = [];
                    log('CLEAR NODES!!!');
                    ajax.nodesCache = ajax.createCache;
                } else {
                    // ajax.placeholders = ajax.placeholders.split(' ');
                    ajax.nodesCache = (ctx, index) => { log('use cache'); return ajax.nodes[index]; };
                }

                ajax.placeholders = ajax.placeholders.split(' ');
                ajax.mods = ajax.data('mod');
                if (ajax.mods && (ajax.mods.length == ajax.placeholders.length)) {
                    ajax.mods = ajax.mods.split(' ');
                    ajax.getMods = (index) => { return ajax.mods[index]; };
                } else {
                    ajax.mods = ajax.mods || 'html';
                    ajax.getMods = () => { return ajax.mods; };
                }
            },
            put: (msg) => {

                // log('PUT');

                if (ajax.type === 'json') {
                    $.each(ajax.placeholders, function (index) {
                        ajax.nodesCache(this, index)[ajax.getMods(index)](msg[$.camelCase(ajax.placeholders[index])]);
                        // $('[data-ajax-put*="' + this + '"]').text(msg[$.camelCase(ajax.placeholders[index])]);
                    });
                } else {
                    $.each(ajax.placeholders, function (index) {
                        ajax.nodesCache(this , index)[ajax.getMods(index)](msg);
                        // $('[data-ajax-put*="' + this + '"]')[ajax.getMods(index)](msg);
                    });
                }

                log('NODES: ' + ajax.nodes);
                console.log(ajax.nodes);

                ajax.lazy ? ajax.init[ajax.lazy](ajax.nodes) : 0;
                ajax.toggleLoader();
            },
            data: function (attr) {
                return ajax.currentNode.data('ajax-' + attr);
            },
            loader: $(document.createElement('div')).addClass('ajax-loader'),
            toggleLoader: () => {
                ajax.loader.exist ?
                ajax.loader.remove() && (ajax.loader.exist = false) :
                body.append(ajax.loader) && (ajax.loader.exist = true);
            }
        };

    $.extend(ajax.init, {
        load: (parents) => {

            log('INIT-load');

            // $('[data-ajax="load"]').click(function (e) {
            $.each(parents, function () {
                $(this).find('[data-ajax="load"]').click(function (e) {
                    ajax.prepare(this, e, 'a');
                    ajax.toggleLoader();
                    $.ajax(ajax.currentLink.attr('href'), {
                        dataType: ajax.type,
                        data: 'part=1',
                        success: ajax.put,
                        error: function(a, b, msg) {
                            console.log('LOAD error: ' + msg)
                        }
                    });
                });
            });
            // parent.find('[data-ajax="load"]').click(function (e) {
            //     ajax.prepare(this, e, 'a');
            //     ajax.toggleLoader();
            //     $.ajax(ajax.currentLink.attr('href'), {
            //         dataType: ajax.type,
            //         data: 'part=1',
            //         success: ajax.put,
            //         error: function(a, b, msg) {
            //             console.log('LOAD error: ' + msg)
            //         }
            //     });
            // });
        },
        link: (parents) => {

            log('INIT-link');

            // $('[data-ajax="link"]').click(function (e) {
            //     ajax.prepare(this, e, 'a');
            //     ajax.currentLink.attr('href') ?
            //         History.pushState(null, ajax.currentLink.attr('data-title') || ajax.currentLink.text(),
            //         ajax.currentLink.attr('href')) : 0;
            // });
            $.each(parents, function () {
                $(this).find('[data-ajax="link"]').click(function (e) {
                    ajax.prepare(this, e, 'a');
                    ajax.currentLink.attr('href') ?
                        History.pushState(null, ajax.currentLink.attr('data-title') || ajax.currentLink.text(),
                            ajax.currentLink.attr('href')) : 0;
                });
            });
        },
        form: (parents) => {

            log('INIT-form');

            // $('[data-ajax="form"]').submit(function (e) {
            //     ajax.prepare(this, e, 'form');
            //     ajax.toggleLoader();
            //
            //     log('AJAX: ' + ajax.currentLink.attr('action') + '?part=1&&' + ajax.currentLink.serialize());
            //
            //     $.ajax(ajax.currentLink.attr('action'), {
            //         // method: 'get',
            //         method: ajax.currentLink.attr('method') || 'get',
            //         dataType: ajax.type,
            //         data: 'part=1&&' + ajax.currentLink.serialize(),
            //         success: ajax.put,
            //         error: function(a, b, msg) {
            //             console.log('FORM error: ' + msg)
            //         }
            //     });
            // });

            $.each(parents, function () {
                $(this).find('[data-ajax="form"]').submit(function (e) {
                    ajax.prepare(this, e, 'form');
                    ajax.toggleLoader();

                    log('AJAX: ' + ajax.currentLink.attr('action') + '?part=1&&' + ajax.currentLink.serialize());

                    $.ajax(ajax.currentLink.attr('action'), {
                        // method: 'get',
                        method: ajax.currentLink.attr('method') || 'get',
                        dataType: ajax.type,
                        data: 'part=1&&' + ajax.currentLink.serialize(),
                        success: ajax.put,
                        error: function(a, b, msg) {
                            console.log('FORM error: ' + msg)
                        }
                    });
                });
            });
        }
    });

    ajax.init();

    History.Adapter.bind(window, 'statechange', function(e) { // Note: We are using statechange instead of popstate
        ajax.lastLink.removeClass('active');
        ajax.currentLink = ajax.lastLink = $('a[href$="' + window.location.href + '"]').addClass('active');
        ajax.toggleLoader();
        $.ajax(History.getState().url, {
            data: 'part=1',
            dataType: ajax.type,
            success: ajax.put,
            error: function(a, b, msg) {
                console.log('LINK error: ' + msg)
            }
        })
    });

    // let removeProductFromCart
}(jQuery);
