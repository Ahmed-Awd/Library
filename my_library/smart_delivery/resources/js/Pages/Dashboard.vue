<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __("Dashboard") }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-wrap">
                    <statistics-card
                        v-for="card in cards"
                        :key="card.title"
                        :title="card.title"
                        :number="card.number"
                        :difference="card.difference"
                        :icon="card.icon"/>
                </div>
                <chart v-if="monthlyEarnings" :data="monthlyEarnings"/>
                <form class="mt-12 mx-2 p-6 rounded-md shadow-md bg-purple-600 flex flex-col" @submit.prevent="chargeBalance" v-if="$page.props.user.roles[0].name == 'owner'">
                    <h2 class="mb-3 text-lg font-bold text-white">Charge Your Balance</h2>
                    <jet-input id="code" v-model="form.code" type="text" placeholder="Code.."/>
                    <div class="text-right mt-3">
                        <jet-button class="border-4 border-white">
                            Charge
                        </jet-button>
                    </div>
                </form>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import StatisticsCard from '@/Components/StatisticsCard.vue'
    import Chart from '@/Components/Chart.vue'
    import JetInput from '@/Jetstream/Input'
    import JetButton from '@/Jetstream/Button'
    import Pusher from 'pusher-js'

    export default {
        components: {
            AppLayout,
            StatisticsCard,
            Chart,
            JetInput,
            JetButton,
        },

        props: {
            cards: Array,
            monthlyEarnings: Array,
        },
        data() {
            return {
                form: this.$inertia.form({
                    code: '',
                }),
            }
        },
        mounted() {
        },
        methods: {
            chargeBalance() {
                console.log('charge balance request');
               this.form.post(this.route('charge-balance'));
            },
            pusher(){
                this.$toast.success(`Welcome to Smart Delivery`);
            }
        }
    }
</script>
