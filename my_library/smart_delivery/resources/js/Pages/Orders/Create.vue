<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{__('Create a new order')}}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <jet-form-section @submitted="submitOrder">
                    <template #form>
                        <div class="col-span-6">
                            <jet-label for="customer_name" value="Customer Name" />
                            <jet-input id="customer_name" type="text" class="mt-1 block w-full" v-model="form.customer_name"/>
                            <jet-input-error :message="form.errors.customer_name" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="customer_mobile" value="Customer mobile" />
                            <jet-input id="customer_mobile" type="text" class="mt-1 block w-full" v-model="form.customer_mobile"/>
                            <jet-input-error :message="form.errors.customer_mobile" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label value="Location" />
                            <location-picker 
                                :default-location="center"
                                @location-change="form.location = $event"
                                @address-change="form.customer_address = $event"
                            />
                            <jet-input type="text" class="mt-1 block w-full bg-gray-200" :value="form.customer_address" disabled/>
                            <jet-input-error :message="form.errors.customer_address" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="building_no" value="Building number" />
                            <jet-input id="building_no" type="text" class="mt-1 block w-full" v-model="form.building_no"/>
                            <jet-input-error :message="form.errors.building_no" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="apartment_no" value="Apartment Number" />
                            <jet-input id="apartment_no" type="text" class="mt-1 block w-full" v-model="form.apartment_no"/>
                            <jet-input-error :message="form.errors.apartment_no" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="total_price" value="Total Price" />
                            <jet-input id="total_price" type="text" class="mt-1 block w-full" v-model="form.total_price"/>
                            <jet-input-error :message="form.errors.total_price" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="expected_time" value="Expected delivery time" />
                            <jet-input id="expected_time" type="datetime-local" class="mt-1 block w-full" v-model="form.expected_time"/>
                            <jet-input-error :message="form.errors.expected_time" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="comment" value="Comment"/>
                            <jet-input id="comment" type="text" class="mt-1 block w-full" v-model="form.comment"/>
                            <jet-input-error :message="form.errors.comment" class="mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                            {{__('Saved')}}.
                        </jet-action-message>

                        <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{__('Save')}}
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
    import LocationPicker from '@/Components/LocationPicker';

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
        },
         props: {
            store_id: Number,
            default_delivery_time:Date,
            order: Object

        },
        data() {
            return {
                form: this.$inertia.form({
                    customer_name: null,
                    customer_mobile: null,
                    location: null,
                    customer_address: null,
                    building_no: null,
                    apartment_no: null,
                    total_price: null,
                    expected_time: this.default_delivery_time,
                    comment: null,
                })
            };
        },

        methods: {
            chooseLocation($event) {
                this.form.location = $event.latLng;
                let geocoder = new google.maps.Geocoder();
                geocoder.geocode({location: this.form.location}, (results, status) => {
                    if (status === 'OK' && results.length > 0) {
                        this.form.customer_address = results[0].formatted_address;
                    } else {
                        console.error('Geocoding request status: ' + status);
                        console.error(results);
                    }
                });

            },
            submitOrder() {
                console.log(this.store_id, (this.store_id? '/stores/' + this.store_id : '')  +'/orders');
                    this.form.post((this.store_id? '/stores/' + this.store_id : '')  +'/orders');
             },
        }
    }
</script>

<style src="@vueform/toggle/themes/default.css"></style>