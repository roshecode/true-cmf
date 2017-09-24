import Validation from '../validation';

export default function sameAs(name) {
    let _fields = {}, _name = name;

    return new Validation(
        (value, fields) => {
            _fields = fields;
            const item = fields[name];

            return item ? value === item.value : false;
        },
        {name}
    ).setMessage(({name}) => {
        return `the ${name} must be the same as ` + (_fields[_name] ? _fields[_name].name : 'unknown');
    });
}
