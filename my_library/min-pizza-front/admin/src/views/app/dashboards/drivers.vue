<template>
  <div class="main-content">
    <breadcumb :page="$t('drivers')" :folder="$t('datatables')" />

    <!-- search -->
    <input
      class="form-control mb-2 searchInput"
      :placeholder="$t('searchTable')"
      type="text"
      v-model="searchtext"
    />
    <vue-good-table
      :columns="columns"
      mode="remote"
      :line-numbers="false"
      :totalRows="totalRecords"
      :search-options="{
        enabled: true,
        placeholder: $t('searchTable'),
        externalQuery: searchtext,
      }"
      @on-page-change="onPageChange"
      @on-sort-change="onSortChange"
      :pagination-options="{
        enabled: true,
        perPage: 15,
        perPageDropdownEnabled: false,
        infoFn: (params) => `${from} - ${to} of ${totalRecords}`,
      }"
      styleClass="tableOne vgt-table"
      :selectOptions="{
        enabled: false,
        selectionInfoClass: 'table-alert__box',
      }"
      :rows="rows"
    >
      <div slot="table-actions" class="mb-3">
        <b-button
          variant="primary"
          class="btn-rounded"
          :disabled="loadingRequest"
          @click="showCreateModal"
        >
          {{ $t("addNew") }}
        </b-button>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'">
          <button
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            @click.prevent="showEditModal(props.row)"
          >
            <i class="i-Eraser-2 text-25 text-success mr-2"></i>
            {{ props.row.button }}
          </button>
          <button
            v-if="
              roleName == 'super_admin' ||
              (roleName == 'admin' &&
                adminPermissionsList.includes('suspend user'))
            "
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            @click.prevent="showActivateModal(props.row)"
          >
            <i class="i-Stopwatch text-25 text-warning mr-2"></i>
            {{ props.row.button }}
          </button>
          <button
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            @click.prevent="showDeleteModal(props.row)"
          >
            <i class="i-Close-Window text-25 text-danger"></i>
            {{ props.row.button }}
          </button>
        </span>
      </template>
    </vue-good-table>
    <!-- create modal -->
    <div>
      <b-modal ref="create-modal" hide-footer :title="$t('createDriver')">
        <div class="d-block">
          <b-form-group :label="$t('name')" class="text-6">
            <b-form-input
              class="form-control"
              type="text"
              @input="$v.new_driver.name.$touch"
              v-model="new_driver.name"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.new_driver.name.$dirty">
              <span class="form__alert" v-if="!$v.new_driver.name.required">{{
                $t("validation.required")
              }}</span>
              <span class="form__alert" v-if="!$v.new_driver.name.minLength">{{
                $t("validation.nameMinLen")
              }}</span>
            </p>
          </b-form-group>
          <b-form-group :label="$t('email')" class="text-6">
            <b-form-input
              class="form-control"
              type="text"
              @input="$v.new_driver.email.$touch"
              v-model="new_driver.email"
            ></b-form-input>
            <p class="errors" v-if="$v.new_driver.email.$dirty">
              <span class="form__alert" v-if="!$v.new_driver.email.required">{{
                $t("validation.required")
              }}</span>
              <span class="form__alert" v-if="!$v.new_driver.email.email">{{
                $t("validation.emailFormat")
              }}</span>
            </p>
          </b-form-group>
          <div class="row">
            <b-form-group :label="$t('password')" class="text-6 col-md-6">
              <b-form-input
                class="form-control"
                type="password"
                @input="$v.new_driver.password.$touch"
                v-model="new_driver.password"
                required
              ></b-form-input>
              <p class="errors" v-if="$v.new_driver.password.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.new_driver.password.required"
                  >{{ $t("validation.required") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.new_driver.password.minLength"
                  >{{ $t("validation.passMinLen") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.new_driver.password.maxLength"
                  >{{ $t("validation.passMaxLen") }}</span
                >
              </p>
            </b-form-group>
            <b-form-group :label="$t('confirmPass')" class="text-6 col-md-6">
              <b-form-input
                class="form-control"
                type="password"
                @input="$v.new_driver.password_confirmation.$touch"
                v-model="new_driver.password_confirmation"
              ></b-form-input>
              <p
                class="errors"
                v-if="$v.new_driver.password_confirmation.$dirty"
              >
                <span
                  class="form__alert"
                  v-if="!$v.new_driver.password_confirmation.sameAsPassword"
                  >{{ $t("validation.passIdentical") }}</span
                >
              </p>
            </b-form-group>
          </div>
          <b-form-group :label="$t('profilePhoto')" class="text-6">
            <b-form-file
              class="form-control"
              accept="image/jpeg, image/png"
              v-model="new_driver.profile_photo_path"
            ></b-form-file>
          </b-form-group>
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
                v-model="new_driver.city_id"
                :options="cities"
                text-field="name"
                value-field="id"
              >
              </b-form-select>
              <p class="errors" v-if="$v.new_driver.city_id.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.new_driver.city_id.required"
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
                v-model="country_code"
                disabled
              ></b-form-input>
            </b-form-group>
            <b-form-group :label="$t('phoneNum')" class="text-3 col-md-9">
              <b-form-input
                class="form-control"
                type="text"
                @input="$v.new_driver.phone.$touch"
                v-model="new_driver.phone"
                required
              ></b-form-input>
              <p class="errors" v-if="$v.new_driver.phone.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.new_driver.phone.required"
                  >{{ $t("validation.required") }}</span
                >
                <span class="form__alert" v-if="!$v.new_driver.phone.numeric">{{
                  $t("validation.numeric")
                }}</span>
                <span
                  class="form__alert"
                  v-if="!$v.new_driver.phone.minLength"
                  >{{ $t("validation.phoneMinLen") }}</span
                >
                <span class="form__alert" v-if="!$v.new_driver.phone.maxLength">
                  {{ $t("validation.phoneMinLen") }}
                </span>
              </p>
            </b-form-group>
          </div>

          <div class="row">
            <b-form-group :label="$t('type')" class="text-3 col-md-3">
              <b-form-select
                class="form-control"
                :options="types"
                v-model="new_driver.type"
                required
              ></b-form-select>
              <p class="errors" v-if="$v.new_driver.type.$dirty">
                <span class="form__alert" v-if="!$v.new_driver.type.required">{{
                  $t("validation.required")
                }}</span>
              </p>
            </b-form-group>
            <b-form-group
              label="Restaurant"
              class="text-3 col-md-9"
              v-if="new_driver.type == 'restaurant'"
            >
              <b-form-select
                class="form-control"
                :options="restaurants"
                text-field="name"
                value-field="id"
                v-model="new_driver.restaurant_id"
              ></b-form-select>
            </b-form-group>
          </div>

          <b-button
            tag="button"
            @click.prevent="createDriver"
            class="btn-rounded btn-block mt-4"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.new_driver.$invalid"
          >
            {{ $t("create") }}
          </b-button>

          <b-button
            class="btn-rounded btn-block mt-4"
            block
            @click="hideCreateModal"
            >{{ $t("cancel") }}</b-button
          >
          <div v-once class="typo__p" v-if="loading">
            <div class="spinner sm spinner-primary mt-3"></div>
          </div>
        </div>
      </b-modal>
    </div>

    <!-- edit modal -->
    <div>
      <b-modal ref="edit-modal" hide-footer :title="$t('editDriver')">
        <div class="d-block" v-if="current_driver.user">
          <b-form-group :label="$t('name')" class="text-6">
            <b-form-input
              class="form-control"
              type="text"
              @input="$v.current_driver.user.name.$touch"
              v-model="current_driver.user.name"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.current_driver.user.name.$dirty">
              <span
                class="form__alert"
                v-if="!$v.current_driver.user.name.required"
                >{{ $t("validation.required") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.current_driver.user.name.minLength"
                >{{ $t("validation.nameMinLen3") }}</span
              >
            </p>
          </b-form-group>

          <b-form-group :label="$t('country')" class="text-6">
            <b-form-select
              class="form-control"
              v-model="editSelectedcountry"
              :options="countries"
              @change="getCode"
              text-field="name"
              value-field="id"
            >
            </b-form-select>
          </b-form-group>

          <div class="row">
            <b-form-group :label="$t('countryCode')" class="text-3 col-md-3">
              <b-form-input
                class="form-control"
                type="text"
                v-model="editCountry_code"
                disabled
                required
              ></b-form-input>
            </b-form-group>

            <b-form-group :label="$t('phoneNum')" class="text-3 col-md-9">
              <b-form-input
                class="form-control"
                type="text"
                @input="$v.current_driver.user.phone.$touch"
                v-model="current_driver.user.phone"
                required
              ></b-form-input>
              <p class="errors" v-if="$v.current_driver.user.phone.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.current_driver.user.phone.required"
                  >{{ $t("validation.required") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.current_driver.user.phone.numeric"
                  >{{ $t("validation.numeric") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.current_driver.user.phone.minLength"
                  >{{ $t("validation.phoneMinLen") }}</span
                >
                <span class="form__alert" v-if="!$v.current_driver.user.phone.maxLength">
                  {{ $t("validation.phoneMinLen") }}
                </span>
              </p>
            </b-form-group>
          </div>

          <div class="row">
            <b-form-group :label="$t('type')" class="text-3 col-md-3">
              <b-form-select
                class="form-control"
                :options="types"
                v-model="current_driver.type"
              ></b-form-select>
            </b-form-group>
            <b-form-group
              :label="$t('restaurant')"
              class="text-3 col-md-9"
              v-if="current_driver.type == 'restaurant'"
            >
              <b-form-select
                class="form-control"
                :options="restaurants"
                text-field="name"
                value-field="id"
                v-model="current_driver.restaurant_id"
              ></b-form-select>
            </b-form-group>
          </div>

          <b-button
            type="submit"
            tag="button"
            @click.prevent="updateDriver"
            class="btn-rounded btn-block mt-4"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.current_driver.$invalid"
          >
            {{ $t("update") }}
          </b-button>
          <b-button
            class="btn-rounded btn-block mt-4"
            block
            @click="hideEditModal"
            >{{ $t("cancel") }}</b-button
          >
          <div v-once class="typo__p" v-if="loading">
            <div class="spinner sm spinner-primary mt-3"></div>
          </div>
        </div>
      </b-modal>
    </div>

    <!-- delete modal -->
    <div>
      <b-modal ref="my-modal" hide-footer :title="$t('deleteConfirm')">
        <div class="d-block text-center">
          <h3>
            {{ $t("sureMsg") }}
            <strong>{{ this.current_driver.user.name }}</strong> ?
          </h3>
        </div>
        <b-button class="mt-3" block @click="hideDeleteModal">{{
          $t("cancel")
        }}</b-button>
        <b-button
          class="mt-3"
          variant="outline-danger"
          block
          @click="deleteDriver"
          >{{ $t("delete") }}
        </b-button>
      </b-modal>
    </div>

    <div>
      <b-modal ref="active-modal" hide-footer :title="$t('confirmation')">
        <div v-if="current_driver.user">
          <div class="d-block text-center">
            <h3 v-if="current_driver.user.is_disabled == 0">
              {{ $t("sureDisable") }}
            </h3>
            <h3 v-else>{{ $t("sureEnable") }}</h3>
          </div>
          <b-button class="mt-3" block @click="hideActivateModal">{{
            $t("cancel")
          }}</b-button>
          <b-button
            v-if="current_driver.user.is_disabled == 0"
            class="mt-3"
            variant="outline-danger"
            block
            @click="changeStatus"
          >
            {{ $t("disable") }}
          </b-button>
          <b-button
            v-else
            class="mt-3 btn-primary"
            variant="confirm"
            block
            @click="changeStatus"
          >
            {{ $t("enabel") }}
          </b-button>
        </div>
      </b-modal>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters ,mapActions } from "vuex";
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
    title: "reviews",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  inject: ["file_url"],
  data() {
    return {
      modalShow: false,
      totalRecords: 0,
      from: 1,
      to: 15,
      serverParams: {
        page: 1,
        perPage: 15,
      },
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("name"),
          field: "name",
        },
        {
          label: this.$t("type"),
          field: "type",
        },
        {
          label: this.$t("image"),
          field: "photo",
          html: true,
        },
        {
          label: this.$t("createdOn"),
          field: "created_at",
          type: "date",
          dateInputFormat: "yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'",
          dateOutputFormat: "yyyy-MM-dd HH:mm",
        },
        {
          label: "",
          field: "button",
          html: true,
          tdClass: "text-right",
          thClass: "text-right",
        },
      ],
      rows: [],
      image: "",
      current_driver: {
        user: {
          is_disabled: 0,
        },
      },
      new_driver: {
        restaurant_id: null,
        country: "3",
      },
      country_code: "",
      editCountry_code: "",
      countries: [],
      cities: [],
      restaurants: [],
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      types: [
        { value: "app", text: "Free Lancer" },
        { value: "restaurant", text: "Restaurant" },
      ],
      selectedcountry: "",
      editSelectedcountry: "",
      searchtext: "",
      loadingRequest: false,
      roleName: "",
      adminPermissionsList: [],
    };
  },

  validations: {
    new_driver: {
      name: {
        required,
        minLength: minLength(2),
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
      password_confirmation: {
        minLength: minLength(6),
        maxLength: maxLength(25),
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
      type: {
        required,
      },
    },
    current_driver: {
      user: {
        name: {
          required,
          minLength: minLength(3),
          maxLength: maxLength(50),
        },
        phone: {
          required,
          numeric,
          minLength: minLength(9),
          maxLength: maxLength(9),
        },
      },
    },
  },

  mounted() {
    this.show();
    this.getCountries();
    this.getRestaurants();
    // this.getCities(this.selectedcountry);
  },
  // watcher for search term value
  watch: {
    searchtext: function (newval) {
      if (newval.length > 1) {
        this.show(newval);
      } else {
        this.show();
      }
    },
  },
  created() {
    // mount for define role name and permissions if admin login
    var role_name = localStorage.getItem("role");
    var adminPermissions = localStorage.getItem("adminPermissions");
    this.roleName = role_name;
    this.adminPermissionsList = adminPermissions;
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.show();
    },
    async onSortChange(params) {
      console.log(params);
      await this.updateParams({sort_by : params[0].field});
      await this.updateParams({sort_type : params[0].type});
      this.show();
    },
    show(searchTerm) {
      let url;
      if (searchTerm) {
        url = `drivers?searchTerm=${searchTerm}`;
      } else {
        url = `drivers`;
      }
      axios
        .get(url, { headers: this.headers, params: this.serverParams })
        .then((response) => {
          this.totalRecords = response.data.drivers.total;
          this.to = response.data.drivers.to || 0;
          this.from = response.data.drivers.from || 0;
          this.rows = response.data.drivers.data.map((item) => ({
            ...item,
            photo: `<image width="80" height="80" src="${this.file_url}${item.user.profile_photo_path}"/>`,
          }));
          console.log(this.rows);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    showDeleteModal(driver) {
      this.current_driver = driver;
      this.$refs["my-modal"].show();
    },
    hideDeleteModal() {
      this.$refs["my-modal"].hide();
    },
    showActivateModal(driver) {
      this.current_driver = driver;
      this.$refs["active-modal"].show();
    },
    hideActivateModal() {
      this.$refs["active-modal"].hide();
    },
    showEditModal(driver) {
      this.current_driver = driver;
      this.editSelectedcountry = this.current_driver.user.city_id;
      this.getCode();
      console.log(this.current_driver);
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.$refs["edit-modal"].hide();
    },
    showCreateModal() {
      this.new_driver = { restaurant_id: null };
      console.log(this.new_driver);
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.$refs["create-modal"].hide();
      this.new_driver = { restaurant_id: null };
    },
    updateDriver() {
      this.loadingRequest = true; // set this before running anything
      this.$v.$touch(); //it will validate all fields
      axios
        .patch(
          `drivers/${this.current_driver.id}`,
          {
            name: this.current_driver.user.name,
            city_id: this.editSelectedcountry,
            country_code: this.editCountry_code,
            phone: this.current_driver.user.phone,
            type: this.current_driver.type,
            restaurant_id: this.current_driver.restaurant_id,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
          //this.$router.go();
          this.show();
          this.hideEditModal();

          this.loadingRequest = false;
        })
        .catch((errors) => {
            const Err = errors.response.data.errors;
            console.log(Err);
            for (const el in Err) {
              Err[el].map((item) => {
                this.makeToast("warning", item);
              });
            }
          this.loadingRequest = false;
        });
    },
    async deleteDriver() {
      this.loadingRequest = true; // set this before running anything

      await axios
        .delete(`drivers/${this.current_driver.id}`, { headers: this.headers })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "delete completed successfully");
          this.hideDeleteModal();
          this.show();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", "delete failed");
          this.hideDeleteModal();
          this.loadingRequest = false;
        });
    },
    changeStatus() {
      this.loadingRequest = true; // set this before running anything

      axios
        .post(
          `users/change-status/${this.current_driver.user.id})`,
          {},
          { headers: this.headers }
        )
        .then((response) => {
          this.makeToast("warning", response.data.message);
          this.hideActivateModal();
          this.show();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          this.makeToast("warning", errors.response.data);
          this.loadingRequest = false;
        });
    },
    createDriver() {
      this.$v.$touch(); //it will validate all fields
      this.loadingRequest = true; // set this before running anything
      
      let fd;
      fd = new FormData();
      fd.append("name", this.new_driver.name);
      fd.append("email", this.new_driver.email);
      fd.append("password", this.new_driver.password);
      fd.append("password_confirmation", this.new_driver.password_confirmation);
      fd.append("country_code", this.country_code);
      fd.append("phone", this.new_driver.phone);
      fd.append("city_id", this.new_driver.city_id);
      fd.append("type", this.new_driver.type);
      if (this.new_driver.type !== "app") {
        fd.append("restaurant_id", this.new_driver.restaurant_id);
      }
      if (this.new_driver.profile_photo_path != null) {
        fd.append("profile_photo_path", this.new_driver.profile_photo_path);
      }
      const headers = {
        Authorization: "Bearer " + localStorage.getItem("token"),

        "Content-Type": "multipart/form-data",
        Accept: "application/json",
      };
      axios
        .post("/drivers", fd, { headers })
        .then((response) => {
          this.makeToast("success", this.$t("successOpertion"));
          this.show();
          this.hideCreateModal();
          //this.$router.go();
          this.loadingRequest = false;
        })
        .catch((errors) => {
            const ErrMsg = errors.response.data.message;
            const Err = errors.response.data.errors;
            console.log(Err);
            for (const el in Err) {
              Err[el].map((item) => {
                this.makeToast("warning", item);
              });
            }
          this.loadingRequest = false;
        });
    },
    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
    getCountries() {
      axios
        .get("countries", { headers: this.headers })
        .then((response) => {
          this.countries = response.data.countries;
          let countryID = this.countries.filter(
            (item) => item.name == 'Sverige'
          );
          countryID.map((item) => {
            this.selectedcountry = item.id;
          });

          let codeSelectedcountry = this.countries.filter(
            (item) => item.id == this.selectedcountry
          );
          codeSelectedcountry.map((item) => {
            this.country_code = item.calling_code;
          });
          console.log(this.countries);
          this.getCities(this.selectedcountry);

        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    getCities(city) {
      let codeSelectedcountry = this.countries.filter(
        (item) => item.id == this.selectedcountry
      );
      codeSelectedcountry.map((item) => {
        this.country_code = item.calling_code;
      });
      axios
        .get(`cities/${city}`, { headers: this.headers })
        .then((response) => {
          this.cities = response.data.cities;
          console.log(this.cities);
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
        this.editCountry_code = item.calling_code;
      });
    },

    getRestaurants() {
      axios
        .get(`restaurants/all/lite`, { headers: this.headers })
        .then((response) => {
          console.log(response.data);
          this.restaurants = response.data.restaurants;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
  },
};
</script>
<style scoped>
.loading-btn {
  text-decoration: none !important;
  margin: 1px !important;
}
.searchInput {
  width: 25em;
}
</style>
