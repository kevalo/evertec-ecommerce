<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import FileInput from "@/Components/FileInput.vue";
import Select from "@/Components/Select.vue";

defineProps({
    categories: Array,
    product: Object
});

const categories = usePage().props.categories;

const product = usePage().props.product;

const form = useForm({
    name: product.name,
    description: product.description,
    price: product.price,
    image: '',
    quantity: product.quantity,
    category_id: product.category_id,
    _method: 'patch'// this allows to send the request as post, and handle it as patch, so files can be sent.
});

const submit = () => {
    form.post(route('products.update', product.id), {
        forceFormData: true
    });
};
</script>

<template>
    <Head :title="$page.props.$t.products.edit"/>

    <AuthenticatedLayout :title="$page.props.$t.products.edit">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div>
                                <InputLabel for="name" :value="$page.props.$t.fields.name"/>
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="input mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />
                                <InputError class="mt-2" :message="form.errors.name"/>
                            </div>

                            <div class="mt-3">
                                <InputLabel for="description" :value="$page.props.$t.fields.description"/>
                                <textarea
                                    id="description"
                                    type="text"
                                    class="textarea mt-1 block w-full border-gray-300 focus:border-primary focus:outline-none"
                                    v-model="form.description"
                                    required
                                    autofocus
                                    autocomplete="description"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description"/>
                            </div>

                            <div class="mt-3">
                                <InputLabel for="image" :value="$page.props.$t.fields.image"/>
                                <img :src="`/storage/${product.image}`" style="max-width: 100px;"
                                     alt="Imagen del producto">
                                <FileInput
                                    id="image"
                                    class="input mt-1 block w-full"
                                    v-model="form.image"
                                    autofocus
                                    autocomplete="image"
                                />
                                <InputError class="mt-2" :message="form.errors.image"/>
                            </div>

                            <div class="mt-3">
                                <InputLabel for="price" :value="$page.props.$t.fields.price"/>
                                <TextInput
                                    id="price"
                                    type="number"
                                    min="0"
                                    class="input mt-1 block w-full"
                                    v-model="form.price"
                                    required
                                    autofocus
                                    autocomplete="price"
                                />
                                <InputError class="mt-2" :message="form.errors.price"/>
                            </div>

                            <div class="mt-3">
                                <InputLabel for="quantity" :value="$page.props.$t.fields.quantity"/>
                                <TextInput
                                    id="quantity"
                                    type="number"
                                    class="input mt-1 block w-full"
                                    v-model="form.quantity"
                                    disabled
                                />
                                <InputError class="mt-2" :message="form.errors.quantity"/>
                            </div>

                            <div class="mt-3">
                                <InputLabel for="category_id" :value="$page.props.$t.fields.category"/>
                                <Select
                                    id="category_id"
                                    class="input mt-1 block w-full select"
                                    v-model="form.category_id"
                                    :options="categories"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.category_id"/>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    :href="route('products.index')"
                                    class="btn btn-secondary mr-5"
                                >
                                    {{ $page.props.$t.labels.back }}
                                </Link>

                                <button class="btn btn-primary" :disabled="form.processing">
                                    {{ $page.props.$t.labels.save }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
