<script setup>

import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({customer: Object});

const customer = usePage().props.customer;

const form = useForm({
    name: customer.name,
    last_name: customer.last_name,
    phone: customer.phone
});

const submit = () => {
    form.put(route('customers.update', customer.id));
};
</script>

<template>
    <Head :title="$page.props.$t.customers.edit"/>

    <AuthenticatedLayout :title="$page.props.$t.customers.edit">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0"
                                    class="transition ease-in-out">
                            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Actualizado
                                correctamente.</p>
                        </Transition>

                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="name" :value="$page.props.$t.fields.names"/>
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

                            <div class="mt-4">
                                <InputLabel for="last_name" :value="$page.props.$t.fields.last_name"/>
                                <TextInput
                                    id="last_name"
                                    type="text"
                                    class="input mt-1 block w-full"
                                    v-model="form.last_name"
                                    required
                                    autofocus
                                    autocomplete="last_name"
                                />
                                <InputError class="mt-2" :message="form.errors.last_name"/>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="phone" :value="$page.props.$t.fields.phone"/>
                                <TextInput
                                    id="phone"
                                    type="text"
                                    class="input mt-1 block w-full"
                                    v-model="form.phone"
                                    required
                                    autofocus
                                    autocomplete="phone"
                                />
                                <InputError class="mt-2" :message="form.errors.phone"/>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="email" :value="$page.props.$t.fields.email"/>
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="input mt-1 block w-full "
                                    :value="customer.email"
                                    required
                                    disabled
                                    autocomplete="username"
                                />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    :href="route('customers')"
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
