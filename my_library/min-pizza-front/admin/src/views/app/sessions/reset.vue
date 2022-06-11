<template>
  <div
    class="auth-layout-wrap"
    :style="{ backgroundImage: 'url(' + bgImage + ')' }"
  >
    <div class="auth-content">
      <div class="card o-hidden">
        <div class="row justify-content-center">
          <div class="col-md-9">
            <div class="p-4">
              <div class="auth-logo text-center mb-30">
                <img :src="logo" alt="" />
              </div>
              <h1 class="mb-3 text-18">Reset Password</h1>
              <form @submit.prevent="resetPassword">
                <div class="form-group">
                  <b-form-group label="Email Address" class="text-12">
                    <b-form-input
                      class="form-control"
                      type="text"
                      v-model="user.email"
                      email
                      required
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group label="Confirmation Code" class="text-12">
                    <b-form-input
                      class="form-control"
                      type="text"
                      v-model="user.code"
                      required
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group label="Password" class="text-12">
                    <b-form-input
                      class="form-control"
                      type="password"
                      v-model="user.password"
                      required
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group label="Confirm Password" class="text-12">
                    <b-form-input
                      class="form-control"
                      type="password"
                      v-model="user.password_confirmation"
                      required
                    ></b-form-input>
                  </b-form-group>
                </div>
                <button
                  class="btn btn-primary btn-block btn-rounded mt-3 text-center"
                >
                  Reset Password
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Reset Password",
  },
  data() {
    return {
      bgImage: require("@/assets/images/bg.png"),
      logo: require("@/assets/images/logo.png"),
      formImage: require("@/assets/images/photo-long-3.jpg"),
      user: {
        email: "",
        code: "",
        password: "",
        password_confirmation: "",
      },
    };
  },
  methods: {
    async resetPassword() {
      const response = await axios
        .post("password/reset", {
          email: this.user.email,
          code: this.user.code,
          password: this.user.password,
          password_confirmation: this.user.password_confirmation,
        })
        .then((response) => {
          console.log(response.data);
          this.$router.push("signIn");
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
  },
};
</script>

<style></style>
