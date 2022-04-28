<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __("General Report") }}
                </h2>
            </div>
            <div class="py-12">

            <div class="max-w-7xl mx-auto">
                <div class="w-1/2 flex items-center w-full justify-between pb-3" >
                    <form class="w-100 flex items-center">
                        <div class="w-50">
                            <jet-label for="driver" :value="__('driver')"/>
                            <multiselect
                                    mode="single"
                                    searchable=true
                                    :placeholder="__('driver name')"
                                    class="searched-select mr-10"
                                    v-model="serverParams.driver"
                                    :options="drivers"
                                    valueProp="id"
                                    label="name"
                                    trackBy="name"
                                    @search-change="searchInSelect"
                                    id="driver"
                                >
                            </multiselect>
                        </div>
                        <div class="w-50 ml-3">
                            <jet-label for="store" :value="__('Store')"/>
                            <multiselect
                                mode="single"
                                searchable=true
                                :placeholder="__('store name')"
                                class="searched-select"
                                v-model="serverParams.store"
                                :options="stores"
                                valueProp="id"
                                label="name"
                                trackBy="name"
                                @search-change="searchInSelect"
                                id="store"
                            >
                            </multiselect>
                        </div>
                        <div class="w-50 ml-3">
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
                            </multiselect>
                        </div>
                        <div class="ml-3">
                            <jet-label for="start_date" :value="__('from')"/>
                            <jet-input id="start_date" type="date" class="mt-1 block w-full" v-model="serverParams.from"/>
                        </div>
                        <div class="ml-3">
                            <jet-label for="end_date" :value="__('to')"/>
                            <jet-input id="end_date" type="date" class="mt-1 block w-full" v-model="serverParams.to"/>
                        </div>
                    </form>
                </div>
                <div class="w-1/2 flex items-center w-full justify-end pb-3">
                    <div class="justify-end flex items-center">
                        <div class="ml-3 mt-20">
                            <button class="btn orang-btn" @click.prevent="filter">{{ __("Filter") }}</button>
                        </div>
                        <div class="ml-3 mt-20">
                            <button class="btn green-btn" @click.prevent="print">{{ __("Print") }}</button>
                        </div>
                        <div class="ml-3 mt-20">
                            <a class="btn excel-btn" target="_blank" :href="xls_url">{{ __("Excel") }}</a>
                        </div>
                    </div>
                </div>
                <div id="element-to-print">
                    <div class="flex flex-col mt-3">
                        <div class="-my-2 overflow-x-auto">
                            <div class="py-2 align-middle inline-block min-w-full">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("ID") }}
                                                </th>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Order No") }}
                                                </th>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Store") }}
                                                </th>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("driver") }}
                                                </th>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{__("driver phone")}}
                                                </th>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Delivery Price") }}
                                                </th>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("distance") }}
                                                </th>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                     {{ __("Delivery Fee")}}
                                                </th>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Store Fee") }}
                                                </th>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Date") }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" v-if="orders.length == 0">
                                            <tr>
                                                <td class="px-4 py-4 whitespace-nowrap" colspan="9">
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
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="flex flex-col">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{key + 1}}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.order_number}}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.store.name}}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.driver ? order.driver.name : ''}}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.driver ? `${order.driver.country_code} ${order.driver.phone}`: ''}}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.delivery_price / 100}}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.distance_store_order }}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.driver_fee / 100}}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{order.store_fee /100 }}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{proccessDate(order.sent_at)  }}</div>
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
                            <span class="bold-font">{{__("Orders Count")}} : </span>
                            <span>{{ orders.length }}</span>
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
                            <span class="bold-font">{{ __("Store Fee") }} : </span>
                            <span>{{ totals.store_fee / 100}}</span>
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
        drivers: Array,
        stores: Array,
        order_status: Array
    },
    mounted() {
        const date = new Date(), y = date.getFullYear(), m = date.getMonth();
        this.serverParams.from =  new Date(y, m, 2).toISOString().split('T')[0];
        this.serverParams.to =  new Date(y, m + 1, 1).toISOString().split('T')[0];
        console.log('order status' , this.order_status);
    },
    data() {
        return {
            selectedStatuses: [],
            serverParams: {
                from: '',
                to: '',
                driver: null,
                store: null,
                order_status: null
            },
            orders: [],
            dataLoading: false,
            totals: [],
            xls_url:"/exports/of/all/app/",
        };
    },
    methods:{
        filter() {
            this.dataLoading = true;
            axios.get(`/reports/of/all/app`,{
                params: this.serverParams,
            })
            .then((response) => {
                this.orders = response.data.data.orders;
                this.totals = response.data.data.totals;
                console.log(response.data.data);
                this.dataLoading = false;
            }).catch((errors) => {
                console.log(errors.data);
            });

            this.xls_url = '/exports/of/all/app/';
            // if(this.serverParams.from != ''){
            this.xls_url+= '?from='+this.serverParams.from;
            // }
            // if(this.serverParams.to != ''){
            this.xls_url+= '&to='+this.serverParams.to;
            // }
            if(this.serverParams.store != null){
                this.xls_url+= '&store='+this.serverParams.store;
            }
            if(this.serverParams.driver != null){
                this.xls_url+= '&driver='+this.serverParams.driver;
            }
            if( this.serverParams.order_status != null ){
                for (let index = 0; index < this.serverParams.order_status.length; index++) {
                    this.xls_url+= '&order_status[]='+this.serverParams.order_status[index];
                }
            }
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
    },

}
</script>
<style src="@vueform/toggle/themes/default.css"></style>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>

</style>
