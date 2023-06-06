import { defineStore } from 'pinia';
import { computed, ref } from "vue";

export const useCartStore = defineStore('cart', () => {

    const products = ref({})
    const current = localStorage.getItem('cart');
    if (current) {
        products.value = JSON.parse(current).products;
    }

    const amount = computed(() => {
        console.log(products.value);
        let n = 0;
        for (const item of Object.values(products.value)) {
            console.log(item);
            n += item;
        }

        return n;
    });

    function add(id, amount) {
        console.log(products);
        products.value[id] = amount;
    }

    return {products, amount, add}
});
