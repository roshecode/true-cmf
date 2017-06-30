// import auth from './api/auth';

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

// router.beforeEach((to, from, next) => {
//     if (!auth.hasToken() && !to.meta.guest) {
//         next({
//             name: 'login',
//             query: {
//                 redirect: to.name,
//             },
//         });
//     } else next();
// });
