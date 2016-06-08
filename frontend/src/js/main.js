+function($) {
    function sendAjax(data) {
        let request = new XMLHttpRequest();
        request.open(data.method, data.url + data.send, true);

        request.onreadystatechange = function() {
            if (this.readyState === 4) {
                if (this.status >= 200 && this.status < 400) {
                    let productsCount = $.querySelector('.block__cart > .field > p');
                    let totalCost = $.querySelector('.block__cart > .field > .total-cost');
                    productsCount.innerHTML = '';
                    alert(this.responseText);
                    // field.insertAdjacentHTML('afterbegin', this.responseText);
                    let data = JSON.parse(this.responseText);
                    productsCount.innerHTML = data.productsCount;
                    totalCost.innerHTML = data.totalCost;
                } else {
                    alert('bad');
                }
            }
        };

        // request.send(data.send); // POST
        request.send(); // GET
        request = null;
    }

    let addToCartButtons = $.querySelectorAll('.field_add-button');
    for (let i = 0; i < addToCartButtons.length; ++i) {
        addToCartButtons[i].addEventListener('click', function(e) {
            e.preventDefault();
            sendAjax({
                method: 'GET',
                url: e.target.parentNode.getAttribute('href'),
                send: '?x=' + e.target.parentNode.parentNode.querySelector('.quantity').value
            });
        });
    }
}(document);
