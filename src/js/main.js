import Vue from 'vue'
import Vuex from 'vuex'
import OnePageCheckout from './one-page-checkout.vue'

Vue.use(Vuex)

const axios = require('axios');

const store = new Vuex.Store({
    state: {
        cart: {}
    },
    mutations: {
        'CART/SET'(state, cart) {
            state.cart = cart
        }
    },
    actions: {
        instantiate_one_page_checkout() {
            this.dispatch('get_cart_contents')
        },
        remove_cart_item(state, item) {
            axios
                .get(window.OnePageCheckout.ajax_url, {
                    params: {
                        action: 'opc_remove_item_from_cart',
                        cart_item_key: item.cart_item_key,
                        security: window.OnePageCheckout.nonce
                    }
                })
                .then(response => {
                    this.dispatch('get_cart_contents')
                })
        },
        get_cart_contents() {
            axios
                .get(window.OnePageCheckout.ajax_url, {
                    params: {
                        action: 'opc_get_cart_contents',
                        security: window.OnePageCheckout.nonce
                    }
                })
                .then(response => {
                    this.commit('CART/SET', response.data)
                })
        }
    }
})

new Vue({
    el: '#one-page-checkout',
    components: { OnePageCheckout },
    store: store,
})