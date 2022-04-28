<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __("Store") }}
                </h2>
            </div>
            <div class="py-12">
                    <div class="max-w-7xl mx-auto">
                    <div class=" flex items-center w-full pb-3">
                        <!-- <form class="w-100 flex items-center"> -->
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
                                <div class="py-2 align-middle inline-block min-w-full">
                                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200" v-if="showReport">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider lightblue-td">
                                                        {{__('Stores')}}
                                                    </th>
                                                    <th scope="col" v-for="store in stores"
                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider lightblue-td">
                                                        {{store}}
                                                    </th>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-large text-gray-500 uppercase tracking-wider lightblue-td">
                                                        {{__('Total')}}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider lightblue-td">
                                                        {{__('Dates')}}
                                                    </th>
                                                    <th scope="col" v-for="store in stores"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider blue-td">
                                                        {{__('Charge')}}
                                                    </th>
                                                    <th scope="col"
                                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider blue-td">
                                                        {{__('Charge')}}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr v-for="(raw, key) in data">
                                                    <td class="px-4 py-3 lightblue-td">
                                                        {{ key }}
                                                    </td>
                                                    <td v-for="one in raw" class="px-4 py-3 w-150 blue-td">
                                                        {{ one / 100 }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 lightblue-td">
                                                        {{ __("Total") }}
                                                    </td>
                                                    <td v-for="store in stores" class="px-4 py-3 red-td">
                                                        {{ totals[store] / 100 }}
                                                    </td>
                                                    <td class="px-4 py-3 w-150 yellow-td">
                                                        {{ totals["total"] / 100 }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="text-sm font-medium text-gray-900 text-center" v-else>
                                            {{ __("Filter Msg") }}
                                        </div>
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
    },
    data() {
        return {
            selectedStatuses: [],
            showReport: false,
            stores: [],
            days: [],
            total: [],
            data: [],
            serverParams: {
                from: '',
                to: '',
            },
        };
    },
    methods:{
        filter(){
            axios.get('/export/of/stores/charges',{
                headers: {"Accept" : "application/json"},
                params: this.serverParams,
            })
            .then((response) => {
                this.stores = response.data.stores;
                this.days = response.data.period;
                this.data = response.data.rows;
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
