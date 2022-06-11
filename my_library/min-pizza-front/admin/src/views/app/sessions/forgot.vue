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
              <h1 class="mb-3 text-18">Forgot Password</h1>
              <form @submit.prevent="forgotPassword">
                <div class="form-group">
                  <b-form-group label="Email Address" class="text-12">
                    <b-form-input
                      class="form-control"
                      type="email"
                      v-model="user.email"
                      email
                      required
                    ></b-form-input>
                  </b-form-group>
                </div>
                <button
                  class="btn btn-primary btn-block btn-rounded mt-3 text-center"
                >
                  Send Email
                </button>
              </form>
              <div class="mt-3 text-center">
                <router-link to="signIn" tag="a" class="text-muted">
                  <u>Sign In</u>
                </router-link>
              </div>
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
    title: "Forgot Password",
  },
  data() {
    return {
      bgImage: require("@/assets/images/bg.png"),
      logo: require("@/assets/images/logo.png"),
      formImage: require("@/assets/images/photo-long-3.jpg"),
      message:"",
      user: {
        email: "",
      },
    };
  },
  methods: {
    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
    async forgotPassword() {
      const response = await axios
        .post("password/forgot", {
          email: this.user.email,
        })
        .then((response) => {
          console.log(response.data);
          //this.$router.push("reset");
        })
        .catch((errors) => {
          this.message = "invalid email address";
          this.user.email = "";
          console.log(errors.response.data);
          this.makeToast("warning", this.message);
        });
    },
  },
};
</script>
