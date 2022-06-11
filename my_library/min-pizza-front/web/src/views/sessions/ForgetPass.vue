<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("form.forgotPass") }}</h1>
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
            <form class="login-form" @submit.prevent="forgotPassword">
              <div class="mb-5">
                <label class="form-label">{{ $t("form.email") }}</label>
                <input
                  type="email"
                  v-model="state.email"
                  class="form-control"
                  required
                />
                <span class="error" v-if="v$.email.$error">
                  {{ v$.email.$errors[0].$message }}
                </span>
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
import { required, email } from "@vuelidate/validators";

export default defineComponent({
  components: {
Header,
  },
  setup() {
    const state = reactive({
      email: "",
    });
    const rules = computed(() => {
      return {
        email: { required, email },
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
      headers: {
        "Accept-Language": "en",
      },
    };
  },
  methods: {
    async forgotPassword() {
      const response = await axios
        .post(
          "password/forgot",
          {
            email: this.state.email,
            role: "customer",
          },
          { headers: this.headers }
        )
        .then((response) => {
          const message = response.data.message;
          this.$toast.success(message, {
            position: "top-right",
          });
          this.$router.push({ name: "resetPass" });
        })
        .catch((errors) => {
          const errMsg = errors.response.data.message;
          this.state.email = "";
          this.$toast.error(errMsg, {
            position: "top-right",
          });
        });
    },
  },
});
</script>
