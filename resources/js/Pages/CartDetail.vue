<script setup>
import { computed, ref } from "vue";
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

let total = computed(() => {
    let n = 0;
    for (const index in products.value) {
        const product = products.value[index];
        if (product) {
            n += product.price * store.products[product.id];
        }
    }
    return n;
});

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
        <h2 class="w-full text-center p-5 uppercase">{{ $page.props.$t.cart.title }}</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 px-4 ">

        <div v-for="product in products"
             class="card card-side bg-base-100 drop-shadow-md  mx-auto my-1 w-full sm:w-3/4">
            <figure style="object-fit: contain;">
                <img :src="`/storage/${product.image}`" class="drop-shadow-md mx-auto" :alt="product.name"
                     style="width: 200px;"/>
            </figure>
            <div class="card-body">
                <h2 class="card-title"><a :href="route('product-detail', product.slug)">{{ product.name }}</a></h2>
                <h3>{{ $page.props.$t.products.unit_price }}: ${{ product.price.toLocaleString('es-CO') }}</h3>
                <div class="flex items-center ">
                    <button class="btn" @click="decrease(product.id)"><i class="fa fa-minus"></i></button>
                    <input type="text" :value="store.products[product.id]" min="1" class="w-20 input input-bordered">
                    <button class="btn" @click="increase(product.id)"><i class="fa fa fa-plus"></i></button>
                </div>
                <h3>{{ $page.props.$t.labels.subtotal }}:
                    ${{ (product.price * store.products[product.id]).toLocaleString('es-CO') }}</h3>
                <div class="card-actions justify-end">
                    <button class="btn btn-outline btn-error" @click="showModal(product.id)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <div class="prose mx-auto mt-16">
        <h2 class="text-center">{{ $page.props.$t.labels.total }}: ${{ total.toLocaleString('es-CO') }}</h2>
    </div>

    <div class="pt-4">
        <button class="btn btn-primary block mx-auto">{{ $page.props.$t.cart.buy }}</button>
    </div>

    <dialog id="removeProductModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <form method="dialog">
                <button for="removeProductModal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕
                </button>
            </form>
            <h3 class="font-bold text-lg">{{ $page.props.$t.cart.remove_product }}</h3>
            <p class="py-4">{{ $page.props.$t.cart.remove_product_desc }}</p>
            <div class="modal-action">
                <button class="btn btn-outline btn-primary" @click="removeProduct">Si</button>
                <form method="dialog">
                    <button class="btn">No</button>
                </form>
            </div>

        </div>

    </dialog>


</template>
