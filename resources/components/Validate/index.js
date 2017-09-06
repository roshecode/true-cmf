import _ from 'lodash';
import Vue from 'vue';

function toFormDataName(path) {
    let array = path.map(value => `[${value}]`);
    array[0] = path[0];

    return array.join('');
}

function traverse(object, callback, path = []) {
    if (Object(object) === object && !Array.isArray(object)) {
        for (let [key, value] of Object.entries(object)) {
            let currentPath = path.slice();
            currentPath.push(key);
            traverse(value, callback, currentPath);
        }
    } else {
        callback(object, path);
    }
}

export default {
    name: 'Validate',

    // functional: true,

    render(createElement) {
        // if (children.length > 1) {
        //     Vue.util.warn('<ui-validate> can only be used on a single element.');
        // }
        // console.log(props);
        //
        // return children[0];

        if (this.$slots.default.length > 1) {
            Vue.util.warn('<ui-validate> can only be used on a single element.');
        }
        return this.$slots.default[0];
    },

    // renderError(h, error) {
    //     return h('pre', { style: { color: 'red' }}, error.stack);
    // },

    props: {
        validations: {
            type: Object,
            required: true
        }
    },

    provide() {
        let validator = {
            dirty: false,
            error: false,
            fields: {},

            checkValidity(data) {
                for (let [key, validate] of Object.entries(validator.fields)) {
                    validate.checkValidity(_.get(data, key));
                }

                return !validator.error;
            },

            reset() {
                for (let validate of Object.values(validator.fields)) {
                    validate.dirty = false;
                    validate.error = false;
                }
            }
        };

        traverse(this.validations, (validations, path) => {
            let key = toFormDataName(path);
            let validate = {
                dirty: false,
                error: false,
                message: '',

                checkValidity: (item) => {
                    validate.dirty = validator.dirty = true;
                    let value = item instanceof Event ? item.target.value : item,
                        error = validate.error = validator.error = false;
                    validator.fields[key].value = value;
                    validator.error = Object.values(validator.fields).some(validate => validate.error);
                    if (Array.isArray(validations)) {
                        for (let validation of validations) {
                            error = !validation.validate(value, validator.fields);
                            if (error) {
                                validate.error = validator.error = error;
                                validate.message = validation.message;
                                break;
                            }
                        }
                    } else {
                        error = !validations.validate(value, validator.fields);
                        validate.error = validator.error = error;
                        validate.message = validations.message;
                    }
                    return !error;
                }
            };

            validator.fields[key] = validate;
        });

        return {validator}
    }
}
