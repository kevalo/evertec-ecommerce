<script setup>
import { Head } from "@inertiajs/vue3";
import PageLogo from "@/Components/PageLogo.vue";
import UserMenu from "@/Components/UserMenu.vue";
import CartIcon from "@/Components/CartIcon.vue";

defineProps({
    order: Object,
    products: Object,
    status: Object,
    newPayment: Boolean,
    currentPaymentUrl: String | Boolean
});

const token = document.getElementById('_token').value;

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
        <h2 class="w-full text-center p-5 uppercase">{{ $page.props.$t.orders.title }} #{{ order.code }}</h2>
        <h3 class="w-full text-center p-5 uppercase">{{ $page.props.$t.fields.status }}:
            {{ $page.props.$t.orders.status[order.status] }}</h3>
    </div>

    <div class="grid grid-cols-1 gap-4 px-4 drop-shadow-md">

        <div v-for="product in order.products" class="flex mx-auto items-center shadow-md pr-4">
            <figure style="object-fit: contain;">
                <img :src="`/storage/${product.image}`" class="drop-shadow-md mx-auto" :alt="product.name"
                     style="width: 150px;"/>
            </figure>
            <div class="ms-3">
                <h2 class="card-title"><a :href="route('product-detail', product.slug)">{{ product.name }}</a></h2>
                <h3>{{ $page.props.$t.products.unit_price }}: ${{ product.pivot.price.toLocaleString('es-CO') }}</h3>
                <h3>{{ $page.props.$t.fields.quantity }}: {{ product.pivot.quantity }}</h3>
                <h3>{{ $page.props.$t.labels.subtotal }}:
                    ${{ (product.pivot.total).toLocaleString('es-CO') }}</h3>
            </div>
        </div>

    </div>

    <div class="alert alert-info w-full md:w-2/4 xl:w-1/4 mx-auto mt-8"
         v-if="order.status === $page.props.status.created">
        {{ $page.props.$t.orders.time_limit_message }}
    </div>

    <div class="prose mx-auto mt-16">
        <h2 class="text-center">{{ $page.props.$t.labels.total }}: ${{ order.total_price.toLocaleString('es-CO') }}</h2>
    </div>

    <div class="pt-4">
        <div class="alert alert-warning w-full sm:w-full md:w-2/4 xl:w-1/4 mx-auto" v-if="!$page.props.auth.user">
            {{ $page.props.$t.auth.do_login }}: <a :href="route('login')" class="underline text-primary">
            {{ $page.props.$t.auth.login }}
        </a>
        </div>
        <form :action="route('payment.create')" method="post" v-else-if="newPayment">
            <input type="hidden" name="_token" :value="token">
            <input type="hidden" name="order_id" :value="order.id">
            <input type="hidden" name="payment_type" value="place_to_pay">
            <button type="submit"
                    class="btn btn-primary block mx-auto">
                {{ $page.props.$t.cart.go_pay }}
            </button>
        </form>
        <div v-else-if="currentPaymentUrl !== false" class="flex">
            <a :href="currentPaymentUrl" class="btn btn-primary  mx-auto">{{ $page.props.$t.cart.go_pay }}</a>
        </div>

    </div>

</template>
