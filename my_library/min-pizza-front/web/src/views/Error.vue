<template>
    <Header />
    <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                        <h1>{{ $t('error') }}</h1>
                        <h4>{{ $t("min_pizza") }}</h4>
                    </div>
                </div>
            </div>
            <div class="login-content white-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12 align-self-center">
                           <img src="images/404-error.png">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <div class="error-div">
                                <h1 class="green-h">404 {{ $t("error") }}</h1>
                                <p>{{ $t("pageNotFound") }}</p>
                                <router-link to="/" class="btn blue-btn">{{ $t("homePage") }}</router-link>
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

export default defineComponent({
    components: {
        Header
    },
});
</script>