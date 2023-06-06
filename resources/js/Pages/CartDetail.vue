<script setup>
import { Head } from "@inertiajs/vue3";
import PageLogo from "@/Components/PageLogo.vue";
import UserMenu from "@/Components/UserMenu.vue";
import CartIcon from "@/Components/CartIcon.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean
});

</script>

<template>
    <Head title="Bienvenido"/>

    <div>
        <div class="flex p-4 border-b-2 justify-between items-center">
            <PageLogo/>
            <div class="flex justify-between items-center">
                <CartIcon/>
                <UserMenu/>
            </div>
        </div>
    </div>

    <div class="prose w-full mx-auto mb-6">
        <h2 class="w-full text-center p-5">Carrito</h2>
    </div>

    <div class="px-4 sm:columns-1 md:columns-2 ">
        TODO
    </div>

</template>
