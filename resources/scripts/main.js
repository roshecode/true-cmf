"use strict";

// require('./global/plugins');
// require('./global/interceptors');
// require('./global/components');
// require('./global/directives');
// require('./global/polyfills');

import Vue from 'vue';
import router from './router';
import store from './store';
// import { sync } from 'vuex-router-sync';

// sync(store, router, { moduleName: 'Route' } );

new Vue({
    el: '#app',
    store,
    router,
    render: (h) => (<router-view />)
});
