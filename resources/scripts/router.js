import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from '../../app/Routes';
// import auth from './api/auth';

Vue.use(VueRouter);

function loadView(path) {
    return typeof path === 'string' ? () => import(`../../app/Views/${path}`) : path;
}

function prepareViewsInRoutes(items) {
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

const router = new VueRouter({
    routes: prepareViewsInRoutes(routes),
    linkActiveClass: 'active',
    mode: 'history',
    scrollBehavior (to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    }
});

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

export default router;
