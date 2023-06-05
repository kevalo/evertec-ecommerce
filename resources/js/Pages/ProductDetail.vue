<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import PageLogo from "@/Components/PageLogo.vue";
import UserMenu from "@/Components/UserMenu.vue";
import { ref } from "vue";
import { useCartStore } from "@/Stores/CartStore";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    product: Object
});

const product = usePage().props.product;

const amount = ref(1);

const store = useCartStore();

const addToCart = () => {
    console.log(amount.value)
    store.add(product.id, amount.value);
}
</script>

<template>
    <Head title="Bienvenido"/>

    <div>
        <div class="flex p-4 border-b-2 justify-between">
            <PageLogo/>
            <UserMenu/>
        </div>
    </div>

    <div class="prose w-full mx-auto mb-6">
        <h2 class="w-full text-center p-5">{{ product.name }}</h2>
    </div>

    <div class="px-4 sm:columns-1 md:columns-2 ">

        <div>
            <figure style="object-fit: contain;">
                <img :src="`/storage/${product.image}`" class="max-w-full mx-auto" :alt="product.name"/>
            </figure>
        </div>

        <div class="prose">
            <span class="badge badge-outline block h-fit">{{ product.category }}</span>
            <p>{{ product.description }}</p>
            <h3>${{ product.price.toLocaleString('es-CO') }}</h3>
        </div>

        <div class="pt-8">
            <span>Add to cart</span>
            <div class="flex">

                <input type="number" class="input input-bordered w-20" v-model="amount">
                <button class="btn btn-outline" @click="addToCart">
                    <i class="fa fa-cart-plus"></i>&nbsp;
                </button>
            </div>
        </div>

    </div>

</template>
