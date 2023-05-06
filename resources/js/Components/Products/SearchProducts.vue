<script setup>

import { ref } from "vue";
import Select from "@/Components/Select.vue";

defineProps({
    categories: Array
})
const emit = defineEmits(['search']);


const searchTerm = ref('');
const category = ref('');
const searchProducts = () => {
    emit('search', searchTerm.value, category.value);
}

</script>

<template>
    <form @submit.prevent="searchProducts" class="flex">
        <input type="search" id="searchTerm" v-model="searchTerm"
               class="input  input-bordered input-primary "
               placeholder="ingresa un nombre">

        <Select class="input ml-2 block w-2/4 select" v-model="category" :options="categories" :text="'categoría'" />

        <button type="submit" class="btn btn-outline ml-3 my-0">
            Buscar
        </button>
    </form>
</template>


