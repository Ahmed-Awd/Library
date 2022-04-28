<template>
    <div>
        <img :src="qrUrl" :alt="content" class="w-full">
        {{ content }}
    </div>
</template>

<script>
    import QRCode from 'qrcode';

    export default {
        props: {
            content: String
        },

        data() {
            return {
                qrUrl: null
            };
        },

        mounted() {
            this.generateQrCode();
        },

        methods: {
            generateQrCode() {
                QRCode.toDataURL(this.content, {width: 300}, (error, url) => {
                    this.qrUrl = url;
                    if (error) console.error(error)
                })
            }
        },

        watch: {
            content() {
                this.generateQrCode();
            }
        }
    }
</script>