<template>
    <Header />
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                    <h1>{{ $t("about") }} Mini Pizza</h1>
                    <h4>Mini Pizza {{ $t("online") }}</h4>
                </div>
            </div>
        </div>
        <div class="about-content white-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="footer-title sec-title">
                            <h5>{{ $t("about") }} Mini Pizza</h5>
                            <hr/>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div v-if="loading" class="loader white-bg">
                            <Circle></Circle>
                        </div>
                        <div class="about-div" v-html="about" v-else>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</template>

<script>
    import { defineComponent } from 'vue';
    import axios from 'axios';
    import Header from '@/components/Header.vue'; // @ is an alias to /src
     // @ is an alias to /src
    import {Circle} from 'vue-loading-spinner'

    export default defineComponent({
        components: {
          Header, Circle
        },
        data() {
            return {
                headers: {
                    "Accept-Language": localStorage.getItem("appLang"),
                },
                about: "",
                loading: false,
            }
        },
        mounted() {
            this.getAboutUs();
        },
        methods: {
            getAboutUs() {
                this.loading = true;
                axios.get('about-us/', {headers: this.headers})
                    .then((response) => {
                        this.about = response.data.about_us.translated_value;
                        console.log(response.data);
                        this.loading = false;
                    }).catch((errors) => {
                    console.log(errors.data);
                    this.loading = false;
                });
            },
        },
    });
</script>