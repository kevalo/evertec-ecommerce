<script setup>
import { ref } from "vue";
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Pagination from "@/Components/Pagination.vue";
import ProductCard from "@/Components/Products/ProductCard.vue";
import SearchProducts from "@/Components/Products/SearchProducts.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Dropdown from "@/Components/Dropdown.vue";

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

    axios.get(`${route('api.products')}/?filter=${text}&category=${category.value}`).then((response) => {
        products.value = response.data.data;
    }).catch((error) => {
        console.log(error);
    });
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
        <div v-if="canLogin" class="flex p-4 border-b-2 justify-between">
            <div class="flex items-center">
                <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                <h1 class="ml-4 sm:hidden lg:flex">EVERTEC - ecommerce</h1>
            </div>

           <SearchProducts @search="searchProducts" :categories="categories" />

            <div class="hidden sm:flex sm:items-center sm:ml-6" v-if="$page.props.auth.user">
                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                    >
                                        {{ $page.props.auth.user.name }}

                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('dashboard')" v-if="$page.props.auth.user.role_id === $page.props.auth.admin"> Dashboard </DropdownLink>
                            <DropdownLink :href="route('profile.edit')"> Perfil </DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">
                                Cerrar sesión
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>

<!--            <template v-if="$page.props.auth.user">-->
<!--                <div class="flex items-center">-->
<!--                    <Link v-if="$page.props.auth.user.role_id === 1"-->
<!--                          :href="route('dashboard')"-->
<!--                          class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-black focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"-->
<!--                    >Dashboard-->
<!--                    </Link>-->
<!--                    <Link-->
<!--                        class=" mx-3 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-black focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"-->
<!--                        :href="route('profile.edit')">-->
<!--                        Perfil-->
<!--                    </Link>-->

<!--                    <Link-->
<!--                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-black focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"-->
<!--                        :href="route('logout')" method="post" as="button">-->
<!--                        Cerrar sesión-->
<!--                    </Link>-->
<!--                </div>-->
<!--            </template>-->

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-black focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Iniciar sesión
                </Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-black focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Registrarme
                </Link>
            </template>
        </div>
        <h2 class="w-full text-center p-5">PRODUCTOS</h2>

        <div class="container mx-auto grid grid-cols-4 gap-6" v-if="products && products.data?.length > 0">
            <div v-for="product in products.data">
               <ProductCard :product="product" />
            </div>
        </div>
        <div v-else class="mx-auto ">
            <p class="mx-auto text-white alert alert-info w-1/4">No se encontraron productos</p>
        </div>
        <Pagination v-if="products && products.data?.length > 0" class="mt-6" :links="products.links"
                    :filter="`&filter=${searchTerm}&category=${category}`" :click="loadProducts"/>
    </div>
</template>
