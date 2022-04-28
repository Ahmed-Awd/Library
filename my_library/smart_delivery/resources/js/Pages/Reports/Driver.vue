<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __("driver") }}
                </h2>
            </div>
            <div class="py-12">

                <div class="max-w-7xl mx-auto">
                    <div class="w-1/2 flex items-center justify-between pb-3">
                        <form class="w-1/2 flex items-center w-100" @submit.prevent="DriverSearch">
                            <multiselect
                                mode="single"
                                searchable=true
                                :placeholder="__('driver name')"
                                class="searched-select"
                                v-model="selectedDriver"
                                :options="drivers"
                                valueProp="id"
                                label="name"
                                trackBy="name"
                                canDeselect="false"
                                canClear="false"

                                @search-change="searchInSelect"
                                @change="DriverSearch"
                            >
                            </multiselect>
                        </form>
                    </div>
                    <div class="w-1/2 flex items-center w-full justify-between pb-3" v-if="showFilter">
                        <form class="w-100 flex items-center">
                            <div>
                                <jet-label for="start_date" :value="__('from')"/>
                                <jet-input id="start_date" type="date" class="mt-1 block w-full"
                                           v-model="serverParams.from"/>
                            </div>
                            <div class="ml-3">
                                <jet-label for="end_date" :value="__('to')"/>
                                <jet-input id="end_date" type="date" class="mt-1 block w-full"
                                           v-model="serverParams.to"/>
                            </div>
                            <div class="flex-grow ml-3">
                                <jet-label for="status" :value="__('Status')"/>
                                <multiselect
                                    v-model="serverParams.order_status"
                                    :options="order_status"
                                    valueProp="id"
                                    label="name"
                                    mode="tags"
                                    :placeholder="__('Select statuses')"
                                    id="status"
                                >
                                    <template v-slot:option="{ option }">
                                        <jet-input type="checkbox" class="mr-2"
                                                   :checked="selectedStatuses.includes(option.id.toString())"/>
                                        {{ option.name }}
                                    </template>
                                    0.8./835
                                </multiselect>
                            </div>
                            <div class="flex-grow ml-3">
                                <jet-label for="rating" :value="__('Rate')"/>
                                <multiselect
                                    v-model="serverParams.rate"
                                    :options="rating"
                                    valueProp="id"
                                    label="value"
                                    mode="tags"
                                    placeholder="Select Rates"
                                    id="rating"
                                >
                                </multiselect>
                            </div>
                            <div class="ml-3 mt-20">
                                <button class="btn orang-btn" @click.prevent="filter">{{ __("Filter") }}</button>
                            </div>
                            <div class="ml-3 mt-20">
                                <button class="btn green-btn" @click.prevent="print">{{ __("Print") }}</button>
                            </div>
                            <div class="ml-3 mt-20" style="flex:0 0 200px">
                                <a class="btn excel-btn" target="_blank" :href="xls_url">{{ __("Excel") }}</a>
                            </div>
                        </form>
                    </div>
                    <div id="element-to-print">
                        <div class="flex flex-col mt-3">
                            <div class="-my-2 overflow-x-auto">
                                <div class="py-2 align-middle inline-block min-w-full">
                                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("ID") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Order No") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Store") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Total") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Store Fee") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Delivery Price") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Driver Fee") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("distance") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Rate") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Status") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Date") }}
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" v-if="orders.length == 0">
                                            <tr>
                                                <td class="px-4 py-4 whitespace-nowrap" colspan="11">
                                                    <div class="flex flex-col">
                                                        <div class="loader" v-if="dataLoading"></div>
                                                        <div class="text-sm font-medium text-gray-900 text-center"
                                                             v-else>
                                                            {{ __("Driver Select Msg") }}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tbody class="bg-white divide-y divide-gray-200" v-else>
                                            <tr v-for="(order, key) in orders" :key="order.id">
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="flex flex-col">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ key + 1 }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{ order.order_number }}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{ order.store.name }}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{
                                                            order.total_price / 100
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{
                                                            order.store_fee / 100
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{
                                                            order.delivery_price / 100
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{
                                                            order.driver_fee / 100
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{
                                                            order.distance_store_order
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{ order.rate }}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{ order.order_status ? order.order_status.name : '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{
                                                            order.sent_at == null ? proccessDate(order.created_at) : proccessDate(order.sent_at)
                                                        }}
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="total-order" v-if="totals.length != 0">
                            <div>
                                <span class="bold-font">Orders Count : </span>
                                <span>{{ orders.length }}</span>
                            </div>
                            <div>
                                <span class="bold-font">Delivery Price : </span>
                                <span>{{ totals.delivery_price / 100 }}</span>
                            </div>
                            <div>
                                <span class="bold-font">Driver Fee : </span>
                                <span>{{ totals.driver_fee / 100 }}</span>
                            </div>
                            <div>
                                <span class="bold-font">Store Fee : </span>
                                <span>{{ totals.store_fee / 100 }}</span>
                            </div>
                            <div>
                                <span class="bold-font">Total Price : </span>
                                <span>{{ totals.total_price / 100 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Paginator from "@/Components/Paginator";
import Toggle from '@vueform/toggle'
import JetButton from '@/Jetstream/Button'
import Rate from '@/Components/Rate';
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import Multiselect from '@vueform/multiselect';
import Dropdown from '@/Jetstream/Dropdown.vue';
import DropdownLink from '@/Jetstream/DropdownLink.vue';
import Vue from "vue";
// import JsonExcel from "vue-json-excel";
import axios from 'axios';
import html2pdf from "html2pdf-jspdf2";
// import downloadexcel from "vue-json-excel";


export default {
    components: {
        AppLayout,
        Paginator,
        Toggle,
        JetButton,
        Rate,
        JetFormSection,
        JetInput,
        JetLabel,
        Multiselect,
        Dropdown,
        DropdownLink,
        // JsonExcel,
        html2pdf,
        // downloadexcel
    },
    props: {
        drivers: Array,
        order_status: Array
    },
    mounted() {
        const date = new Date(), y = date.getFullYear(), m = date.getMonth();
        this.serverParams.from =  new Date(y, m, 2).toISOString().split('T')[0];
        this.serverParams.to =  new Date(y, m + 1, 1).toISOString().split('T')[0];
        console.log(this.serverParams);
    },
    data() {
        return {
            selectedDriver: '',
            selectedStatuses: [],
            search: null,
            serverParams: {
                from: '',
                to: '',
                order_status: null,
                rate: null
            },
            showFilter: false,
            driversArr: [],
            original_url: "/exports/of/driver/",
            xls_url: "/exports/of/driver/",
            rating: [
                {id: 1, value: 1},
                {id: 2, value: 2},
                {id: 3, value: 3},
                {id: 4, value: 4},
                {id: 5, value: 5},
            ],
            totals: [],
            orders: [],
            dataLoading: false,
        };
    },
    methods: {
        DriverSearch() {
            console.log("Selected Driver ID " + this.selectedDriver);
            this.showFilter = true;
        },
        filter() {
            this.dataLoading = true;
            axios.get(`/reports/of/driver/${this.selectedDriver}`, {
                params: this.serverParams,
            })
                .then((response) => {
                    this.orders = response.data.data.orders;
                    this.totals = response.data.data.totals;
                    console.log(response.data.data.orders);
                    this.dataLoading = false;
                }).catch((errors) => {
                console.log(errors.data);
                this.dataLoading = false;
            });
            // this.xls_url = http://localhost:8000/reports/of/driver/232?from=2021-12-06&to=2022-02-15
            // http://localhost:8000/reports/of/driver/232?from=2022-01-03&to=2022-02-06&order_status[]=1&order_status[]=3
            this.xls_url = '/exports/of/driver/' + this.selectedDriver;
            if (this.serverParams.from != '') {
                this.xls_url += '?from=' + this.serverParams.from;
            }
            if (this.serverParams.to != '') {
                this.xls_url += '&to=' + this.serverParams.to;
            }
            if (this.serverParams.order_status != null) {
                for (let index = 0; index < this.serverParams.order_status.length; index++) {
                    this.xls_url += '&order_status[]=' + this.serverParams.order_status[index];
                }
            }
            if (this.serverParams.rate != null) {
                for (let index = 0; index < this.serverParams.rate.length; index++) {
                    this.xls_url += '&rate[]=' + this.serverParams.rate[index];
                }
            }

            console.log(this.xls_url);
        },
        proccessDate(date) {
            return Intl.DateTimeFormat('en-BZ', {
                month: '2-digit',
                day: '2-digit',
                year: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            }).format(new Date(date))
        },
        print() {
            console.log('print function');
            var element = document.getElementById("element-to-print");
            var opt = {
                margin: 0.25,
                filename: "orders.pdf",
                image: {type: "jpeg", quality: 0.98},
                html2canvas: {scale: 2},
                jsPDF: {
                    unit: "in",
                    format: "letter",
                    orientation: "portrait",
                },
                pagebreak: {mode: ["avoid-all", "css", "legacy"]},
            };

            html2pdf().set(opt).from(element).save();
        },
        searchInSelect() {
            // let searchedText = document.getElementsByClassName("multiselect-search")[0].value;
            // console.log(searchedText);
            // return this.drivers.filter(driver => driver.phone.includes(searchedText));
        },
        exportToExcel() {
            // this.$router.push({ ${this.xls_url}${this.selectedDriver}, params: this.serverParams });
        },
    },

}
</script>
<style src="@vueform/toggle/themes/default.css"></style>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>

</style>
