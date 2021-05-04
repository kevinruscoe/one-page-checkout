<template>
  <div id="app">

    <div class="w-screen p-8 h-screen antialiased grid gap-12 grid-rows-[auto,1fr] grid-cols-[1fr,400px]">

      <div class="col-span-2">
        <h1 class="text-3xl">Checkout</h1>
      </div>

      <div class="grid grid-cols-1 gap-12">

        <div class="relative">

          <h3 class="flex pb-1 mb-4 font-semibold text-gray-400 uppercase border-b">
            <span class="mr-auto">Shipping Details</span>

            <a class="p-2 py-1 text-xs text-white bg-blue-500 rounded cursor-pointer">edit</a>
          </h3>

          <template x-if="editor_open">
            <div class="absolute grid w-full grid-cols-2 gap-4 p-4 bg-white border rounded-lg shadow-md">
              <div class="col-span-2">
                <label for="shipping-address-line-1">Address Line 1</label>
                <input type="text" name="shipping-address-line-1" placeholder="1 Street Name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" x-model="get_shipping_address_1" value="<?php print $customer->get_shipping_address_1() ?>">
              </div>
              <div class="col-span-2">
                <label for="shipping-address-line-2">Address Line 2</label>
                <input type="text" name="shipping-address-line-2" placeholder="" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
              </div>
              <div class="col-span-2">
                <label for="shipping-address-line-3">Address Line 3</label>
                <input type="text" name="shipping-address-line-3" placeholder="" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
              </div>
              <div class="col-span-1">
                <label for="shipping-address-town">Town / City</label>
                <input type="text" name="shipping-address-city" placeholder="" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
              </div>
              <div class="col-span-1">
                <label for="shipping-address-country">Country</label>
                <input type="text" name="shipping-address-country" placeholder="" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
              </div>
              <div class="col-span-2">
                <button x-on:click.prevent="save_address" class="w-full p-3 text-white bg-green-500 rounded-md hover:bg-green-600">
                  Save Address
                </button>
              </div>
            </div>
          </template>

          <template x-if="! editor_open">
            <div class="grid items-start grid-cols-2 gap-4">
              <div class="relative">
                <address class="m-0">
                  <span x-text="get_formatted_address()"></span>
                </address>
              </div>
              <div class="">
                <p>
                  kevdotbadger@gmail.com
                </p>
              </div>
            </div>
          </template>
        </div>

        <div class="" data-payment-details>
          <h3 class="pb-1 mb-4 font-semibold text-gray-400 uppercase border-b">Payment Details</h3>
          <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
              <label for="card-name">Name on card</label>
              <input type="text" name="card-name" placeholder="Mr B Dover" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="col-span-2">
              <label for="card-number">Card Number</label>
              <input type="text" name="card-number" placeholder="0000-0000-0000-0000" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="">
              <label for="card-number">Expiry Date</label>
              <input type="text" name="card-expiry" placeholder="mm/yy" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="">
              <label for="card-cvv">CVV</label>
              <input type="text" name="card-cvv" placeholder="000" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="col-span-2">
              <button class="w-full p-3 text-white bg-green-500 rounded-md hover:bg-green-600">
                Purchase {{ cart.total }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="">
        <div class="">
          <h3 class="pb-1 mb-4 font-semibold text-gray-400 uppercase border-b">Your Order</h3>
            <ul class="space-y-4">

              <template v-if="cartIsEmpty">
                <p>Cart is empty.</p>
              </template>
              <template v-else>
                <one-page-checkout-item-row 
                  v-if="cart.items"
                  v-for="(item, key) in cart.items" 
                  :item="item" />
              </template>

              <li>
                <a href="/">&laquo; Continue shopping</a>
              </li>
            </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import OnePageCheckoutItemRow from './one-page-checkout/item-row.vue'

export default {
  components: {
    OnePageCheckoutItemRow
  },
  mounted() {
    this.$store.dispatch('instantiate_one_page_checkout')
  },
  computed: {
    cart() {
      return this.$store.state.cart
    },
    cartIsEmpty() {
      if (typeof this.$store.state.cart.items === "undefined") {
        return true;
      }
      
      return this.$store.state.cart.items.length === 0
    }
  }
};
</script>