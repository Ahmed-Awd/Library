<template>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/fontawe    12SX some-all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/css/owl.theme.default.css">
    <link rel="stylesheet" href="/css/animate.min.css">
    <link rel="stylesheet" href="/css/style.css" v-if="locale != 'ar' ">
    <link rel="stylesheet" href="/css/style-ar.css" v-else>

    <!-- Start Header -->
    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-4 col-sm-4 col-4">
                    <a href="/">
                        <img src="/images/logo-SmartDelivery.png" class="logo">
                    </a>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-8 col-8 align-self-center">
                    <nav class="nav social-icon-nav justify-content-end">
                        <a class="nav-link" :href="social.facebook.value" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a class="nav-link" :href="social.twitter.value" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a class="nav-link" :href="social.instagram.value" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a class="nav-link" :href="social.youtube.value" target="_blank"><i class="fab fa-youtube"></i></a>
                        <inertia-link v-if="$page.props.user" href="/dashboard" class="login-btn">
                            Dashboard
                        </inertia-link>
                        <inertia-link  v-else :href="route('login')" class="login-btn">
                            Log in
                        </inertia-link>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header -->

    <!-- Start Slider -->
    <section class="slider-sec pad-50">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-10 col-sm-12 col-12">
                    <h3 class="title">{{post.title}}</h3>
                    <p v-html="post.content">
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End Slider -->

    <!-- Start About Section -->
    <section class="about-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="image-container">
                        <img src="images/pic-about.png" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="about-black-div">
                        <img src="images/logo-smart.png" alt="" class="smart-logo">
                        <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. 
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End About Section -->

    <!-- Start Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-12 col-12 text-start align-self-center">
                    <p class="copyright">@ 2021 Samrt delivery <a href="#"><img src="/images/smartlife-footer.png"></a></p>
                </div>
                <div class="col-lg-6 col-md-4 col-sm-12 col-12 text-end">
                    <ul class="nav footer-menu justify-content-end">
                        <li class="nav-item">
                            <a class="nav-link" href="/page/1">About</a>
                        </li>
                        <li class="sperator">|</li>
                        <li class="nav-item">
                            <a class="nav-link" href="/page/2">Privacy & Policy</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->
</template>



<script>
    export default {
        props: {
            canLogin: Boolean,
            canRegister: Boolean,
            laravelVersion: String,
            phpVersion: String,
            social:Object,
            apps:Object,
            post:Object
        },
        data(){
            return{
                locale : this.$page.props.LocaleLang
            }
        },
        mounted() {
            console.log(this.locale);
        }
    }
</script>
