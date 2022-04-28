<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Orders') }}
                </h2>
                <div>
                    <inertia-link
                        :href="route((route().params.store? 'stores.' : '') + 'orders.create', route().params.store)">
                        <jet-button>
                            {{__('New Order')}}
                        </jet-button>
                    </inertia-link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-1/2 flex items-center w-full justify-between pb-3">
                    <form class="flex items-center" @submit.prevent="searchRequest">
                        <jet-input type="text" class="mt-1 block mr-2" v-model="search" :placeholder="__('Order Id')"/>
                        <jet-button>
                            <icon name="search" class="w-5 h-5"/>
                        </jet-button>
                    </form>

                    <form @submit.prevent="filterOrder" class="w-1/2 flex items-center">
                        <div class="flex-grow">
                            <multiselect v-model="selectedStatuses" :options="OrderStatus" value-prop="id" label="name" mode="multiple" :multipleLabel="n => ` ${n.length} `+ __('statuses selected')">
                                <template v-slot:option="{ option }">
                                    <jet-input type="checkbox" class="mr-2" :checked="selectedStatuses.includes(option.id.toString())"/>
                                    {{ option.translated_name }}
                                </template>
                            </multiselect>
                            <jet-input-error :message="form.errors.status" class="mt-2"/>
                        </div>
                        <div class="ml-3">
                            <dropdown>
                                <template #trigger>
                                    <div class="flex items-center text-gray-400 capitalize border px-4 py-2 rounded-md border-gray-300 cursor-pointer hover:bg-gray-200">
                                        <icon name="date" class="w-5 h-5 mr-2"/>
                                        {{ form.range }}
                                        <icon name="dropdown" class="w-4 h-4 ml-2"/>
                                    </div>
                                </template>
                                <template #content>
                                    <dropdown-link v-for="filter in dateFilters" :key="filter" as="button" @click="form.range = filter">
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
                <dialog-modal :show="showDateModal == true"  @close="showDateModal == false">
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
                     v-if="suspondedCount > 0 && show == true">
                    <div class="flex items-center align-center">
                        <div class="bg-white rounded-md text-yellow-500 p-2">
                            <icon name="info" class="w-5 h-5"/>
                        </div>
                        <div class="pl-3">
                            <b>{{__('Notice')}}:</b> {{__('You have')}} {{ suspondedCount }} {{__('susponded orders')}}.
                        </div>
                    </div>
                    <div class="flex items-right">
                        <inertia-link
                            :href="route((route().params.store? 'stores.' : '') + 'orders.index', {'store': route().params.store, status: '6'})"
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

                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Order Id')}}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Customer Name')}}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Total Price')}}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Status')}}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Rate')}}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Date')}}
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">{{__('Details')}}</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="order in orders.data" :key="order.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ order.order_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ order.customer_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ order.total_price / 100 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ order.order_status ? order.order_status.translated_name : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <rate v-if="order.status == 4" v-model="order.rate"
                                                  @change="rate(order.id, order.rate)" :disabled="order.rate > 0"/>
                                            <i v-else>{{__('You cannot rate order yet')}}</i>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {{ order.sent_at == null ? proccessDate(order.created_at) : proccessDate(order.sent_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="#" v-if="order.status==1"
                                               class="text-indigo-600 hover:text-indigo-900 mr-5" style="color: red"
                                               @click="caorder(order.id)">{{__('cancel-order')}}</a>
                                                <a href="#" v-if="order.status==6"
                                               class="text-indigo-600 hover:text-indigo-900 mr-5"
                                               @click="reorder(order.id)">{{__('Re-order')}}</a>
                                            <inertia-link :href="route('orders.show', order.id)"
                                                          class="text-indigo-600 hover:text-indigo-900 mr-5">{{__('Details')}}
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
        store_id: Number,
        OrderStatus: Object,
        suspondedCount: Number,
    },
    data() {
        return {
            selectedStatuses: this.route().params.status? this.route().params.status.split('-') : this.OrderStatus.filter(i => i.id != 6).map(i => i.id),
            form: this.$inertia.form({
                status: null,
                range: this.route().params.range? this.route().params.range : 'all',
            }),
            search: null,
            showDateModal: false,
            dateFrom: null,
            dateTo: null,
            dateFilters: [
                'all', 'today', 'yesterday','this-week', 'prev-week', 'this-month', 'prev-month'
            ],
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
            if (this.$page.props.user.roles[0].name == 'owner')
                this.form.get(this.route('orders.index', this.store_id));
            else
                this.form.get(this.route('stores.orders.index', this.store_id));
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
</style>
