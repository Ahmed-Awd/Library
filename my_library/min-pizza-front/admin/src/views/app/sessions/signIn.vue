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
                <img :src="logo" />
              </div>
              <h1 class="mb-3 text-18">Sign In</h1>
              <b-form @submit.prevent="login">
                <b-form-group label="Email Address" class="text-12">
                  <b-form-input
                    class="form-control"
                    type="text"
                    v-model="user.email"
                    email
                    required
                  ></b-form-input>
                </b-form-group>

                <b-form-group label="Password" class="text-12">
                  <b-form-input
                    class="form-control"
                    type="password"
                    v-model="user.password"
                  ></b-form-input>
                </b-form-group>

                <b-form-group label="Default Language" class="text-12">
                  <b-form-select
                    class="form-control"
                    v-model="user.default_lang"
                    :options="user.languages"
                    id="inline-form-custom-select-pref1"
                  >
                  </b-form-select>
                </b-form-group>

                <b-form-group label="Login as" class="text-12">
                  <b-form-select
                    class="form-control"
                    v-model="user.role"
                    :options="user.roles"
                    id="inline-form-custom-select-pref1"
                  >
                  </b-form-select>
                </b-form-group>
                <div>
                  <p class="text-danger">{{ this.message }}</p>
                </div>
                <!-- <b-button block to="/" variant="primary btn-rounded mt-2"
                  >Sign In</b-button
                > -->
                <b-button
                  type="submit"
                  tag="button"
                  class="btn-rounded btn-block mt-4"
                  variant="primary mt-2"
                  :disabled="loading"
                >
                  Sign In
                </b-button>
                <div v-once class="typo__p" v-if="loading">
                  <div class="spinner sm spinner-primary mt-3"></div>
                </div>
              </b-form>

              <div class="mt-3 text-center">
<!--                <router-link to="forgot" tag="a" class="text-muted">-->
<!--                  <u>Forgot Password?</u>-->
<!--                </router-link>-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import axios from "axios";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "SignIn",
  },
  data() {
    return {
      bgImage: require("@/assets/images/bg.png"),
      logo: require("@/assets/images/logo.png"),
      message: "",
      errors: {},
      signInImage: require("@/assets/images/photo-long-3.jpg"),
      user: {
        email: "",
        password: "",
        default_lang: "en",
        role: "super_admin",
        languages: [
          { value: "option", text: " select an option" },
          { value: "en", text: "english" },
          { value: "sv", text: "swedish" },
        ],
        roles: [
          { value: "admin", text: "Admin" },
          { value: "super_admin", text: "Super admin" },
          { value: "owner", text: "Restaurant owner" },
        ],
      },
    };
  },
  computed: {
    validation() {
      return this.user.userId.length > 4 && this.user.userId.length < 13;
    },
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  methods: {
    ...mapActions(["login"]),
    login() {
      axios
        .post("login", this.user)
        .then((response) => {
          console.log(response.data.role);
          localStorage.removeItem("token");
          localStorage.removeItem("user");
          localStorage.setItem("token", response.data.access_token);
          localStorage.setItem("user", response.data.user);
          localStorage.setItem("userID", response.data.user.id);
          localStorage.setItem("role", response.data.role);
          if (response.data.user.permissions) {
            localStorage.setItem(
              "adminPermissions",
              response.data.user.permissions.map((item) => {
                return item.name;
              })
            );
          }

          if (response.data.role == "owner") {
            localStorage.setItem("logedAdmin", 0);
            localStorage.setItem("restaurantID", response.data.restaurant.id);
            localStorage.setItem(
              "restaurantName",
              response.data.restaurant.name
            );
            window.location.href = `/app/dashboards/restaurant/menu/categories/${response.data.restaurant.id}`;
          } else {
            localStorage.setItem("logedAdmin", 1);
            console.log(response.data.user);
            this.$router.push("/");
          }
        })
        .catch((errors) => {
          console.log(errors);
          this.message = errors.response.data.message;
          this.makeToast("warning", this.message);
        });
    },
    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
  },
  watch: {
    loggedInUser(val) {
      if (val && val.uid && val.uid.length > 0) {
        this.makeToast("success", "Successfully Logged In");

        setTimeout(() => {
          //this.$router.push({ path: '/' })
        }, 500);
      }
    },
    error(val) {
      if (val != null) {
        this.makeToast("warning", val.message);
      }
    },
  },
};
</script>

<style>
.spinner.sm {
  height: 2em;
  width: 2em;
}
</style>
