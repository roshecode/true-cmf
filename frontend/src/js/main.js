// var $ = document;
var t = {};
+function($) {
    // let home = 'http://work/avtomagazin.dp.ua';
    // let home = 'http://avto.emis.net.ua';

    t.data = {
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
    
    t.ajax = function(params) {
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
            History.pushState(null, this.innerText, this.getAttribute('href'));
        };
    }

    for (let i = 0; i < t.data.buttons_product.length; ++i) {
        t.data.buttons_product[i].onclick = function(e) {
            e.preventDefault();
            History.pushState(null, this.innerText, this.getAttribute('href'));
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
        t.ajax({
            type: 'GET',
            url: History.getState().url + '?part=1',
            success: function(msg) {
                t.data.placeholder_content.innerHTML = msg;
                // eval(t.data.placeholder_content.getElementsByTagName('script')[0].innerText);
                // eval('+' + addToCart.toString() + '()');
                addToCart();

                // jQuery(t.data.placeholder_content).html(msg);
                // if (t.ajax.updateScript) t.updateScript(src);
                // t.updateScript(home + '/frontend/build/js/main.js');
            }
        })
    });

    // let removeProductFromCart
}(document);
