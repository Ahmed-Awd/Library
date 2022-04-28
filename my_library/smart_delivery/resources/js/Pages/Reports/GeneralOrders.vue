<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __("General")}} {{__("Store")}} {{__("orders") }}
                </h2>
            </div>
            <div class="py-12">
                    <div class="max-w-7xl mx-auto">
                    <div class=" flex items-center w-full pb-3">
                        <!-- <form class="w-100 flex items-center"> -->
                            <div class="w-50 mr-4">
                                <jet-label for="status" :value="__('Status')"/>
                                <multiselect
                                    v-model="serverParams.order_status"
                                    :options="order_status"
                                    class="mt-1"
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
                            <div>
                                <jet-label for="start_date" :value="__('from')"/>
                                <jet-input id="start_date" type="date" class="mt-1 block w-full" v-model="serverParams.from"/>
                            </div>
                            <div class="ml-3">
                                <jet-label for="end_date" :value="__('to')"/>
                                <jet-input id="end_date" type="date" class="mt-1 block w-full" v-model="serverParams.to"  :min="serverParams.from"/>
                            </div>
                            <jet-button class="ml-2 mt-5" @click.prevent="filter">
                                <icon name="search" class="w-5 h-5"/>
                            </jet-button>
                        <!-- </form> -->
                    </div>
                    <div id="element-to-print">
                        <div class="flex flex-col mt-3">
                            <div class="-my-2 overflow-x-auto ">
                                <div class="py-2 flex align-middle inline-block min-w-full" >
                                    <div class="flex shadow overflow-hidden border-b border-gray-200 sm:rounded-lg" v-if="showReport">
                                        <div class="w-1/3 flex mr-2">
                                        <table class="min-w-full divide-y divide-gray-200" >
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        colspan="4"
                                                        class="px-2 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider orange-td text-center">
                                                        {{__('Smart Delivery')}}
                                                    </th>
                                                </tr>

                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr>
                                                    <th scope="col"
                                                        colspan="4"
                                                        class="px-2 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider orange-td text-center">
                                                        {{__('FreeLancers')}}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        {{__('Date')}}
                                                    </th>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        FL
                                                    </th>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        O/Day
                                                    </th>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        TL
                                                    </th>
                                                </tr>
                                                <tr v-for="(raw, key) in data">
                                                    <td class="px-4 py-3 lightorange-td text-center whitespace-nowrap">
                                                        {{ key }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        {{ raw.free_lancers.active_drivers }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 yellow-td text-center">
                                                        {{ raw.free_lancers.orders_count }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        {{ raw.free_lancers.driver_fee / 100 }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 lightorange-td text-center whitespace-nowrap">
                                                        {{ __("Total")}}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        -
                                                    </td>
                                                    <td class="px-4 py-3 w-150 yellow-td text-center">
                                                        {{ totals["free_lancers_orders"] }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        {{ totals["free_lancers_fee"] / 100 }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </div>
                                        <div class="w-1/3 flex mr-2">
                                        <table class="min-w-full divide-y divide-gray-200" >
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        colspan="4"
                                                        class="px-2 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider orange-td text-center">
                                                        {{__('Smart Delivery')}}
                                                    </th>
                                                </tr>

                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr>
                                                    <th scope="col"
                                                        colspan="4"
                                                        class="px-2 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider orange-td text-center">
                                                        {{__("app")}} {{__('Drivers')}}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        {{__('Date')}}
                                                    </th>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        AD
                                                    </th>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        O/Day
                                                    </th>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        TL
                                                    </th>
                                                </tr>
                                                <tr v-for="(raw, key) in data">
                                                    <td class="px-4 py-3 lightorange-td text-center whitespace-nowrap">
                                                        {{ key }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        {{ raw.app_drivers.active_drivers }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 yellow-td text-center">
                                                        {{ raw.app_drivers.orders_count }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        {{ raw.app_drivers.driver_fee / 100 }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 lightorange-td text-center whitespace-nowrap">
                                                        {{ __("Total")}}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        -
                                                    </td>
                                                    <td class="px-4 py-3 w-150 yellow-td text-center">
                                                        {{ totals["app_drivers_orders"] }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        {{ totals["app_drivers_fee"] / 100 }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </div>
                                        <div class="w-1/3 flex mr-2">
                                        <table class="min-w-full divide-y divide-gray-200" >
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        colspan="4"
                                                        class="px-2 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider orange-td text-center">
                                                        {{__('Smart Delivery')}}
                                                    </th>
                                                </tr>

                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr>
                                                    <th scope="col"
                                                        colspan="4"
                                                        class="px-2 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider orange-td text-center">
                                                        {{__('Stores')}}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        {{__('Date')}}
                                                    </th>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        {{ __("Stores") }}
                                                    </th>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        O/Day
                                                    </th>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider yellow-td text-center">
                                                        TL
                                                    </th>
                                                </tr>
                                                <tr v-for="(raw, key) in data">
                                                    <td class="px-4 py-3 lightorange-td text-center whitespace-nowrap">
                                                        {{ key }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        {{ raw.stores.active_stores }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 yellow-td text-center">
                                                        {{ raw.stores.orders_count }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        {{ raw.stores.store_fee / 100 }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 lightorange-td text-center whitespace-nowrap">
                                                        {{ __("Total")}}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        -
                                                    </td>
                                                    <td class="px-4 py-3 w-150 yellow-td text-center">
                                                        {{ totals["store_orders"] }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 red-td text-center">
                                                        {{ totals["store_fee"] / 100 }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                    <div class="w-100 text-sm font-medium text-gray-900 text-center" v-else>
                                        {{ __("Filter Msg") }}
                                    </div>
                                </div>
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
            showReport: false,
            data: [],
            totals: [],
            serverParams: {
                from: '',
                to: '',
                order_status: null
            },
            selectedStatuses: [],
        };
    },
    methods:{
        filter(){
            axios.get('/export/of/general/orders',{
                headers: {"Accept" : "application/json"},
                params: this.serverParams,
            })
            .then((response) => {
                this.data = response.data.data;
                this.totals = response.data.totals;
                this.showReport = true;
            }).catch((errors) => {
                console.log(errors.data);

            });
        }
    },

}
</script>
<style src="@vueform/toggle/themes/default.css"></style>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>

</style>
