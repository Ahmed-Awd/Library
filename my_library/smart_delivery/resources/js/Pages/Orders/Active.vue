<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Active Orders') }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">

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
                                            {{__('Driver')}}
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Send at Date')}}
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Status')}}
                                        </th>
                                        <th scope="col" class="relative px-2 py-3">
                                            <span class="sr-only">{{__('Details')}}</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="order in orders.data"  :key="order.id">
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            {{ order.id }}
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
                                        <td class="px-2 py-3 w-150" v-if="order.driver">
                                            {{ order.driver.name }}
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap text-sm font-medium">
                                            {{ order.sent_at == null ? proccessDate(order.created_at) : proccessDate(order.sent_at) }}
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            {{ order.order_status ? order.order_status.translated_name : '-' }}
                                        </td>
                                        <td class="px-2 py-3 whitespace-nowrap text-right text-sm font-medium option-td">
                                            <a href="#" v-if="(order.status==1 || order.status==6) && order.store" style="color: red"
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
                                            <inertia-link :href="route('orders.show', order.id)" v-if="order.store"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                </svg>
                                            </inertia-link>
                                            <!-- Tracing Icon -->
                                            <inertia-link :href="route('orders.trace-order', order.id)"
                                                class="text-indigo-600 hover:text-indigo-900">
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
    },
    data() {
        return {
            form: this.$inertia.form({
                status: null,
                range: this.route().params.range ? this.route().params.range : 'all',
            }),
            search: null,
            show: true,
        };
    },

    methods: {
        rate(orderId, rateValue) {
            this.$inertia.post(this.route('order.rate', orderId), {
                rate: rateValue,
            })
        },
        caorder(orderId) {
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
