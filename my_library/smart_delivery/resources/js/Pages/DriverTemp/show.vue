<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ `show data of driver "${driver.name}"` }}
            </h2>
        </template>

        <div class="py-12">
            <div class="flex flex-col max-w-7xl mx-auto sm:px-6 lg:px-8">

             
                <div class="flex mt-3">
                    <div class="flex flex-col text-center bg-white p-4 rounded-lg shadow w-2/3 mr-1">
                        <div class="flex flex-col w-1/3" v-for="file in driver.files"><img :src="'/storage/' + file.path"></div>

                    </div>
                    <div class="flex flex-col w-1/3 ml-1">
                        <div class="flex flex-col bg-white p-4 rounded-lg shadow flex-grow">
                            <h2 class="text-lg text-gray-800">driver Info</h2>
                            <hr class="mt-2 mb-4">
                            <div>
                                <b>driver Name:</b> {{ driver.driver.name }}
                            </div>
                            <div>
                                <b>new driver Name:</b> {{ driver.name }}
                            </div>
                            <div>
                                <b>driver username:</b> {{ driver.driver.username }}
                            </div>
                             <div>
                                <b>new driver username:</b> {{ driver.username }}
                            </div>
                            <div>
                                <b>driver email:</b> {{driver.driver.email }}
                            </div>
                            <div>
                                <b>new driver email:</b> {{ driver.email }}
                            </div>
                             <div>
                                <b>driver phone:</b> {{driver.driver.phone }}
                            </div>
                            <div>
                                <b>new driver phone:</b> {{ driver.email }}
                            </div>
                            
                            <div>
                                <inertia-link :href="route('driver-temps.accept',driver.id)"><b style="color: #621b18"> accept update</b></inertia-link>
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
