<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{__('Settings of')}} {{ store.name }}
                </h2>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('setting name')}}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('value')}}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{__('delivery perice percentage payed from store')}}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="col-span-6">
                                                <jet-label for="value"/>
                                                <jet-input id="delivery_perice_percentage" type="text" class="mt-1 block w-full"
                                                           v-model="store.setting.delivery_perice_percentage" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{__('taken percentage from store per order')}}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="col-span-6">
                                                <jet-label for="value"/>
                                                <jet-input id="taken_percentage_from_store" type="text" class="mt-1 block w-full"
                                                           v-model="store.setting.taken_percentage_from_store"
                                                           :readonly="$page.props.user.roles[0].name == 'owner'" />
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <jet-button v-on:click="save()">
                                    {{__('Save')}}
                                </jet-button>
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
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import DialogModal from '@/Jetstream/DialogModal.vue';
import DangerButton from '@/Jetstream/DangerButton.vue';
import SecondaryButton from '@/Jetstream/SecondaryButton.vue';


export default {
    name: "Setting",
    components: {
        AppLayout,
        Paginator,
        Toggle,
        JetButton,
        DialogModal,
        DangerButton,
        SecondaryButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetActionMessage,
        JetSecondaryButton,
    },
    props: {
        store:Object,
    },
    data() {
    },
    methods: {
        save(){
            this.$inertia.post(`/stores/settings/${this.store.id}`, {
                delivery_perice_percentage:this.store.setting.delivery_perice_percentage,
                taken_percentage_from_store:this.store.setting.taken_percentage_from_store,
            } );
        },
    },
}
</script>

<style src="@vueform/toggle/themes/default.css"></style>
