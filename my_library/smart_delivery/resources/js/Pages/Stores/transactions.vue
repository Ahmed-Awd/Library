<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ `  ${__("Transactions history")} "${store.name}"` }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form class="w-full flex mb-4 filter-frm" @submit.prevent="filterOrder">
                    <div class="col-span-3 flex-col w-1/3">
                        <jet-label for="range" :value="__('Date')"/>
                        <select id="range" v-model="currentFilter"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            <option v-for="filter in dateFilters" :key="filter" :value="filter">{{ __(filter) }}</option>
                        </select>
                    </div>
                    <div v-if="currentFilter === 'custom'" class="col-span-3 flex-col w-1/4">
                        <jet-label for="start_date" :value="__('start_date')"/>
                        <jet-input id="start_date" type="date" class="mt-1 block w-full"/>
                        <!-- <jet-label for="end_ate" value="date to"/>
                        <jet-input id="end_ate" type="date" class="mt-1 block w-full"/> -->
                    </div>
                    <div v-if="currentFilter === 'custom'" class="col-span-3 flex-col w-1/4">
                        <jet-label for="end_ate" :value="__('end_date')"/>
                        <jet-input id="end_ate" type="date" class="mt-1 block w-full"/>
                    </div>
                    <div class="col-span-3 flex-col w-1/3">
                        <jet-label for="type" :value="__('Transactions type')"/>
                        <select id="types" v-model="currentType"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            <option v-for="type in types" :key="type" :value="type">{{ __(type) }}</option>
                        </select>
                    </div>
                    <div class="flex-col w-1/3 filter-btn">
                        <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >
                            {{__("Filter")}}
                        </jet-button>
                    </div>
                </form>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__("Date")}}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__("Value")}}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__("Status")}}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="record in records.data">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{
                                                        moment(record.created_at).format("dddd, MMMM Do YYYY, h:mm:ss a")
                                                    }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ parseFloat((record.value/100).toFixed(1))}}
                                        </td>
                                        <td>
                                            <img :src="'/img/green_arrow.png'" width="25" height="25"
                                                 v-if="record.status === 'ingoing'">
                                            <img :src="'/img/red_arrow.png'" width="25" height="25" v-else>

                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <paginator :paginator="records"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="static-div">
                    <div class="total-order more-line">
                        <span class="bold-font">{{__("Total")}} {{__("outgoing")}} : </span>
                        <span class="ml-auto">{{ totals.outgoing / 100  }}</span>
                    </div>
                    <div class="total-order  more-line">
                        <span class="bold-font">{{__("Total")}} {{__("ingoing")}} : </span>
                        <span class="ml-auto">{{ totals.ingoing / 100  }}</span>
                    </div>
                    <div class="total-order">
                        <span class="bold-font">{{__("Total")}} : </span>
                        <span class="ml-auto">{{ totals.total / 100  }}</span>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import Toggle from "@vueform/toggle";
import AppLayout from "../../Layouts/AppLayout";
import Paginator from "../../Components/Paginator";
import SecondaryButton from "../../Jetstream/SecondaryButton";
import JetButton from '@/Jetstream/Button'
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'

var moment = require('moment');


export default {
    name: "transactions",
    components: {
        AppLayout,
        Paginator,
        Toggle,
        JetButton,
        SecondaryButton,
        JetFormSection,
        JetInput,
        JetLabel,
    },
    data() {
        return {
            moment: moment,
            form: this.$inertia.form({
                range: null,
                type: "all",
                page: 1,
            }),
            currentFilter: null,
            currentType: "all",
            dateFilters: [
                "today", "this-week", "prev-week", "this-month", "prev-month", "all", "custom"
            ],
            types:[
                "ingoing","outgoing","all"
            ],
            // isFilter: false,
        }
    },
    props: {
        store: Object,
        records: Object,
        totals: Object,
    },
    methods: {
        filterOrder() {
            this.form.range = this.range === 'custom' ? '' : this.currentFilter;
            this.form.type  =  this.currentType;
            this.form.get(this.route('store.transactions', this.store.id));
        },
    }
}
</script>

<style scoped>

</style>
