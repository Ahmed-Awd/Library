<template>
  <div class="main-content">
    <breadcumb :page="$t('createAdmin')" :folder="$t('createAdmin')" />
    <!-- <b-form> -->
    <b-row>
      <b-col md="12">
        <b-card class="mb-30">
          <div class="d-block">
            <div class="row">
              <b-form-group :label="$t('name')" class="text-6 col-md-6">
                <b-form-input
                  class="form-control"
                  type="text"
                  @input="$v.new_admin.name.$touch"
                  v-model="new_admin.name"
                  required
                ></b-form-input>

                <p class="errors" v-if="$v.new_admin.name.$dirty">
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.name.required"
                    >{{ $t("validation.required") }}</span
                  >
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.name.minLength"
                    >{{ $t("validation.nameMinLen") }}</span
                  >
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.name.maxLength"
                    >{{ $t("validation.nameMaxLen") }}</span
                  >
                </p>
              </b-form-group>

              <b-form-group :label="$t('email')" class="text-6 col-md-6">
                <b-form-input
                  class="form-control"
                  type="email"
                  @input="$v.new_admin.email.$touch"
                  v-model="new_admin.email"
                  required
                ></b-form-input>
                <p class="errors" v-if="$v.new_admin.email.$dirty">
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.email.required"
                    >{{ $t("validation.required") }}</span
                  >
                  <span class="form__alert" v-if="!$v.new_admin.email.email">{{
                    $t("validation.emailFormat")
                  }}</span>
                </p>
              </b-form-group>
            </div>

            <div class="row">
              <b-form-group :label="$t('password')" class="text-6 col-md-6">
                <b-form-input
                  class="form-control"
                  type="password"
                  @input="$v.new_admin.password.$touch"
                  v-model="new_admin.password"
                  required
                ></b-form-input>
                <p class="errors" v-if="$v.new_admin.password.$dirty">
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.password.required"
                    >{{ $t("validation.required") }}</span
                  >
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.password.minLength"
                    >{{ $t("validation.passMinLen") }}</span
                  >
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.password.maxLength"
                    >{{ $t("validation.passMaxLen") }}</span
                  >
                </p>
              </b-form-group>

              <b-form-group :label="$t('confirmPass')" class="text-6 col-md-6">
                <b-form-input
                  class="form-control"
                  type="password"
                  @input="$v.new_admin.confirmPass.$touch"
                  v-model="new_admin.confirmPass"
                  required
                ></b-form-input>
                <p class="errors" v-if="$v.new_admin.confirmPass.$dirty">
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.confirmPass.sameAsPassword"
                    >{{ $t("validation.passIdentical") }}</span
                  >
                </p>
              </b-form-group>
            </div>

            <div class="row">
              <b-form-group :label="$t('country')" class="text-6 col-md-6">
                <b-form-select
                  class="form-control"
                  v-model="selectedcountry"
                  :options="countries"
                  @change="getCities"
                  text-field="name"
                  value-field="id"
                >
                </b-form-select>
              </b-form-group>

              <b-form-group :label="$t('city')" class="text-6 col-md-6">
                <b-form-select
                  class="form-control"
                  v-model="new_admin.city_id"
                  :options="cities"
                  text-field="name"
                  value-field="id"
                >
                </b-form-select>
                <p class="errors" v-if="$v.new_admin.city_id.$dirty">
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.city_id.required"
                    >{{ $t("validation.required") }}</span
                  >
                </p>
              </b-form-group>
            </div>

            <div class="row">
              <b-form-group :label="$t('countryCode')" class="text-3 col-md-3">
                <b-form-input
                  class="form-control"
                  type="text"
                  v-model="calling_code"
                  disabled
                  required
                ></b-form-input>
              </b-form-group>
              <b-form-group :label="$t('phoneNum')" class="text-3 col-md-9">
                <b-form-input
                  class="form-control"
                  type="text"
                  @input="$v.new_admin.phone.$touch"
                  v-model="new_admin.phone"
                  required
                ></b-form-input>
                <p class="errors" v-if="$v.new_admin.phone.$dirty">
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.phone.required"
                    >{{ $t("validation.required") }}</span
                  >
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.phone.numeric"
                    >{{ $t("validation.numeric") }}</span
                  >
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.phone.minLength"
                    >{{ $t("validation.phoneMinLen") }}</span
                  >
                  <span
                    class="form__alert"
                    v-if="!$v.new_admin.phone.maxLength"
                  >
                    {{ $t("validation.phoneMinLen") }}
                  </span>
                </p>
              </b-form-group>
            </div>

            <b-form-group :label="$t('image')" class="text-6">
              <b-form-file
                class="form-control"
                accept="image/jpeg, image/png"
                v-model="new_admin.image"
                required
              ></b-form-file>
            </b-form-group>

            <h6 class="mt-3">{{ $t("addPermission") }} :</h6>
            <div class="row">
              <div
                class="form-check col-md-4"
                v-for="permission in permissionsData"
                :key="permission.id"
              >
                <label class="form-check-label ml-3" :for="permission.id">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="secondaryCat"
                    :id="permission.id"
                    :value="permission.name"
                    v-model="permissionVal"
                  />
                  {{ permission.name }}
                </label>
              </div>
            </div>
            <b-form-group class="text-6 mt-3">
              <b-form-checkbox
                v-model="new_admin.is_active"
                name="is_active"
                value="0"
                unchecked-value="1"
              >
                {{ this.$t("enableAdmin") }}
              </b-form-checkbox>
            </b-form-group>

            <div class="text-right">
              <b-button
                type="submit"
                tag="button"
                @click.prevent="createAdmin"
                class="btn btn-primary mt-4 mr-3"
                variant="primary mt-2"
                :disabled="loading || $v.new_admin.$invalid"
              >
                {{ this.$t("create") }}
              </b-button>
            </div>

            <div v-once class="typo__p" v-if="loading">
              <div class="spinner sm spinner-primary mt-3"></div>
            </div>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <!-- </b-form> -->
  </div>
</template>
<script>
import axios from "axios";
import { mapGetters } from "vuex";
import {
  required,
  email,
  sameAs,
  numeric,
  minLength,
  maxLength,
} from "vuelidate/lib/validators";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Add New Admin",
  },

  inject: ["file_url"],
  data() {
    return {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
        Accept: "application/json",
      },

      new_admin: {
        is_active: 0,
      },
      country_code: "",
      countries: [],
      cities: [],
      selectedcountry: "",

      permissionVal: [],
      permissionsData: [],
    };
  },
  validations: {
    new_admin: {
      name: {
        required,
        minLength: minLength(2),
        maxLength: maxLength(50),
      },
      email: {
        required,
        email,
      },
      password: {
        required,
        minLength: minLength(6),
        maxLength: maxLength(25),
      },
      confirmPass: {
        sameAsPassword: sameAs("password"),
      },
      city_id: {
        required,
      },
      phone: {
        required,
        numeric,
        minLength: minLength(9),
        maxLength: maxLength(9),
      },
    },
  },
  mounted() {
    this.getCountries();
    // this.getCities(3);
    this.listPermissions();
  },
  methods: {
    onSelect(option) {
      console.log(option);
      this.cat.push(option.id);
    },
    getCountries() {
      axios
        .get("countries", { headers: this.headers })
        .then((response) => {
          this.countries = response.data.countries;
          console.log(this.countries);
          let codeSelectedcountry = this.countries.filter(
            (item) => item.id == this.selectedcountry
          );
          codeSelectedcountry.map((item) => {
            this.calling_code = item.calling_code;
          });
          let countryID = this.countries.filter(
            (item) => item.name == 'Sverige'
          );
          countryID.map((item) => {
            this.selectedcountry = item.id;
          });
          this.getCities(this.selectedcountry);
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
          console.log(this.cities);
          // alert('dd');
        })
        .catch((errors) => {
          console.log(errors);
        });
      for (var i = 0; i < this.countries.length; i++) {
        if (this.countries[i].id == city) {
          console.log(this.countries[i].calling_code);
          this.calling_code = this.countries[i].calling_code;
        }
      }
    },

    // display all permissions
    listPermissions() {
      axios
        .get("permissions/all", {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data.permissions);
          this.permissionsData = response.data.permissions;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    // create new admin
    createAdmin() {
      this.loading = true; // set this before running anything
      this.$v.$touch(); //it will validate all fields
      let fd;
      fd = new FormData();
      fd.append("name", this.new_admin.name);
      fd.append("email", this.new_admin.email);
      fd.append("password", this.new_admin.password);
      fd.append("password_confirmation", this.new_admin.confirmPass);
      fd.append("city_id", this.new_admin.city_id);
      fd.append("phone", this.new_admin.phone);
      fd.append("country_code", this.calling_code);
      if (this.new_admin.image != null) {
        fd.append("profile_photo_path", this.new_admin.image);
      }
      fd.append("is_disabled", this.new_admin.is_active);
      this.permissionVal.map((item, index) => {
        fd.append(`permissions[${index}]`, item);
      });
      axios
        .post("admin/user/", fd, { headers: this.headers })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
        })
        .catch((errors) => {
          const Err = errors.response.data.errors;
          for (const el in Err) {
            Err[el].map((item) => {
              this.makeToast("warning", item);
            });
            this.loading = false;
          }
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
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
label.checkbox {
  margin-left: 1.1rem;
}

.delete-btn {
  cursor: pointer;
}

.mb-10 {
  margin-bottom: 10px;
}

.d-inline-flex {
  display: inline-flex;
  align-items: center;
}
/* pretty checkbox */
input[type="checkbox"] {
  -webkit-appearance: none;
  width: 15px;
  height: 15px;
  border: 1px solid darkgray;
}

input[type="checkbox"]:hover {
  box-shadow: 0 0 5px 0px orange inset;
}

input[type="checkbox"]:before {
  content: "";
  display: block;
  width: 60%;
  height: 60%;
  margin: 20% auto;
}
input[type="checkbox"]:checked:before {
  background: #8ac054;
}
</style>
