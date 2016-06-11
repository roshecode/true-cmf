Object.prototype.assign = function () {
    for (let i = 0; i < arguments.length; ++i) {
        Object.assign(this, arguments[i])
    }
    return this;
};

+function ($) {
    let q = function(selector, context) {
        return new q.fn.init(selector, context);
    };
    q.extend = q.assign;
    q.expr = {
        checkClass: /^$/,
        htmlStringWrapper: /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/
    };
    q.assign({
        // body: q($.body),
        create: (item) => {
            return q($.createElement(item));
        },
        checkClassesRegExp: (className) => {
            return new RegExp("(^|\\s)" + className + "(\\s|$)", "g");
        },
        replaceScript: (oldSrc, newSrc, type) => {
            let oldScript = $.querySelector('script[src="' + oldSrc + '"]');
            let newScript = $.createElement('script');
            type ? newScript.setAttribute("type",type) : 0;
            newScript.setAttribute("src", newSrc);
            oldScript.parentNode.replaceChild(newScript, oldScript);
        },
        updateScript: (src) => {
            q.replaceScript(src, src);
        },
        ajax: (params) => {
            q.ajax.toggleLoader();
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
                    q.ajax.toggleLoader();
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
        }
    });
    q.fn = q.prototype = {
    // let tQuery = function trueQuery() {};
    // tQuery = new tQuery();
    // tQuery.assign({
        version: '0.0.1',
        length: 0,
        constructor: q,
        now: Date.now,
        init: function(selector, context) {
            if (!selector) {
                return this;
            }
            if (typeof selector === 'string') {
                this.node = context ? context.querySelector(selector) : $.querySelector(selector);
                this.length = 1;
            } else if (selector.nodeType) {
                this.node = selector;
                this.length = 1;
            }
            return this;
        },
        emptyClass: function () {
            return !this.node.className;
        },
        hasClass: function (c) {
            return q.checkClassesRegExp(c).test(this.node.className);
        },
        addClass: function(c) {
            if (this.emptyClass(c)) {
                this.node.className = c;
                return this;
            }
            if (this.hasClass(c)) return this;
            this.node.className = (this.node.className + " " + c).replace(/\s+/g, " ").replace(/(^ | $)/g, "");
            return this;
        },
        removeClass: function(c) {
            this.node.className = this.node.className.replace(q.checkClassesRegExp(c), "$1")
                .replace(/\s+/g, " ").replace(/(^ | $)/g, "");
            return this;
        },
        toggleClass: function(c) {
            this.hasClass(c) ? this.removeClass(c) : this.addClass(c);
            return this;
        },
        append: function (item) {
            if (typeof item === 'string') {
                // let matches = q.expr.htmlStringWrapper.exec(item);
                // console.dir(matches);
                let div = q.create('div');
                div.node.innerHTML = item;
                this.node.appendChild(div.node.children[0]);
            } else if (item.nodeType) {
                this.node.appendChild(item);
            } else if (typeof item === 'object') {
                this.node.appendChild(item.node);
                item.exist = true;
            }
            return this;
        },
        remove: function (child) {
            if (child) {
                this.node.removeChild(child);
                // child.exist = false;
            } else {
                this.node.remove();
                this.exist = false;
            }
            return this;
        },
        click: function (fun) {
            if (this.node) {
                this.node.addEventListener('click', fun);
            }
        },
        css: function () {

        }
    };
    // q.fn = q.prototype = tQuery;
    q.fn.init.prototype = q.fn;
    // q.fn = q.prototype = tQuery;
    // q.fn.init.prototype = tQuery;

    q.ajax.loader = q.create('div').addClass('ajax-loader');
    q.ajax.toggleLoader = function () {
        q.ajax.loader.exist ?
            q.ajax.loader.remove() :
            q($.body).append(q.ajax.loader);
    };
    window.q = q;

    q.fn.validation = function () {
        console.log(this);
    };

    // q('header').validation();

    // let test = q('.block__content');
    // q('.block__brand').node.style.background = 'red';
    // test.node.style.background = 'green';
    // test.toggleClass('block__content');

}(document);
