<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{__('Order')}} #{{order.store_id}}/{{order.order_number}}
                </h2>
                <span class="bg-purple-600 text-white px-4 py-1 rounded-full">
                    {{order.order_status.translated_name}}
                </span>
            </div>
        </template>

        <div class="py-12">
            <div class="flex flex-col max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="flex text-center bg-white p-4 rounded-lg shadow justify-between mobile-m-10">
                    <div>
                        {{order.total_price}}
                        {{order.delivery_price}}
                        {{order.distance_store_order}}
                        {{order.expected_time}}
                        <rate v-model="order.rate" @change="rate" :disabled="order.rate > 0"/>
                        {{order.created_at}}
                    </div>
                    <qr-code :content="order.qr_code.toString()" class="w-28"/>
                </div>
                <div class="flex mt-3 mobile-flex mobile-m-10">
                    <div class="flex flex-col text-center bg-white p-4 rounded-lg shadow w-2/3 mr-1 mobile-flex mobile-mt-10">
                        <GoogleMap
                            ref="mapRef"
                            :api-key="googleKey"
                            :street-view-control="false"
                            :map-type-control="false"
                            style="width: 100%; height: 500px"
                            :center="location"
                            :zoom="15">
                            <Marker :options="{ position: location}"/>
                            <Marker :options="{ position: locationStore}"/>
                        </GoogleMap>
                    </div>
                    <div class="flex flex-col w-1/3 ml-1 mobile-flex mobile-ml-0">
                        <div class="flex flex-col bg-white p-4 rounded-lg shadow flex-grow mobile-flex mobile-mt-10 ">
                            <h2 class="text-lg text-gray-800">{{__('Customer Info')}}</h2>
                            <hr class="mt-2 mb-4">
                            <div>
                                <b>{{__('Customer Name')}}:</b> {{order.customer_name}}
                            </div>
                            <div>
                                <b>{{__('Customer mobile')}}:</b> <a :href="`tel:${order.customer_mobile}`">{{order.customer_mobile}}</a>
                            </div>
                            <div>
                                <b>{{__('Address')}}:</b> {{order.customer_address}}
                            </div>
                            <div>
                                <b>{{__('Building number')}}:</b> {{order.building_no}}
                            </div>
                            <div>
                                <b>{{__('Apartment Number')}}:</b> {{order.apartment_no}}
                            </div>
                             <div>
                                <b>{{__('distance')}}:</b> {{order.distance_store_order}}
                            </div>
                            <div>
                                <b>{{__('order price')}}:</b> {{order.order_price / 100}}
                            </div>
                            <div>
                                <b>{{__('customer payed')}}:</b> {{order.customer_payed / 100}}
                            </div>
                            <div>
                                <b>{{__('customer payed for delivery')}}:</b> {{order.customer_payed_for_delivery / 100}}
                            </div>
                            <div>
                                <b>{{__('store profit')}}:</b> {{order.store_profit / 100}}
                            </div>
                            <div  v-if="order.accepted_by_driver_at">
                                <b>{{__('accepted by driver at')}}:</b> {{proccessDate(order.accepted_by_driver_at)}}
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow mt-3 flex-grow mobile-flex mobile-mt-10" >
                            <h2 class="text-lg text-gray-800">{{__('store Info')}}</h2>
                            <hr class="mt-2 mb-4">
                            <div>
                                <b>{{__('store name')}}:</b> {{order.store.name}}
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow mt-3 flex-grow" >
                            <h2 class="text-lg text-gray-800">{{__('Driver')}}</h2>
                            <hr class="mt-2 mb-4">
                            <div class="flex items-center" v-if="order.driver">
                                <img :src="order.driver.profile_photo_url" :alt="`${order.driver.name}'s photo`" class="w-18 h-18 rounded-full">
                                <div class="flex flex-col text-gray-800 font-bold pl-2">
                                    {{order.driver.name}}
                                    <span class="text-gray-500 font-normal text-sm">@{{order.driver.username}}</span>
                                </div>
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
    import QrCode from '@/Components/QrCode.vue';
    import Rate from '@/Components/Rate';
    import {GoogleMap, Marker} from 'vue3-google-map'

    export default {
        components: {
            AppLayout,
            Paginator,
            Toggle,
            JetButton,
            QrCode,
            GoogleMap,
            Marker,
            Rate,
        },
        props: {
            order: Object,
        },

        data() {
            return {
                location: {lat: parseFloat(this.order.customer_lat), lng: parseFloat(this.order.customer_lng)},
                locationStore: {lat: parseFloat(this.order.store.lat), lng: parseFloat(this.order.store.lng)},
                googleKey : this.$page.props.googleMapKey
            }
        },

        methods: {
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
            rate() {
                this.$inertia.post(this.route('order.rate', this.order.id), {
                    rate: this.order.rate,
                })
            }
        }
    }
</script>

<style src="@vueform/toggle/themes/default.css"></style>
