<script setup>
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";
import PageLogo from "@/Components/PageLogo.vue";
import UserMenu from "@/Components/UserMenu.vue";
import CartIcon from "@/Components/CartIcon.vue";
import { useCartStore } from "@/Stores/CartStore";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean
});

const store = useCartStore();

const products = ref([]);
const loadProducts = () => {
    axios.post(route('api.getCartProducts'), {ids: Object.keys(store.products)})
        .then((response) => {
            products.value = response.data.data;
        });
}
loadProducts();

const productToDelete = ref(null);
const showModal = (productId) => {
    removeProductModal.showModal();
    productToDelete.value = productId;
}

const removeProduct = () => {
    store.deleteProduct(productToDelete.value);
    loadProducts();
    removeProductModal.close();
}

const increase = (productId) => {
    store.add(productId, 1);
}

const decrease = (productId) => {
    if (store.products[productId] > 1) {
        store.add(productId, -1);
    }
}

</script>

<template>
    <Head title="Bienvenido"/>

    <div>
        <div class="flex p-4 border-b-2 justify-between items-center">
            <PageLogo/>
            <div class="flex justify-between items-center">
                <CartIcon/>
                <UserMenu/>
            </div>
        </div>
    </div>

    <div class="prose w-full mx-auto mb-6">
        <h2 class="w-full text-center p-5">Carrito</h2>
    </div>

    <div class="grid grid-cols-1 px-4">

        <div v-for="product in products"
             class="card card-side bg-base-100 shadow-xl  mx-auto my-1 w-full sm:w-3/4 md:w-2/4 xl:w-1/4">
            <figure style="object-fit: contain;">
                <img :src="`/storage/${product.image}`" class=" mx-auto" :alt="product.name"
                     style="width: 200px;"/>
            </figure>
            <div class="card-body">
                <h2 class="card-title">{{ product.name }}</h2>
                <h3>Valor unitario: ${{ product.price.toLocaleString('es-CO') }}</h3>
                <div class="flex items-center justify-between">
                    <button class="btn" @click="decrease(product.id)"><i class="fa fa-minus"></i></button>
                    <input type="text" :value="store.products[product.id]" min="1" class="w-20 input input-bordered">
                    <button class="btn" @click="increase(product.id)"><i class="fa fa fa-plus"></i></button>
                </div>
                <h3>Subtotal: ${{ (product.price * store.products[product.id]).toLocaleString('es-CO') }}</h3>
                <div class="card-actions justify-end">
                    <button class="btn btn-outline btn-error" @click="showModal(product.id)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <dialog id="removeProductModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <form method="dialog">
                <button for="removeProductModal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕
                </button>
            </form>
            <h3 class="font-bold text-lg">Hello!</h3>
            <p class="py-4">Realmente deseas quitar este producto del carrito</p>
            <div class="modal-action">
                <button class="btn btn-outline btn-primary" @click="removeProduct">Si</button>
                <form method="dialog">
                    <button class="btn">No</button>
                </form>
            </div>

        </div>

    </dialog>


</template>
