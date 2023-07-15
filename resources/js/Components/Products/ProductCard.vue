<script setup>
defineProps({
    product: Object
})
</script>

<template>
    <a :href="route('product-detail', product.slug)">
        <div class="card card-compact bg-base-100 shadow-lg ">
            <figure style="object-fit: contain; height: 200px">
                <img v-if="product.image" :src="`/storage/${product.image}`" :alt="product.name"/>
                <img v-else :src="`https://placehold.co/360x280?text=${product.name}`" :alt="product.name"/>
            </figure>
            <div class="card-body prose">
                <h2 class="card-title">{{ product.name }}</h2>
                <p>{{ product.description.slice(0, 138) }}...</p>
                <strong>${{ product.price.toLocaleString('es-CO') }}</strong>
                <p class="badge badge-outline block h-fit">{{ product.category }}</p>
            </div>
        </div>
    </a>
</template>
