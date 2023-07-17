<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import FileInput from "@/Components/FileInput.vue";
import InputError from "@/Components/InputError.vue";

const form = useForm({
    file: null,
    _method: 'post'
});

const submit = () => {
    form.post(route('products.import.process'), {
        forceFormData: true
    });
};

</script>
<template>
    <Head :title="$page.props.$t.products.import"/>

    <AuthenticatedLayout :title="$page.props.$t.products.import">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 ">
                        <p>
                            {{ $page.props.$t.products.import_format_file }}:
                            <a class="link link-primary"
                               href="/formats/formato_importar_productos.csv">{{ $page.props.$t.labels.download }}</a>
                        </p>

                        <div class="alert my-5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 class="stroke-info shrink-0 w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <ul>
                                <li v-for="item in $page.props.$t.products.import_alert">
                                    {{ item }}
                                </li>
                            </ul>
                        </div>

                        <form @submit.prevent="submit" enctype="multipart/form-data" class="flex" method="post">
                            <div class="form-control w-full max-w-xs ">
                                <label class="label" for="file">
                                    <span class="label-text">{{ $page.props.$t.labels.file_upload }}</span>
                                </label>
                                <FileInput
                                    id="file"
                                    class="input mt-1 block w-full"
                                    v-model="form.file"
                                    autofocus
                                    autocomplete="image"
                                />

                            </div>
                            <button class="btn btn-primary btn-sm ml-2  self-end" type="submit">
                                {{ $page.props.$t.labels.import }}
                            </button>
                        </form>
                        <InputError class="mt-2" :message="form.errors.file"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
