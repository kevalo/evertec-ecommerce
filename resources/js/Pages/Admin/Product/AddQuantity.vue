<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    categories: Array,
    product: Object
});

const categories = usePage().props.categories;

const product = usePage().props.product;

const form = useForm({
    quantity: 1,
    _method: 'patch'// this allows to send the request as post, and handle it as patch, so files can be sent.
});

const submit = () => {
    form.post(route('products.add', product.id), {
        forceFormData: true
    });
};
</script>

<template>
    <Head :title="$page.props.$t.products.add_quantity_title"/>

    <AuthenticatedLayout :title="$page.props.$t.products.add_quantity_title">

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
                                    :value="product.name"
                                    disabled
                                />
                            </div>

                            <div class="mt-3">
                                <InputLabel for="current_quantity" :value="$page.props.$t.fields.current_quantity"/>
                                <TextInput
                                    id="current_quantity"
                                    type="number"
                                    class="input mt-1 block w-full"
                                    :value="product.quantity"
                                    disabled
                                />
                                <small>{{ $page.props.$t.fields.add_quantity_warning }}</small>
                            </div>

                            <div class="mt-3">
                                <InputLabel for="quantity" :value="$page.props.$t.fields.add_quantity"/>
                                <TextInput
                                    id="quantity"
                                    type="number"
                                    class="input mt-1 block w-full"
                                    v-model="form.quantity"
                                    min="1"
                                    required
                                    autofocus
                                    autocomplete="add_quantity"
                                />
                                <InputError class="mt-2" :message="form.errors.quantity"/>
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
