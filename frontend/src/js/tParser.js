;+function(){
    //var div = document.createElement('div');
    //var state = {
    //    name: '',
    //    markup: ''
    //};
    // 1) i=0; '<'; state='OPEN';
    // 2) i=1; 'u'; getName(1[>]); text='ul class="good"';
    // 3) i=16;'>'; state='TEXT';
    // 4) i=17;'<'; state='OPEN';
    // 5) i=18;'l'; getName(1[>]); text='li';
    // 6) i=20;'>'; state='TEXT';
    // 7) i=21;'1'; getName(0[<]); text='1';

    //console.log("'".charCodeAt(0));
    //process.exit();

    // " - 34
    // ' - 39

    function isNull(ctx) {
        if (ctx == null) {
            throw new TypeError('Context is null or not defined');
        }
    }
    Object.prototype.fill = function(fillVal, propArr) {
        for (var i = 0; i < propArr.length; ++i) {
            this[propArr[i]] = fillVal;
        }
    };
    Object.prototype.fillDeep = function(fillVal, propArr) {
        isNull(this);
        for (var i = 0; i < propArr.length; ++i) {
            typeof propArr[i] != 'object' ? this[propArr[i]] = fillVal : this.fill(fillVal, propArr[i]);
        }
    };
    Object.prototype.flip = function(obj) {
        //isNull(this);
        console.log(this);
        if (this == null) {
            throw new TypeError('this is null or not defined');
        }
        obj = obj || {};
        for (i in this) {
            if (this.hasOwnProperty(i)) {
                typeof this[i] != 'object' ? obj[this[i]] = i : obj.fill(i, this[i]);
            }
        }
        return obj;
    };

    var TEST;
    try {
        //Object.prototype.flip.call();
        Object.prototype.flip.bind(TEST);
        TEST.flip();
    }
    catch (e) {
        console.log(e.message);
    }
    process.exit();

    var html = ''
        +'<ul class=list title="name" select>'
        +'<li>1</li>'
        +'<li>2</li>'
        +'<li>3</li>'
        +'<li>4</li>'
        +'<li/>'
        +'</ul>';

    var length = html.length;
    var i = 0;

    function currentTokenIs(chArr) {
        var ch = html[i];
        for (var j = 0; j < chArr.length; ++j) {
            if (ch == chArr[j]) {
                return true;
            }
        }
        return false;
    }
    var getVal = function(nextTokensArr) {
        var str = '';
        while (!currentTokenIs(nextTokensArr) && i < length) {
            str += html[i];
            ++i;
        }
        if (str) --i;
        return str;
    };

    var currentToken = '';
    var out;
    var nextTokens = [];
    var prevToken;

    var tokens = {
        NODE_CREATE:        '<',
        NODE_ADD_ATTR:      ' ',
        ATTR_SET_VALUE:     '=',
        ATTR_SET_STRING:    ['"', "'"],
        NODE_ADD_TEXT:      '>',
        NODE_CLOSE:         '/'
    };
    var tokensNames = Object.keys(tokens);
    var actions = tokens.flip();

    //:NODE_CREATE
    //  NODE_ADD_ATTR
    //  NODE_CLOSE
    //  NODE_ADD_TEXT
    //:NODE_CLOSE
    //  NODE_ADD_TEXT
    //:NODE_ADD_TEXT
    //  NODE_CREATE
    //:NODE_ADD_ATTR
    //  ATTR_SET_VALUE
    //  NODE_ADD_ATTR
    //  NODE_ADD_TEXT
    //:ATTR_SET_VALUE
    //  NODE_ADD_ATTR
    //  NODE_ADD_TEXT
    //:ATTR_SET_STRING[0]
    //  ATTR_SET_STRING[0]
    //:ATTR_SET_STRING[1]
    //  ATTR_SET_STRING[1]

    for (i = 0; i < length; ++i) {
        currentToken = html[i];
        nextTokens =
            currentToken == tokens.NODE_CREATE ?
                [
                    tokens.NODE_ADD_ATTR,
                    tokens.NODE_CLOSE,
                    tokens.NODE_ADD_TEXT
                ] :
                currentToken == tokens.NODE_CLOSE ?
                    [
                        tokens.NODE_ADD_TEXT
                    ] :
                    currentToken == tokens.NODE_ADD_TEXT ?
                        [
                            tokens.NODE_CREATE
                        ] :
                        currentToken == tokens.NODE_ADD_ATTR ?
                            [
                                tokens.ATTR_SET_VALUE,
                                tokens.NODE_ADD_ATTR,
                                tokens.NODE_ADD_TEXT
                            ] :
                            currentToken == tokens.ATTR_SET_VALUE ?
                                [
                                    tokens.NODE_ADD_ATTR,
                                    tokens.NODE_ADD_TEXT
                                ] :
                                currentToken == tokens.ATTR_SET_STRING[0] ?
                                    [
                                        tokens.ATTR_SET_STRING[0]
                                    ] :
                                    currentToken == tokens.ATTR_SET_STRING[1] ?
                                        [
                                            tokens.ATTR_SET_STRING[1]
                                        ] : getVal(nextTokens);
        typeof nextTokens == 'string' ? console.log(actions[prevToken] + ': ' + nextTokens) : false;
        //console.log(actions[prevToken] + ': ' + nextTokens);
        prevToken = currentToken;
    }




    process.exit();
    for (i = 0; i < length; ++i) {
        switch (html[i]) {
            case tokens.NODE_CREATE:
                currentToken = tokensNames[0];
                nextTokens = [
                    tokens.NODE_ADD_ATTR,   // ' '
                    tokens.NODE_CLOSE,      // '/'
                    tokens.NODE_ADD_TEXT    // '>'
                ];
                continue;
            case tokens.NODE_CLOSE:
                currentToken = tokensNames[5];
                nextTokens = [
                    tokens.NODE_ADD_TEXT
                ];
                continue;
            case tokens.NODE_ADD_ATTR:
                currentToken = tokensNames[1];
                nextTokens = [
                    tokens.ATTR_SET_VALUE,
                    tokens.NODE_ADD_ATTR,
                    tokens.NODE_ADD_TEXT
                ];
                continue;
            case tokens.ATTR_SET_VALUE:
                //var nextCh = tokens.SET_ATTR_STRING.indexOf(html[i + 1]);
                //if (~nextCh) {
                //    ++i;
                //    currentToken = tokensNames[3];
                //    nextToken = [
                //        tokens.SET_ATTR_STRING[nextCh]
                //    ];
                //    back = 0;
                //    continue;
                //}
                currentToken = tokensNames[2];
                nextTokens = [
                    tokens.NODE_ADD_ATTR,
                    tokens.NODE_ADD_TEXT
                ];
                continue;
            case tokens.ATTR_SET_STRING[0]:
                nextTokens = [
                    tokens.ATTR_SET_STRING[0]
                ];
                continue;
            case tokens.ATTR_SET_STRING[1]:
                nextTokens = [
                    tokens.ATTR_SET_STRING[1]
                ];
                continue;
            case tokens.NODE_ADD_TEXT: //>
                currentToken = tokensNames[4];
                nextTokens = [
                    tokens.NODE_CREATE
                ];
                continue;
            default:
                //if (currentToken == 'NODE_CLOSE') --i;
                out = getVal(nextTokens);
                break;
            //continue;
        }
        console.log(currentToken + ': ' + out);
        //if (currentToken == 'SET_ATTR_STRING') ++i;
    }
}();
