export default class Validation {
    name = 'unknown';
    value;
    params;
    valid = () => true;
    dirty = false;
    error = false;
    message = 'Invalid!';

    constructor(valid, params = {}) {
        this.params = params;
        this.valid = valid;
    }

    setName(name) {
        this.name = name;

        return this;
    }

    setMessage(message) {
        Object.prototype.toString.call(message) === '[object Function]'
            ? Object.defineProperty(this, 'message', {
                get: () => message({...this.params, name: this.name}),
            })
            : this.message = message;

        return this;
    }

    checkValidity(item, fields = {}) {
        this.dirty = true;

        return !(this.error = !this.valid(this.value = item instanceof Event ? item.target.value : item, fields));
    }

    reset() {
        this.dirty = this.error = false;
        this.value = null;
    }
}
