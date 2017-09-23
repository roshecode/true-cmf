import Validation from './validation';

export function email() {
    return new Validation(
        value => /(^$|^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$)/.test(value),
    ).setMessage('please enter a valid email address');
}

export function minLength(min) {
    return new Validation(
        value => Array.isArray(value)
            ? value.length >= min
            : String(value).trim().length >= min,
        {min}
    ).setMessage(`the value is too short, try to enter at least ${min} symbols`);
}

export function maxLength(max) {
    return new Validation(
        value => Array.isArray(value)
            ? value.length <= max
            : String(value).trim().length <= max,
        {max}
    ).setMessage(`the value is too long, try to enter less then ${max} symbols`);
}

export function beetwen() {
}

export function required(require = true) {
    return {
        params: {required},
        validate: value => require
            ? Array.isArray(value)
                ? value.length !== 0
                : Object(value) === value
                    ? Object.keys(value).length !== 0
                    : value !== null && value !== undefined && String(value).trim() !== ''
            : true,
        message: 'this field is required',
    };
    // return new Validation(
    //     value => require
    //         ? Array.isArray(value)
    //             ? value.length !== 0
    //             : Object(value) === value
    //                 ? Object.keys(value).length !== 0
    //                 : value !== null && value !== undefined && String(value).trim() !== ''
    //         : true,
    //     {require}
    // ).setMessage('this field is required');
}

export function sameAs(name) {
    return new Validation(
        (value, fields) => {
            let item = fields[name];
            return item ? value === item.value : null;
        },
        {name}
    ).setMessage(`this field must be the same as ${name}`);
}

export default {
    email,
    minLength,
    maxLength,
    required,
    sameAs
}
