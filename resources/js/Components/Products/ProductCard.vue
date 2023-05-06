<script setup>
defineProps({
    product: Object
})
</script>

<template>
    <div class="card card-compact bg-base-100 shadow-lg ">
        <figure style="object-fit: contain; height: 200px">
            <img :src="`/storage/${product.image}`" :alt="product.name"/>
        </figure>
        <div class="card-body">
            <h2 class="card-title">{{ product.name }}</h2>
            <p>{{product.description.slice(0, 150)}}...</p>
            <p>${{ product.price.toLocaleString('es-CO') }}</p>
            <div class="badge badge-outline">{{ product.category }}</div>
        </div>
    </div>
</template>
