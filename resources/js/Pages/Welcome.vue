<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { ref } from "vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    products: Object
});

const searchTerm = ref('');
const products = ref([]);

const searchProducts = () => {
    axios.get(`${route('api.products')}/?filter=${searchTerm.value}`).then((response) => {
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
                <h1 class="ml-4">EVERTEC - ecommerce</h1>
            </div>

            <form @submit.prevent="searchProducts" class="flex">
                <input type="text" id="searchTerm" v-model="searchTerm"
                       class="input  input-bordered input-primary "
                       placeholder="ingresa un nombre">
                <button type="submit" class="btn btn-outline ml-3 my-0">
                    Buscar
                </button>
            </form>

            <template v-if="$page.props.auth.user">
                <div class="flex items-center">
                    <Link v-if="$page.props.auth.user.role_id === 1"
                          :href="route('dashboard')"
                          class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-black focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Dashboard
                    </Link>
                    <Link
                        class=" mx-3 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-black focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        :href="route('profile.edit')">
                        Perfil
                    </Link>

                    <Link
                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-black focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        :href="route('logout')" method="post" as="button">
                        Cerrar sesión
                    </Link>
                </div>
            </template>

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
            <div v-for="product in products.data" class="card card-compact bg-base-100 shadow-lg ">
                <figure style="object-fit: contain; height: 200px" >
                    <img :src="`/storage/${product.image}`" :alt="product.name"  />
                </figure>
                <div class="card-body">
                    <h2 class="card-title">{{ product.name }}</h2>
                    <p>${{ product.price.toLocaleString('es-CO') }}</p>
                    <div class="badge badge-outline">{{product.category}}</div>
                </div>
            </div>
        </div>
        <Pagination class="mt-6" :links="products.links" :searchTerm="searchTerm"
                    :click="loadProducts"/>
    </div>
</template>
