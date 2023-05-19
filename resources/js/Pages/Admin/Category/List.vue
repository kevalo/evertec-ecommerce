<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";

const generalStatus = usePage().props.GeneralStatus;

const categories = ref([]);

const toggleStatus = (e) => {
    axios.patch(route('api.categories.toggleStatus'), {id: e.target.dataset.category}).catch((err) => {
        console.error(err);
    });
}

const loadCategories = (url = null) => {
    axios.get(url || route('api.categories')).then((response) => {
        categories.value = response.data.data;
    }).catch((error) => {
        console.log(error);
    });
}

loadCategories();
</script>

<template>
    <Head :title="$page.props.$t.categories.title"/>

    <AuthenticatedLayout :title="$page.props.$t.categories.title">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex flex-col">

                        <Link
                            :href="route('categories.create')"
                            class="btn btn-primary w-20 self-end"
                        >
                            {{ $page.props.$t.labels.create }}
                        </Link>

                        <div v-if="categories && categories.data?.length > 0" class="mt-5">
                            <table class="table table-compact w-full border-2 text-center">
                                <caption>{{ $page.props.$t.categories.list }}</caption>
                                <thead class="border-b-2">
                                <tr>
                                    <th>{{ $page.props.$t.fields.name }}</th>
                                    <th>{{ $page.props.$t.fields.status }}</th>
                                    <th>{{ $page.props.$t.labels.actions }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="category in categories.data" class="border-b-2">
                                    <td>{{ category.name }}</td>
                                    <td>
                                        <input type="checkbox"
                                               class="toggle toggle-success"
                                               :data-category="category.id"
                                               :checked="category.status === generalStatus['active']"
                                               @change="toggleStatus($event)"
                                        />
                                    </td>
                                    <td>
                                        <a class="btn btn-outline btn-primary"
                                           :href="route('categories.show', category.id)"
                                           :title="$page.props.$t.categories.edit"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <Pagination class="mt-6" :links="categories.links" :click="loadCategories"/>
                        </div>
                        <div v-else class="text-center">
                            No se encontraron categorías
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

