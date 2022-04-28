<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __("Drivers") }}
                </h2>
            </div>
            <div class="py-12">

            <div class="max-w-7xl mx-auto">
                <div class="w-1/2 flex items-center w-full justify-between pb-3">
                    <form class="w-100 flex items-center">
                        <div>
                            <jet-label for="start_date" :value="__('driver name')"/>
                            <jet-input type="text" class="mt-1 block mr-2" v-model="serverParams.filter" :placeholder="__('Search by Name')"/>
                        </div>
                        <div>
                            <jet-label for="start_date" :value="__('from')"/>
                            <jet-input id="start_date" type="date" class="mt-1 block w-full" v-model="serverParams.from"/>
                        </div>
                        <div class="ml-3">
                            <jet-label for="end_date" :value="__('to')"/>
                            <jet-input id="end_date" type="date" class="mt-1 block w-full" v-model="serverParams.to"/>
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
                                </template>0.8./835
                            </multiselect>
                        </div>
                        <div class="ml-3 mt-20">
                            <button class="btn orang-btn" @click.prevent="filter">{{ __("Filter") }}</button>
                        </div>
                        <div class="ml-3 mt-20">
                            <button class="btn green-btn" @click.prevent="print">{{ __("Print") }}</button>
                        </div>
                        <div class="ml-3 mt-20"  style="flex:0 0 200px">
                            <a class="btn excel-btn" target="_blank" :href="xls_url">{{ __("Excel") }}</a>
                        </div>
                    </form>
                </div>
                <div id="element-to-print">
                    <div class="flex flex-col mt-3">
                        <div class="-my-2 overflow-x-auto ">
                            <div class="py-2 align-middle inline-block min-w-full">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("ID") }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Name") }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Phone Number") }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Total orders count") }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Total Delivery price") }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Total Orders Price") }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Total Delivery Fee") }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Rate") }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" v-if="orders.length == 0">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap" colspan="9">
                                                    <div class="flex flex-col">
                                                        <div class="loader" v-if="dataLoading"></div>
                                                        <div class="text-sm font-medium text-gray-900 text-center" v-else>
                                                            {{ __("Filter Msg") }}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody class="bg-white divide-y divide-gray-200" v-else>
                                            <tr v-for="(order, key) in orders" :key="order.id">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex flex-col">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{key + 1}}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.name}}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.country_code }} {{ order.phone }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.orders_count }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.driver_sum_delivery_price / 100 }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.drivers_sum_total_price / 100 }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.total_fee / 100 }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.avarage_rate ? parseFloat(order.avarage_rate).toFixed(2) : "Not Rated" }}</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="total-order">
                        <div>
                            <span class="bold-font">{{__("Orders Count")}} : </span>
                            <span>{{ totals.orders_count }}</span>
                        </div>
                        <div>
                            <span class="bold-font">{{ __("Delivery Price") }} : </span>
                            <span>{{ totals.delivery_price / 100}}</span>
                        </div>
                        <div>
                            <span class="bold-font">{{ __("Driver Fee") }} : </span>
                            <span>{{ totals.driver_fee / 100}}</span>
                        </div>
                        <div>
                            <span class="bold-font">{{ __("Total Price") }} : </span>
                            <span>{{ totals.total_price / 100}}</span>
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
        html2pdf
    },
    props: {
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
            selectedStatuses: [],
            serverParams: {
                from: '',
                to: '',
                filter: '',
                order_status: null,
            },
            orders: [],
            totals:{
                total_price : 0,
                delivery_price : 0,
                orders_count : 0,
                driver_fee : 0
            },
            dataLoading: false,
            xls_url:"/exports/of/all/drivers/",
        };
    },
    methods:{
        filter() {
            this.dataLoading = true;
            axios.get(`/reports/of/all/drivers`,{
                params: this.serverParams,
            })
            .then((response) => {
                this.orders = response.data.data;
                this.totals = response.data.totals;
                this.dataLoading = false;
            }).catch((errors) => {
                console.log(errors.data);
            });

            this.xls_url = '/exports/of/all/drivers/';
            // if(this.serverParams.from != ''){
            this.xls_url+= '?from='+this.serverParams.from;
            // }
            // if(this.serverParams.to != ''){
            this.xls_url+= '&to='+this.serverParams.to;
            // }
            if(this.serverParams.filter != ''){
                this.xls_url+= '&filter='+this.serverParams.filter;
            }
            if( this.serverParams.order_status != null ){
                for (let index = 0; index < this.serverParams.order_status.length; index++) {
                    this.xls_url+= '&order_status[]='+this.serverParams.order_status[index];
                }
            }
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
        print(){
            console.log('print function');
            var element = document.getElementById("element-to-print");
            var opt = {
                margin: 0.25,
                filename: "orders.pdf",
                image: { type: "jpeg", quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: {
                unit: "in",
                format: "letter",
                orientation: "portrait",
                },
                pagebreak: { mode: ["avoid-all", "css", "legacy"] },
            };

            html2pdf().set(opt).from(element).save();
        }
    },

}
</script>
<style src="@vueform/toggle/themes/default.css"></style>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>

</style>
