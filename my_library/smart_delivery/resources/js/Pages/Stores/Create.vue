<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ store?  __('Edit store') + `"${store.name}"` : __('Create a new store') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-5" v-if="!store">
                    <div class="md:col-start-2 md:col-span-3 p-2 mb-3">
                        <div class="flex bg-purple-600 text-white p-4 rounded-md shadow">
                            <div class="bg-white rounded-md text-purple-600 p-2">
                                <icon name="info"/>
                            </div>
                            <div class="pl-3">
                                <b>{{__("Notice")}}:</b>
                                 {{__("A password set link will be sent to the store owner email. No need to insert password in this page.")}}
                            </div>
                        </div>
                    </div>
                </div>
                <jet-form-section @submit.prevent="submit">
                    <template #form>
                        <div class="col-span-6">
                            <jet-label for="store_name" :value="__('Store name')"/>
                            <jet-input id="store_name" type="text" class="mt-1 block w-full" v-model="form.store_name"/>
                            <jet-input-error :message="form.errors.store_name" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <jet-label for="activity_type" :value="__('Activity Type')" />
                            <select id="activity_type" v-model="form.activity_type"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option v-for="i in types" :value="i.id">{{ i.trans_name }}</option>
                            </select>
                            <jet-input-error :message="form.errors.activity_type" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <jet-label for="Town" :value="__('Town')"/>
                            <select id="towns" v-model="form.town"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option v-for="i in towns" :value="i.id">{{ i.name }}</option>
                            </select>
                            <jet-input-error :message="form.errors.town" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <jet-label :value="__('Location')"/>
                            <location-picker
                                :default-location="center"
                                @location-change="form.location = $event"
                                @address-change="form.address = $event"
                            />
                            <jet-input type="text" class="mt-1 block w-full bg-gray-200" :value="form.address"
                                       disabled/>
                            <jet-input-error :message="form.errors.address" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <jet-label for="default_delivery_time" :value="__('Default delivery time')"/>
                            <jet-input id="default_delivery_time" type="number" class="mt-1 block w-full" v-model="form.default_delivery_time"/>
                            <jet-input-error :message="form.errors.default_delivery_time" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <jet-label for="owner_name" :value="__('Owner name')"/>
                            <jet-input id="owner_name" type="text" class="mt-1 block w-full" v-model="form.owner_name"/>
                            <jet-input-error :message="form.errors.owner_name" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <jet-label for="owner_username" :value="__('Owner Username')"/>
                            <jet-input id="owner_username" type="text" class="mt-1 block w-full"
                                       v-model="form.owner_username"/>
                            <jet-input-error :message="form.errors.owner_username" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <jet-label for="owner_email" :value="__('Owner Email')"/>
                            <jet-input id="owner_email" type="email" class="mt-1 block w-full"
                                       v-model="form.owner_email"/>
                            <jet-input-error :message="form.errors.owner_email" class="mt-2"/>
                        </div>
                    </template>

                    <template #actions>
                        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                            Saved.
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
import LocationPicker from '@/Components/LocationPicker'

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
        store: Object,
        types: Array,
        towns: Array,
    },

    data() {
        return {
            center: this.store ? {lat: parseFloat(this.store.lat), lng: parseFloat(this.store.lng)} : null,
            form: this.$inertia.form({
                store_name: this.store? this.store.name : null,
                activity_type: this.store? this.store.type_id : 1,
                town: this.store? this.store.town_id : null,
                location: null,
                address: this.store? this.store.address : null,
                owner_name: this.store? this.store.owner.name : null,
                default_delivery_time: this.store? this.store.default_delivery_time : null,
                owner_username: this.store? this.store.owner.username : null,
                owner_email: this.store? this.store.owner.email : null,
                updated_user_id: this.store? this.store.owner.id : null,
            })
        };
    },

    created() {
        this.form.location = this.center;
    },

    methods: {
        submit() {
            if (this.store) {
                this.form.patch(this.route('stores.update', this.store.id))
            } else {
                this.form.post(this.route('stores.store'));
            }
        },
    }
}
</script>

<style src="@vueform/toggle/themes/default.css"></style>
