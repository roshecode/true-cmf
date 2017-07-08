import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';
import routes from '../../../app/Routes/front/admin';
import { prepareViewsInRoutes } from '../utils';

new Vue({
    el: '#app',
    metaInfo: {
        titleTemplate: '%s | True CMF admin'
    },
    store,
    router: new VueRouter({
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
    }),
    render: (h) => (<router-view />)
});