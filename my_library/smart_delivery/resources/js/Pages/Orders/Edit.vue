<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{__('Edit order')}}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <jet-form-section @submitted="submitOrder">
                    <template #form>
                        <div class="col-span-6">
                            <jet-label for="customer_name" :value="__('Customer Name')" />
                            <jet-input id="customer_name" type="text" class="mt-1 block w-full" v-model="form.customer_name"/>
                            <jet-input-error :message="form.errors.customer_name" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="customer_mobile" :value="__('Customer mobile')" />
                            <jet-input id="customer_mobile" type="text" class="mt-1 block w-full" v-model="form.customer_mobile"/>
                            <jet-input-error :message="form.errors.customer_mobile" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="building_no" :value="__('Building number')" />
                            <jet-input id="building_no" type="text" class="mt-1 block w-full" v-model="form.building_no"/>
                            <jet-input-error :message="form.errors.building_no" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="apartment_no" :value="__('Apartment Number')" />
                            <jet-input id="apartment_no" type="text" class="mt-1 block w-full" v-model="form.apartment_no"/>
                            <jet-input-error :message="form.errors.apartment_no" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="total_price" :value="__('Total Price')" />
                            <jet-input id="total_price" type="text" class="mt-1 block w-full" v-model="form.total_price"/>
                            <jet-input-error :message="form.errors.total_price" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="expected_time" :value="__('Expected delivery time')" />
                            <jet-input id="expected_time" type="datetime-local" class="mt-1 block w-full" v-model="form.expected_time"/>
                            <jet-input-error :message="form.errors.expected_time" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="comment" :value="__('Comment')"/>
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
    import axios from 'axios';

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
            order: Object,
        },
        data() {
            return {
                form: this.$inertia.form({
                    customer_name: this.order.customer_name,
                    customer_mobile: this.order.customer_mobile,
                    customer_address: this.order.customer_address,
                    building_no: this.order.building_no,
                    apartment_no: this.order.apartment_no,
                    total_price: this.order.total_price,
                    expected_time: this.order.expected_time,
                    comment: this.order.comment,
                })
            };
        },
        methods: {
            submitOrder() {
                console.log(this.order.id, (this.order.id? '/orders/' + this.order.id : '') );
                this.form.put((this.order.id? '/orders/' + this.order.id : ''));
             },
        }
    }
</script>

<style src="@vueform/toggle/themes/default.css"></style>