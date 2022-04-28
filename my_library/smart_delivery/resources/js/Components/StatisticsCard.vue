<template>
    <div class="w-full sm:w-1/4 px-2 pb-3">
        <div class="flex bg-white rounded-md px-5 py-3 shadow-md">
            <div class="rounded-lg bg-purple-600 text-white p-3">
                <icon :name="icon"/>
            </div>
            <div class="flex flex-grow justify-between">
                <div class="pl-5">
                    <div class="text-sm font-bold text-gray-600">
                        {{__(title)}}
                    </div>
                    <div class="text-2xl font-bold text-gray-700">
                        {{number}}
                    </div>
                </div>
                <div class="flex items-center" v-if="difference">
                    <div class="flex items-center text-white pl-1 pr-2 text-center rounded-full text-sm font-bold" :class="colorClass">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5" viewBox="0 0 16 16" v-if="this.differenceNumber > 0">
                            <path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5" viewBox="0 0 16 16" v-else-if="this.differenceNumber < 0">
                            <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5" viewBox="0 0 16 16" v-else>
                            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                        </svg>
                        {{difference.replace('-', '')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            title: String,
            number: String,
            difference: String,
            icon: String,
        },

        computed: {
            colorClass() {
                let color = 'bg-yellow-500';
                if (this.differenceNumber > 0) {
                    color = 'bg-green-500';
                }

                if (this.differenceNumber < 0) {
                    color = 'bg-red-500';
                }
                return color;
            },
            differenceNumber() {
                return parseInt(this.difference);
            }
        }
    }
</script>
