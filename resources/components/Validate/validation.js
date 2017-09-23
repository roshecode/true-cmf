export default class Validation {
    value = '';
    params = null;
    validate = null;
    message = 'Invalid!';

    constructor(validate, params = {}) {
        this.params = params;
        this.validate = validate;
    }

    setMessage(message) {
        Object.prototype.toString.call(message) === '[object Function]'
            ? Object.defineProperty(this, 'message', {
                get: () => message(this.params)
            })
            : this.message = message;

        return this;
    }
}
