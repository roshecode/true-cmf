import Validation from './validation';
import { getByPath, traverse } from './utils';

export default class Validator
{
    dirty = false;
    error = false;
    fields = {};

    constructor(validations = {}) {
        traverse(validations, (validations, path) => {
            const key = path.join('.'),
                field = this.fields[key] = new Validation(value => {
                    if (!Array.isArray(validations)) {
                        validations = [validations];
                    }

                    for (let validation of validations) {
                        if (validation.name === 'unknown') {
                            validation.setName(key);
                        }

                        if (!validation.checkValidity(value, this.fields)) {
                            field.setMessage(validation.message);

                            return false;
                        }
                    }

                    return true;
                })//.setName(name || key);
        });
    }

    checkValidity(data) {
        this.dirty = true;
        let error = false;

        for (let [key, validate] of Object.entries(this.fields)) {
            if (!validate.checkValidity(getByPath(data, key.split('.')))) {
                error = true;
            }
        }

        return error;
    }

    reset() {
        for (let validate of Object.values(this.fields)) {
            validate.dirty = false;
            validate.error = false;
        }
    }
}
