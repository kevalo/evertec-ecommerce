<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    status: false
});

const submit = () => {
    form.post(route('categories.store'));
};
</script>

<template>
    <Head :title="$page.props.$t.categories.create"/>

    <AuthenticatedLayout :title="$page.props.$t.categories.create">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <form @submit.prevent="submit">
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
                                <InputLabel for="status" :value="$page.props.$t.fields.status"/>
                                <input type="checkbox"
                                       name="status" id="status"
                                       v-model="form.status"
                                       class="toggle toggle-success"/>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    :href="route('categories.index')"
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
