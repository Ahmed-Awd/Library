<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Drivers
                </h2>
                <div>
                </div>
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                   
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User Name 
                                    </th>
                                    
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">show or delete</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(drivertemp, key) in drivers.data" :key="drivertemp.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{drivertemp.name}}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                          {{drivertemp.driver.name}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{drivertemp.username}}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                          {{drivertemp.driver.username}}
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <inertia-link :href="route('driver-temps.show', drivertemp.id)" class="text-indigo-600 hover:text-indigo-900 mr-5">show</inertia-link>
                                        <a href="#" class="text-red-600 hover:text-red-900" @click="driverToDeleteIndex = key">Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <paginator :paginator="drivers"/>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <dialog-modal :show="driverToDeleteIndex !== null" @close="driverToDeleteIndex = null">
            <template #title>
                Delete driver "{{drivers.data[driverToDeleteIndex].name}}"
            </template>
            <template #content>
                If you deleted the driver, All related data will be deleted as well and you won't be able to restore it again.
            </template>
            <template #footer>
                <secondary-button @click="driverToDeleteIndex = null" class="mx-2">
                    Cancel
                </secondary-button>
                <danger-button @click="deleteDriver(drivers.data[driverToDeleteIndex].id)">
                    DELETE!
                </danger-button>
            </template>
        </dialog-modal>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Paginator from "@/Components/Paginator";
    import Toggle from '@vueform/toggle'
    import JetButton from '@/Jetstream/Button'
    import DialogModal from '@/Jetstream/DialogModal.vue';
    import DangerButton from '@/Jetstream/DangerButton.vue';
    import SecondaryButton from '@/Jetstream/SecondaryButton.vue';

    export default {
        components: {
            AppLayout,
            Paginator,
            Toggle,
            JetButton,
            DialogModal,
            DangerButton,
            SecondaryButton,
        },

        props: {
            drivers: Object,
        },

        data() {
            return {
                driverToDeleteIndex: null,
                mounted: false,
            };
        },

        mounted() {
            this.mounted = true;
        },

        methods: {
            deleteDriver(driverId) {
                this.$inertia.delete(this.route('driver-temps.destroy', driverId));
                this.driverToDeleteIndex = null;
            }
        }
    }
</script>

<style src="@vueform/toggle/themes/default.css"></style>
