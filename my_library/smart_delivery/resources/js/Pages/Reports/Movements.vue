<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __("Orders Movements") }}
                </h2>
            </div>

            <div class="py-12">

                <div class="max-w-7xl mx-auto">
                    <div class="w-1/2  items-center w-full justify-between pb-3">
                        <div class="w-100 flex items-center mb-4">
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
                            <div class="ml-3 w-50">
                                <jet-label for="range" :value="__('range')"/>
                                <!-- <multiselect
                                    mode="single"
                                    searchable=flse
                                    class="searched-select mr-10 full-width"
                                    v-model="serverParams.range"
                                    :options="dateFilters"
                                    valueProp="value"
                                    :label="__('name')"
                                    id="range"
                                >
                                </multiselect> -->
                                <dropdown>
                                    <template #trigger>
                                        <div
                                            class="flex items-center text-gray-400 capitalize border px-4 py-2 rounded-md border-gray-300 cursor-pointer hover:bg-gray-200">
                                            <icon name="date" class="w-5 h-5 mr-2"/>
                                            {{ serverParams.range }}
                                            <icon name="dropdown" class="w-4 h-4 ml-2"/>
                                        </div>
                                    </template>
                                    <template #content>
                                        <dropdown-link v-for="filter in dateFilters" :key="filter" as="button"
                                                    @click="serverParams.range = filter">
                                            <span class="capitalize">{{ __(filter) }}</span>
                                        </dropdown-link>
                                        <div class="border-t border-gray-200"></div>
                                        <dropdown-link as="button" button-type="button" @click="showDateModal = true">
                                            <span class="capitalize">{{__('Custom')}}</span>
                                        </dropdown-link>
                                    </template>
                                </dropdown>
                            </div>
                            <div v-if="serverParams.range === 'custom'">
                            </div>
                        </div>
                        <div class="w-100 flex items-center">
                            <div class="w-50">
                                <jet-label for="status" :value="__('Status')"/>
                                <multiselect
                                    mode="single"
                                    searchable=flse
                                    class="searched-select mr-10 full-width"
                                    v-model="serverParams.change_status"
                                    :options="statusFilters"
                                    valueProp="value"
                                    :label="__('name')"
                                    id="status"
                                    :disabled="serverParams.no_interval"
                                >
                                </multiselect>
                            </div>
                        
                            <div class="ml-3 small-width">
                                <jet-label for="from_time" :value="__('from')+' '+__('time')"/>
                                <jet-input id="from_time" type="number" min="1" v-model="serverParams.from_time" :disabled="serverParams.no_interval"/>
                            </div>
                            <div class="ml-3 small-width">
                                <jet-label for="to_time" :value="__('to')+' '+__('time')"/>
                                <jet-input id="to_time" type="number" min="1" v-model="serverParams.to_time" :disabled="serverParams.no_interval"/>
                            </div>
                            <div class="checkbox-div auto-width ml-3 mt-4">
                                <input
                                    type="checkbox"
                                    class="block mr-2"
                                    id="interval"
                                    v-model="serverParams.no_interval"
                                />
                                <label for="interval">{{__("Disable Time")}}</label>
                            </div>
                            <div class="ml-auto mt-20">
                                <button class="btn orang-btn" @click.prevent="filter">{{ __("Filter") }}</button>
                            </div>
                            <!-- <div class="ml-3 mt-20">
                                <button class="btn green-btn" @click.prevent="print">{{ __("Print") }}</button>
                            </div>
                            <div class="ml-3 mt-20"  style="flex:0 0 200px">
                                <a class="btn excel-btn" target="_blank" :href="xls_url">{{ __("Excel") }}</a>
                            </div> -->
                        </div>
                    </div>
                    <dialog-modal :show="showDateModal == true" @close="showDateModal == false">
                        <template #title>
                            {{__('Choose date range')}}
                        </template>
                        <template #content>
                            <div>
                                <jet-label for="start_date" value="From"/>
                                <jet-input id="start_date" type="date" class="mt-1 block w-full" v-model="dateFrom"/>
                            </div>
                            -
                            <div>
                                <jet-label for="end_date" value="To"/>
                                <jet-input id="end_date" type="date" class="mt-1 block w-full" v-model="dateTo"/>
                            </div>
                        </template>

                        <template #footer>
                            <jet-secondary-button @click="showDateModal = false">
                                {{__('Cancel')}}
                            </jet-secondary-button>

                            <jet-button class="ml-2" @click="chooseCustomDate">
                                {{__('Save')}}
                            </jet-button>
                        </template>
                    </dialog-modal>
                    <div id="element-to-print">
                        <div class="flex flex-col mt-3">
                            <div class="-my-2 overflow-x-auto">
                                <div class="py-2 align-middle inline-block min-w-full">
                                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("ID") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Order No") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Store") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("driver") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("distance") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Date") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Created At") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Accepted By Driver At") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Taken From Store At") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("delivered at") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Created At") }} -> {{ __("Accepted By Driver At") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Accepted By Driver At") }} -> {{ __("Taken From Store At") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Taken From Store At") }} -> {{ __("delivered at") }}
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Total") }} {{ __("time") }}
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200" v-if="orders.length == 0">
                                            <tr>
                                                <td class="px-4 py-4 whitespace-nowrap" colspan="9">
                                                    <div class="flex flex-col">
                                                        <div class="loader" v-if="dataLoading"></div>
                                                        <div class="text-sm font-medium text-gray-900 text-center"
                                                             v-else>
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
                                                            {{ key + 1 }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{ order.order_number }}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{ order.store ? order.store.name : '' }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{ order.driver ? order.driver.name : '' }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{ order.distance_store_order }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{ order.created_at ? proccessDay(order.created_at) : '' }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{ order.created_at ? proccessDate(order.created_at) : '' }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{
                                                            order.accepted_by_driver_at ? proccessDate(order.accepted_by_driver_at) : ''
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{
                                                            order.order_taken_from_store_at ? proccessDate(order.order_taken_from_store_at) : ''
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{
                                                            order.order_delivered_at ? proccessDate(order.order_delivered_at) : ''
                                                        }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                            {{   order.accepting_time || 0  }} m
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{   order.taking_time || 0  }} m
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{   order.deliver_time || 0  }} m
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{   order.total_time || 0 }} m
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="total-order" v-if="orders.length != 0">
                        <div>
                            <span class="bold-font">{{ __("Orders Count") }} : </span>
                            <span>{{ orders.length }}</span>
                        </div>
                        <div>
                            <span class="bold-font">{{ __("Average accepting time") }} : </span>
                            <span>{{ (averages.accepting_time).toFixed(2) }} mins</span>
                        </div>
                        <div>
                            <span class="bold-font">{{ __("Average taking order time") }} : </span>
                            <span>{{ (averages.taking_time).toFixed(2) }} mins</span>
                        </div>
                        <div>
                            <span class="bold-font">{{ __("Average delivering time") }} : </span>
                            <span>{{ (averages.deliver_time).toFixed(2) }} mins</span>
                        </div>
                        <div>
                            <span class="bold-font">{{ __("Average total time") }} : </span>
                            <span>{{ (averages.total_time).toFixed(2) }} mins</span>
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
import moment from 'moment'
import Button from "../../Jetstream/Button";
import DialogModal from '../../Jetstream/DialogModal.vue';

export default {
    components: {
        Button,
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
        DialogModal
    },
    props: {
        drivers: Array,
        stores: Array
    },
    mounted() {
    },
    data() {
        return {
            serverParams: {
                driver: null,
                store: null,
                range: 'yesterday',
                from_time: 1,
                to_time: 2,
                change_status: "accepted_by_driver_at",
                no_interval: false
            },
            dateFrom: null,
            dateTo: null,
            orders: [],
            dataLoading: false,
            averages:{
                accepting_time:0,
                taking_time:0,
                deliver_time:0,
                total_time:0
            },
            xls_url: "/exports/of/all/app/",
            // dateFilters: [
            //     {name: 'all', value: 'false'},
            //     {name: 'today', value: 'today'},
            //     {name: 'yesterday', value: 'yesterday'},
            //     {name: 'this-week', value: 'this-week'},
            //     {name: 'prev-week', value: 'prev-week'},
            //     {name: 'this-month', value: 'this-month'},
            //     {name: 'prev-month', value: 'prev-month'},
            // ],
            dateFilters: [
                'all', 'today', 'yesterday','this-week', 'prev-week', 'this-month', 'prev-month'
            ],
            statusFilters: [
                {name: "accepting time", value: "accepted_by_driver_at"},
                {name: "taking from store time", value: "order_taken_from_store_at"},
                {name: "delivering time", value: "order_delivered_at"},
                {name: "all process time", value: "all_way_long"},
            ],
            showDateModal: false,
        };
    },
    methods: {
        filter() {
            axios.get(`/get-orders-movement`, {
                params: this.serverParams,
            })
                .then((response) => {
                    this.orders = response.data.orders;
                    this.averages = response.data.averages;
                    console.log(this.averages);
                }).catch((errors) => {
                    console.log(errors.data);
                });
        },
        proccessDate(date) {
            return Intl.DateTimeFormat('en-BZ', {
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            }).format(new Date(date))
        },
        proccessDay(date) {
            return Intl.DateTimeFormat('en-BZ', {
                month: '2-digit',
                day: '2-digit',
                year: 'numeric',
            }).format(new Date(date))
        },
        chooseCustomDate() {
            this.showDateModal = false;
            this.serverParams.range = this.dateFrom + ',' + this.dateTo;
            // this.filter();
        },
    },

}
</script>
<style src="@vueform/toggle/themes/default.css"></style>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>

</style>
