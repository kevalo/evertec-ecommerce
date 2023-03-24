<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';

</script>

<template>
    <Head title="Dashboard - Customers"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard - Customers</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table v-if="$page.props.customers.length > 0" class="table w-full border-2">
                            <caption>Listado de clientes</caption>
                            <thead class="border-b-2">
                            <tr>
                                <th>Correo electrónico</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody class="text-center">
                            <tr v-for="customer in $page.props.customers" class="border-b-2">
                                <td>{{ customer.email }}</td>
                                <td>{{ customer.name }}</td>
                                <td>{{ customer.last_name }}</td>
                                <td>{{ customer.phone }}</td>
                                <td>{{ customer.status }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div v-else>
                            No hay clientes registrados
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
