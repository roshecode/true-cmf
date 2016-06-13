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

    // body.click(globalInit);

    let logNode = $(document.createElement('div')).addClass('log');
    $(document.body).prepend(logNode);
    function log(txt) {
        logNode.append('<p>' + txt + '</p>');
    }

    let body = $(document.body),
        ajax = {
            initMsg: 'part=1',
            init: () => {
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
            loader: $(document.createElement('div')).addClass('ajax-loader'),
            toggleLoader: () => {
                ajax.loader.exist ?
                ajax.loader.remove() && (ajax.loader.exist = false) :
                body.append(ajax.loader) && (ajax.loader.exist = true);
            },
            dataAttr: (attr) => {
                return ajax.currentNode.data('ajax-' + attr);
            },
            createCache: (ctx, index) => {
                return ajax.nodes[index] = $('[data-ajax-put*="' + ctx + '"]');
            },
            prepare: (ctx, event, tag) => {
                // ajax.toggleLoader();
                event.preventDefault();
                ajax.currentNode = $(ctx);
                ajax.currentLink = $(event.target).closest(tag);
                ajax.type = ajax.dataAttr('type');
                ajax.lazy = ajax.dataAttr('init');
                ajax.mods = ajax.dataAttr('mod');
                ajax.placeholders = ajax.dataAttr('to') || 'body';
                if (ajax.placeholders && (ajax.placeholders !== ajax.lastPlaceholders)) {
                    ajax.lastPlaceholders = ajax.placeholders;
                    ajax.nodes = [];
                    ajax.nodesCache = ajax.createCache;
                } else {
                    ajax.nodesCache = (ctx, index) => { return ajax.nodes[index]; };
                }
                ajax.placeholders = ajax.placeholders.split(' ');
                if (ajax.mods && (ajax.mods.length == ajax.placeholders.length)) {
                    ajax.mods = ajax.mods.split(' ');
                    ajax.getMods = (index) => { return ajax.mods[index]; };
                } else {
                    ajax.mods = ajax.mods || 'html';
                    ajax.getMods = () => { return ajax.mods; };
                }
            },
            put: (msg) => {
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
                ajax.lazy ? $.each(ajax.lazy.split(' '), function () {
                    ajax.init[$.camelCase(this)](ajax.nodes);
                }) : 0;
                ajax.toggleLoader();
            },
            send: (url, method, data) => {
                $.ajax(url, {
                    method: method || 'get',
                    dataType: ajax.type || 'html',
                    data: data || ajax.initMsg,
                    success: ajax.put,
                    error: function(a, b, msg) {
                        console.log('ERROR: ' + msg)
                    }
                })
            }
        };

    $.extend(ajax.init, {
        load: (parents) => {
            $.each(parents, function () {
                $(this).find('[data-ajax="load"]').click(function (e) {
                    ajax.prepare(this, e, 'a');
                    ajax.toggleLoader();
                    ajax.send(ajax.currentLink.attr('href'));
                });
            });
        },
        link: (parents) => {
            ajax.data = ajax.initMsg;
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
            $.each(parents, function () {
                $(this).find('[data-ajax="form"]').submit(function (e) {
                    ajax.prepare(this, e, 'form');
                    ajax.toggleLoader();
                    ajax.send(ajax.currentLink.attr('action'), 'get', ajax.currentLink.serialize() + '&&' + ajax.initMsg)
                });
            });
        }
    });

    History.Adapter.bind(window, 'statechange', function(e) { // Note: We are using statechange instead of popstate
        ajax.lastLink.removeClass('active');
        ajax.currentLink = ajax.lastLink = $('a[href$="' + window.location.href + '"]').addClass('active');
        ajax.toggleLoader();
        ajax.send(History.getState().url);
    });

    
    $('footer nav').click(function (e) {
        ajax.globalLink = true;
        $('html, body').animate({
            scrollTop: $('.block__menu').offset().top
        }, 300);
    });

    $.extend(ajax.init, {
        callback: () => {
            let callback = $('.block_callback-wrapper');
            callback.click(function () {
                callback.remove();
            });
            callback.find('.block_callback').click(function (e) {
                e.stopPropagation();
            });
            callback.find('.block_callback > header > i').click(function () {
                callback.remove();
            });
        },
        cart: () => {
            let cartBody = $('table > tbody');
            cartBody.click((e) => {
                if (e.target.tagName == 'I' || e.target.tagName == 'A') {
                    $(e.target).closest('tr').remove();
                }
            });
            $('tfoot .remove').click((e) => {
                e.preventDefault();
                cartBody.remove();
            });
        }
    });


    ajax.init();
}(jQuery);
