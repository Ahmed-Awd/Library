<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Orders') }}
                </h2>
                <inertia-link
                    :href="route('orders.current-active')"
                    class="btn"
                >
                <jet-button>
                    {{ __('Active Orders') }}
                </jet-button>
                </inertia-link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">
                <div class="w-1/2 flex items-center w-full justify-between pb-3 mobile-flex">
                    <form class="flex items-center mobile-frm-flex" @submit.prevent="searchRequest">
                        <jet-input type="text" class="mt-1 block mr-2 mobile-w-100" v-model="search" :placeholder="__('Serial Number')"/>
                        <jet-button>
                            <icon name="search" class="w-5 h-5"/>
                        </jet-button>
                    </form>

                    <form @submit.prevent="filterOrder" class="w-1/2 flex items-center mobile-mt-10">
                        <div class="flex-grow">
                            <multiselect
                                v-model="selectedStatuses"
                                :options="OrderStatus"
                                valueProp="id"
                                label="name"
                                mode="tags"
                                value="name"
                                :placeholder="__('Select statuses')"
                            >
                                <template v-slot:option="{ option }">
                                    <jet-input type="checkbox" class="mr-2"
                                               :checked="selectedStatuses.includes(option.id.toString())"/>
                                    {{ option.translated_name }}
                                </template>
                            </multiselect>
                            <jet-input-error :message="form.errors.status" class="mt-2"/>
                        </div>
                        <div class="ml-3">
                            <dropdown>
                                <template #trigger>
                                    <div
                                        class="flex items-center text-gray-400 capitalize border px-4 py-2 rounded-md border-gray-300 cursor-pointer hover:bg-gray-200">
                                        <icon name="date" class="w-5 h-5 mr-2"/>
                                        {{ form.range }}
                                        <icon name="dropdown" class="w-4 h-4 ml-2"/>
                                    </div>
                                </template>
                                <template #content>
                                    <dropdown-link v-for="filter in dateFilters" :key="filter" as="button"
                                                   @click="form.range = filter">
                                        <span class="capitalize">{{ __(filter) }}</span>
                                    </dropdown-link>
                                    <div class="border-t border-gray-200"></div>
                                    <dropdown-link as="button" button-type="button" @click="showDateModal = true">
                                        <span class="capitalize">{{__('Custom')}}</span>
                                    </dropdown-link>
                                </template>
                            </dropdown>
                        </div>
                        <div v-if="form.range === 'custom'">
                        </div>
                    </form>
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

                <div class="flex items-center justify-between bg-yellow-500 text-white p-4 rounded-md shadow mb-3"
                     v-if="suspendedCount > 0 && show == true">
                    <div class="flex items-center">
                        <div class="bg-white rounded-md text-yellow-500 p-2">
                            <icon name="info" class="w-5 h-5"/>
                        </div>
                        <div class="pl-3">
                            <b>{{__('Notice')}}:</b> {{__('You have')}} {{ suspendedCount }} {{__('suspended orders')}}.
                        </div>
                    </div>
                    <div class="flex items-right align-center">
                    <inertia-link
                        :href="route((route().params.store? 'stores.' : '') + 'orders.all', {'store': route().params.store, status: '6'})"
                        class="text-sm font-bold">
                        {{__('View')}}
                    </inertia-link>
                    <button
                        type="button"
                        class="-mr-1 ml-1 flex p-2 rounded-md focus:outline-none sm:-mr-2 transition"
                        :class="{ 'hover:bg-indigo-600 focus:bg-indigo-600': style == 'success', 'hover:bg-red-600 focus:bg-red-600': style == 'danger' }"
                        aria-label="Dismiss"
                        @click.prevent="show = false">
                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    </div>
                </div>

                <div class="flex flex-col hidden-overflow">
                    <div class="-my-2 overflow-x-auto">
                        <div class="py-2 align-middle inline-block min-w-full">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Order Id')}}
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Customer Name')}}
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Store name')}}
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Delivery Price')}}
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Driver Fee')}}
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                           {{__( 'Store Fee')}}
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Total')}}
                                        </th>
                                        <th scope="col"
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Total Price')}}
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Status')}}
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Rate')}}
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Date')}}
                                        </th>
                                        <th scope="col" class="relative px-2 py-3">
                                            <span class="sr-only">{{__('Details')}}</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="order in orders.data"  :key="order.id">
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            {{ order.order_number }}
                                        </td>
                                        <td class="px-2 py-3 w-150">
                                            {{ order.customer_name }}
                                        </td>
                                        <td class="px-2 py-3 w-150" v-if="order.store">
                                            {{ order.store.name }}
                                        </td>
                                        <td v-else style="color:red;font-size: 12px;">
                                            {{__('Store is deleted')}}
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            {{ order.delivery_price / 100 }}
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            {{ order.driver_fee / 100 }}
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            {{ order.store_fee / 100 }}
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            {{ order.smart_income / 100 }}
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            {{ order.total_price / 100 }}
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            {{ order.order_status ? order.order_status.translated_name : '-' }}
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap text-sm font-medium">
                                            <rate v-if="order.status == 4" v-model="order.rate"
                                                  @change="rate(order.id, order.rate)" :disabled="order.rate > 0"/>
                                            <span v-else style="color:red;font-size: 12px;">{{__('Order cannot rated')}}</span>
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap text-sm font-medium">
                                            {{ order.sent_at == null ? proccessDate(order.created_at) : proccessDate(order.sent_at) }}
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap text-right text-sm font-medium option-td">
                                            <a href="#" v-if="order.status==1 || order.status==2 || order.status==3"
                                               class="text-indigo-600 hover:text-indigo-900 mr-1"
                                               @click="changeDriver(order.id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
                                                    <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                                </svg>
                                            </a>
                                            <a href="#"  v-if="(toCancel.indexOf(order.status) != -1) && order.store" style="color: red"
                                               class="text-indigo-600 hover:text-indigo-900 mr-1"
                                               @click="caorder(order.id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-x" viewBox="0 0 16 16">
                                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                                </svg>
                                            </a>
                                            <a href="#" v-if="order.status==6"
                                               class="text-indigo-600 hover:text-indigo-900 mr-1"
                                               @click="reorder(order.id)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#f59e0b" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                                    <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                                    </svg>
                                            </a>
                                            <inertia-link :href="route('orders.edit', order.id)"  v-if="(order.status==1 || order.status==2) && order.store"
                                                class="text-indigo-600 hover:text-indigo-900 mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                </svg>
                                            </inertia-link>
                                            <inertia-link :href="route('orders.show', order.id)" v-if="order.store"
                                                class="text-indigo-600 hover:text-indigo-900 mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                </svg>
                                            </inertia-link>
                                            <inertia-link :href="route('orders.trace-order', order.id)" v-if="order.status==2 || order.status==3"
                                                class="text-indigo-600 hover:text-indigo-900 ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-geo" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.319 1.319 0 0 0-.37.265.301.301 0 0 0-.057.09V14l.002.008a.147.147 0 0 0 .016.033.617.617 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.619.619 0 0 0 .146-.15.148.148 0 0 0 .015-.033L12 14v-.004a.301.301 0 0 0-.057-.09 1.318 1.318 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465-1.281 0-2.462-.172-3.34-.465-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411z"/>
                                                </svg>
                                            </inertia-link>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <paginator :paginator="orders"/>
                            </div>
                        </div>
                    </div>
                </div>
                <dialog-modal  :show="showCancelModal == true" @close="showCancelModal == false">
                    <template #title>{{__('Cancel order')}}</template>
                    <template #content>
                        {{__('Are you sure you want to cancel order number')}} {{orderId}}
                    </template>
                    <template #footer>
                        <jet-secondary-button @click="showCancelModal = false">
                            {{__('No')}}
                        </jet-secondary-button>

                        <jet-button class="ml-2" @click="caorder(orderId)">
                            {{__('Yes')}}
                        </jet-button>
                        </template>
                </dialog-modal>
                <dialog-modal :show="showDeriverModal == true" @close="showDeriverModal == false">
                    <template #title>
                        {{__('Choose Driver')}}
                    </template>
                    <template #content>
                        <div class="w-100 mb-5 mt-5 pb-50">
                            <jet-label for="driver" :value="__('driver')" class="mt-5 mb-2"/>
                            <multiselect
                                    mode="single"
                                    searchable=true
                                    :placeholder="__('driver name')"
                                    class="searched-select mr-10 mb-5"
                                    v-model="driver_id"
                                    :options="drivers"
                                    valueProp="id"
                                    label="name"
                                    trackBy="name"
                                    id="driver"
                                >
                            </multiselect>
                        </div>
                    </template>

                    <template #footer>
                        <jet-secondary-button @click="showDeriverModal = false">
                            {{__('Cancel')}}
                        </jet-secondary-button>

                        <jet-button class="ml-2" @click="confirmDriver">
                            {{__('Save')}}
                        </jet-button>
                    </template>
                </dialog-modal>
                <div class="total-order">
                    <span class="bold-font">{{__('Total Orders Fee')}} : </span>
                    <span>{{ sumOfIncome / 100 }}</span>
                </div>
            </div>
        </div>
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
import DialogModal from '../../Jetstream/DialogModal.vue';
import axios from 'axios';

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
        DialogModal,
    },

    props: {
        orders: Object,
        OrderStatus: Object,
        suspendedCount: Number,
        sumOfIncome:Number,
    },
    data() {
        return {
            selectedStatuses: this.route().params.status ? this.route().params.status.split('-') : [],
            form: this.$inertia.form({
                status: null,
                range: this.route().params.range ? this.route().params.range : 'all',
            }),
            search: null,
            showDateModal: false,
            showCancelModal: false,
            toCancel:[1,2,3,6],
            dateFrom: null,
            dateTo: null,
            dateFilters: [
                'all', 'today', 'yesterday','this-week', 'prev-week', 'this-month', 'prev-month'
            ],
            show: true,
            orderId: null,
            drivers: [],
            showDeriverModal: false,
            orderID: null,
            driver_id: null,
            serverParams: {
                driver_id: null,
            },
        };
    },

    methods: {
        rate(orderId, rateValue) {
            this.$inertia.post(this.route('order.rate', orderId), {
                rate: rateValue,
            })
        },
        caorder(orderId) {
            this.showCancelModal = false;
            this.$inertia.post(this.route('orders.cancel', orderId))
        },
        reorder(orderId) {
            this.$inertia.post(this.route('orders.reorder', orderId))
        },
        filterOrder() {
            this.form.status = this.selectedStatuses.join('-');
            this.form.get(this.route('orders.all', this.store_id));

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
        chooseCustomDate() {
            this.showDateModal = false;
            this.form.range = this.dateFrom + ',' + this.dateTo;
            this.filterOrder();
        },
        searchRequest() {
            this.$inertia.get(this.route('orders.by-serial', this.search.replace('/', ',')))
        },
        cancelOrder(orderId) {
            this.showCancelModal = true;
            this.orderId = orderId;
        },
        changeDriver(orderId){
            axios.get(`/get/available-drivers`)
            .then((response) => {
                console.log(response.data);
                this.drivers = response.data.data;
                this.orderID = orderId;
                this.showDeriverModal = true;
            }).catch((errors) => {
                console.log(errors.data);
            });
        },
        confirmDriver(){
            axios.post(`/orders/change-driver/${this.orderID}`,{
                driver_id: this.driver_id,
            })
            .then((response) => {
                console.log(response.data);
                this.showDeriverModal = false;
            }).catch((errors) => {
                console.log(errors.data);
            });
        }
    },

    watch: {
        selectedStatuses() {
            this.filterOrder();
            console.log(this.OrderStatus);
        },
    }
}
</script>

<style src="@vueform/toggle/themes/default.css"></style>
<style src="@vueform/multiselect/themes/default.css"></style>

<style>
:root {
    --ms-option-bg-selected: #ffffff;
    --ms-option-bg-selected-pointed: #f3f4f6;
    --ms-option-color-selected: #000000;
    --ms-option-color-selected-pointed: #000000;
}
td{
    font-size: 14px;
}
</style>
