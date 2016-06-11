+function($) {
    // let home = 'http://work/avtomagazin.dp.ua';
    // let home = 'http://avto.emis.net.ua';

    q.data = {
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
        value_search: $.querySelector('.block__search > input[type=search]'),
        button_callback: q('.button_callback')
        // button_callbackSend: q('.button_callback-send')
    };

    q.data.lastItem = q('a[href*="' + window.location.href + '"]').addClass('active');

    q.data.button_home.onclick = function (e) {
        e.preventDefault();
        History.pushState(null, 'Главная | Автомагазин', this.getAttribute('href'));
    };

    for (let i = 0; i < q.data.buttons_page.length; ++i) {
        q.data.buttons_page[i].onclick = function(e) {
            e.preventDefault();
            History.pushState(null, this.getAttribute('data-title'), this.getAttribute('href'));
            q.data.lastItem = q(this).addClass('active');
        };
    }

    for (let i = 0; i < q.data.buttons_product.length; ++i) {
        q.data.buttons_product[i].onclick = function(e) {
            e.preventDefault();
            History.pushState(null, this.innerText, this.getAttribute('href'));
            q.data.lastItem = q(this).addClass('active');
        };
    }

    q.data.button_callback.click(function (e) {
        e.preventDefault();
        q.ajax({
            url: q.data.home + '/callback?part=1',
            success: (msg) => {
                q($.body).append(msg);
                sendCallback();
            }
        });
    });

    function sendCallback() {
        q('.block_callback > header > i').click(function() {
            q('.block_callback-wrapper').remove();
        });
        q('.button_callback-send').click(function (e) {
            e.preventDefault();
            q.ajax({
                type: 'POST',
                url: q.data.home + '/callback/send',
                data: new FormData(q('.block_callback > form').node),
                success: (msg) => {
                    // q($.body).append(msg);
                    if (msg == 'success') q('.block_callback-wrapper').remove();
                }
            });
        });
    }
    sendCallback();

    function addToCart() {
        // alert('connect');
        let buttons_addToCart = $.querySelectorAll('.field_add-button');
        for (let i = 0; i < buttons_addToCart.length; ++i) {
            // buttons_addToCart[i].addEventListener('click', function(e) {
            buttons_addToCart[i].onclick = function(e) {
                e.preventDefault();
                q.ajax({
                    type: 'GET',
                    url: this.getAttribute('href'),
                    data: '?x=' + this.parentNode.querySelector('.quantity').value,
                    dataType: 'json',
                    success: function(msg) {
                        q.data.placeholder_productsCount.innerHTML = msg.productsCount;
                        q.data.placeholder_totalCost.innerHTML = msg.totalCost;
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
                q.ajax({
                    url: this.getAttribute('href'),
                    dataType: 'json',
                    success: function(msg) {
                        let body = $.querySelector('.block__content > table > tbody');
                        if (body) body.remove();
                        q.data.placeholder_productsCount.innerHTML = msg.productsCount;
                        q.data.placeholder_totalCost.innerHTML = msg.totalCost;
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
                q.ajax({
                    url: this.getAttribute('href'),
                    dataType: 'json',
                    success: function(msg) {
                        context.parentNode.parentNode.remove();
                        q.data.placeholder_productsCount.innerHTML = msg.productsCount;
                        q.data.placeholder_totalCost.innerHTML = msg.totalCost;
                        placeholder_cartProductsCount.innerHTML = 'x' + parseInt(msg.productsCount);
                        placeholder_cartTotalCost.innerHTML = msg.totalCost;
                    }
                })
            })
        }
    }
    removeFromCart();

    q.data.button_cart.onclick = function(e) {
        e.preventDefault();
        History.pushState(null, 'Корзина', this.getAttribute('href'));
    };

    q.data.button_search.onclick = function(e) {
        e.preventDefault();
        History.pushState(null, 'Результат поиска', q.data.home + '/search/' +
            q.data.value_search.value.replace(/[^a-zA-Z0-9]/g, ''));
        q.ajax.updateScript = true;
    };

    History.Adapter.bind(window, 'statechange', function(){ // Note: We are using statechange instead of popstate
        q.data.lastItem.removeClass('active');
        q.ajax({
            type: 'GET',
            url: History.getState().url + '?part=1',
            success: function(msg) {
                q.data.placeholder_content.innerHTML = msg;
                addToCart();
                removeFromCart();
                sendCallback();
            }
        })
    });

    // let removeProductFromCart
}(document);
