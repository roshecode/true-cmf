import Vue from '../main';
import VueRouter from 'vue-router';
import store from '../store';
import { prepareViewsInRoutes } from '../utils';

new Vue({
    el: '#app',
    store,
    router: new VueRouter({
        routes: prepareViewsInRoutes([]),
        linkActiveClass: 'active',
        mode: 'history',
        scrollBehavior (to, from, savedPosition) {
            if (savedPosition) {
                return savedPosition
            } else {
                return { x: 0, y: 0 }
            }
        }
    }),
    render: (h) => (<router-view />)
});