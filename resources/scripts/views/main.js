import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../../store';
import routes from '../../../app/Routes/front/main';
import { prepareViewsInRoutes } from '../utils';

new Vue({
    el: '#app',
    metaInfo: {
        titleTemplate: '%s | Your company'
    },
    store,
    router: new VueRouter({
        routes: prepareViewsInRoutes(routes, path => import(`../../views/${path}`)),
        linkActiveClass: 'active',
        mode: 'history',
        scrollBehavior(to, from, savedPosition) {
            return savedPosition || { x: 0, y: 0 };
        }
    }),
    render: (h) => (<router-view />)
});
