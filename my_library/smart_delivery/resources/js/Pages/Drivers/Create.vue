<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ driver ? `${__("edit driver")} ${driver.name}` : 'Create a new driver' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="md:grid md:grid-cols-5" v-if="!driver">
                    <div class="md:col-start-2 md:col-span-3 p-2 mb-3">
                        <div class="flex bg-purple-600 text-white p-4 rounded-md shadow">
                            <div class="bg-white rounded-md text-purple-600 p-2">
                                <icon name="info"/>
                            </div>
                            <div class="pl-3">
                                <b>Notice:</b> A password set link will be sent to the driver email. No need to insert
                                password in this page.
                            </div>
                        </div>
                    </div>
                </div>
                <jet-form-section @submitted="submit">
                    <template #form>
                        <div class="col-span-6">
                            <jet-label for="name" :value="__('Name')"/>
                            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name"/>
                            <jet-input-error :message="form.errors.name" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <jet-label for="username" :value="__('Username')"/>
                            <jet-input id="username" type="text" class="mt-1 block w-full" v-model="form.username"/>
                            <jet-input-error :message="form.errors.username" class="mt-2"/>
                        </div>

                        <div class="col-span-2">
                            <jet-label for="country_code" :value="__('country_code')"/>
                            <jet-input id="country_code" type="text" class="mt-1 block w-full"
                                       v-model="form.country_code"/>
                            <jet-input-error :message="form.errors.country_code" class="mt-2"/>
                        </div>
                        <div class="col-span-4">
                            <jet-label for="phone" :value="__('Phone Number')"/>
                            <jet-input id="phone" type="text" class="mt-1 block w-full"
                                       v-model="form.phone"/>
                            <jet-input-error :message="form.errors.phone" class="mt-2"/>
                        </div>
                         <div class="col-span-6">
                            <jet-label for="DriverType" :value="__('Driver Type')"/>
                            <select id="driver_types" v-model="form.driver_type"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option  value="app"  selected="form.driver_type==app">{{__("app")}}</option>
                                <option  value="freelancer" selected="form.driver_type==freelancer" >{{__("free lancer")}}</option>
                            </select>
                            <jet-input-error :message="form.errors.driver_type" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <label for="personal_photo">{{__("personal_photo")}}: </label>
                            <input id="personal_photo" type="file" accept="image/png, image/jpg, image/jpeg" @input="form.personal_photo = $event.target.files[0]" />
                        </div>
                        <div class="col-span-6">
                            <label for="license_photo"> {{__("license_photo")}}: </label>
                            <input id="license_photo" type="file" accept="image/png, image/jpg, image/jpeg" @input="form.license_photo = $event.target.files[0]" />
                            <jet-input-error :message="form.errors.license_photo" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <label for="vehicle_photo"> {{__("vehicle_photo")}} : </label>
                            <input id="vehicle_photo" type="file" accept="image/png, image/jpg, image/jpeg" @input="form.vehicle_photo = $event.target.files[0]" />
                            <jet-input-error :message="form.errors.vehicle_photo" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <label for="vehicle_license_photo"> {{__("vehicle_license_photo")}} :</label>
                            <input id="vehicle_license_photo" type="file" accept="image/png, image/jpg, image/jpeg" @input="form.vehicle_license_photo = $event.target.files[0]" />
                            <jet-input-error :message="form.errors.vehicle_license_photo" class="mt-2"/>
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
import JetButton from '@/Jetstream/Button'
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import { useForm } from '@inertiajs/inertia-vue3'

export default {
    components: {
        AppLayout,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetActionMessage,
        JetSecondaryButton,
    },

    props: {
        driver: Object,
    },

    data() {
        return {
            form: useForm({
                name: this.driver ? this.driver.name : null,
                username: this.driver ? this.driver.username : null,
                country_code: this.driver ? this.driver.country_code : null,
                driver_type: this.driver ? this.driver.driver_type : null,
                phone: this.driver ? this.driver.phone : null,
                updated_user_id: this.driver ? this.driver.id : null,
                personal_photo:  null,
                license_photo:  null,
                vehicle_photo:  null,
                vehicle_license_photo:  null,
                _method:"patch"
            })
        };
    },
    methods: {
        submit() {
            if (this.driver) {
                console.log(this.form);
                this.form.post(this.route('drivers.update', this.driver.id));
            } else {
                this.form.post(this.route('drivers.store'));
            }
        },
    },
}
</script>
