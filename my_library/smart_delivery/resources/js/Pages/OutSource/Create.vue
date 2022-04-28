<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ store? `Edit Company "${store.name}"` : 'Create a new Company' }}
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
                                <b>Notice:</b> A password set link will be sent to the company email. No need to
                                insert password in this page.
                            </div>
                        </div>
                    </div>
                </div>
                <jet-form-section @submit.prevent="submit">
                    <template #form>
                        <div class="col-span-6">
                            <jet-label for="name" value="Name"/>
                            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name"/>
                            <jet-input-error :message="form.errors.name" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <jet-label for="username" value="Username"/>
                            <jet-input id="username" type="text" class="mt-1 block w-full"
                                       v-model="form.username"/>
                            <jet-input-error :message="form.errors.username" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <jet-label for="email" value="Email"/>
                            <jet-input id="email" type="email" class="mt-1 block w-full"
                                       v-model="form.email"/>
                            <jet-input-error :message="form.errors.email" class="mt-2"/>
                        </div>
                        <div class="col-span-6">
                            <jet-label for="country_code" value="Country Code"/>
                            <jet-input id="country_code" type="number" class="mt-1 block w-full"
                                       v-model="form.country_code"/>
                            <jet-input-error :message="form.errors.country_code" class="mt-2"/>
                        </div>
                         <div class="col-span-6">
                            <jet-label for="phone" value="phone"/>
                            <jet-input id="phone" type="number" class="mt-1 block w-full"
                                       v-model="form.phone"/>
                            <jet-input-error :message="form.errors.phone" class="mt-2"/>
                        </div>
                    </template>

                    <template #actions>
                        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                            Saved.
                        </jet-action-message>

                        <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Save
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
        outsource: Object,
    },

    data() {
        return {
            form: this.$inertia.form({
                name: this.outsource? this.outsource.name : null,
                username: this.outsource? this.outsource.username : null,
                email: this.outsource? this.outsource.email : null,
                country_code: this.outsource? this.outsource.country_code : null,
                phone: this.outsource? this.outsource.phone : null,
                updated_user_id: this.outsource? this.outsource.id : null,
            })
        };
    },

    methods: {
        submit() {
            if (this.outsource) {
                this.form.patch(this.route('outsources.update', this.outsource.id))
            } else {
                this.form.post(this.route('outsources.store'));
            }
        },
    }
}
</script>

<style src="@vueform/toggle/themes/default.css"></style>
