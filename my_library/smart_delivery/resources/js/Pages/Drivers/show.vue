<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ `${__("show data of driver")} ${driver.name}` }}
            </h2>
        </template>

        <div class="py-12">
            <div class="flex flex-col max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!--                <div class="flex text-center bg-white p-4 rounded-lg shadow justify-between">-->
                <!--                    <div>-->
                <!--                        {{order.total_price}}-->
                <!--                        {{order.delivery_price}}-->
                <!--                        {{order.distance_store_order}}-->
                <!--                        {{order.expected_time}}-->
                <!--                        <rate v-model="order.rate" @change="rate" :disabled="order.rate > 0"/>-->
                <!--                        {{order.created_at}}-->
                <!--                    </div>-->
                <!--                    <qr-code :content="order.qr_code.toString()" class="w-28"/>-->
                <!--                </div>-->
                <div class="flex mt-3 mobile-flex mobile-m-10">
                    <div class="flex text-center bg-white p-4 rounded-lg shadow w-2/3 mr-1 mobile-flex">
                        <div class="flex-col w-1/3" v-for="file in driver.files">
                            <span>{{__(file.description)}}</span>
                            <div class="img-container">
                                <img :src="'/storage/' + file.path"  @error="$event.target.src='/images/SmartDelivery-login.png'">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-1/3 ml-1 mobile-flex mobile-ml-0 mobile-mt-10">
                        <div class="flex flex-col bg-white p-4 rounded-lg shadow flex-grow">
                            <h2 class="text-lg text-gray-800">{{__("driver Info")}}</h2>
                            <hr class="mt-2 mb-4">
                            <div>
                                <b>{{__("driver name")}}:</b> {{ driver.name }}
                            </div>
                            <div>
                                <b>{{__("driver username")}}:</b> {{ driver.username }}
                            </div>
                            <div>
                                <b>{{__("driver town")}}:</b> {{ driver.town.name }}
                            </div>
                            <div v-if="driver.email">
                                <b>{{__("driver email")}}:</b> {{ driver.email }}
                            </div>
                            <div>
                                <b>{{__("driver phone")}}:</b>       {{driver.country_code }} {{ driver.phone }}
                            </div>
                            <div>
                                <b>{{__("driver status")}}:
                                    <span v-if="driver.is_disabled === 1" style="color: red">{{ __("inactive") }}</span>
                                    <span v-else style="color: greenyellow">{{ __("active") }}</span>
                                </b>
                            </div>
                            <div>
                                <inertia-link :href="route('driver.transactions',driver.id)"><b style="color: #621b18">{{__("Transactions history")}}</b></inertia-link>
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
    },

    data() {

    },
}
</script>
