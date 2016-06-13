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
            linkChanged: true,
            initMsg: 'part=1',
            globalInit: (parents) => {
                ajax.initScripts(body);
                ajax.curentLink = ajax.lastLink = $('a[href$="' + window.location.href + '"]').addClass('active');
            },
            init: () => {
                for (let init in ajax.init) {
                    if (ajax.init.hasOwnProperty(init)) {
                        ajax.init[init](body);
                    }
                }
                ajax.lastLink = $('a[href$="' + window.location.href + '"]').addClass('active');
            },
            initScripts: (ctx) => {
                ctx.find('[data-ajax]').each(function () {
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
            showLoader: () => {
                !ajax.loader.exist ? body.append(ajax.loader) && (ajax.loader.exist = true) : 0;
            },
            hideLoader: () => {
                ajax.loader.exist ? ajax.loader.remove() && (ajax.loader.exist = false) : 0;
            },
            attr: (attr) => {
                return ajax.currentNode.data('ajax-' + attr);
            },
            createCache: (ctx, index) => {
                // return ajax.nodes[index] = $('[data-ajax-put*="' + ctx + '"]');
                ajax.nodes[index] = $('[data-ajax-put*="' + ctx + '"]');
                //------------------------------------
                // console.log('create cahce');
                // console.log(ajax.nodes[index]);
                //-------------------------------------
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
                            //------------------------------------
                            // console.log('use cahce');
                            // console.log(ajax.nodes[index]);
                            //----------------------------------
                            return ajax.nodes[index]; };
                    } else {
//!!!!!!!!!!!!!!!!!!!!
                        ajax.lastPlaceholders = ajax.placeholders;
//                         if (ajax.linkChanged) {
//                             ajax.linkChanged = false;
//                             ajax.lastPlaceholders = ajax.placeholders;
//                         }
                        // Clear previous cache
                        ajax.nodes = [];
                        ajax.nodesCache = ajax.createCache;
                    }
                    ajax.placeholders = ajax.placeholders.split(' ');
                } else {
                    ajax.placeholders = ['body'];
                    ajax.nodesCache = (ctx) => { return $(ctx); };
                }

                // console.log(ajax.nodes);

                // If has mods - split them
                if (ajax.mods) {
                    ajax.mods = ajax.mods.split(' ');
                    if (ajax.mods.length == ajax.placeholders.length) {
                        ajax.getMods = (index) => { return ajax.mods[index]; };
                    } else {
                        ajax.getMods = () => { return ajax.mods[0]; };
                    }
                } else {
                    ajax.getMods = () => { return ajax.mods; };
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
                    ajax.msg = (ctx) => { return msg[$.camelCase(ctx)]; };
                } else {
                    ajax.mods = ajax.mods || 'html';
                    ajax.msg = () => { return msg; };
                }
                // Insert content
                $.each(ajax.placeholders, function (index) {
                    ajax.nodesCache(this , index)[ajax.getMods(index)](ajax.msg(this));
                    // $('[data-ajax-put*="' + this + '"]')[ajax.getMods(index)](ajax.msg(this));
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
                    error: function(a, b, msg) {
                        // ajax.hideLoader();
                        console.log('ERROR: ' + msg)
                    }
                })
            }
        };

    $.extend(ajax.init, {
        load: (parent) => {
            // $.each(parents, function () {
            //     $(this).find('[data-ajax="load"]').click(function (e) {
                $(parent).click(function (e) {
                    ajax.prepare(this, e, 'a');
                    // ajax.toggleLoader();
                    ajax.send(ajax.currentLink.attr('href'));
                });
            // });
        },
        link: (parent) => {
            // ajax.data = ajax.initMsg;
            // $.each(parents, function () {
            //     $(this).find('[data-ajax="link"]').click(function (e) {
                $(parent).click(function (e) {
                    // ajax.prepare(this, e, 'a');
                    ajax.prepareLink(this, e, 'a');
                    ajax.currentLink.attr('href') ?
                        History.pushState(null, ajax.currentLink.attr('data-title') || ajax.currentLink.text(),
                            ajax.currentLink.attr('href')) : 0;
                });
            // });
        },
        form: (parent) => {
            // $.each(parents, function () {
            //     $(this).find('[data-ajax="form"]').submit(function (e) {
                $(parent).submit(function (e) {
                    ajax.prepare(this, e, 'form');
                    // ajax.toggleLoader();
                    ajax.send(ajax.currentLink.attr('action'), 'get', ajax.currentLink.serialize() + '&' + ajax.initMsg)
                });
            // });
        }
    });

    $(document).bind("ajaxSend", function(){
        // $("#loading").show();
        ajax.toggleLoader();
    }).bind("ajaxComplete", function(){
        // $("#loading").hide();
        ajax.toggleLoader();
    });

    History.Adapter.bind(window, 'statechange', function(e) { // Note: We are using statechange instead of popstate
        ajax.lastLink.removeClass('active');
        ajax.currentLink = ajax.lastLink = $('a[href$="' + window.location.href + '"]').addClass('active');
        // ajax.toggleLoader();

        // ajax.linkChanged = true;
        // ajax.prepare(this, null, 'a');
        ajax.prepareData();
        ajax.send(History.getState().url);
    });


    $('footer nav').click(function (e) {
        ajax.globalLink = true;
        $('html, body').animate({
            scrollTop: $('.block__menu').offset().top
        }, 300);
    });

    ajax.scripts = {};
    $.extend(ajax.scripts, {
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
                // e.preventDefault();
                cartBody.remove();
            });
        }
    });


    // ajax.init();
    ajax.globalInit();
}(jQuery);
