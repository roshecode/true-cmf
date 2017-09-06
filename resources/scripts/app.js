// require('./global/plugins');
// require('./global/interceptors');
// require('./global/components');
// require('./global/directives');
// require('./global/polyfills');
// require('./animation');

import Vue from 'vue'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import VueResourceCaseConverter from 'vue-resource-case-converter';
import VueMeta from 'vue-meta'
import VueTouch from 'vue-touch'


Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(VueResource);

// Vue.http.headers.common['Authorization'] = 'Basic YXBpOnBhc3N3b3Jk';

Vue.http.options.emulateHTTP = true;
let urlFilterRegExp = new RegExp(`${Vue.http.options.root = 'api'}/`);
Vue.use(VueResourceCaseConverter, {
    convert: false,

    responseUrlFilter(url) {
        if (urlFilterRegExp.test(url)) {
            return {
                convert: true
            };
        }
    },

    requestUrlFilter(url) {
        if (urlFilterRegExp.test(url)) {
            return {
                convert: true
            };
        }
    }
});
Vue.use(VueMeta);
Vue.use(VueTouch);

import Icon from '../components/Icon.vue';
import Motion from '../components/Motion.vue';

Vue.component('Icon', Icon);
Vue.component('Motion', Motion);

