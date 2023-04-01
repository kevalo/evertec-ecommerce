<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import {Head} from '@inertiajs/vue3';
import {ref} from 'vue'

const props = defineProps({
    customers: Object,
    title: String,
    filter: String
});

const searchTerm = ref(props.filter ? props.filter : '');
const customers = ref(props.customers);

const toggleStatus = (e) => {
    axios.patch(route('toggleCustomerStatus'), {id: e.target.dataset.customer}).catch((err) => {
        console.error(err);
    });
}

const searchCustomers = async () => {
    try {
        const response = await fetch(`${route('customers')}/?filter=${searchTerm.value}`);
        const data = await response.json();
        customers.value = data.customers;
    } catch (error) {
        console.error(error);
    }
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

                        <form @submit.prevent="searchCustomers" class="flex">
                            <input type="text" id="searchTerm" v-model="searchTerm"
                                   class="input  w-1/4 input-bordered input-primary "
                                   placeholder="ingresa un correo o un nombre">
                            <button type="submit" class="btn btn-primary ml-3 my-0">
                                Buscar
                            </button>
                        </form>

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
                            <Pagination class="mt-6" :links="customers.links" :searchTerm="searchTerm"/>
                        </div>
                        <div v-else class="text-center">
                            No se encontraron clientes
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
