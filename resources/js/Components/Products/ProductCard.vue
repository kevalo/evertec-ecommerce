<script setup>
defineProps({
    product: Object
})
</script>

<template>
    <a :href="route('product-detail', product.slug)">
        <div class="card card-compact bg-base-100 shadow-lg ">
            <figure style="object-fit: contain; height: 200px">
                <img :src="`/storage/${product.image}`" :alt="product.name"/>
            </figure>
            <div class="card-body">
                <h2 class="card-title">{{ product.name }}</h2>
                <p>{{ product.description.slice(0, 144) }}...</p>
                <p>${{ product.price.toLocaleString('es-CO') }}</p>
                <p class="badge badge-outline block h-fit">{{ product.category }}</p>
            </div>
        </div>
    </a>
</template>
