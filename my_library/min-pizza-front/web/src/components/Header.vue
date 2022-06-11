<template>
  <header class="header">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-4 col-3">
          <router-link to="/">
            <img src="/images/logo.png" class="logo"
          /></router-link>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-8 col-9 align-self-center">
          <ul class="nav justify-content-end align-self-center">
            <!-- language -->
            <li
              class="nav-item align-self-center d-flex justify-content-center align-items-center"
            >
              <img src="/images/icon-lang.png" />

              <select
                class="select_lang form-control d-inline-block "
                @change="changeLang($event)"
              >
                <option selected disabled>
                  {{ $t("language") }}
                </option>
                <option value="en">
                  English
                </option>
                <option value="sv">
                  Swedish
                </option>
              </select>
            </li>
            <!-- end language -->

            <li class="align-self-center"><span class="line"></span></li>
            <div class="d-flex" v-if="loggedIn">
              <!-- start user -->
              <li class="nav-item">
                <a
                  class="nav-link dropdown-toggle"
                  type="button"
                  id="userBtn"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img src="/images/icon-user.png" />
                </a>
                <ul class="dropdown-menu" aria-labelledby="userBtn">
                  <li>
                    <router-link to="/Profile" class="dropdown-item" href="#">{{
                      $t("profile")
                    }}</router-link>
                  </li>
                  <li>
                    <a class="dropdown-item" @click="logoutUser()">{{
                      $t("logout")
                    }}</a>
                  </li>
                </ul>
              </li>
              <!-- end user -->

              <!-- cart -->
              <li class="nav-item">
                <router-link to="/Cart" class="nav-link" href="#">
                  <img src="/images/icon-shopcard.png" />
                  <span class="cart-item-no">{{ cartCount }}</span>
                </router-link>
              </li>
            </div>
            <!-- end cart -->

            <!-- if not login -->
            <li class="nav-item" v-else>
              <router-link to="/Login" class="nav-link">
                {{ $t("login") }}
              </router-link>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import { defineComponent } from "vue";
import axios from "axios";

export default defineComponent({
  name: "Header",
  data() {
    return {
      loggedIn: false,
      timer: null,
      cartCount: 0,
    };
  },
  mounted() {
    this.auth();
    this.checkCart();
  },
  created() {
    let curVal = JSON.parse(localStorage.getItem("myOrder"));
    this.timer = setInterval(() => {
      const newVal = JSON.parse(localStorage.getItem("myOrder"));
      if (localStorage.getItem("myOrder")) {
        if (newVal !== curVal) {
          curVal = newVal;
          this.cartCount = newVal.length;
        }
      } else {
        this.cartCount = 0;
      }
    }, 500);
  },

  beforeUnmount() {
    clearInterval(this.timer);
  },

  methods: {
    checkCart() {
      if (localStorage.getItem("myOrder")) {
        this.cartCount = JSON.parse(localStorage.getItem("myOrder")).length;
      }
    },

    auth() {
      if (localStorage.getItem("customerToken")) {
        this.loggedIn = true;
      } else {
        this.loggedIn = false;
      }
    },
    changeLang(event) {
      localStorage.setItem("appLang", event.target.value);
      window.location.reload();
    },
    // logout user
    logoutUser() {
      axios
        .post("logout")
        .then((response) => {
          this.$router.push("/Login");
          // delete all stored data
          localStorage.clear();
        })
        .catch((errors) => {
          this.$router.push("/Login");
          console.log(errors);
        });
      // localStorage.removeItem("customerToken");
    },
  },
});
</script>
<style scoped>
.select_lang {
  border: none !important;
  height: 100% !important;
  line-height: 2;
}
.select_lang:focus,
.select_lang:active,
.select_lang:focus-visible {
  border: none !important;
}
</style>
