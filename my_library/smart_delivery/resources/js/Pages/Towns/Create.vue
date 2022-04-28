<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ town? `${__("Edit town")} ${town.name} ` : __("Create new town") }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <jet-form-section @submit.prevent="submit">
                    <template #form>
                        <div class="col-span-6">
                            <jet-label for="name" :value='__("Town name")'/>
                            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name"/>
                            <jet-input-error :message="form.errors.name" class="mt-2"/>
                            <p class="note">{{__("Please add the name in English for translating")}}</p>
                        </div>
                        <div class="col-span-6">
                            <GoogleMap
                                ref="mapRef"
                                :api-key="googleKey"
                                :street-view-control="false"
                                :map-type-control="false"
                                style="width: 100%; height: 400px"
                                :center="location"
                                @click="addMarker"
                                :zoom="12">
                                <Marker :options="{ position: location }"/>
                            </GoogleMap>
                            <!-- <location-picker
                                :default-location="center"
                                @location-change="form.location = $event"
                                @address-change="form.address = $event"
                            /> -->
                        </div>
                    </template>
                    <template #actions>
                        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                            {{__("Saved")}}.
                        </jet-action-message>

                        <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{__("Save")}}
                        </jet-button>
                    </template>
                </jet-form-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Paginator from "@/Components/Paginator";
import Toggle from '@vueform/toggle'
import JetButton from '@/Jetstream/Button'
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import LocationPicker from '@/Components/LocationPicker'
import {GoogleMap, Marker} from 'vue3-google-map'

export default {
    components: {
        AppLayout,
        Paginator,
        Toggle,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetActionMessage,
        JetSecondaryButton,
        LocationPicker,
        GoogleMap,
        Marker,
    },

    props: {
        town: Object,
        towns: Array,
    },

    data() {
        return {
            center: this.town ? {lat: parseFloat(this.town.center_lat), lng: parseFloat(this.town.center_lng)} : {lat: 38.9637, lng: 35.2433},
            form: this.$inertia.form({
                name: this.town? this.town.name : null,
                center_lat: this.town ? parseFloat(this.town.center_lat) : 38.9637,
                center_lng: this.town ? parseFloat(this.town.center_lng) : 35.2433,
            }),
            location:
                this.town ? {lat: parseFloat(this.town.center_lat), lng: parseFloat(this.town.center_lng)} : {lat: 38.9637, lng: 35.2433},
            googleKey : this.$page.props.googleMapKey,

        };
    },

    created() {
        console.log(this.town)
    },

    methods: {
        addMarker($event) {
            this.location = $event.latLng;
            console.log($event.latLng.lat(), $event.latLng.lng());
            this.form.center_lat = $event.latLng.lat(),
            this.form.center_lng = $event.latLng.lng();
            let geocoder = new google.maps.Geocoder();
            geocoder.geocode({location: this.location}, (results, status) => {
                console.log(results);
                if (status === 'OK' && results.length > 0) {
                    this.address = results[0].formatted_address;
                } else {
                    console.error('Geocoding request status: ' + status);
                    console.error(results);
                }
            });
        },
        submit() {
            if (this.town) {
                console.log(this.form);
                this.form.patch(this.route('towns.update', this.town.id))
            } else {
                console.log(this.form);
                this.form.post(this.route('towns.store'));
            }
        },
    }
}
</script>

<style src="@vueform/toggle/themes/default.css"></style>
