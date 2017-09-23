import Vue from 'vue';
import { getByPath, traverse } from './utils';

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
                    validate.checkValidity(getByPath(data, key.split('.')));
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
            const key = path.join('.');
            let validate = {
                dirty: false,
                error: false,
                message: '',

                checkValidity: (item) => {
                    validate.dirty = validator.dirty = true;
                    let value = item instanceof Event ? item.target.value : item,
                        error;
                    validator.fields[key].value = value;
                    validator.error = Object.values(validator.fields).some(validate => validate.error);

                    if (!Array.isArray(validations)) {
                        validations = [validations];
                    }

                    for (let validation of validations) {
                        error = !validation.validate(value, validator.fields);
                        if (error) {
                            validate.error = validator.error = true;
                            validate.message = validation.message;
                            this.$emit('update:v', validator);
                            break;
                        }
                    }

                    return !error;
                }
            };

            validator.fields[key] = validate;
        });

        return {validator}
    }
}
