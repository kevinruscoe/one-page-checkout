<template>
    <li class="flex">
        <div class="w-1/4 mr-4">
            <img title="Image of product." :src=item.image_url>
        </div>
        <div class="flex flex-col flex-wrap justify-center flex-1">
            <h3 class="flex justify-between">
                {{ item.title }}

                <a href="#remove_item" title="Remove this item from the cart." v-on:click.prevent="remove_item()">&times;</a>
            </h3>
            <p>
                {{ item.price }} &times; {{ item.quantity }}
            </p>
        </div>
    </li>
</template>
<script>
export default {
    props: ['item'],
    methods: {
        remove_item() {
            this.$store.dispatch( "remove_cart_item", this.item )
        }
    }
}
</script>