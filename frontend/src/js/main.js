// var $ = document;
+function($) {
    let home = 'http://work/avtomagazin.dp.ua';
    // let home = 'http://avto.emis.net.ua';

    let t = {};
    t.ajax = function(params) {
        // alert(params.url + params.data);
        // params.data = params.data || '';
        let request = new XMLHttpRequest();
        request.open(params.type, params.url + ((params.type === 'GET' && params.data) ? params.data : ''), true);

        request.onreadystatechange = function() {
            if (this.readyState === 4) {
                if (this.status >= 200 && this.status < 400) {
                    alert(this.responseText);
                    params.success(this);
                } else {
                    alert('Ajax false!');
                }
            }
        };

        // request.send(data.send); // POST
        // request.send(); // GET
        (params.type === 'POST' && params.data) ? request.send(params.data) : request.send();
        request = null;
    };

    function change(oldFileName, fileName) {
        // fileName, oldFileName
        var scripts = document.getElementsByTagName('script');
        for (var i = scripts.length; i--;) {
            console.log(scripts[i].getAttribute('src'));
            if (scripts[i] && scripts[i].getAttribute('src')!=null && scripts[i].getAttribute('src').indexOf(oldFileName)!=-1){
                console.log('found!');
                var newScript = document.createElement('script');
                newScript.setAttribute("type","text/javascript");
                newScript.setAttribute("src", fileName);
                scripts[i].parentNode.replaceChild(newScript, scripts[i]);
            }
        }
    }

    t.updateScript = function (src) {
        let oldScript = $.querySelector('script[src="' + src + '"]');
        if (oldScript) console.log('found!');
        let newScript = $.createElement('script');
        // newScript.setAttribute("type","text/javascript");
        newScript.setAttribute("src", src);
        oldScript.parentNode.replaceChild(newScript, oldScript);
    };

    let productsCount = $.querySelector('.block__cart > .field > p');
    let totalCost = $.querySelector('.block__cart > .field > .total-cost');
    let addToCartButtons = $.querySelectorAll('.field_add-button');
    // let quantity = $.querySelector('.add-to-cart .quantity');
    for (let i = 0; i < addToCartButtons.length; ++i) {
        addToCartButtons[i].addEventListener('click', function(e) {
        // addToCartButtons[i].onclick = function(e) {
            e.preventDefault();
            t.ajax({
                type: 'GET',
                url: e.target.parentNode.getAttribute('href'),
                data: '?x=' + e.target.parentNode.parentNode.querySelector('.quantity').value,
                success: function(msg) {
                    // productsCount.innerHTML = '';
                    // alert(this.responseText);
                    // field.insertAdjacentHTML('afterbegin', this.responseText);
                    let data = JSON.parse(msg.responseText);
                    productsCount.innerHTML = data.productsCount;
                    totalCost.innerHTML = data.totalCost;
                    // addToCartEvents();
                }
            });
        });
    }

    let showCartButton = $.querySelector('.block__cart');
    let blockContent = $.querySelector('.block__content');
    showCartButton.addEventListener('click', function(e) {
    // showCartButton.onclick = function(e) {
        t.ajax({
            type: 'GET',
            url: home + '/cart',
            success: function (msg) {
                // alert(e.responseText);
                // $.querySelector('body').insertAdjacentHTML('afterbegin', e.responseText);
                blockContent.innerHTML = msg.responseText;
            }
        });
    });

    // var searchButton = $.querySelector('.block__search > input[type=submit]');
    let searchButton = $.querySelector('.block__search > input[type=submit]');
    let searchValue = $.querySelector('.block__search > input[type=search]');
    searchButton.addEventListener('click', function(e) {
    // searchButton.onclick = function(e) {
        e.preventDefault();
        // history.pushState({}, 'TEST', '/search/' + );
        history.pushState({}, 'TEST', home + '/search/' + searchValue.value);
        t.ajax({
            type: 'GET',
            url: home + '/search/' + searchValue.value,
            success: function(msg) {
                // $.body.innerHTML = e.responseText;
                blockContent.innerHTML = msg.responseText;
                // change(home + '/frontend/build/js/main.js', home + '/frontend/build/js/main.js');
                t.updateScript(home + '/frontend/build/js/main.js');
            }
        })
    });

    // let removeProductFromCart
// }.call(document);
}(document);
