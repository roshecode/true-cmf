import Validation from '../validation';

export default function required(require = true) {
    return new Validation(
        value => require
            ? Array.isArray(value)
                ? value.length !== 0
                : Object(value) === value
                    ? Object.keys(value).length !== 0
                    : value !== null && value !== undefined && String(value).trim() !== ''
            : true,
        {require}
    ).setMessage(({name}) => `the ${name} is required`);
}
