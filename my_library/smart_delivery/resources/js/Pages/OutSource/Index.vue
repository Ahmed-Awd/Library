<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Companies
                </h2>
                <div>
                    <inertia-link :href="route('outsources.create')">
                        <jet-button>
                            New Company
                        </jet-button>
                    </inertia-link>
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
                                         name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                         email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        phone
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit or delete</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(outsource, key) in outsources.data" :key="outsource.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{outsource.name}}</div>
                                        <div class="text-sm text-gray-500">{{outsource.username}}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{outsource.email}}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{outsource.country_code}}-{{outsource.phone}}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                                        <toggle :id="'store_toggle_' + outsource.id"
                                            :model-value="!outsource.is_disabled"
                                            off-label="inactive"
                                            on-label="active"
                                            :width="100"
                                            @change="toggleUserStatus(outsource.id)"/>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <inertia-link :href="route('outsources.edit', outsource.id)" class="text-indigo-600 hover:text-indigo-900 mr-5">Edit</inertia-link>
                                        <a href="#" class="text-red-600 hover:text-red-900" @click="outsourceToDeleteIndex = key">Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <paginator :paginator="outsources"/>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <dialog-modal :show="outsourceToDeleteIndex !== null" @close="outsourceToDeleteIndex = null">
            <template #title>
                Delete OutSource "{{outsources.data[outsourceToDeleteIndex].name}}"
            </template>
            <template #content>
                If you deleted the store, All related data will be deleted as well and you won't be able to restore it again.
            </template>
            <template #footer>
                <secondary-button @click="outsourceToDeleteIndex = null" class="mx-2">
                    Cancel
                </secondary-button>
                <danger-button @click="deleteOutsource(outsources.data[outsourceToDeleteIndex].id)">
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
            outsources: Array
        },

        data() {
            return {
                outsourceToDeleteIndex: null,
                mounted: false,
            };
        },

        mounted() {
            this.mounted = true;
        },

        methods: {
            deleteOutsource(outsourceId) {
                this.$inertia.delete(this.route('outsources.destroy', outsourceId));
                this.outsourceToDeleteIndex = null;
            },
            toggleUserStatus(userId) {
                if (!this.mounted) {
                    return;
                }
                this.$inertia.patch(this.route('users.switch-status', userId));
            }
        }
    }
</script>

<style src="@vueform/toggle/themes/default.css"></style>
