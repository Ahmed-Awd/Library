<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __("Drivers") }}
                </h2>
                <div>
                    <!--                    <inertia-link :href="route('drivers.create')">-->
                    <!--                        <jet-button>-->
                    <!--                            New Driver-->
                    <!--                        </jet-button>-->
                    <!--                    </inertia-link>-->
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-1/2 flex items-center w-full justify-between pb-3">
                    <form class="w-100 flex items-center">
                        <div>
                            <jet-label for="search" value=""/>
                            <jet-input
                                type="text"
                                class="mt-1 block mr-2"
                                v-model="searchValue"
                                :placeholder="__('Search by Name or Phone')"
                            />
                        </div>
                        <div class="ml-3 mt-1">
                            <button class="btn orang-btn" @click.prevent="searching">
                                {{ __("Filter") }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="
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
                                            {{ __("Name") }}
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
                                            {{ __("Town") }}
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
                                            {{ __("Phone Number") }}
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
                                            {{ __("Disable or Active") }}
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
                                            {{ __("Current Status") }}
                                        </th>

                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Edit or Delete</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(driver, key) in driversCopy.data">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ driver.name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ driver.username }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ driver.balance / 100 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ driver.town.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ driver.country_code }} {{ driver.phone }}
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
                                                :id="'driver_toggle_' + driver.id"
                                                :model-value="driver.is_disabled == 0"
                                                :off-label="__('inactive')"
                                                :on-label="__('active')"
                                                :width="100"
                                                @click="toggleUserStatus(driver, key)"
                                            />
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ __(driver.current_states) }}
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
                                            <div class="d-flex">
                                                <inertia-link
                                                    :href="route('drivers.edit', driver.id)"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-5"
                                                >{{ __("Edit") }}
                                                </inertia-link
                                                >
                                                <inertia-link
                                                    :href="route('drivers.show', driver.id)"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-5"
                                                >{{ __("Show") }}
                                                </inertia-link
                                                >
                                                <!-- <a href="#" class="text-red-600 hover:text-red-900" @click="driverToDeleteIndex = key">Delete</a>-->
                                                <inertia-link
                                                    :href="route('drivers.newPapers', driver.id)"
                                                    class="
                              text-indigo-600
                              hover:text-indigo-900
                              d-inline
                            "
                                                    v-if="driver.has_new_papers == 1"
                                                >
                                                    <img src="images/icon-file.png" class="w-5"/>
                                                </inertia-link>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <paginator :paginator="driversCopy"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="total-order">
                    <div>
                        <span class="bold-font">{{ __("Drivers Count") }} : </span>
                        <span>{{ driversCount }}</span>
                    </div>
                </div>
            </div>
        </div>
        <dialog-modal
            :show="driverToDeleteIndex !== null"
            @close="driverToDeleteIndex = null"
        >
            <template #title>
                Suspend driver "{{ drivers.data[driverToDeleteIndex].name }}"
            </template>
            <template #content>
                If you delete the driver, he deleted for ever
            </template>
            <template #footer>
                <secondary-button @click="driverToDeleteIndex = null" class="mx-2">
                    Cancel
                </secondary-button>
                <danger-button
                    @click="deleteDriver(drivers.data[driverToDeleteIndex].id)"
                >
                    DELETE!
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
import JetInput from "@/Jetstream/Input";
import JetLabel from "@/Jetstream/Label";
import DialogModal from "@/Jetstream/DialogModal.vue";
import DangerButton from "@/Jetstream/DangerButton.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import axios from "axios";

export default {
    components: {
        AppLayout,
        Paginator,
        Toggle,
        JetButton,
        JetInput,
        JetLabel,
        DialogModal,
        DangerButton,
        SecondaryButton,
    },


    data() {
        return {
            searchValue: "",
            currentPage:1,
            driverToDeleteIndex: null,
            driversCount: 0,
            driversCopy: [],
            driversOriginal: [],
			urlPrams : '',
            serverParams: {
                page:1,
                search_value:""
            },
        };
    },
    mounted() {
        this.getDrivers();
    },
    methods: {
        getDrivers() {
            const params = new URLSearchParams(window.location.search)
			// console.log("params" , params.get('search_value'));
            this.serverParams.page = params.get('page') || 1;
            this.serverParams.search_value = params.get('search_value') || this.searchValue;
            axios.get(`drivers`, {
                headers: {"Accept": "application/json"},
                params: this.serverParams,
            })
                .then((response) => {
                    this.driversCopy = response.data.drivers
                    this.driversCount = this.driversOriginal.length;
                    console.log(response.data);
                }).catch((errors) => {
                console.log(errors.data);
            });
        },
        searching() {
            this.serverParams.search_value =  this.searchValue;
            const params = new URLSearchParams(window.location.search)
            params.set("page",1);
            params.set("search_value",this.searchValue);
            window.location.search = params;
            this.getDrivers();
        },
        deleteDriver(driverId) {
            this.$inertia.delete(this.route("drivers.destroy", driverId));
            this.driverToDeleteIndex = null;
        },
        toggleUserStatus(driver, index) {
            if (driver.loaderStatus == true) {
                driver.is_disabled ? 0 : 1;
                return;
            }
            driver.loaderStatus = true
            let url = driver.is_disabled === 1 ? "users/enable-user/" : "users/disable-user/";

            this.toggleApi(url, driver, index);
        },
        toggleApi(url, driver, index) {
            axios
                .patch(url + "" + driver.id, {})
                .then((response) => {
                    driver.loaderStatus = false;
                    console.log(response.data);
                    this.$toast.success(response.data.message);

                    this.driversOriginal[index]['is_disabled'] = response.data.user.is_disabled;
                    this.driversCopy[index]['is_disabled'] = response.data.user.is_disabled;

                    console.log("drivers ", this.drivers[index]['is_disabled']);
                })
                .catch((errors) => {
                    driver.loaderStatus = false;
                    driver.is_disabled ? 0 : 1;
                    console.log(errors.data);
                });
        },
    },
};
</script>

<style src="@vueform/toggle/themes/default.css"></style>
