<template>
    <Header />
    <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                        <h1>{{ $t("fqa") }}</h1>
                        <h4>Mini Pizza {{ $t("online") }}</h4>
                    </div>
                </div>
            </div>
            <div class="about-content white-bg">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="footer-title sec-title">
                                <h5>{{ $t("fqa") }}</h5>
                                <hr/>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-11 col-sm-12 col-12">
                            <div v-if="loading && pageLoad == 0" class="loader white-bg">
                                <Circle></Circle>
                            </div>
                            <div v-else>
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item" v-for="fqa in fqas" :key="fqa.id">
                                    <h2 class="accordion-header" :id="`heading${fqa.id}`">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="`#collapse${fqa.id}`" aria-expanded="true" :aria-controls="`collapse${fqa.id}`">
                                        {{ fqa.question }}
                                        </button>
                                    </h2>
                                    <div :id="`collapse${fqa.id}`" class="accordion-collapse collapse" :aria-labelledby="`heading${fqa.id}`" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            {{ fqa.answer }}
                                        </div>
                                    </div>
                                </div>
                                <div :class="fqas.length == total ? 'text-center hidden' : 'text-center'" > 
                                    <button class="btn btn-primary blue-btn mt-3" 
                                        @click="onPageChange"
                                    >{{ $t("loadMore") }}</button>
                                </div>
                            </div>
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
        Header,Circle
    },
    data() {
        return {
            headers: {
                "Accept-Language": localStorage.getItem("appLang"),
            },
            disable: false,
            fqas: [],
            pageSize: 15,
            currentPage: 1,
            serverParams: {
                page: 1,
                perPage: 15,
            },
            total: 0,
            loading: false,
            pageLoad: 0,
        }
    },
    mounted() {
        this.getFQA();
    },
    methods: {
        onPageChange(params) {
            this.serverParams = Object.assign({}, this.serverParams, this.serverParams.page++ );
            this.getFQA();
        },
        getFQA() {
            this.loading = true;
            axios.get('FQA', { 
                headers: this.headers,
                params: this.serverParams,
            }).then((response) => {
                    response.data.questions.data && (this.fqas = [...this.fqas, ...response.data.questions.data])
                    console.log(this.fqas.length);
                    this.total = response.data.questions.total;
                    this.loading = false;
                    this.pageLoad = 1;
                }).catch((errors) => {
                console.log(errors.data);
                this.loading = false;
            });
        },
    },
    watch: {
    page (value) {
      this.disable = value > 10
    }
  }
});
</script>