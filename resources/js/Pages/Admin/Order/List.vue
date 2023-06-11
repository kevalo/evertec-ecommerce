<script setup>
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";

const orders = ref([]);

const loadOrders = (url = null) => {
    axios.get(url || route('api.orders.index')).then((response) => {
        orders.value = response.data.data;
    }).catch((error) => {
        console.log(error);
    });
}

loadOrders();
</script>

<template>
    <Head :title="$page.props.$t.orders.plural_title"/>

    <AuthenticatedLayout :title="$page.props.$t.orders.plural_title">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex flex-col">

                        <div v-if="orders && orders.data?.length > 0" class="mt-5">
                            <table class="table table-compact w-full border-2 text-center">
                                <caption>{{ $page.props.$t.orders.list }}</caption>
                                <thead class="border-b-2">
                                <tr>
                                    <th>{{ $page.props.$t.fields.code }}</th>
                                    <th>{{ $page.props.$t.fields.status }}</th>
                                    <th>{{ $page.props.$t.labels.total }}</th>
                                    <th>{{ $page.props.$t.fields.created_at }}</th>
                                    <th>{{ $page.props.$t.labels.actions }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="order in orders.data" class="border-b-2">
                                    <td>{{ order.code }}</td>
                                    <td>
                                        {{ $page.props.$t.orders.status[order.status] }}
                                    </td>
                                    <td>${{ order.total_price.toLocaleString('es-CO') }}</td>
                                    <td>{{ new Date(order.created_at).toLocaleString('es-CO') }}</td>
                                    <td>
                                        <a :href="route('orders.show', order.id)" target="_blank"
                                           :title="$page.props.$t.products.title">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <Pagination class="mt-6" :links="orders.links" :click="loadOrders"/>
                        </div>
                        <div v-else class="text-center">
                            {{ $page.props.$t.orders.no_records }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

