import Validation from '../validation';

export default function maxLength(max) {
    return new Validation(
        value => Array.isArray(value)
            ? value.length <= max
            : String(value).trim().length <= max,
        {max}
    ).setMessage(`the value is too long, try to enter less then ${max} symbols`);
}
