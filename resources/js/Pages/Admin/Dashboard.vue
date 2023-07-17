<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pie from "@/Components/Charts/Pie.vue";
import Bar from "@/Components/Charts/Bar.vue";
import { Head } from '@inertiajs/vue3';
import { ref } from "vue";
import { jsPDF } from "jspdf";

const startDate = ref('');
const endDate = ref('');

const bestProductsChart = ref([]);
const bestCategoriesChart = ref([]);
const desiredProductsChart = ref([]);
const bestCustomersChart = ref([]);
const ordersStatusChart = ref([]);
const paymentsStatusChart = ref([]);

const loadReports = () => {
    axios.post(route('api.reports'), {start_date: startDate.value, end_date: endDate.value})
        .then((resp) => {
            bestProductsChart.value = resp.data.data.best_products;
            bestCategoriesChart.value = resp.data.data.best_categories;
            desiredProductsChart.value = resp.data.data.desired_products;
            bestCustomersChart.value = resp.data.data.best_customers;
            ordersStatusChart.value = resp.data.data.orders_status;
            paymentsStatusChart.value = resp.data.data.payments_status;
        }).catch((err) => console.log(err));
}

const pdf = () => {
    const doc = new jsPDF();
    doc.setFontSize(20);

    doc.text("10 Productos más vendidos", 10, 10,);
    let canvas1 = document.querySelector('#best_products2');
    let img1 = canvas1.toDataURL("image/jpeg", 1.0);
    doc.addImage(img1, 'JPEG', 10, 20, 180, 100);

    doc.text("10 Categorías más vendidas", 10, 140,);
    let canvas2 = document.querySelector('#best_categories');
    let img2 = canvas2.toDataURL("image/jpeg", 1.0);
    doc.addImage(img2, 'JPEG', 10, 150, 180, 100);

    doc.addPage();

    doc.text("10 Productos más deseados", 10, 10,);
    let canvas3 = document.querySelector('#desired_products');
    let img3 = canvas3.toDataURL("image/jpeg", 1.0);
    doc.addImage(img3, 'JPEG', 10, 20, 180, 100);

    doc.text("10 mejores clientes", 10, 140,);
    let canvas4 = document.querySelector('#best_customers');
    let img4 = canvas4.toDataURL("image/jpeg", 1.0);
    doc.addImage(img4, 'JPEG', 10, 150, 180, 100);

    doc.addPage();

    doc.text("Pedidos por estado", 10, 10,);
    let canvas5 = document.querySelector('#orders_status');
    let img5 = canvas5.toDataURL("image/jpeg", 1.0);
    doc.addImage(img5, 'JPEG', 10, 20, 100, 100);

    doc.text("Pagos por estado", 10, 140,);
    let canvas6 = document.querySelector('#payments_status');
    let img6 = canvas6.toDataURL("image/jpeg", 1.0);
    doc.addImage(img6, 'JPEG', 10, 150, 100, 100);

    doc.save(`reporte ${startDate.value} a ${endDate.value}.pdf`);
}
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <strong>Reportes</strong><br>

                        <form class="flex mb-3" @submit.prevent="loadReports">
                            <div class="form-control w-full max-w-xs">
                                <label class="label">
                                    <span class="label-text">Fecha inicial</span>
                                </label>
                                <input type="date" name="start_date" id="start_date" v-model="startDate"
                                       class="input input-bordered w-full max-w-xs" required/>
                            </div>

                            <div class="form-control w-full max-w-xs ml-2">
                                <label class="label">
                                    <span class="label-text">Fecha final</span>
                                </label>
                                <input type="date" name="end_date" id="end_date" v-model="endDate"
                                       class="input input-bordered w-full max-w-xs" required/>
                            </div>

                            <button class="btn btn-primary self-end ml-2" type="submit">CONSULTAR</button>
                            <button class="btn  self-end ml-2" @click="pdf" type="button">
                                <i class="fa fa-download"></i>
                            </button>
                        </form>
                        <hr>
                        <div class="grid grid-cold-1 md:grid-cols-2 mt-4">
                            <Bar id="best_products2" :data="bestProductsChart" label="Unidades vendidas"
                                 title="10 Productos más vendidos"/>
                            <Bar id="best_categories" :data="bestCategoriesChart" label="Unidades vendidas"
                                 title="10 Categorías más vendidas"/>
                            <Bar id="desired_products" :data="desiredProductsChart" label="Unidades pedidas"
                                 title="10 Productos más deseados"/>
                            <Bar id="best_customers" :data="bestCustomersChart" label="Pedidos realizados"
                                 title="10 mejores clientes"/>
                            <Pie id="orders_status" :data="ordersStatusChart" label="Pedidos"
                                 title="Pedidos por estado"/>
                            <Pie id="payments_status" :data="paymentsStatusChart" label="Pagos"
                                 title="Pagos por estado"/>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
