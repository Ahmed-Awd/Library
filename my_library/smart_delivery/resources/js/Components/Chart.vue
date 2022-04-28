<template>
    <div class="pt-10">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script>
    import Chart from 'chart.js/auto';
    export default {
        props: {
            data: Array
        },

        data() {
            return {
                chartjsObject: null,
                earning:this.__("Earnings Per Month")
            }
        },
        methods(){
            function __(key) {
                let transaltion = window._translations[key] || key;
                return transaltion;
            }
        },
        mounted() {
            this.chartjsObject = new Chart(this.$refs.chartCanvas, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: this.earning,
                        data: this.data,
                        backgroundColor: ['rgba(124, 58, 237)'],
                    }]
                }
            })
        }
    }
</script>
