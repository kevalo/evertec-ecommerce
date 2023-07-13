<script setup>

import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import Select from "@/Components/Select.vue";

const props = defineProps({categories: Array});

const generalStatus = usePage().props.GeneralStatus;

const searchTerm = ref('');
const category = ref('');
const products = ref([]);

const toggleStatus = (e) => {
    axios.patch(route('api.admin.products.toggleStatus'), {id: e.target.dataset.product}).catch((err) => {
        console.error(err);
    });
}

const searchProducts = () => {
    let searchUrl = `${route('api.admin.products')}/?filter=${searchTerm.value}&category=${category.value}`;
    loadProducts(searchUrl)
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
    <Head :title="$page.props.$t.products.title"/>

    <AuthenticatedLayout :title="$page.props.$t.products.title">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 ">

                        <div class="flex">

                            <form @submit.prevent="searchProducts" class="flex">
                                <input type="text" id="searchTerm" v-model="searchTerm"
                                       class="input  input-bordered input-primary "
                                       placeholder="ingresa un nombre">

                                <Select class="input ml-2 block w-2/4 select" v-model="category" :options="categories"
                                        :text="'categoría'"/>

                                <button type="submit" class="btn btn-primary ml-3 my-0">
                                    {{ $page.props.$t.labels.search }}
                                </button>
                            </form>

                            <Link
                                :href="route('products.import')"
                                class="btn ml-auto"
                            >
                                {{ $page.props.$t.labels.import }}
                            </Link>

                            <Link
                                :href="route('products.create')"
                                class="btn btn-primary ml-2"
                            >
                                {{ $page.props.$t.labels.create }}
                            </Link>

                        </div>

                        <div v-if="products && products.data?.length > 0" class="mt-5">
                            <table class="table table-compact w-full border-2 text-center">
                                <caption>{{ $page.props.$t.products.list }}</caption>
                                <thead class="border-b-2">
                                <tr>
                                    <th>{{ $page.props.$t.fields.name }}</th>
                                    <th>{{ $page.props.$t.fields.category }}</th>
                                    <th>{{ $page.props.$t.fields.price }}</th>
                                    <th>{{ $page.props.$t.fields.quantity }}</th>
                                    <th>{{ $page.props.$t.fields.status }}</th>
                                    <th>{{ $page.props.$t.labels.actions }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="product in products.data" class="border-b-2">
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.category }}</td>
                                    <td>$ {{ product.price.toLocaleString('es-CO') }}</td>
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
                                           :title="$page.props.$t.products.edit"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a class="btn btn-outline ml-1"
                                           :href="route('products.add', product.id)"
                                           :title="$page.props.$t.products.add_quantity_title"
                                        >
                                            <i class="fa fa-plus-minus"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <Pagination class="mt-6" :links="products.links"
                                        :filter="`&filter=${searchTerm}&category=${category}`"
                                        :click="loadProducts"/>
                        </div>
                        <div v-else class="text-center pt-5">
                            No se encontraron productos
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
