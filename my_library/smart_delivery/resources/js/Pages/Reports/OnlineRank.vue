<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                   {{__("Online Drivers")}}
                </h2>
            </div>
            <div class="py-12">

            <div class="max-w-7xl mx-auto">
                <div class="w-1/2 flex items-center w-full justify-between pb-3">
                    <form class="w-100 flex items-center">
                        <div class="checkbox-div">
                            <input
                                type="checkbox"
                                class="mt-1 block mr-2"
                                id="today"
                                v-model="serverParams.day"
                                true-value="today"
                                false-value=""
                            />
                            <label for="today">{{__("Today")}}</label>
                        </div>
                        <div>
                            <!-- <jet-label for="start_date" value="Date"/> -->
                            <jet-input
                                id="start_date"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="serverParams.day"
                                :max="yesterdayDate"
                            />
                        </div>
                        <div class="ml-3">
                            <button class="btn orang-btn" @click.prevent="filter">{{ __("Filter") }}</button>
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
                                                    {{ __("Online time" ) }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __("Orders Count") }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="(driver, key) in drivers" :key="driver.id">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex flex-col">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ key + 1 }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{ driver.name }} </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{ driver.online_time_today }} {{__("min")}}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500"> {{ driver.driver_count }}</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import Vue from "vue";
import axios from 'axios';

export default {
    components: {
        AppLayout,
        Paginator,
        Toggle,
        JetButton,
        JetFormSection,
        JetInput,
        JetLabel,
    },
    mounted() {
        this.getYesterdayDate();
        this.filter();
    },
    data() {
        return {
            serverParams: {
                day: 'today',
            },
            drivers: [],
            yesterdayDate: '',
        };
    },
    methods:{
        filter() {
            axios.get(`/get-top-online`,{
                params: this.serverParams,
            })
            .then((response) => {
                this.drivers = response.data.data;
                console.log(response.data.data);
            }).catch((errors) => {
                console.log(errors.data);
            });

        },
        proccessDate(date) {
            return Intl.DateTimeFormat('en', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            }).format(new Date(date))
        },
        getYesterdayDate(){
            // let date = new Date();
            // date.setDate(date.getDate() - 1);
            // this.yesterdayDate = this.proccessDate(date);

            const date = new Date();
            date.setDate(date.getDate() - 1);

            this.yesterdayDate = date.getFullYear()+'-'+(date.getMonth()+1).toString().padStart(2, "0")+'-'+date.getDate();
            console.log(this.yesterdayDate);

            // const newDate = this.proccessDate(date);
            // console.log(newDate.getFullYear()+'-'+(newDate.getMonth()+1)+'-'+newDate.getDate());
        }
    },

}
</script>
<style src="@vueform/toggle/themes/default.css"></style>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>

</style>

