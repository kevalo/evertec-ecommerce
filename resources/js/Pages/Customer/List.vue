<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import {Head, usePage} from '@inertiajs/vue3';

defineProps({
    customers: Object,
    title: String
})

const customers = usePage().props.customers;

const toggleStatus = (e) => {
    axios.patch(route('toggleCustomerStatus'), {id: e.target.dataset.customer}).catch((err) => {
        console.error(err);
    });
}
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
                    <div class="p-6 text-gray-900">

                        <div v-if="customers.data.length > 0">
                            <table class="table w-full border-2 text-center">
                                <caption>Listado de clientes</caption>
                                <thead class="border-b-2">
                                <tr>
                                    <th>Correo electrónico</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="customer in customers.data" class="border-b-2">
                                    <td>{{ customer.email }}</td>
                                    <td>{{ customer.name }}</td>
                                    <td>{{ customer.last_name }}</td>
                                    <td>{{ customer.phone }}</td>
                                    <td>
                                        <input type="checkbox"
                                               class="toggle toggle-success"
                                               :data-customer="customer.id"
                                               :checked="customer.status === 'active'"
                                               @change="toggleStatus($event)"
                                        />
                                    </td>
                                    <td>
                                        <a class="btn btn-outline btn-primary"
                                           :href="route('customers.edit', customer.id)"
                                           title="Editar usuario"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <Pagination class="mt-6" :links="customers.links"/>
                        </div>
                        <div v-else>
                            No hay clientes registrados
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
