<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("login") }}</h1>
          <h4>{{ $t("min_pizza") }}</h4>
        </div>
      </div>
    </div>
    <div class="login-content white-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-5 col-md-5 col-sm-12 col-12 align-self-center">
            <img src="images/app-login.png" />
          </div>
          <div class="col-lg-7 col-md-7 col-sm-12 col-12 align-self-center">
            <form class="login-form">
              <div class="mb-3">
                <label for="email" class="form-label">{{
                  $t("form.email")
                }}</label>
                <input
                  type="email"
                  v-model="state.email"
                  class="form-control"
                  id="email"
                />
                <span class="error" v-if="v$.email.$error">
                  {{ v$.email.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-3">
                <div class="relative-div">
                  <label for="password" class="form-label">{{
                    $t("form.password")
                  }}</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    v-model="state.password"
                  />
                  <i
                    class="far view-pass-icon"
                    :class="{
                      'fa-eye-slash': showPassword,
                      'fa-eye': !showPassword,
                    }"
                    @click="toggleShow"
                  ></i>
                </div>
                <span class="error" v-if="v$.password.$error">
                  {{ v$.password.$errors[0].$message }}
                </span>
              </div>
              <div class="row mb-3 ">
                <div class="col-md-6 col-12">
                  <div class="form-check">
                    <input
                      type="checkbox"
                      id="remember"
                      class="form-check-input"
                    />
                    <label class="form-check-label" for="remember">{{
                      $t("form.remember")
                    }}</label>
                  </div>
                </div>
                <div class="col-md-6 col-12 text-right">
                  <router-link to="/ForgetPass" class="forget-pass" href="#">
                    {{ $t("form.forgotPass") }}
                  </router-link>
                </div>
              </div>
              <div class="text-center justify-content-center">
                <button
                  type="submit"
                  class="btn btn-primary blue-btn mb-3"
                  @click.prevent="login()"
                >
                  {{ $t("login") }}
                </button>
                <router-link to="/Signup" class="btn custom-btn">{{
                  $t("register")
                }}</router-link>
                <div v-if="login_checked">
                <p class="text-center small mt-3 mb-3">{{ $t('signwith') }}</p>
                <ul class="nav social-login">
                    <li class="nav-item">
                        <a href="#"><img src="images/facebook.png"></a>
                        <!-- <facebook-login class="button"
                          appId="326022817735322"
                          @login="getUserData"
                          @logout="onLogout"
                          @get-initial-status="getUserData">
                        </facebook-login> -->
                    </li>
                    <li class="nav-item">
                        <a href="#"><img src="images/apple.png"></a>
                    </li>
                    <li class="nav-item">
                        <a @click.prevent="googleLogin"><img src="images/google.png"></a>
                    </li>
                </ul>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  
</template>

<script>
import { defineComponent } from "vue";
import Header from "@/components/Header.vue"; // @ is an alias to /src
 // @ is an alias to /src
import axios from "axios";
import useVuelidate from "@vuelidate/core";
import { required, minLength, maxLength, email } from "@vuelidate/validators";
import { reactive, computed } from "vue";
// import facebookLogin from 'facebook-login-vuejs';


export default defineComponent({
  data() {
    return {
      showPassword: false,
      login_checked:false,
      isLogin: false,

    };
  },

  setup() {
    const state = reactive({
      email: "",
      password: "",
    });

    const rules = computed(() => {
      return {
        email: { required, email },
        password: {
          required,
          minLength: minLength(6),
          maxLength: maxLength(20),
        },
      };
    });

    const v$ = useVuelidate(rules, state);

    return {
      state,
      v$,
    };
  },

  components: {
    Header, 
    // facebookLogin
  },
  mounted() {
    this.socialLogin();
  },
  methods: {
    //   Functions to show/hide password
    toggleShow() {
      this.showPassword = !this.showPassword;
      var p = document.getElementById("password");
      if (this.showPassword) {
        p.setAttribute("type", "text");
      } else {
        p.setAttribute("type", "password");
      }
    },

    // login fn
    login() {
      const result = this.v$.$validate();
      const data = {
        email: this.state.email,
        password: this.state.password,
        default_lang: localStorage.getItem("appLang") || "en",
        role: "customer",
      };
      if (!this.v$.$error) {
        axios
          .post("login", data)
          .then((response) => {
            localStorage.setItem("customerToken", response.data.access_token);
            // console.log("user", response.data);
            window.location.href = "/";
          })
          .catch((errors) => {
            if (errors.response.data.errors) {
              const Err = errors.response.data.errors;
              for (const el in Err) {
                Err[el].map((item) => {
                  this.$toast.error(item, {
                    position: "top-right",
                  });
                });
              }
            } else {
              const errMsg = errors.response.data.message;
              this.$toast.error(errMsg, {
                position: "top-right",
              });
            }
          });
      }
    },

    // social login on/off
    socialLogin() {
      axios
        .get("general-settings")
        .then((response) => {
          this.login_checked = response.data.settings.filter(function (elem) {
            if (elem.key === "login_by_social_media") return elem.value;
          });

          this.login_checked =
            this.login_checked[0].value === "true" ||
            this.login_checked[0].value === "1";
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    // login with google
    async googleLogin() {
      const googleUser = await this.$gAuth.signIn();
      console.log("googleUSer", googleUser);
      console.log("id", googleUser.getid());
      console.log("profile", googleUser.getBasicProfile());
      console.log("authResponse", googleUser.getAuthResponse());
      this.isLogin = this.$gAuth.isAuthorized

    }
  },
});
</script>
