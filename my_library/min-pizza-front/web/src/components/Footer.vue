<template>
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-12 align-self-center">
          <ul class="nav social-nav align-self-center">
            <li class="nav-item">
              <a class="nav-link" :href="facebook">
                <i class="fab fa-facebook-f"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" :href="twitter">
                <i class="fab fa-twitter"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" :href="instagram">
                <i class="fab fa-instagram"></i>
              </a>
            </li>
            <li class="nav-item align-self-center">
              <span>@2021 minipizza.online</span>
            </li>
          </ul>
          <ul class="nav small-nav">
            <li class="nav-item">
              <router-link to="/about" active-class="active" class="nav-link">
                {{ $t("about") }}
              </router-link>
            </li>
            <li><span class="line"></span></li>
            <li class="nav-item">
              <router-link to="/terms" active-class="active" class="nav-link">
                {{ $t("termsOne") }}
              </router-link>
            </li>
            <li><span class="line"></span></li>
            <li class="nav-item">
              <router-link to="/fqa" active-class="active" class="nav-link">
                {{ $t("fqa") }}
              </router-link>
            </li>
            <li><span class="line"></span></li>
            <li class="nav-item">
              <router-link to="/" active-class="active" class="nav-link">
                Mini Pizza
              </router-link>
            </li>
            <li><span class="line"></span></li>
            <li class="nav-item">
              <router-link to="/contact" active-class="active" class="nav-link">
                {{ $t("contact") }}
              </router-link>
            </li>
          </ul>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
          <div class="footer-title">
            <h5>Payment Method</h5>
            <hr />
          </div>
          <ul class="nav card-nav">
            <li class="nav-item" v-for="payment in payment_methods" :key="payment.id">
              <img :src="getImgUrl(payment.photo)" />
            </li>
          </ul>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12 align-self-end">
          <ul class="nav store-nav">
            <li class="nav-item">
              <a class="nav-link" href="#">
                <img src="images/app-store.png" />
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <img src="images/play-store.png" />
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import { defineComponent } from "vue";
import axios from "axios";

export default defineComponent({
  name: "Footer",
  inject: ['image_location'],
  props: {
    msg: String,
  },

  data() {
    return {
      facebook: "",
      twitter: "",
      instagram: "",
      payment_methods:[]
    };
  },
  mounted() {
    this.webInfo();
  },

  methods: {
    webInfo() {
      axios
        .get("contact-info")
        .then((response) => {
          const info = response.data.data;
          this.facebook = info[2].value;
          this.instagram = info[3].value;
          this.twitter = info[4].value;
        })
        .catch((errors) => {
          console.log(errors);
        });
      axios
          .get("payment-method")
          .then((response) => {
            this.payment_methods = response.data.methods;
            console.log(this.payment_methods);
          })
          .catch((errors) => {
            console.log(errors);
          });
    },
    getImgUrl(img){
      return  this.image_location+img;
    }
  },
});
</script>
