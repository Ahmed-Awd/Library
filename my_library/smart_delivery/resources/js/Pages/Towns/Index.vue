<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{__("Towns")}}
                </h2>
                <div>
                    <inertia-link :href="route('towns.create')">
                        <jet-button>
                             {{__("New Town")}}
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
                                        {{__("Town name")}}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{__("Status")}}
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit or delete</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(town, key) in towns" :key="town.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{town.name}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <toggle :id="'town_toggle_' + town.id"
                                            :model-value="town.is_active"
                                            :off-label="__('inactive')"
                                            :on-label="__('active')"
                                            :width="100"
                                            @change="toggleUserStatus(town.id)"/>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <inertia-link :href="`driver/all/on-map/${town.id}`" class="text-indigo-600 hover:text-indigo-900 mr-5">{{__("Active Drivers")}}</inertia-link>
                                        <inertia-link :href="route('towns.edit', town.id)" class="text-indigo-600 hover:text-indigo-900 mr-5">{{__("Edit")}}</inertia-link>
                                        <a href="#" class="text-red-600 hover:text-red-900" @click="townToDeleteIndex = key">{{__("Delete")}}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <dialog-modal :show="townToDeleteIndex !== null" @close="townToDeleteIndex = null">
            <template #title>
                Delete town "{{towns[townToDeleteIndex].name}}"
            </template>
            <template #content>
                If you deleted the town, All related data will be deleted as well and you won't be able to retown it again.
            </template>
            <template #footer>
                <secondary-button @click="townToDeleteIndex = null" class="mx-2">
                    Cancel
                </secondary-button>
                <danger-button @click="deletetown(towns[townToDeleteIndex].id)">
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
            towns: Array
        },

        data() {
            return {
                townToDeleteIndex: null,
                mounted: false,
            };
        },

        mounted() {
            this.mounted = true;
            console.log(this.towns);
        },

        methods: {
            deletetown(townId) {
                this.$inertia.delete(this.route('towns.destroy', townId));
                this.townToDeleteIndex = null;
            },
            toggleUserStatus(townId) {
                if (!this.mounted) {
                    return;
                }
                this.$inertia.patch(this.route('towns.switch-status', townId));
            }
        }
    }
</script>

<style src="@vueform/toggle/themes/default.css"></style>
