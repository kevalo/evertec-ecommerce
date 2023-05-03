<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import FileInput from "@/Components/FileInput.vue";
import Select from "@/Components/Select.vue";

defineProps({
    title: String,
    categories: Array,
    product: Object
})

const categories = usePage().props.categories;

const product = usePage().props.product;

const form = useForm({
    quantity: 1,
    _method: 'patch'// this allows to send the request as post, and handle it as patch, so files can be sent.
});

const submit = () => {
    form.post(route('products.add', product.id),{
        forceFormData: true
    });
};
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

                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div>
                                <InputLabel for="name" value="Nombre"/>
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="input mt-1 block w-full"
                                    :value="product.name"
                                    disabled
                                />
                            </div>

                            <div class="mt-3">
                                <InputLabel for="current_quantity" value="Cantidad actual"/>
                                <TextInput
                                    id="current_quantity"
                                    type="number"
                                    class="input mt-1 block w-full"
                                    :value="product.quantity"
                                    disabled
                                />
                                <small>Este valor puede variar al guardar los cambios</small>
                            </div>

                            <div class="mt-3">
                                <InputLabel for="quantity" value="Agregar cantidad"/>
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
                                    Regresar
                                </Link>

                                <button class="btn btn-primary" :disabled="form.processing">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
