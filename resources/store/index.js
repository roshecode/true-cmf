import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions';
import * as getters from './getters';

import Cart from './modules/cart';

Vue.use(Vuex);

/* global process */

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    state: {
        // loading: false,
        // layout: null,
    },

    // actions,

    // getters,

    modules: {
        Cart,
    },

    mutations: {
    },

    strict: debug,
});
