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
import VueTouch from 'vue-touch'

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(VueResource);
Vue.use(VueTouch);

Vue.http.options.emulateHTTP = true;
