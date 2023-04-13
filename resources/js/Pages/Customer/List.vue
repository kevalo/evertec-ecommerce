<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import {Head} from '@inertiajs/vue3';
import {ref} from 'vue'

const props = defineProps({
    title: String
});

const searchTerm = ref('');
const customers = ref([]);

const toggleStatus = (e) => {
    axios.patch(route('api.customers.toggleStatus'), {id: e.target.dataset.customer}).catch((err) => {
        console.error(err);
    });
}

const searchCustomers = () => {
    axios.get(`${route('api.customers')}/?filter=${searchTerm.value}`).then((response) => {
        customers.value = response.data;
    }).catch((error) => {
        console.log(error);
    });
}

const loadCustomers = (url = null) => {
    axios.get(url || route('api.customers')).then((response) => {
        customers.value = response.data;
    }).catch((error) => {
        console.log(error);
    });
}

loadCustomers();

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

                        <div v-if="customers && customers.data?.length > 0">
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
                            <Pagination class="mt-6" :links="customers.links" :searchTerm="searchTerm"
                                        :click="loadCustomers"/>
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
