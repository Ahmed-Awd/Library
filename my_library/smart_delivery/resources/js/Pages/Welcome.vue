<template>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/fontawesome-all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <link href="css/style.css" rel="stylesheet">

    <!-- Start Header -->
    <header class="header">
        <div class="container">
            <div class="top-heder">
                <nav class="nav social-icon-nav justify-content-start">
                    <span>{{ __("Join US")}}</span>
                    <a target="_blank" class="nav-link" :href="social.instagram.value"><i class="fab fa-instagram"></i></a>
                    <a target="_blank" class="nav-link" :href="social.linkedIn ? social.linkedIn.value : ''"><i class="fab fa-linkedin-in"></i></a>
                    <a target="_blank" class="nav-link" :href="social.facebook.value"><i class="fab fa-facebook-f"></i></a>
                    <a target="_blank" class="nav-link" :href="social.whats_app.value"><i class="fab fa-whatsapp"></i></a>
                    <a class="sperator"><div></div></a>
                    <a target="_blank" class="mail" > <i class="far fa-envelope"></i> <span>{{social.email ? social.email.value : ' '}}</span></a>
                </nav>
                <select
                    class="select_lang-wel form-control "
                    @change="changeLang($event)"
                >
                    <option selected disabled>
                    {{ __("language")}}
                    </option>
                    <option value="en">{{ __("English") }}</option>
                    <option value="ar">{{ __("Arabic") }}</option>
                    <option value="tr">{{ __("Turkish") }}</option>
                </select>
            </div>
            <div class="main-header">
                <div class="row">
                    <div class="col-lg-6 col-md-4 col-sm-4 col-4">
                        <a href="/">
                            <img src="images/logo-SmartDelivery.png" class="logo">
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-8 col-sm-8 col-8 align-self-center">
                        <nav class="nav social-icon-nav justify-content-end">
                            <inertia-link v-if="$page.props.user" href="/dashboard" class="login-btn">
                                {{ __("Dashboard") }}
                            </inertia-link>
                            <inertia-link v-else :href="route('login')" class="login-btn">
                                {{ __("Login") }}
                            </inertia-link>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header -->
    <!-- Start Slider -->
    <section class="slider-sec">
        <div class="slider-carousel owl-carousel owl-theme">
            <div class="slider-item">
                <img src="images/slider1.png" class="big-img">
                <img src="images/mobile1.png" class="small-img">
                <div class="slider-desc align-self-center">
                    <div class="container">
                        <div class="row">
                            <div
                                class="offset-lg-5 col-lg-7 col-md-12  col-sm-12 col-12 wow animate__animated animate__fadeInUpBig">
                                <img src="images/type-smartdelivery.png" class="slider-logo big-img">
                                <img src="images/type-smartdelivery-w.png" class="slider-logo small-img">
                                <p class="mb-5">{{__("Slider Text")}}</p>
                                <h4>{{ __("For Delivery") }}</h4>
                                <p class="small">{{ __("Dawnload") }}</p>
                                <ul class="nav store-nav">
                                    <li class="nav-item">
                                        <a target="_blank" class="nav-link" :href="apps.app_store.value">
                                            <img src="images/app-store.png">
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a target="_blank" class="nav-link" :href="apps.google_store.value">
                                            <img src="images/google-store.png">
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a target="_blank" class="nav-link" :href="apps.huawei_app_gallery.value">
                                            <img src="images/app-gallaery.png">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider-item">
                <img src="images/slider2.png" class="big-img">
                <img src="images/mobile2.png" class="small-img">
                <div class="slider-desc">
                    <div class="container">
                        <div class="row">
                            <div
                                class="col-lg-5 col-md-12 col-sm-12 col-12 wow animate__animated animate__fadeInUpBig offset-lg-ar-5">
                                <img src="images/type-smartdelivery.png" class="slider-logo big-img">
                                <img src="images/type-smartdelivery-w.png" class="slider-logo small-img">
                                <p class="mb-5">{{__("Slider Text")}}</p>
                                <h4>{{ __("For Customer") }}</h4>
                                <p class="small">{{ __("Dawnload") }}</p>
                                <ul class="nav store-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" target="_blank" :href="apps.app_store.value">
                                            <img src="images/app-store.png">
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" target="_blank" :href="apps.google_store.value">
                                            <img src="images/google-store.png">
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" target="_blank" :href="apps.huawei_app_gallery.value">
                                            <img src="images/app-gallaery.png">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider-item">
                <img src="images/slider3.png" class="big-img">
                <img src="images/mobile3.png" class="small-img">
                <div class="slider-desc">
                    <div class="container">
                        <div class="row">
                            <div
                                class="offset-lg-6 col-lg-6 col-md-12 col-sm-12 col-12 wow animate__animated animate__fadeInUpBig">
                                <img src="images/type-smartdelivery.png" class="slider-logo big-img">
                                <img src="images/type-smartdelivery-w.png" class="slider-logo small-img">
                                <p class="mb-5">{{__("Slider Text")}}</p>
                                <h4>{{ __("For Seller") }}</h4>
                                <p class="small">{{ __("Dawnload") }}</p>
                                <ul class="nav store-nav">
                                    <li class="nav-item">
                                        <a class="nav-link" target="_blank" :href="apps.app_store.value">
                                            <img src="images/app-store.png">
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" target="_blank" :href="apps.google_store.value">
                                            <img src="images/google-store.png">
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" target="_blank" :href="apps.huawei_app_gallery.value">
                                            <img src="images/app-gallaery.png">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Slider -->

    <!-- Start About Section -->
    <section class="about-sec pad-50">
        <div class="container">
            <div class="row mb-50">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="image-container">
                        <img src="images/pic-about.png" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="about-black-div">
                        <div>
                            <img src="images/logo-smart.png" alt="" class="smart-logo mb-3">
                            <p>
                                {{__("About Text")}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-12 col-12 align-self-center">
                    <div class="image-container">
                        <h2 class="mb-3">{{ __("Best Choise") }}</h2>
                        <p>{{__("About Text2")}}</p>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-12 col-12">
                    <img src="images/pic-smartdelivery.png" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- End About Section -->

    <!-- Start Service Section -->
    <section class="service-sec gray-bg pad-50">
        <div class="container">
            <div class="sec-title">
                <h3>{{__("What Sets")}} <span class="light-font">{{__("us apart")}}</span></h3>
                <hr/>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="service-block">
                        <div class="service-image">
                            <img src="images/icon-map.png" alt="">
                        </div>
                        <div class="service-desc">
                            <h4>{{__("advantage1")}}</h4>
                            <p>
                                {{__("Advantage1 Desc")}}
                            </p>
                            <div class="service-ico">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="service-block">
                        <div class="service-image">
                            <img src="images/Group 34.png" alt="">
                        </div>
                        <div class="service-desc">
                            <h4>{{__("advantage2")}}</h4>
                            <p>{{__("Advantage1 Desc")}}</p>
                            <div class="service-ico">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="service-block">
                        <div class="service-image">
                            <img src="images/icon-lang.png" alt="">
                        </div>
                        <div class="service-desc">
                            <h4>{{__("advantage3")}}</h4>
                            <p>
                                {{__("Advantage3 Desc")}}
                            </p>
                            <div class="service-ico">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                    <div class="service-block">
                        <div class="service-image">
                            <img src="images/icon-shield.png" alt="">
                        </div>
                        <div class="service-desc">
                            <h4>{{__("advantage4")}}</h4>
                            <p>
                                {{__("Advantage4 Desc")}}
                            </p>
                            <div class="service-ico">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Service Section -->

    <!-- Start Accourdion Section -->
    <section class="accourdion-sec pad-50">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-5 offset-lg-1 col-md-5 offset-md-1 col-sm-12 col-12">
                    <h3>{{__("Service We Provide")}} </h3>
                    <h3 class="mb-3">Smart Delivery</h3>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                {{__("emergency team")}}
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                   {{__("emergency text")}}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                {{__("Say goodbye")}}
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    {{__("Say goodbye text")}}
                                </div>
                            </div>
                        </div>
                        <!-- <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Product Delivery Services
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <img src="images/pic-delivery.png" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- Start Accourdion Section -->
    
    <!-- Start Prallex Section -->
    <section class="prallex-sec">

    </section>
    <!-- End Prallex Section -->

    <!-- Subscribe -->
    <section class="subscribe-sec pad-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <h2>{{__("Subscribe title")}}</h2>
                    <p>
                        {{__("Subscribe text")}}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Subscribe -->

    <!-- Start Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-12 col-12 text-start align-self-center">
                    <p class="copyright">@ 2021 Samrt delivery All rights reserved
                    </p>
                </div>
                <div class="col-lg-6 col-md-4 col-sm-12 col-12 text-end text-left">
                    <a href="#" class="smart-logo"><img src="images/smartlife-footer.png"></a>
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
        social: Object,
        apps: Object
    },
     methods: {
        changeLang(event) {
            this.$inertia.get(this.route('set-lang', event.target.value)).then((result)=>{
                window.location.reload();
            });
        },
    }
}
</script>
