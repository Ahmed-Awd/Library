<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("profile") }}</h1>
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
            <form class="login-form">
              <div class="mb-3">
                <label class="form-label">{{ $t("form.name") }}</label>
                <input type="text" class="form-control" v-model="state.name" />
                <span class="error" v-if="v$.name.$error">
                  {{ v$.name.$errors[0].$message }}
                </span>
              </div>
              <div class="row mb-3">
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
              <div class="row mb-3">
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
                  </div>
                  <span class="error" v-if="v$.phone.$error">
                    {{ v$.phone.$errors[0].$message }}
                  </span>
                </div>
              </div>

              <div class="text-center justify-content-center mt-4">
                <button
                  type="button"
                  class="btn btn-primary blue-btn"
                  @click.prevent="updateData()"
                >
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
import { defineComponent } from "vue";
import Header from "@/components/Header.vue"; // @ is an alias to /src
 // @ is an alias to /src
import Pannel from "@/components/Pannel.vue"; // @ is an alias to /src
import axios from "axios";
import useVuelidate from "@vuelidate/core";
import {
  required,
  minLength,
  maxLength,
  numeric,
  helpers,
} from "@vuelidate/validators";
import { reactive, computed } from "vue";

export default defineComponent({
  data() {
    return {
      countries: [],
      cities: [],
      calling_code: "",
      selectedcountry: "",
    };
  },

  setup() {
    const state = reactive({
      name: "",
      city_id: "",
      phone: "",
    });

    const phoneLen = (value) => value.length == 9;
    const rules = computed(() => {
      return {
        name: { required, minLength: minLength(5), maxLength: maxLength(20) },
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
    Pannel,
  },

  mounted() {
    this.getUserData();
    this.getCountries();
  },

  methods: {
    getUserData() {
      axios
        .get("my-info")
        .then((response) => {
          const userData = response.data.user;
          // localStorage.setItem('CustomerData',)
          this.state.name = userData.name;
          this.selectedcountry = userData.city.country.id;
          this.state.city_id = userData.city_id;
          this.state.phone = userData.phone;
          this.getCities(this.selectedcountry);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    // get countries, code, country code
    getCountries() {
      axios
        .get("countries")
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
        (item) => item.id == this.selectedcountry
      );
      codeSelectedcountry.map((item) => {
        this.calling_code = item.calling_code;
      });
    },

    getCities(city) {
      axios
        .get(`cities/${city}`)
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

    updateData() {
      const result = this.v$.$validate();
      const data = {
        name: this.state.name,
        phone: this.state.phone,
        country_code: this.calling_code,
        city_id: this.state.city_id,
      };
      console.log(data);
      if (!this.v$.$error) {
        axios
          .post("my-info", data)
          .then((response) => {
            this.$toast.success(this.$t("successMsg"), {
              position: "top-right",
            });
          })
          .catch((errors) => {
            console.log(errors);
          });
      }
    },
  },
});
</script>
