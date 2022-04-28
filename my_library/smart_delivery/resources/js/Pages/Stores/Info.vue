<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('show the details of store') + ` "${record.name}"` }}
            </h2>
        </template>

        <div class="py-12">
            <div class="flex flex-col max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex mt-3">
                    <div class="flex flex-col text-center bg-white p-4 rounded-lg shadow w-2/3 mr-1">
                        <div class="flex flex-col text-center bg-white p-4 rounded-lg shadow w-2/3 mr-1">
                            <GoogleMap
                                ref="mapRef"
                                :api-key="googleKey"
                                :street-view-control="false"
                                :map-type-control="false"
                                style="width: 100%; height: 500px"
                                :center="locationStore"
                                :zoom="15">
                                <Marker :options="{ position: locationStore}"/>
                            </GoogleMap>
                        </div>

                    </div>
                    <div class="flex flex-col w-1/3 ml-1">
                        <div class="flex flex-col bg-white p-4 rounded-lg shadow flex-grow">
                            <h2 class="text-lg text-gray-800">{{__('store Info')}}</h2>
                            <hr class="mt-2 mb-4">
                            <div>
                                <b>{{__('Store name')}}:</b> {{ record.name }}
                            </div>
                            <div>
                                <b>{{__('Store Town')}}:</b> {{ record.town.name }}
                            </div>
                            <div>
                                <b>{{__('Store address')}}:</b> {{ record.address }}
                            </div>
                            <div>
                                <b>{{__('Store type')}}:</b> {{ record.type.name }}
                            </div>
                            <div>
                                <b>{{__('Store balance')}}:</b> {{ record.owner.balance }}
                            </div>
                            <div>
                                <b>{{__('Store Owner')}}:</b> {{ record.owner.name }}
                            </div>
                            <div>
                                <b>{{__('Store delivery rate payed')}}:</b> {{ record.setting.delivery_perice_percentage }} %
                            </div>
                            <div>
                                <b>{{('Store Owner Phone')}} :</b> {{ record.owner.phone }}
                            </div>
                            <div>
                                <b>{{__('Store status')}}:
                                    <span v-if="record.owner.is_disabled === 1" style="color: red">{{__('inactive')}}</span>
                                    <span v-else style="color: greenyellow">{{__('active')}}</span>
                                </b>
                            </div>
                            <div>
                                <inertia-link :href="route('store.transactions',record.id)"><b style="color: #621b18">{{__('Transactions history')}}</b></inertia-link>
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
import JetButton from '@/Jetstream/Button'
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import {GoogleMap, Marker} from 'vue3-google-map'
export default {
    name: "Info",
    props:{
        record:Object
    },
    components:{
        AppLayout,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        GoogleMap,
        Marker,
        JetActionMessage,
        JetSecondaryButton,
    },
    mounted() {

    },
    data(){
        return{
            locationStore: {lat: parseFloat(this.record.lat), lng: parseFloat(this.record.lng)},
            googleKey : this.$page.props.googleMapKey
        }
    },

}
</script>

<style scoped>

</style>
