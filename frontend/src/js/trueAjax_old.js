+function($) {
    $.camelCase = (str) => {
        return str.replace(/(?:^\w|[A-Z]|\b\w)/g, function (letter, index) {
            return index == 0 ? letter.toLowerCase() : letter.toUpperCase();
        }).replace(/[\s-]+/g, '');
    };

    // $.ucFirst = (str) => {
    //     return str.charAt(0).toUpperCase() + str.substr(1, str.length - 1);
    // };

    $(document).bind("ajaxSend", function () {
        ajax.toggleLoader();
    }).bind("ajaxComplete", function () {
        ajax.toggleLoader();
    });

    let body = $('body');
    let ajax = {
        scripts: {},
        initMsg: 'ajax=1',
        init: () => {
            ajax.initScripts(body);
            // ajax.currentHref = ajax.lastHref = window.location.href;
            ajax.curentLink = ajax.lastLink = $('a[href$="' + window.location.pathname + '"]').addClass('active');
        },
        initScripts: (ctx) => {
            // ctx.find('[data-ajax]').each(function () {
            ctx.find('[data-ajax]').addBack(ctx).each(function () {
                let node = $(this);
                (ajax.initEventName = node.data('ajax')) ? ajax.init[ajax.initEventName](this) : 0;
                ajax.initScriptNames = node.data('ajax-src');
                if (ajax.initScriptNames) {
                    $.each(ajax.initScriptNames.split(' '), function () {
                        ajax.scripts[this](node);
                    });
                }
            });
        },
        loader: $(document.createElement('div')).addClass('ajax-loader'),
        toggleLoader: () => {
            ajax.loader.exist ?
            ajax.loader.remove() && (ajax.loader.exist = false) :
            body.append(ajax.loader) && (ajax.loader.exist = true);
        },
        attr: (attr) => {
            return ajax.currentNode.data('ajax-' + attr);
        },
        createCache: (ctx, index) => {
            ajax.nodes[index] = $('[data-ajax-put*="' + ctx + '"]');
            return ajax.nodes[index];
        },
        prepareLink: (ctx, event, tag) => {
            event.preventDefault();
            ajax.currentNode = $(ctx);
            ajax.currentLink = $(event.target).closest(tag);
        },
        prepareData: () => {
            ajax.type = ajax.attr('type');
            ajax.lazy = ajax.attr('init');
            ajax.mods = ajax.attr('mod');
            ajax.placeholders = ajax.attr('to');
            // If have placeholders - split -it
            if (ajax.placeholders) {
                if (ajax.placeholders == ajax.lastPlaceholders) {
                    ajax.nodesCache = (ctx, index) => {
                        return ajax.nodes[index];
                    };
                } else {
                    ajax.lastPlaceholders = ajax.placeholders;
                    // Clear previous cache
                    ajax.nodes = [];
                    ajax.nodesCache = ajax.createCache;
                }
                ajax.placeholders = ajax.placeholders.split(' ');
            } else {
                ajax.placeholders = ['body'];
                ajax.nodesCache = (ctx) => {
                    return $(ctx);
                };
            }
            // If has mods - split them
            if (ajax.mods) {
                ajax.mods = ajax.mods.split(' ');
                if (ajax.mods.length == ajax.placeholders.length) {
                    ajax.getMods = (index) => {
                        return ajax.mods[index];
                    };
                } else {
                    ajax.getMods = () => {
                        return ajax.mods[0];
                    };
                }
            } else {
                ajax.getMods = () => {
                    return ajax.mods;
                };
            }
        },
        prepare: (ctx, event, tag) => {
            ajax.prepareLink(ctx, event, tag);
            ajax.prepareData();
        },
        put: (msg) => {
            // Check content type
            if (ajax.type === 'json') {
                ajax.mods = ajax.mods || 'text';
                ajax.msg = (ctx) => {
                    return msg[$.camelCase(ctx)];
                };
            } else {
                ajax.mods = ajax.mods || 'html';
                ajax.msg = () => {
                    return msg;
                };
            }
            // Insert content
            $.each(ajax.placeholders, function (index) {
                ajax.nodesCache(this, index)[ajax.getMods(index)](ajax.msg(this));
            });
            // Init logic
            $.each(ajax.nodes, function () {
                ajax.initScripts(this);
            });
        },
        send: (url, method, data) => {
            $.ajax(url, {
                method: method || 'get',
                dataType: ajax.type || 'html',
                data: data || ajax.initMsg,
                success: ajax.put,
                error: function (obj, type, msg) {
                    console.log('ERROR: ' + msg)
                }
            })
        }
    };

    $.extend(ajax.init, {
        load: (node) => {
            $(node).click(function (e) {
                ajax.prepare(this, e, 'a');
                ajax.send(ajax.currentLink.attr('href'));
            });
        },
        link: (node) => {
            $(node).click(function (e) {
                ajax.prepareLink(this, e, 'a');
                // ajax.curentLink != ajax.lastLink ? ajax.prepareData() : 0;
                ajax.currentLink.attr('href') ? History.pushState(null, ajax.currentLink.attr('data-title') ||
                    ajax.currentLink.text(), ajax.currentLink.attr('href')) : 0;
            });
        },
        form: (node) => {
            $(node).submit(function (e) {
                ajax.prepare(this, e, 'form');
                ajax.send(ajax.currentLink.attr('action'), 'post', ajax.currentLink.serialize() + '&' + ajax.initMsg)
            });
        }
    });

    window.ajax = ajax;

    History.Adapter.bind(window, 'statechange', function (e) { // Note: We are using statechange instead of popstate
        ajax.lastLink.removeClass('active');
        ajax.currentLink = ajax.lastLink = $('a[href$="' + window.location.pathname + '"]').addClass('active');

        ajax.prepareData();
        ajax.send(History.getState().url);
    });
}(jQuery);