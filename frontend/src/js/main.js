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
                ajax.nodeNames = [];
                ajax.nodes = [];
                ajax.currentLink = ajax.lastLink = $('a[href$="' + window.location.href + '"]').addClass('active');
                // ajax.currentNode
                ajax.loader.exist = false;
                for (let init in ajax.init) {
                    if (ajax.init.hasOwnProperty(init)) {
                        ajax.init[init]();
                    }
                }
                // ajax.init.load('load');
            },
            prepare: () => {
                ajax.type = ajax.data('type') || ajax.type;
                ajax.placeholders = ajax.data('to') || null;
                if (ajax.placeholders && (ajax.placeholders !== ajax.lastPlaceholders)) {
                    ajax.lastPlaceholders = ajax.placeholders;
                    ajax.placeholders = ajax.placeholders.split(' ');
                    $.each(ajax.placeholders, (index, value) => {
                        ajax.nodeNames[index] = '[data-ajax-put*="' + value + '"]';
                    });
                    ajax.nodesCache = (ctx, index) => { console.log('calc'); return ajax.nodes[index] = $(ctx); };
                } else {
                    ajax.nodesCache = (ctx, index) => { console.log('cache'); return ajax.nodes[index]; };
                }
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
                if (ajax.type === 'json') {
                    $.each(ajax.nodeNames, function (index) {
                        ajax.nodesCache(this, index)[ajax.getMods(index)](msg[$.camelCase(ajax.placeholders(index))]);
                    });
                } else {
                    $.each(ajax.nodeNames, function (index) {
                        ajax.nodesCache(this, index)[ajax.getMods(index)](msg);
                    });
                }
            },
            ref: (href) => {
                return href + '?part=1';
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

    ajax.save = function () {

    };

    $.extend(ajax.init, {
        load: () => {
            $('a[data-ajax="load"]').click(function (e) {
                e.preventDefault();
                // ajax.currentLink = $(this);
                ajax.currentNode = $(this);
                ajax.currentLink = $(e.target).closest('a');
                ajax.prepare();
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
                ajax.currentNode = $(this);
                ajax.currentLink = $(e.target).closest('a');
                ajax.prepare();
                ajax.currentLink.attr('href') ?
                    History.pushState(null, ajax.currentLink.attr('data-title') || ajax.currentLink.text(),
                    ajax.currentLink.attr('href')) : 0;
            });
        },
        form: () => {
            $('form[data-ajax="form"]').submit(function (e) {
                e.preventDefault();
                ajax.currentNode = $(this);
                ajax.currentLink = $(e.target).closest('form');
                ajax.prepare();
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
                // placeholder_content.html(msg);
                ajax.put(msg);
            }
        })
    });

    // let removeProductFromCart
}(jQuery);
