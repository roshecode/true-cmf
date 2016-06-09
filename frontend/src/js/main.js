// var $ = document;
+function($) {
    let home = 'http://work/avtomagazin.dp.ua';
    // let home = 'http://avto.emis.net.ua';

    let placeholder_productsCount = $.querySelector('.block__cart > .field > p');
    let placeholder_totalCost = $.querySelector('.block__cart > .field > .total-cost');
    let buttons_addToCart = $.querySelectorAll('.field_add-button');
    let button_showCart = $.querySelector('.block__cart');
    let block_content = $.querySelector('.block__content');
    let button_search = $.querySelector('.block__search > input[type=submit]');
    let value_search = $.querySelector('.block__search > input[type=search]');

    var t = {};
    t.ajax = function(params) {
        // alert(params.url + params.data);
        let type = params.type || 'GET',
            dataType = params.dataType || 'html',
            async = params.async || true,
            request = new XMLHttpRequest();
        request.open(type, params.url + ((type === 'GET' && params.data) ? params.data : ''), async);

        request.onreadystatechange = function() {
            if (this.readyState === 4) {
                if (this.status >= 200 && this.status < 400) {
                    let msg = this.responseText;
                    switch (dataType.toLowerCase()) {
                        case 'json': msg = JSON.parse(msg); break;
                    }
                    params.success(msg);
                } else {
                    alert('Ajax false!');
                }
            }
        };

        // request.send(data.send); // POST
        // request.send(); // GET
        (type === 'POST' && params.data) ? request.send(params.data) : request.send();
        request = null;
    };

    t.fullAjax = function(params, full) {
        // let newParams = Object.assign(params, {
        //     url: params.url + '&'
        // });
        // params.part = params.part || true;
        // params.url = params.url + params.part ? '&part=1' : '';
        full = full || false;
        params.url = params.url + (full ? '' : '?part=1');
        t.ajax(params);
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

    let content = {};
    content.showCart = function (full) {
        t.fullAjax({
            type: 'GET',
            url: home + '/cart',
            success: function (msg) {
                // $.querySelector('body').insertAdjacentHTML('afterbegin', e.responseText);
                block_content.innerHTML = msg;
            }
        }, full);
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
        content.showCart();
    });

    button_search.addEventListener('click', function(e) {
        e.preventDefault();
        history.pushState({}, 'TEST', home + '/search/' + value_search.value);
        t.ajax({
            type: 'GET',
            url: home + '/search/' + value_search.value,
            success: function(msg) {
                // $.body.innerHTML = e.responseText;
                block_content.innerHTML = msg;
                t.updateScript(home + '/frontend/build/js/main.js');
            }
        })
    });

    // let removeProductFromCart
}(document);
