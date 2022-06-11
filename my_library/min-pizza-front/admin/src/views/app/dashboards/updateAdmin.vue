<template>
  <div class="main-content">
    <breadcumb :page="$t('editAdmin')" :folder="$t('editAdmin')" />
    <b-row>
      <b-col md="12">
        <b-card class="mb-30">
          <div class="d-block">
            <div class="row">
              <b-form-group :label="$t('name')" class="text-6 col-md-6">
                <b-form-input
                  class="form-control"
                  type="text"
                  v-model="current_admin.name"
                  required
                ></b-form-input>
              </b-form-group>

              <b-form-group :label="$t('email')" class="text-6 col-md-6">
                <b-form-input
                  class="form-control"
                  type="email"
                  v-model="current_admin.email"
                  required
                ></b-form-input>
              </b-form-group>
            </div>

            <div class="row">
              <b-form-group :label="$t('password')" class="text-6 col-md-6">
                <b-form-input
                  class="form-control"
                  type="password"
                  v-model="current_admin.password"
                  required
                ></b-form-input>
              </b-form-group>

              <b-form-group :label="$t('confirmPass')" class="text-6 col-md-6">
                <b-form-input
                  class="form-control"
                  type="password"
                  v-model="current_admin.confirmPass"
                  required
                ></b-form-input>
              </b-form-group>
            </div>

            <div class="row">
              <b-form-group :label="$t('country')" class="text-6 col-md-6">
                <b-form-select
                  class="form-control"
                  v-model="selectedcountry"
                  :options="countries"
                  @input="getCities(selectedcountry)"
                  text-field="name"
                  value-field="id"
                >
                </b-form-select>
              </b-form-group>
              <b-form-group :label="$t('city')" class="text-6 col-md-6">
                <b-form-select
                  class="form-control"
                  v-model="current_admin.city_id"
                  :options="cities"
                  text-field="name"
                  value-field="id"
                >
                </b-form-select>
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
                  v-model="current_admin.phone"
                  required
                ></b-form-input>
              </b-form-group>
            </div>

            <b-form-group :label="$t('image')" class="text-6">
              <b-form-file
                class="form-control"
                accept="image/jpeg, image/png"
                v-model="current_admin.image"
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
                    @change="checkInput($event)"
                  />
                  {{ permission.name }}
                </label>
              </div>
            </div>
            <b-form-group class="text-6 mt-3">
              <b-form-checkbox
                v-model="current_admin.is_active"
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
                @click.prevent="updateAdmin"
                class="btn btn-primary mt-4 mr-3"
                variant="primary mt-2"
                :disabled="loadingRequest"
              >
                {{ this.$t("update") }}
              </b-button>
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

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Edit Admin",
  },
  inject: ["file_url"],

  data() {
    return {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
        Accept: "application/json",
      },
      loadingRequest:false,
      current_admin: {},
      country_code: "",
      calling_code:"",
      countries: [],
      cities: [],
      selectedcountry: "",
      countryID: "",
      permissionVal: [],
      permissionsData: [],
      adminID: this.$route.params.id,
    };
  },
  mounted() {
    this.show();
    this.getCountries();
    this.getCities(this.selectedcountry);
    this.listPermissions();
  },
  methods: {
    //   list data
    show() {
      axios
        .get(`admin/user/${this.adminID}`, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
          },
          params: this.serverParams,
        })
        .then((response) => {
          this.current_admin.name = response.data.data[0].name;
          this.current_admin.email = response.data.data[0].email;
          this.selectedcountry = response.data.data[0].city.country.id;
          this.current_admin.city_id = response.data.data[0].city.id;
          this.current_admin.phone = response.data.data[0].phone;
          this.current_admin.image = null;
          this.current_admin.confirmPass = null;
          this.current_admin.password = null;
          this.current_admin.is_active = response.data.data[0].is_disabled;
          if (response.data.data[0].permissions) {
            this.permissionVal = [];
            response.data.data[0].permissions.map((el) => {
              this.permissionVal.push(el.name);
            });
          }
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    onSelect(option) {
      this.cat.push(option.id);
    },

    // attach and dettach permission to admin
    checkInput(e) {
      if (e.target.checked == true) {
        this.linkPermission(e.target._value);
        console.log(e.target._value);
      } else if (e.target.checked == false) {
        this.removeLinkPermission(e.target._value);
      }
    },

    // update admin
    updateAdmin() {
      let fd;
      fd = new FormData();
      fd.append("name", this.current_admin.name);
      if (this.current_admin.password) {
        fd.append("password", this.current_admin.password);
        fd.append("password_confirmation", this.current_admin.confirmPass);
      }
      fd.append("city_id", this.current_admin.city_id);
      fd.append("phone", this.current_admin.phone);
      fd.append("country_code", this.calling_code);
      if (this.current_admin.image != null) {
        fd.append("profile_photo_path", this.current_admin.image);
      }
      fd.append("is_disabled", this.current_admin.is_active);
      fd.append("_method", "patch");
      axios
        .post(`admin/user/${this.adminID}`, fd, { headers: this.headers })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "update completed successfully");
        })
        .catch((errors) => {
          const ErrMsg = errors.response.data.message;
          const Err = errors.response.data.errors;
          console.log(Err);
          for (const el in Err) {
            Err[el].map((item) => {
              this.makeToast("warning", item);
            });
            this.loadingRequest = false;
          }
        });
    },

    // link permission with admin
    linkPermission(val) {
      axios
        .post(
          `admin/user/add-permission/${this.adminID}`,
          {
            permission: val,
          },
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token"),
            },
          }
        )
        .then((response) => {
          console.log(response);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    // remove link permission with admin
    removeLinkPermission(val) {
      axios
        .post(
          `admin/user/remove-permission/${this.adminID}`,
          {
            permission: val,
          },
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token"),
            },
          }
        )
        .then((response) => {
          console.log(response);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

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

    getCities(city) {
      console.log(city);
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

    getCode() {
      let codeSelectedcountry = this.countries.filter(
        (item) => item.id == this.editSelectedcountry
      );
      codeSelectedcountry.map((item) => {
        this.editCalling_code = item.calling_code;
      });
    },

    // display all permissions
    listPermissions() {
      axios
        .get("permissions/all", {
          headers: this.headers,
        })
        .then((response) => {
          this.permissionsData = response.data.permissions;
        })
        .catch((errors) => {
          console.log(errors);
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
