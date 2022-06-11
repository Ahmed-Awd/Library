<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("change_password") }}</h1>
          <h4>{{ $t("min_pizza") }}</h4>
        </div>
      </div>
    </div>
    <div class="about-content white-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-12 col-12">
            <Pannel />
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-12 align-self-center">
            <form class="login-form" @submit.prevent="changePassword">
              <div class="mb-3 relative-div">
                <label class="form-label">{{ $t("old_password") }}</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  v-model="state.old_password"
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
                <div>
                  <span class="error" v-if="v$.old_password.$error">
                    {{ v$.old_password.$errors[0].$message }}
                  </span>
                </div>
              </div>
              <div class="mb-3 relative-div">
                <label for="password_2" class="form-label">{{
                  $t("new_password")
                }}</label>
                <input
                  type="password"
                  class="form-control"
                  id="password_2"
                  v-model="state.password"
                />
                <div>
                  <span class="error" v-if="v$.password.$error">
                    {{ v$.password.$errors[0].$message }}
                  </span>
                </div>
              </div>
              <div class="mb-3 relative-div">
                <label for="password_3" class="form-label">{{
                  $t("form.confirmPass")
                }}</label>
                <input
                  type="password"
                  class="form-control"
                  id="password_3"
                  v-model="state.password_confirmation"
                />
                <div>
                  <span class="error" v-if="v$.password_confirmation.$error">
                    {{ v$.password_confirmation.$errors[0].$message }}
                  </span>
                </div>
              </div>
              <div class="text-center justify-content-center">
                <button type="submit" class="btn btn-primary blue-btn">
                  {{ $t("save") }}
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
import Pannel from "@/components/Pannel.vue"; // @ is an alias to /src
import useVuelidate from "@vuelidate/core";
import {
  required,
  email,
  minLength,
  maxLength,
  sameAs,
} from "@vuelidate/validators";
import axios from "axios";

export default defineComponent({
  components: {
Header,
    Pannel,
  },
  setup() {
    const state = reactive({
      old_password: "",
      password: "",
      password_confirmation: "",
    });

    const rules = computed(() => {
      return {
        old_password: {
          required,
          minLength: minLength(6),
          maxLength: maxLength(20),
        },
        password: {
          required,
          minLength: minLength(6),
          maxLength: maxLength(20),
        },
        password_confirmation: {
          sameAs: sameAs(state.password),
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
      var p1 = document.getElementById("password");
      var p2 = document.getElementById("password_2");
      var p3 = document.getElementById("password_3");
      if (this.showPassword) {
        p1.setAttribute("type", "text");
        p2.setAttribute("type", "text");
        p3.setAttribute("type", "text");
      } else {
        p1.setAttribute("type", "password");
        p2.setAttribute("type", "password");
        p3.setAttribute("type", "password");
      }
    },
    changePassword() {
      const result = this.v$.$validate();
      if (!this.v$.$error) {
        axios
          .post("password/change", this.state)
          .then((response) => {
            const message = response.data.message;
            this.$toast.success(message, { position: "top-right" });
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
