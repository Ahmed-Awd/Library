<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __("Packages codes") }}
                </h2>
                <div>
                    <inertia-link :href="route('packages.codes.create', package_id)">
                        <jet-button>
                            {{ __("Generate New Code") }}
                        </jet-button>
                    </inertia-link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-1/2 flex items-center w-full justify-between pb-3">
                    <div class="w-1/2 flex items-center">
                        <div class="flex-grow">
                            <multiselect
                                mode="single"
                                searchable=false
                                :placeholder="__('Select Type')"
                                class="searched-select"
                                v-model="selectedTypes"
                                :options="codeTypes"
                                label="name"
                                v-on:click="filterCode"
                            >
                            </multiselect>
                            <jet-input-error :message="form.errors.status" class="mt-2"/>
                        </div>
                        <div class="ml-3">
                            <dropdown>
                                <template #trigger>
                                    <div
                                        class="flex items-center text-gray-400 capitalize border px-4 py-2 rounded-md border-gray-300 cursor-pointer hover:bg-gray-200">
                                        <icon name="date" class="w-5 h-5 mr-2"/>
                                        {{ form.range }}
                                        <icon name="dropdown" class="w-4 h-4 ml-2"/>
                                    </div>
                                </template>
                                <template #content>
                                    <dropdown-link v-for="filter in dateFilters" :key="filter" as="button"
                                                   @click="form.range = filter">
                                        <span class="capitalize">{{ __(filter) }}</span>
                                    </dropdown-link>
                                    <div class="border-t border-gray-200"></div>
                                    <dropdown-link as="button" button-type="button" @click="showDateModal = true">
                                        <span class="capitalize">{{ __('Custom') }}</span>
                                    </dropdown-link>
                                </template>
                            </dropdown>
                        </div>
                        <div v-if="form.range === 'custom'">
                        </div>
                        <div class="ml-3">
                            <jet-button v-on:click="submitSearch">
                                <icon name="search" class="w-5 h-5"/>
                            </jet-button>
                        </div>
                    </div>
                </div>
                <dialog-modal :show="showDateModal == true" @close="showDateModal == false">
                    <template #title>
                        {{ __('Choose date range') }}
                    </template>
                    <template #content>
                        <div>
                            <jet-label for="start_date" value="From"/>
                            <jet-input id="start_date" type="date" class="mt-1 block w-full" v-model="dateFrom"/>
                        </div>
                        -
                        <div>
                            <jet-label for="end_date" value="To"/>
                            <jet-input id="end_date" type="date" class="mt-1 block w-full" v-model="dateTo"/>
                        </div>
                    </template>

                    <template #footer>
                        <jet-secondary-button @click="showDateModal = false">
                            {{ __('Cancel') }}
                        </jet-secondary-button>

                        <jet-button class="ml-2" @click="chooseCustomDate">
                            {{ __('Save') }}
                        </jet-button>
                    </template>
                </dialog-modal>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("Code") }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("Is Used") }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("User Name") }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("Purchased At") }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="code in codes.data" :key="code.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ code.code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ code.purchased_at ? __("Yes") : __("No") }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" v-if="this.type == 'driver'">
                                            {{ code.user == null ? null : code.user.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap" v-else>
                                            <p v-if="code.user == null"></p>
                                            <p v-else> {{ code.user.store == null ? null : code.user.store.name }} </p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span v-if="code.purchased_at != null">{{
                                                    proccessDate(code.purchased_at)
                                                }}</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <paginator :paginator="codes"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="total-order">
                    <span class="bold-font">{{ __('Total Fee') }} : </span>
                    <span>{{ totalPrice / 100 }}</span>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Paginator from "@/Components/Paginator";
import JetButton from '@/Jetstream/Button'
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import Multiselect from '@vueform/multiselect';
import Dropdown from '@/Jetstream/Dropdown.vue';
import DropdownLink from '@/Jetstream/DropdownLink.vue';
import DialogModal from '../../Jetstream/DialogModal.vue';

export default {
    components: {
        AppLayout,
        Paginator,
        JetButton,
        JetFormSection,
        JetInput,
        JetLabel,
        Multiselect,
        Dropdown,
        DropdownLink,
        DialogModal,
    },
    props: {
        codes: Array,
        package_id: Number,
        status: Number,
        type: String,
        totalPrice: Number,
    },
    data() {
        return {
            selectedTypes: this.route().params.type ? this.route().params.type : 'all',
            form: this.$inertia.form({
                range: this.route().params.range ? this.route().params.range : 'all',
                type: null,
            }),
            codeTypes: [
                {'name': this.__('all'), 'value': "all"},
                {'name': this.__('used'), 'value': "used"},
                {'name': this.__('new'), 'value': "new"},
            ],
            showDateModal: false,
            dateFrom: null,
            dateTo: null,
            dateFilters: [
                'all', 'today', 'yesterday', 'this-week', 'prev-week', 'this-month', 'prev-month'
            ],
            show: true,
        };
    },

    methods: {
        filterCode() {
            console.log(this.selectedTypes);
        },
        submitSearch() {
            console.log(this.selectedTypes);
            this.form.type = this.selectedTypes;
            console.log(this.form);
            this.form.get(this.route('packages.codes.index', this.package_id));
        },
        proccessDate(date) {
            return Intl.DateTimeFormat('en-BZ', {
                month: '2-digit',
                day: '2-digit',
                year: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            }).format(new Date(date))
        },
        chooseCustomDate() {
            this.showDateModal = false;
            this.form.range = this.dateFrom + ',' + this.dateTo;
            this.filterCode();
        },
    },
    watch: {
        selectedStatuses() {
            this.filterCode();
            console.log(this.form.type);
        },
    }
}
</script>
<style src="@vueform/toggle/themes/default.css"></style>
<style src="@vueform/multiselect/themes/default.css"></style>
