+function ($) {
    let q = function(selector, context) {
        return new q.fn.init(selector, context);
    };
    q.checkClassesRegExp = function (className) {
        return new RegExp("(^|\\s)" + className + "(\\s|$)", "g");
    };
    q.fn = q.prototype = {
        version: '0.0.1',
        length: 0,
        constructor: q,
        now: Date.now(),
        init: function(selector, context) {
            if ( !selector ) {
                return this;
            }
            if (typeof selector === 'string') {
                q.node = context ? context.querySelector(selector) : $.querySelector(selector);
                console.log(q.node);
                return q.fn;
            } else if (selector.nodeType) {
                q.node = selector;
                return q.fn;
            }
        },
        addClass: function(c) {
            if (q.checkClassesRegExp(c).test(q.node.className)) return false;
            q.node.className = (q.node.className + " " + c).replace(/\s+/g, " ").replace(/(^ | $)/g, "");
            return q;
        }
    };
    window.q = q;
}(document);

var t = function () {
    let t = this;
    return function (query) {
        t.node = document.querySelector(query);
        return t;
    }
};

var tn = function () {
    let t = this;
    return function (node) {
        t.node = node;
        return t;
    }
};

+function ($) {
    let _ = {};
    _.n = function (node) {
        _.node = node;
        return _;
    };

    _.a = function (query) {
        _.node = document.querySelectorAll(query);
        return _;
    };

    _.checkDomClassesRegExp = function(className) {
        return new RegExp("(^|\\s)" + className + "(\\s|$)", "g");
    };

    _.addClass = function(c) {
        if (_.checkDomClassesRegExp(c).test(_.node.className)) return false;
        _.node.className = (_.node.className + " " + c).replace(/\s+/g, " ").replace(/(^ | $)/g, "")
        return _;
    };

    _.removeClass = function(c) {
        _.node.className = _.node.className.replace(_.checkDomClassesRegExp(c), "$1").replace(/\s+/g, " ").replace(/(^ | $)/g, "");
        return _;
    };

    _.toggleClass = function(c) {
        if (!_.addClass(_.node, c)) _.removeClass(_.node, c);
        return _;
    };

    t = t.apply(_);
    t.n = tn.apply(_);
}(document);

+function($) {
    // let home = 'http://work/avtomagazin.dp.ua';
    // let home = 'http://avto.emis.net.ua';

    let buttons_menu = t('.block__menu > li > a');
    let buttons_categories = t('.block__categories > ul > li > a');

    t.data = {
        // loader: $.querySelector('.ajax-loader'),
        home: window.location.protocol + '//' + window.location.host + '/avtomagazin.dp.ua',
        placeholder_productsCount: $.querySelector('.block__cart > .field > p'),
        placeholder_totalCost: $.querySelector('.block__cart > .field > .total-cost'),
        placeholder_content: $.querySelector('.block__content'),
        // buttons_page: $.querySelectorAll('.block__menu > li > a'),
        buttons_page: $.querySelectorAll('.links_pages a'),
        buttons_product: $.querySelectorAll('.block__categories > ul > li > a'),
        // buttons_addToCart: $.querySelectorAll('.field_add-button'),
        button_home: $.querySelector('.block__brand > a'),
        button_cart: $.querySelector('.block__cart'),
        button_search: $.querySelector('.block__search > input[type=submit]'),
        value_search: $.querySelector('.block__search > input[type=search]')
    };

    t.data.lastItem = t('a[href*="' + window.location.href + '"]').addClass('active');

    // window.addEventListener('click', function (e) {
    //     t.n(t.data.lastItem).removeClass('active');
    //     t.data.lastItem = t('a[href*="' + window.location.href + '"]').addClass('active');
    // });

    
    t.ajax = function(params) {
        t.ajax.showLoader();
        // alert(params.url + params.data);
        let type = params.type || 'GET',
            dataType = params.dataType || 'html',
            async = params.async || true,
            success = params.success || function(msg) {
                    document.open();
                    document.write(msg);
                    document.close();
                },
            request = new XMLHttpRequest();
        request.open(type, params.url + ((type === 'GET' && params.data) ? params.data : ''), async);

        request.onreadystatechange = function() {
            if (this.readyState === 4) {
                t.ajax.hideLoader();
                if (this.status >= 200 && this.status < 400) {
                    let msg = this.responseText;
                    switch (dataType.toLowerCase()) {
                        case 'json': msg = JSON.parse(msg); break;
                    }
                    success(msg);
                } else {
                    alert('Ajax false!');
                }
            }
        };

        (type === 'POST' && params.data) ? request.send(params.data) : request.send();
        request = null;
    };

    t.ajax.loader = $.createElement('div');
    t.ajax.loader.setAttribute('class', 'ajax-loader');

    t.ajax.showLoader = function () {
        $.body.appendChild(t.ajax.loader);
    };

    t.ajax.hideLoader = function () {
        $.body.removeChild(t.ajax.loader);
    };

    t.replaceScript = function (oldSrc, newSrc) {
        let oldScript = $.querySelector('script[src="' + oldSrc + '"]');
        let newScript = $.createElement('script');
        // newScript.setAttribute("type","text/javascript");
        newScript.setAttribute("src", newSrc);
        oldScript.parentNode.replaceChild(newScript, oldScript);
    };

    t.updateScript = function (src) {
        t.replaceScript(src, src);
    };

    t.one = function(node, type, callback) {
        // create event
        node.addEventListener(type, function(e) {
            // remove event
            e.target.removeEventListener(e.type, arguments.callee);
            // call handler
            return callback(e);
        });
    };

    t.data.button_home.onclick = function (e) {
        e.preventDefault();
        History.pushState(null, 'Главная | Автомагазин', this.getAttribute('href'));
    };

    for (let i = 0; i < t.data.buttons_page.length; ++i) {
        t.data.buttons_page[i].onclick = function(e) {
            e.preventDefault();
            History.pushState(null, this.getAttribute('data-title'), this.getAttribute('href'));
            t.data.lastItem = t.n(this).addClass('active');
        };
    }

    for (let i = 0; i < t.data.buttons_product.length; ++i) {
        t.data.buttons_product[i].onclick = function(e) {
            e.preventDefault();
            History.pushState(null, this.innerText, this.getAttribute('href'));
            t.data.lastItem = t.n(this).addClass('active');
        };
    }

    function addToCart() {
        // alert('connect');
        let buttons_addToCart = $.querySelectorAll('.field_add-button');
        for (let i = 0; i < buttons_addToCart.length; ++i) {
            // buttons_addToCart[i].addEventListener('click', function(e) {
            buttons_addToCart[i].onclick = function(e) {
                e.preventDefault();
                t.ajax({
                    type: 'GET',
                    url: this.getAttribute('href'),
                    data: '?x=' + this.parentNode.querySelector('.quantity').value,
                    dataType: 'json',
                    success: function(msg) {
                        t.data.placeholder_productsCount.innerHTML = msg.productsCount;
                        t.data.placeholder_totalCost.innerHTML = msg.totalCost;
                    }
                });
            };
        }
    }
    addToCart();
    
    function removeFromCart() {
        let button_removeAllFromCart = $.querySelector('.block__content > table > tfoot .remove > a');
        if (button_removeAllFromCart) {
            button_removeAllFromCart.addEventListener('click', function (e) {
                e.preventDefault();
                t.ajax({
                    url: this.getAttribute('href'),
                    dataType: 'json',
                    success: function(msg) {
                        let body = $.querySelector('.block__content > table > tbody');
                        if (body) body.remove();
                        t.data.placeholder_productsCount.innerHTML = msg.productsCount;
                        t.data.placeholder_totalCost.innerHTML = msg.totalCost;
                        placeholder_cartProductsCount.innerHTML = 'x' + parseInt(msg.productsCount);
                        placeholder_cartTotalCost.innerHTML = msg.totalCost;
                    }
                })
            });
        }
        let placeholder_cartProductsCount = $.querySelector('.block__content .element_products-count'),
            placeholder_cartTotalCost = $.querySelector('.block__content .element_total-cost');
        let buttons_removeFromCart = $.querySelectorAll('.block__content > table > tbody .remove > a');
        for (let i = 0; i < buttons_removeFromCart.length; ++i) {
            buttons_removeFromCart[i].addEventListener('click', function(e) {
                let context = this;
                e.preventDefault();
                t.ajax({
                    url: this.getAttribute('href'),
                    dataType: 'json',
                    success: function(msg) {
                        context.parentNode.parentNode.remove();
                        t.data.placeholder_productsCount.innerHTML = msg.productsCount;
                        t.data.placeholder_totalCost.innerHTML = msg.totalCost;
                        placeholder_cartProductsCount.innerHTML = 'x' + parseInt(msg.productsCount);
                        placeholder_cartTotalCost.innerHTML = msg.totalCost;
                    }
                })
            })
        }
    }
    removeFromCart();

    t.data.button_cart.onclick = function(e) {
        e.preventDefault();
        History.pushState(null, 'Корзина', this.getAttribute('href'));
    };

    t.data.button_search.onclick = function(e) {
        e.preventDefault();
        History.pushState(null, 'Результат поиска', t.data.home + '/search/' +
            t.data.value_search.value.replace(/[^a-zA-Z0-9]/g, ''));
        t.ajax.updateScript = true;
    };

    History.Adapter.bind(window, 'statechange', function(){ // Note: We are using statechange instead of popstate
        t.data.lastItem.removeClass('active');
        t.ajax({
            type: 'GET',
            url: History.getState().url + '?part=1',
            success: function(msg) {
                t.data.placeholder_content.innerHTML = msg;
                // eval(t.data.placeholder_content.getElementsByTagName('script')[0].innerText);
                // eval('+' + addToCart.toString() + '()');
                addToCart();
                removeFromCart();

                // jQuery(t.data.placeholder_content).html(msg);
                // if (t.ajax.updateScript) t.updateScript(src);
                // t.updateScript(home + '/frontend/build/js/main.js');
            }
        })
    });

    // let removeProductFromCart
}(document);
