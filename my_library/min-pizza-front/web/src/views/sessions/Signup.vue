<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("register") }}</h1>
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
            <form class="signUp-form">
              <div class="mb-2">
                <label class="form-label" for="name">{{
                  $t("form.name")
                }}</label>

                <input
                  type="text"
                  class="form-control"
                  id="name"
                  v-model="state.name"
                />

                <span class="error" v-if="v$.name.$error">
                  {{ v$.name.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-2">
                <label for="email" class="form-label">{{
                  $t("form.email")
                }}</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  v-model="state.email"
                />
                <span class="error" v-if="v$.email.$error">
                  {{ v$.email.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-2">
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
                <div>
                  <span class="error" v-if="v$.password.$error">
                    {{ v$.password.$errors[0].$message }}
                  </span>
                </div>
              </div>
              <div class="mb-2">
                <div class="relative-div">
                  <label for="confirmPass" class="form-label">{{
                    $t("form.confirmPass")
                  }}</label>
                  <input
                    type="password"
                    class="form-control"
                    id="confirmPass"
                    v-model="state.confirmPass"
                  />
                  <i
                    class="far view-pass-icon"
                    :class="{
                      'fa-eye-slash': showPasswordC,
                      'fa-eye': !showPasswordC,
                    }"
                    @click="toggleShowTwo"
                  ></i>
                </div>
                <div>
                  <span class="error" v-if="v$.confirmPass.$error">
                    {{ v$.confirmPass.$errors[0].$message }}
                  </span>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-6">
                  <div>
                    <label class="form-label" for="country">{{
                      $t("form.country")
                    }}</label>
                    <select
                      class="form-select"
                      id="country"
                      v-model="selectedcountry"
                      @change="getCities(selectedcountry)"
                    >
                      <option
                        v-for="country in countries"
                        :key="country"
                        :value="country.id"
                      >
                        {{ country.name }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div>
                    <label class="form-label" for="city">{{
                      $t("form.city")
                    }}</label>
                    <select
                      class="form-select"
                      id="city"
                      v-model="state.city_id"
                    >
                      <option
                        v-for="city in cities"
                        :key="city"
                        :value="city.id"
                      >
                        {{ city.name }}
                      </option>
                    </select>
                    <span class="error" v-if="v$.city_id.$error">
                      {{ v$.city_id.$errors[0].$message }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-4">
                  <div>
                    <label class="form-label" for="country-code">{{
                      $t("form.country_code")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="calling_code"
                      id="country-code"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-md-8">
                  <div>
                    <label class="form-label" for="phone">{{
                      $t("form.phone")
                    }}</label>
                    <input
                      type="text"
                      class="form-control"
                      id="phone"
                      v-model="state.phone"
                    />
                    <span class="error" v-if="v$.phone.$error">
                      {{ v$.phone.$errors[0].$message }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="row mb-2 mt-2 ">
                <div class="col-md-6 col-12">
                  <div class="form-check">
                    <input
                      type="checkbox"
                      class="form-check-input"
                      id="terms"
                    />
                    <label class="form-check-label" for="terms">
                      <router-link to="/terms">{{
                        $t("form.terms")
                      }}</router-link>
                    </label>
                  </div>
                </div>
              </div>
              <div class="text-center justify-content-center mt-4">
                <button
                  type="submit"
                  class="btn btn-primary blue-btn"
                  @click.prevent="signUp()"
                >
                  {{ $t("register") }}
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
import { defineComponent } from "vue";
import Header from "@/components/Header.vue"; // @ is an alias to /src
 // @ is an alias to /src
import axios from "axios";
import useVuelidate from "@vuelidate/core";
import {
  required,
  minLength,
  maxLength,
  email,
  sameAs,
  numeric,
  helpers,
} from "@vuelidate/validators";
import { reactive, computed } from "vue";

export default defineComponent({
  data() {
    return {
      showPassword: false,
      showPasswordC: false,
      countries: [],
      cities: [],
      calling_code: "",
      editSelectedcountry: "",
      selectedcountry: "3",
    };
  },

  setup() {
    const state = reactive({
      name: "",
      email: "",
      password: "",
      confirmPass: "",
      city_id: "",
      phone: "",
    });

    const phoneLen = (value) => value.length == 9;
    const rules = computed(() => {
      return {
        name: { required, minLength: minLength(5), maxLength: maxLength(20) },
        email: { required, email },
        password: {
          required,
          minLength: minLength(6),
          maxLength: maxLength(20),
        },
        confirmPass: {
          sameAs: sameAs(state.password),
        },
        city_id: {
          required,
        },
        phone: {
          required,
          numeric,
          phoneLen: helpers.withMessage(
            "Phone number must be 9 numbers",
            phoneLen
          ),
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
  },
  mounted() {
    this.getCountries();
    this.getCities(3);
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

    toggleShowTwo() {
      this.showPasswordC = !this.showPasswordC;
      var p = document.getElementById("confirmPass");
      if (this.showPasswordC) {
        p.setAttribute("type", "text");
      } else {
        p.setAttribute("type", "password");
      }
    },
    // get countries, code, country code
    getCountries() {
      axios
        .get("countries", { headers: this.headers })
        .then((response) => {
          this.countries = response.data.countries;
          let codeSelectedcountry = this.countries.filter(
            (item) => item.id == this.selectedcountry
          );
          codeSelectedcountry.map((item) => {
            this.calling_code = item.calling_code;
          });
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    getCode() {
      let codeSelectedcountry = this.countries.filter(
        (item) => item.id == this.editSelectedcountry
      );
      codeSelectedcountry.map((item) => {
        this.editCalling_code = item.calling_code;
      });
    },

    getCities(city) {
      axios
        .get(`cities/${city}`, { headers: this.headers })
        .then((response) => {
          this.cities = response.data.cities;
        })
        .catch((errors) => {
          console.log(errors);
        });
      for (var i = 0; i < this.countries.length; i++) {
        if (this.countries[i].id == city) {
          this.calling_code = this.countries[i].calling_code;
        }
      }
    },

    // sign up fn
    signUp() {
      const result = this.v$.$validate();
      const data = {
        name: this.state.name,
        email: this.state.email,
        password: this.state.password,
        password_confirmation: this.state.confirmPass,
        phone: this.state.phone,
        country_code: this.calling_code,
        city_id: this.state.city_id,
      };
      if (!this.v$.$error) {
        axios
          .post("register", data, { headers: this.headers })
          .then((response) => {
            this.$toast.success(response.data.message, {
              position: "top-right",
            });
            this.$router.push("/Login");
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
  },
});
</script>
