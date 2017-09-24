export function getByPath(object, path) {
    return path.reduce((objectValue, pathValue) => {
        return objectValue[pathValue];
    }, object);
}

export function traverse(object, callback, path = []) {
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
    getByPath,
    traverse,
}
