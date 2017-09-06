import _ from 'lodash';

class Validation {
    value = '';
    params = null;
    validate = null;

    constructor(params = {}, message = '', validate) {
        this.params = params;
        Object.prototype.toString.call(message) === '[object Function]'
            ? Object.defineProperty(this, 'message', {
                get: () => message(params)
            })
            : this.message = message;
        this.validate = validate;
    }
}

export function email(msg = 'please enter a valid email address') {
    return new Validation({}, msg, value => /(^$|^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$)/.test(value));
}

export function minLength(min, msg) {
    msg = msg || `the value is too short, try to enter at least ${min} symbols`;
    return new Validation({min}, msg, value => Array.isArray(value)
        ? value.length >= min
        : String(value).trim().length >= min);
}

export function maxLength(max, msg) {
    msg = msg || `the value is too long, try to enter less then ${max} symbols`;
    return new Validation({max}, msg, value => Array.isArray(value)
        ? value.length <= max
        : String(value).trim().length <= max);
}

export function beetwen() {
    
}

export function required(require = true, msg = 'this field is required') {
    return new Validation({require}, msg, value => require
        ? Array.isArray(value)
            ? value.length !== 0
            : Object(value) === value
                ? Object.keys(value).length !== 0
                : value !== null && value !== undefined && String(value).trim() !== ''
        : true);
}

export function sameAs(name, msg) {
    msg = msg || `this field must be the same as ${name}`;
    return new Validation({name}, msg, (value, fields) => {
        console.log(value, name, fields);
        let item = _.get(fields, name);
        return item ? value === item.value : null;
    });
}

export default {
    email,
    minLength,
    maxLength,
    required,
    sameAs
}
