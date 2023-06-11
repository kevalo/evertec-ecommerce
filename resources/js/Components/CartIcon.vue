<script setup>

import { useCartStore } from "@/Stores/CartStore";

const store = useCartStore();

</script>
<template>
    <a :href="route('cart')" class="w-16 flex justify-center items-center">
        <i class="fa fa-cart-plus"></i>
        <span class="badge badge-outline px-1 ms-1">{{ store.amount }}</span>
    </a>
</template>
