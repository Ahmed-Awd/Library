<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{__("Packages")}}
                </h2>
                <div class="row">
                    <jet-button class="mr-2 excel-btn"  @click="showDateModal = true">
                        {{ __("Excel") }}
                    </jet-button>
                    <inertia-link :href="route('packages.create')">
                        <jet-button class="mr-2">
                            {{__("New Package")}}
                        </jet-button>
                    </inertia-link>
                    <inertia-link :href="route('packages.codes.all')">
                        <jet-button class="btn-primary">
                            {{__("all codes")}}
                        </jet-button>
                    </inertia-link>
                    <!-- <div class="ml-3 mt-20" style="flex:0 0 200px">
                        <a class="btn excel-btn" target="_blank" :href="xls_url">{{ __("Excel") }}</a>
                    </div> -->
                </div>
                <dialog-modal :show="showDateModal == true" @close="showDateModal == false">
                    <template #title>
                        {{__('Choose date range')}}
                    </template>
                    <template #content>
                        <div class="mb-2">
                            <jet-label for="start_date" value="From"/>
                            <jet-input id="start_date" type="date" class="mt-1 block w-full" v-model="from"/>
                        </div>
                        <div>
                            <jet-label for="end_date" value="To"/>
                            <jet-input id="end_date" type="date" class="mt-1 block w-full" v-model="to" :min="from"/>
                        </div>
                    </template>

                    <template #footer>
                        <jet-secondary-button class="btn" @click="showDateModal = false">
                            {{__('Cancel')}}
                        </jet-secondary-button>
                        <a class=" ml-2" target="_blank" :href="xls_url">
                            <jet-button class="btn excel-btn" @click="exportExcel">
                                {{__('Excel')}}
                            </jet-button>
                        </a>
                    </template>
                </dialog-modal>
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
                                        {{__("Package Name")}}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{__("Price")}}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{__("Value")}}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{__("type")}}
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit or delete</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(balancePackage, key) in packages.data" :key="balancePackage.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{balancePackage.package_name}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{balancePackage.price/100}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{balancePackage.value/100}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{__(balancePackage.type)}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <inertia-link :href="route('packages.codes.index', balancePackage.id)" class="text-indigo-600 hover:text-indigo-900 mr-5">{{__("Codes")}}</inertia-link>
                                        <inertia-link :href="route('packages.edit', balancePackage.id)" class="text-indigo-600 hover:text-indigo-900 mr-5">{{__("Edit")}}</inertia-link>
                                        <a href="#" class="text-red-600 hover:text-red-900" @click="packageToDeleteIndex = key">{{__("Delete")}}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <paginator :paginator="balancePackage"/>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <dialog-modal :show="packageToDeleteIndex !== null" @close="packageToDeleteIndex = null">
            <template #title>
                {{__("Delete package")}} "{{packages.data[packageToDeleteIndex].package_name}}"
            </template>
            <template #content>
                {{__("are you sure")}}
            </template>
            <template #footer>
                <secondary-button @click="packageToDeleteIndex = null" class="mx-2">
                    {{__("Cancel")}}
                </secondary-button>
                <danger-button @click="deletePackage(packages.data[packageToDeleteIndex].id)">
                    {{__("Delete")}}!
                </danger-button>
            </template>
        </dialog-modal>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Paginator from "@/Components/Paginator";
    import JetButton from '@/Jetstream/Button'
    import DialogModal from '@/Jetstream/DialogModal.vue';
    import DangerButton from '@/Jetstream/DangerButton.vue';
    import SecondaryButton from '@/Jetstream/SecondaryButton.vue';
    import Toggle from '@vueform/toggle'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetLabel from '@/Jetstream/Label'

    export default {
        components: {
            AppLayout,
            Paginator,
            JetButton,
            DialogModal,
            DangerButton,
            SecondaryButton,
            Toggle,
            JetFormSection,
            JetInput,
            JetLabel
        },

        props: {
            packages: Array
        },

        data() {
            return {
                packageToDeleteIndex: null,
                showDateModal: false,
                xls_url: "/export/of/stores/charges",
                from: '',
                to: '',
            };
        },

        methods: {
            deletePackage(packageId) {
                this.$inertia.delete(this.route('packages.destroy', packageId));
                this.packageToDeleteIndex = null;
            },
            exportExcel(){
                this.xls_url = '/export/of/stores/charges';
                if (this.from != '') {
                    this.xls_url += '?from=' + this.from;
                }
                if (this.to != '') {
                    this.xls_url += '&to=' + this.to;
                }
            }
        }
    }
</script>
