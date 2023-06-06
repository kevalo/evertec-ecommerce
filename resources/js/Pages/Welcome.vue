<script setup>
import { ref } from "vue";
import { Head } from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
import ProductCard from "@/Components/Products/ProductCard.vue";
import SearchProducts from "@/Components/Products/SearchProducts.vue";
import PageLogo from "@/Components/PageLogo.vue";
import UserMenu from "@/Components/UserMenu.vue";
import CartIcon from "@/Components/CartIcon.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    products: Object,
    categories: Array
});

const searchTerm = ref('');
const category = ref('');

const products = ref([]);

const searchProducts = (text, cat) => {
    searchTerm.value = text;
    category.value = cat;

    loadProducts(`${route('api.products')}/?filter=${text}&category=${category.value}`);
}

const loadProducts = (url = null) => {
    axios.get(url || route('api.products')).then((response) => {
        products.value = response.data.data;
    }).catch((error) => {
        console.log(error);
    });
}

loadProducts();
</script>

<template>
    <Head title="Bienvenido"/>

    <div>
        <div class="flex p-4 border-b-2 justify-between items-center">
            <PageLogo/>
            <SearchProducts @search="searchProducts" :categories="categories"/>
            <div class="flex justify-between items-center">
                <CartIcon/>
                <UserMenu/>
            </div>
        </div>
        <h2 class="w-full text-center p-5">PRODUCTOS</h2>

        <div class="container px-3 mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
             v-if="products && products.data?.length > 0">
            <div v-for="product in products.data">
                <ProductCard :product="product"/>
            </div>
        </div>
        <div v-else class="mx-auto ">
            <p class="mx-auto text-white alert alert-info w-1/4">No se encontraron productos</p>
        </div>
        <Pagination v-if="products && products.data?.length > 0" class="mt-6" :links="products.links"
                    :filter="`&filter=${searchTerm}&category=${category}`" :click="loadProducts"/>
    </div>
</template>
