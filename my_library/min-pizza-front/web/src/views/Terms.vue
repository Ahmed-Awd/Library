<template>
    <Header />
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                    <h1>{{ $t('terms') }}</h1>
                    <h4>Mini Pizza  {{ $t("online") }}</h4>
                </div>
            </div>
        </div>
        <div class="about-content white-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="footer-title sec-title">
                            <h5>{{ $t('terms') }}</h5>
                            <hr/>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div v-if="loading" class="loader white-bg">
                            <Circle></Circle>
                        </div>
                        <div class="about-div" v-html="terms" v-else>
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
                terms: "",
                loading: false,
            }
        },
        mounted() {
            this.getTerms();
        },
        methods: {
            getTerms() {
                this.loading = true;
                axios.get('terms-and-conditions/', {headers: this.headers})
                    .then((response) => {
                        this.terms = response.data.terms.translated_value;
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