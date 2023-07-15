<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import PageLogo from "@/Components/PageLogo.vue";
import UserMenu from "@/Components/UserMenu.vue";
import { ref } from "vue";
import { useCartStore } from "@/Stores/CartStore";
import CartIcon from "@/Components/CartIcon.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    product: Object,
    category: Object
});

const product = usePage().props.product;

const amount = ref(1);
const showAlert = ref(false);

const showStockError = ref(false);

const store = useCartStore();

const addToCart = () => {
    if (amount.value < 1) {
        return;
    }

    let totalAmount = amount.value
    if (store.products.hasOwnProperty(product.id)) {
        totalAmount += store.products[product.id];
    }

    axios.post(route('api.products.checkStock'), {
        'id': product.id,
        amount: totalAmount
    }).then((r) => {
        const response = r.data;
        if (response.data.stock) {
            store.add(product.id, amount.value);
            showAlert.value = true;
            setTimeout(() => showAlert.value = false, 2000);
        } else {
            showStockError.value = true;
            setTimeout(() => showStockError.value = false, 5000);
        }
    }).catch((e) => console.log(e));
}
</script>

<template>
    <Head :title="product.name"/>

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
        <h2 class="w-full text-center p-5">{{ product.name }}</h2>
    </div>

    <div class="px-4 sm:columns-1 md:columns-2 ">

        <div class="mb-3">
            <figure style="object-fit: contain;">
                <img v-if="product.image" :src="`/storage/${product.image}`"
                     class="max-w-full mx-auto drop-shadow-md rounded"
                     :alt="product.name"
                     style="max-height: 720px;"/>
                <img v-else :src="`https://placehold.co/600x400?text=${product.name}`"
                     class="max-w-full mx-auto drop-shadow-md rounded"
                     :alt="product.name"
                     style="max-height: 720px;"/>
            </figure>
        </div>

        <div>
            <span class="badge badge-outline block h-fit">{{ category.name }}</span>

            <div class="prose mt-3">

                <p>{{ product.description }}</p>
                <h3>${{ product.price.toLocaleString('es-CO') }}</h3>
            </div>

            <div class="pt-8">
                <span>{{ $page.props.$t.cart.add }}</span>
                <div class="flex">

                    <input type="number" class="input input-bordered w-20" min="1" v-model="amount">
                    <button class="btn btn-outline ml-1" @click="addToCart">
                        <i class="fa fa-cart-plus"></i>
                    </button>
                </div>
                <div v-if="showStockError" class="alert alert-warning w-2/4 mt-2">
                    {{ $page.props.$t.labels.stock_error }}
                </div>
            </div>
            <small>{{ $page.props.$t.labels.stock }}: {{ product.quantity }}</small>

            <div class="toast toast-middle toast-end " v-if="showAlert">
                <div class="alert alert-success">
                    <span class="badge" id="amount">{{ amount }}</span><span>{{ $page.props.$t.cart.added }}</span>
                </div>
            </div>
        </div>


    </div>


</template>
<style>
.prose {
    max-width: 90% !important;
}
</style>
