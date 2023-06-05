import { defineStore } from 'pinia';
import { ref } from "vue";

export const useCartStore = defineStore('cart', () => {

    const products = ref({})
    const current = localStorage.getItem('cart');
    if (current) {
        products.value = JSON.parse(current).products;
    }

    function add(id, amount) {
        console.log(products);
        products.value[id] = amount;
    }

    return {products, add}
});
