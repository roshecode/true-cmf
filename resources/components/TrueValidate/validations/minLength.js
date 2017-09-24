import Validation from '../validation';

export default function minLength(min) {
    return new Validation(
        value => Array.isArray(value)
            ? value.length >= min
            : value ? String(value).trim().length >= min : true,
        {min}
    ).setMessage(`the value is too short, try to enter at least ${min} symbols`);
}
