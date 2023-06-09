import { defineStore } from 'pinia';
import { computed, ref } from "vue";

export const useCartStore = defineStore('cart', () => {

    const products = ref({})
    const current = localStorage.getItem('cart');
    if (current) {
        products.value = JSON.parse(current).products;
    }

    const amount = computed(() => {
        let n = 0;
        for (const item of Object.values(products.value)) {
            n += item;
        }

        return n;
    });

    function add(id, amount) {
        if (products.value.hasOwnProperty(id)) {
            products.value[id] += amount;
        } else {
            products.value[id] = amount;
        }
    }

    function deleteProduct(id) {
        Reflect.deleteProperty(products.value, id);
    }

    function clear() {
        products.value = {};
    }

    return {products, amount, add, deleteProduct, clear}
});
