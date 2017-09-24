import Validation from '../validation';

export default function sameAsMe(name) {
    return new Validation(
        (value, fields) => {
            const item = fields[name];

            if (item.value) {
                item.checkValidity(item.value);
            }

            return true;
        },
    );
}
