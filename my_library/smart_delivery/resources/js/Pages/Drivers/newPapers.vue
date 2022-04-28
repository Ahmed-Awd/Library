<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ `show data of driver "${driver.name}"` }}
            </h2>
        </template>

        <div class="py-12">
            <div class="flex flex-col max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex mt-3">
                    <div class="text-center bg-white p-4 rounded-lg shadow w-2/3 mr-1">
                        <div class="flex">
                            <div class="flex-col w-1/4 paper-container" v-if="papers.personal_photo">
                                <span>Personal Photo</span>
                                <div class="img-container">
                                    <img :src="'/storage/' + papers.personal_photo" @error="$event.target.src='/images/SmartDelivery-login.png'">
                                </div>
                            </div>
                            <div class="flex-col w-1/4 paper-container" v-if="papers.license_photo">
                                <span>License Photo</span>
                                <div class="img-container">
                                    <img :src="'/storage/' + papers.license_photo" @error="$event.target.src='/images/SmartDelivery-login.png'">
                                </div>
                            </div>
                            <div class="flex-col w-1/4 paper-container" v-if="papers.vehicle_photo">
                                <span>Vehicle Photo</span>
                                <div class="img-container">
                                    <img :src="'/storage/' + papers.vehicle_photo" @error="$event.target.src='/images/SmartDelivery-login.png'">
                                </div>
                            </div>
                            <div class="flex-col w-1/4 paper-container" v-if="papers.vehicle_license_photo">
                                <span>Vehicle license photo</span>
                                <div class="img-container">
                                    <img :src="'/storage/' + papers.vehicle_license_photo" @error="$event.target.src='/images/SmartDelivery-login.png'">
                                </div>
                            </div>
                        </div>
                    <div class="save-frm">
                        <jet-form-section @submitted="submit">
                            <template #actions>
                            <jet-button class="orang-btn">
                                confirm
                            </jet-button>
                            </template>
                        </jet-form-section>
                    </div>
                    </div>
                    <div class="flex flex-col w-1/3 ml-1">
                        <div class="flex flex-col bg-white p-4 rounded-lg shadow flex-grow">
                            <h2 class="text-lg text-gray-800">driver Info</h2>
                            <hr class="mt-2 mb-4">
                            <div>
                                <b>driver Name:</b> {{ driver.name }}
                            </div>
                            <div>
                                <b>driver username:</b> {{ driver.username }}
                            </div>
                            <div>
                                <b>driver email:</b> {{ driver.email }}
                            </div>
                            <div>
                                <b>driver phone:</b>       {{driver.country_code }} {{ driver.phone }}
                            </div>
                            <div>
                                <b>driver status:
                                    <span v-if="driver.is_disabled === 1" style="color: red">inactive</span>
                                    <span v-else style="color: greenyellow">active</span>
                                </b>
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
import {useForm} from "@inertiajs/inertia-vue3";

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
        papers: Object,
    },

    data() {
        return {
            form: useForm({
                updated_user_id: this.driver ? this.driver.id : null,
            })
        };
    },
    methods: {
        submit() {
            console.log('submited')
            this.form.post(this.route('drivers.confirm_papers', this.driver.id));
        },
    },

}
</script>
