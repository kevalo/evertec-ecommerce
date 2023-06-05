<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
    <a :href="route('home')">
        <div class="flex items-center">
            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
            <h1 class="ml-4 sm:hidden lg:flex">EVERTEC - ecommerce</h1>
        </div>
    </a>
</template>
