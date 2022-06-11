<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("form.resetPass") }}</h1>
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
            <form class="login-form" @submit.prevent="reset">
              <div class="mb-2">
                <label class="form-label">{{ $t("form.email") }}</label>
                <input
                  type="email"
                  class="form-control"
                  v-model="state.email"
                  required
                />
                <span class="error" v-if="v$.email.$error">
                  {{ v$.email.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-2">
                <label class="form-label">{{ $t("form.code") }}</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="state.code"
                  required
                />
                <span class="error" v-if="v$.code.$error">
                  {{ v$.code.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-2">
                <div class="relative-div">
                  <label class="form-label">{{ $t("form.password") }}</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    v-model="state.password"
                    required
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

                <div>
                  <span class="error" v-if="v$.password.$error">
                    {{ v$.password.$errors[0].$message }}
                  </span>
                </div>
              </div>
              <div class="mb-5">
                <label class="form-label">{{ $t("form.confirmPass") }}</label>
                <input
                  type="password"
                  id="password_2"
                  v-model="state.password_confirmation"
                  class="form-control"
                  required
                />
                <div>
                  <span class="error" v-if="v$.password_confirmation.$error">
                    {{ v$.password_confirmation.$errors[0].$message }}
                  </span>
                </div>
              </div>
              <div class="text-center justify-content-center">
                <button type="submit" class="btn btn-primary blue-btn mb-3">
                  {{ $t("send") }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  
</template>

<script>
import { defineComponent, reactive, computed } from "vue";
import Header from "@/components/Header.vue"; // @ is an alias to /src
 // @ is an alias to /src
import axios from "axios";
import useVuelidate from "@vuelidate/core";
import {
  required,
  email,
  minLength,
  maxLength,
  sameAs,
} from "@vuelidate/validators";

export default defineComponent({
  components: {
Header,
  },
  setup() {
    const state = reactive({
      email: "",
      code: "",
      password: "",
      password_confirmation: "",
    });
    const rules = computed(() => {
      return {
        email: { required, email },
        password: {
          required,
          minLength: minLength(6),
          maxLength: maxLength(20),
        },
        password_confirmation: {
          sameAs: sameAs(state.password),
        },
        code: {
          required,
          minLength: minLength(3),
          maxLength: maxLength(15),
        },
      };
    });
    const v$ = useVuelidate(rules, state);
    return {
      state,
      v$,
    };
  },
  data() {
    return {
      message: "",
      email: "",
      showPassword: false,
    };
  },
  methods: {
    toggleShow() {
      this.showPassword = !this.showPassword;
      var p = document.getElementById("password");
      var v = document.getElementById("password_2");
      if (this.showPassword) {
        p.setAttribute("type", "text");
        v.setAttribute("type", "text");
      } else {
        p.setAttribute("type", "password");
        v.setAttribute("type", "password");
      }
    },
    reset() {
      const result = this.v$.$validate();
      if (!this.v$.$error) {
        axios
          .post("password/reset", this.state)
          .then((response) => {
            const message = response.data.message;
            this.$toast.success(message, { position: "top-right" });
            this.$router.push({ name: "Login" });
          })
          .catch((errors) => {
            const errMsg = errors.response.data.message;
            this.$toast.error(errMsg, { position: "top-right" });
          });
      }
    },
  },
});
</script>
