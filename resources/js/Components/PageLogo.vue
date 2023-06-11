<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
    <a :href="route('home')" class="w-16 sm:w-16 md:w-auto">
        <div class="flex items-center">
            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
            <h1 class="ml-4 hidden sm:hidden md:flex ">EVERTEC - ecommerce</h1>
        </div>
    </a>
</template>
