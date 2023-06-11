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
    <form @submit.prevent="searchProducts" class="flex flex-wrap md:flex-nowrap w-3/4 sm:w:3/4 md:w-auto">
        <input type="search" id="searchTerm" v-model="searchTerm"
               class="input  input-bordered input-primary "
               placeholder="ingresa un nombre">


        <Select class="input ms-0 sm:ms-0 md:ms-1 block select" v-model="category" :options="categories"
                :text="'categoría'"/>

        <button type="submit" class="btn btn-outline ml-3 my-0 ms-0">
            {{ $page.props.$t.labels.search }}
        </button>

    </form>
</template>


