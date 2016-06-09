// var $ = document;
+function($) {
    // let home = 'http://work/avtomagazin.dp.ua';
    // let home = 'http://avto.emis.net.ua';
    let home = window.location.protocol + '//' + window.location.host + '/avtomagazin.dp.ua';

    let placeholder_productsCount = $.querySelector('.block__cart > .field > p');
    let placeholder_totalCost = $.querySelector('.block__cart > .field > .total-cost');
    let placeholder_content = $.querySelector('.block__content');
    let buttons_addToCart = $.querySelectorAll('.field_add-button');
    let button_showCart = $.querySelector('.block__cart');
    let button_search = $.querySelector('.block__search > input[type=submit]');
    let value_search = $.querySelector('.block__search > input[type=search]');

    let lastUrl = window.location.pathname;
    let skip = false;
    var changeUrlListener = function() {
        let url = window.location.pathname;
        // console.log('CURR URL: ' + url);
        if (url != lastUrl && !t.ajax.skip) {
            lastUrl = url;
            t.ajax.skip = true;
            console.log('changed');
            // console.log('LAST URL: ' + lastUrl);
            content.views[content.data.view]();
        }
    };
    setInterval(changeUrlListener, 1000);

    var t = {};
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

    t.smartAjax = function(params, skip = true) {
        history.pushState({}, '', params.url);
        t.ajax.skip = skip;
        params.url = params.url + '?part=1';
        t.ajax(params);
    };

    t.setUrl = function(view, data) {
        content.data.view = view || home;
        history.pushState({}, '', home + '/' + view + (data ? '/' + data : ''));
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

    // This object contains content showing functions
    let content = { data: {} };
    content.views = {
        home: function (full) {
            alert('home');
        },
        cart: function (full) {
            t.smartAjax({
                type: 'GET',
                url: home + '/cart',
                success: function (msg) {
                    placeholder_content.innerHTML = msg;
                }
            });
        },
        search: function (full) {
            t.smartAjax({
                type: 'GET',
                url: home + '/search/' + value_search.value,
                success: function(msg) {
                    placeholder_content.innerHTML = msg;
                    t.updateScript(home + '/frontend/build/js/main.js');
                }
            })
        }
    };

    for (let i = 0; i < buttons_addToCart.length; ++i) {
        buttons_addToCart[i].addEventListener('click', function(e) {
            e.preventDefault();
            t.ajax({
                type: 'GET',
                url: e.target.parentNode.getAttribute('href'),
                data: '?x=' + e.target.parentNode.parentNode.querySelector('.quantity').value,
                dataType: 'json',
                success: function(msg) {
                    placeholder_productsCount.innerHTML = msg.productsCount;
                    placeholder_totalCost.innerHTML = msg.totalCost;
                }
            });
        });
    }

    button_showCart.addEventListener('click', function(e) {
        
        content.views.cart();
        // t.setUrl('cart');
    });

    button_search.addEventListener('click', function(e) {
        // skip = true;
        e.preventDefault();
        // history.pushState({}, 'TEST', home + '/search/' + value_search.value);
        // content.views.search();
        // t.setUrl('search', value_search.value);
        content.views.search();
    });

    // let removeProductFromCart
}(document);
