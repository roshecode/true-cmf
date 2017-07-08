// Convert

function loadView(path) {
    return typeof path === 'string' ? () => import(`../views/dynamic/${path}`) : path;
}

export function prepareViewsInRoutes(items) {
    for (let item of items) {
        if (item.component) {
            let path = item.component;
            item.component = loadView(path);
        } else if (item.components) {
            let components = item.components;
            for (let component in components) {
                if (components.hasOwnProperty(component)) {
                    let path = components[component];
                    item.components[component] = loadView(path);
                }
            }
        }
        if (item.children) {
            prepareViewsInRoutes(item.children);
        }
    }
    return items;
}


// Get scrollable parent element

function getScrollableParentRecursion(node) {
    let computedStyle = window.getComputedStyle(node);

    return /(auto|scroll)/.test(
        computedStyle.getPropertyValue('overflow') +
        computedStyle.getPropertyValue('overflow-y') +
        computedStyle.getPropertyValue('overflow-x')
    ) ? node : node.parentNode === null ? false : getScrollableParentRecursion(node.parentNode);
}

export const getScrollableParent = (node) => (node instanceof HTMLElement || node instanceof SVGElement)
    ? getScrollableParentRecursion(node) || document.scrollingElement || document.documentElement
    : undefined;
