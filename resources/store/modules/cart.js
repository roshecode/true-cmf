import * as types from '../mutationTypes';

const state = {
    products: [],
    grandTotal: 0
};

function normalizeNumber(number) {
    return Math.round(number * 100) / 100
}

const mutations = {
    [types.ADD_TO_CART](state, {product}) {
        let storeProduct = state.products.find(storeProduct => storeProduct.id === product.id);

        if (storeProduct) {
            ++storeProduct.quantity;
        } else {
            product.quantity = 1;
            state.products.push(product);
        }

        state.grandTotal += product.price;
        state.grandTotal = normalizeNumber(state.grandTotal);
    },

    [types.REMOVE_FROM_CART](state, {product}) {
        let storeProductIndex = state.products.findIndex(storeProduct => storeProduct.id === product.id),
            storeProduct = state.products[storeProductIndex];

        state.products.splice(storeProductIndex, 1);
        state.grandTotal -= storeProduct.price * storeProduct.quantity;
        state.grandTotal = normalizeNumber(state.grandTotal);
    },
};

const actions = {};

const getters = {
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}
