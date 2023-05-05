<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { Head, Link, usePage } from '@inertiajs/vue3';
import {ref} from 'vue'

const props = defineProps({
    title: String
});

const generalStatus = usePage().props.GeneralStatus;

const searchTerm = ref('');
const products = ref([]);

const toggleStatus = (e) => {
    axios.patch(route('api.admin.products.toggleStatus'), {id: e.target.dataset.product}).catch((err) => {
        console.error(err);
    });
}

const searchProducts = () => {
    axios.get(`${route('api.admin.products')}/?filter=${searchTerm.value}`).then((response) => {
        products.value = response.data.data;
    }).catch((error) => {
        console.log(error);
    });
}

const loadProducts = (url = null) => {
    axios.get(url || route('api.admin.products')).then((response) => {
        products.value = response.data.data;
    }).catch((error) => {
        console.log(error);
    });
}

loadProducts();

</script>

<template>
    <Head :title="title"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 ">

                        <div class="flex">

                            <form @submit.prevent="searchProducts" class="flex">
                                <input type="text" id="searchTerm" v-model="searchTerm"
                                       class="input  input-bordered input-primary "
                                       placeholder="ingresa un nombre">
                                <button type="submit" class="btn btn-primary ml-3 my-0">
                                    Buscar
                                </button>
                            </form>

                            <Link
                                :href="route('products.create')"
                                class="btn btn-primary w-20 ml-auto"
                            >
                                Crear
                            </Link>

                        </div>

                        <div v-if="products && products.data?.length > 0" class="mt-5">
                            <table class="table table-compact w-full border-2 text-center">
                                <caption>Listado de productos</caption>
                                <thead class="border-b-2">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="product in products.data" class="border-b-2">
                                    <td>{{ product.name }}</td>
                                    <td>$ {{ product.price }}</td>
                                    <td>{{ product.quantity }}</td>
                                    <td>
                                        <input type="checkbox"
                                               class="toggle toggle-success"
                                               :data-product="product.id"
                                               :checked="product.status === generalStatus['active']"
                                               @change="toggleStatus($event)"
                                        />
                                    </td>
                                    <td>
                                        <a class="btn btn-outline btn-primary"
                                           :href="route('products.show', product.id)"
                                           title="Editar usuario"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a class="btn btn-outline ml-1"
                                           :href="route('products.add', product.id)"
                                           title="Agregar unidades"
                                        >
                                            <i class="fa fa-plus-minus"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <Pagination class="mt-6" :links="products.links" :searchTerm="searchTerm"
                                        :click="loadProducts"/>
                        </div>
                        <div v-else class="text-center">
                            No se encontraron productos
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
