<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Stores
                </h2>
                <div>
                    <inertia-link :href="route('stores.create')">
                        <jet-button>
                            {{ __("New Store") }}
                        </jet-button>
                    </inertia-link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-1/2 flex items-center w-full justify-between pb-3">
                    <form class="flex items-center" @submit.prevent="searchRequest">
                        <jet-input
                            type="text"
                            class="block mr-2"
                            v-model="filter"
                            :placeholder="__('Store name')"
                        />
                        <jet-button>
                            <icon name="search" class="w-5 h-5"/>
                        </jet-button>
                    </form>
                </div>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div
                            class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
                        >
                            <div
                                class="
                  shadow
                  overflow-hidden
                  border-b border-gray-200
                  sm:rounded-lg
                "
                            >
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="
                          px-6
                          py-3
                          text-left text-xs
                          font-medium
                          text-gray-500
                          uppercase
                          tracking-wider
                        "
                                        >
                                            {{ __("Store name") }}
                                        </th>
                                        <th
                                            scope="col"
                                            class="
                          px-6
                          py-3
                          text-left text-xs
                          font-medium
                          text-gray-500
                          uppercase
                          tracking-wider
                        "
                                        >
                                            {{ __("Owner name") }}
                                        </th>
                                        <th
                                            scope="col"
                                            class="
                          px-6
                          py-3
                          text-left text-xs
                          font-medium
                          text-gray-500
                          uppercase
                          tracking-wider
                        "
                                        >
                                            {{ __("Store Town") }}
                                        </th>
                                        <th
                                            scope="col"
                                            class="
                          px-6
                          py-3
                          text-left text-xs
                          font-medium
                          text-gray-500
                          uppercase
                          tracking-wider
                        "
                                        >
                                            {{ __("Balance") }}
                                        </th>
                                        <th
                                            scope="col"
                                            class="
                          px-6
                          py-3
                          text-left text-xs
                          font-medium
                          text-gray-500
                          uppercase
                          tracking-wider
                        "
                                        >
                                            {{ __("rate of delivery payed") }}
                                        </th>
                                        <th
                                            scope="col"
                                            class="
                          px-6
                          py-3
                          text-left text-xs
                          font-medium
                          text-gray-500
                          uppercase
                          tracking-wider
                        "
                                        >
                                            {{ __("Status") }}
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">{{ __("Edit or delete") }}</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(store, key) in stores.data" :key="store.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ store.name }}
                                                </div>
                                                <div class="text-sm text-gray-500 capitalize">
                                                    {{ store.type.name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ store.owner.name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ store.owner.username }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ store.town.name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ store.owner.balance / 100 }}
                                            </div>
                                            <div
                                                class="text-sm text-gray-500"
                                                v-if="store.owner.reserved_balance > 0"
                                            >
                                                + {{ store.owner.reserved_balance / 100 }} reserved
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ store.setting.delivery_perice_percentage }} %
                                            </div>
                                        </td>
                                        <td
                                            class="
                          px-6
                          py-4
                          whitespace-nowrap
                          text-sm text-gray-500
                        "
                                        >


                                            <toggle
                                                :id="'store_toggle_' + store.id"
                                                :model-value="store.owner.is_disabled == 0"
                                                :off-label="__('inactive')"
                                                :on-label="__('active')"
                                                :width="100"
                                                @click="toggleUserStatus(store)"
                                            />
                                        </td>
                                        <td
                                            class="
                          px-6
                          py-4
                          whitespace-nowrap
                          text-right text-sm
                          font-medium
                        "
                                        >
                                            <inertia-link
                                                :href="route('stores.info', store.id)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-5"
                                            >{{ __("Details") }}
                                            </inertia-link
                                            >
                                            <inertia-link
                                                :href="route('stores.settings', store.id)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-5"
                                            >{{ __("Settings") }}
                                            </inertia-link
                                            >
                                            <inertia-link
                                                :href="route('stores.orders.index', store.id)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-5"
                                            >{{ __("Orders") }}
                                            </inertia-link
                                            >
                                            <inertia-link
                                                :href="route('stores.edit', store.id)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-5"
                                            >{{ __("Edit") }}
                                            </inertia-link
                                            >
                                            <a
                                                href="#"
                                                class="text-red-600 hover:text-red-900"
                                                @click="storeToDeleteIndex = key"
                                            >{{ __("Delete") }}</a
                                            >
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <paginator :paginator="stores"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <dialog-modal
            :show="storeToDeleteIndex !== null"
            @close="storeToDeleteIndex = null"
        >
            <template #title>
                {{ __("Delete store") }} "{{ stores.data[storeToDeleteIndex].name }}"
            </template>
            <template #content>
                {{
                    __(
                        "If you deleted the store, All related data will be deleted as well and you won't be able to restore it again."
                    )
                }}
            </template>
            <template #footer>
                <secondary-button @click="storeToDeleteIndex = null" class="mx-2">
                    {{ __("Cancel") }}
                </secondary-button>
                <danger-button @click="deleteStore(stores.data[storeToDeleteIndex].id)">
                    {{ __("Delete") }}
                </danger-button>
            </template>
        </dialog-modal>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Paginator from "@/Components/Paginator";
import Toggle from "@vueform/toggle";
import JetButton from "@/Jetstream/Button";
import DialogModal from "@/Jetstream/DialogModal.vue";
import DangerButton from "@/Jetstream/DangerButton.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetInput from "@/Jetstream/Input";
import JetLabel from "@/Jetstream/Label";
import axios from "axios";

export default {
    components: {
        AppLayout,
        Paginator,
        Toggle,
        JetButton,
        DialogModal,
        DangerButton,
        SecondaryButton,
        JetInput,
        JetLabel,
    },

    props: {
        stores: Array,
    },

    data() {
        return {
            storeToDeleteIndex: null,
            
            filter: null,
            form: this.$inertia.form({
                filter: this.route().params.filter ? this.route().params.filter : "",
            }),
        };
    },

    mounted() {
      
    },

    methods: {
        deleteStore(storeId) {
            this.$inertia.delete(this.route("stores.destroy", storeId));
            this.storeToDeleteIndex = null;
        },
        toggleUserStatus(store) {
            if (store.loaderStatus == true) {
                store.owner.is_disabled ? 0 : 1;
                return;
            }
            store.loaderStatus = true
            let url = store.owner.is_disabled === 1 ? "users/enable-user/" : "users/disable-user/";
            this.toggleApi(url, store);
        },
        toggleApi(url, store) {
            axios
                .patch(url + "" + store.owner.id, {})
                .then((response) => {
                    store.loaderStatus = false;
                    console.log(response.data);
                    this.$toast.success(response.data.message);
                    store.owner = response.data.user;
                })
                .catch((errors) => {
                    store.loaderStatus = false;
                    store.owner.is_disabled ? 0 : 1;

                    console.log(errors.data);
                });
        },
        searchRequest() {
            this.filter = "filter=" + this.filter;
            this.$inertia.get(this.route("stores.index", this.filter).slice(0, -1));
        },
    },
};
</script>

<style src="@vueform/toggle/themes/default.css"></style>
