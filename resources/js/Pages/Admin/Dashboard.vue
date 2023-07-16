<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <strong>Reportes</strong><br>

                        <form action="" method="post" class="flex mb-3">
                            <div class="form-control w-full max-w-xs">
                                <label class="label">
                                    <span class="label-text">Fecha inicial</span>
                                </label>
                                <input type="date" name="start_date" id="start_date"
                                       class="input input-bordered w-full max-w-xs"/>
                            </div>

                            <div class="form-control w-full max-w-xs ml-2">
                                <label class="label">
                                    <span class="label-text">Fecha final</span>
                                </label>
                                <input type="date" name="end_date" id="end_date"
                                       class="input input-bordered w-full max-w-xs"/>
                            </div>

                            <button class="btn btn-primary self-end ml-2" type="submit">CONSULTAR</button>

                        </form>
                        <small>Recibirás un correo electrónico cuando se generen los reportes.</small>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
