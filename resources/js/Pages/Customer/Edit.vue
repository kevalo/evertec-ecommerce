<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage,  Link, useForm} from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    title: String,
    customer: Object
})

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
    <Head :title="title"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Actualizado correctamente.</p>
                        </Transition>

                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="name" value="Nombres"/>
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
                                <InputLabel for="last_name" value="Apellidos"/>
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
                                <InputLabel for="phone" value="Teléfono"/>
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
                                <InputLabel for="email" value="Correo electrónico"/>
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
                                    Regresar
                                </Link>

                                <button class="btn btn-primary" :disabled="form.processing">
                                    Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
