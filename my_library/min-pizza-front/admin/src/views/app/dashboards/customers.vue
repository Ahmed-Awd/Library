<template>
  <div class="main-content">
    <breadcumb :page="$t('customers')" :folder="$t('datatables')" />
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
          @click="showCreateModal"
          :disabled="loadingRequest"
        >
          {{ $t("addNew") }}
        </b-button>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'">
          <router-link
            v-if="
              roleName == 'super_admin' ||
              (roleName == 'admin' &&
                adminPermissionsList.includes('view orders'))
            "
            :to="{ path: `customerOrders/${props.row.id}` }"
            class="btn btn-outline-primary text-black text-10 p-1 mr-2"
          >
            {{ $t("orders") }}
          </router-link>
          <button
            class="loading-btn btn btn-link p-0"
            :disabled="loadingRequest"
            @click.prevent="showEditModal(props.row)"
          >
            <i class="i-Eraser-2 text-25 text-success mr-2"></i>
            {{ props.row.button }}
          </button>
          <button
            class="loading-btn btn btn-link p-0"
            v-if="
              roleName == 'super_admin' ||
              (roleName == 'admin' &&
                adminPermissionsList.includes('suspend user'))
            "
            :disabled="loadingRequest"
            @click.prevent="showActivateModal(props.row)"
          >
            <i class="i-Security-Block text-25 text-warning mr-2"></i>
            {{ props.row.button }}
          </button>
          <button
            v-if="
              props.row.is_disabled == 0 ||
              roleName == 'super_admin' ||
              (roleName == 'admin' &&
                adminPermissionsList.includes('suspend user'))
            "
            :disabled="loadingRequest"
            class="loading-btn btn btn-link p-0"
            @click.prevent="showHoldModal(props.row)"
          >
            <i class="i-Stopwatch text-25 text-warning mr-2"></i>
            {{ props.row.button }}
          </button>
          <button
            class="loading-btn btn btn-link p-0"
            :disabled="loadingRequest"
            @click.prevent="showDeleteModal(props.row)"
          >
            <i class="i-Close-Window text-25 text-danger"></i>
            {{ props.row.button }}
          </button>
        </span>
      </template>
    </vue-good-table>

    <div>
      <b-modal ref="create-modal" hide-footer :title="$t('createCustomer')">
        <div class="d-block">
          <b-form-group :label="$t('name')" class="text-6">
            <b-form-input
              class="form-control"
              type="text"
              @input="$v.new_customer.name.$touch"
              v-model="new_customer.name"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.new_customer.name.$dirty">
              <span class="form__alert" v-if="!$v.new_customer.name.required">{{
                $t("validation.required")
              }}</span>
              <span
                class="form__alert"
                v-if="!$v.new_customer.name.minLength"
                >{{ $t("validation.nameMinLen") }}</span
              >
            </p>
          </b-form-group>

          <b-form-group :label="$t('email')" class="text-6">
            <b-form-input
              class="form-control"
              type="email"
              @input="$v.new_customer.email.$touch"
              v-model="new_customer.email"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.new_customer.email.$dirty">
              <span
                class="form__alert"
                v-if="!$v.new_customer.email.required"
                >{{ $t("validation.required") }}</span
              >
              <span class="form__alert" v-if="!$v.new_customer.email.email">{{
                $t("validation.emailFormat")
              }}</span>
            </p>
          </b-form-group>

          <div class="row">
            <b-form-group :label="$t('password')" class="text-6 col-md-6">
              <b-form-input
                class="form-control"
                type="password"
                @input="$v.new_customer.password.$touch"
                v-model="new_customer.password"
                required
              ></b-form-input>
              <p class="errors" v-if="$v.new_customer.password.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.new_customer.password.required"
                  >{{ $t("validation.required") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.new_customer.password.minLength"
                  >{{ $t("validation.passMinLen") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.new_customer.password.maxLength"
                  >{{ $t("validation.passMaxLen") }}</span
                >
              </p>
            </b-form-group>

            <b-form-group :label="$t('confirmPass')" class="text-6 col-md-6">
              <b-form-input
                class="form-control"
                type="password"
                @input="$v.new_customer.password_confirmation.$touch"
                v-model="new_customer.password_confirmation"
                required
              ></b-form-input>
              <p
                class="errors"
                v-if="$v.new_customer.password_confirmation.$dirty"
              >
                <span
                  class="form__alert"
                  v-if="!$v.new_customer.password_confirmation.sameAsPassword"
                  >{{ $t("validation.passIdentical") }}</span
                >
              </p>
            </b-form-group>
          </div>

          <b-form-group :label="$t('profilePhoto')" class="text-6">
            <b-form-file
              class="form-control"
              accept="image/jpeg, image/png"
              v-model="new_customer.profile_photo_path"
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
                v-model="new_customer.city_id"
                :options="cities"
                text-field="name"
                value-field="id"
              >
              </b-form-select>
              <p class="errors" v-if="$v.new_customer.city_id.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.new_customer.city_id.required"
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
                @input="$v.new_customer.phone.$touch"
                v-model="new_customer.phone"
                required
              ></b-form-input>
              <p class="errors" v-if="$v.new_customer.phone.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.new_customer.phone.required"
                  >{{ $t("validation.required") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.new_customer.phone.numeric"
                  >{{ $t("validation.numeric") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.new_customer.phone.minLength"
                  >{{ $t("validation.phoneMinLen") }}</span
                >
                <span class="form__alert" v-if="!$v.new_customer.phone.maxLength">
                  {{ $t("validation.phoneMinLen") }}
                </span>
              </p>
            </b-form-group>
          </div>

          <b-button
            type="submit"
            tag="button"
            @click.prevent="createCustomer"
            class="btn-rounded btn-block mt-4"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.new_customer.$invalid"
          >
            {{ $t("create") }}
          </b-button>
          <b-button
            class="btn-rounded btn-block mt-4"
            block
            @click="hideCreateModal"
            >{{ $t("cancel") }}
          </b-button>
          <div v-once class="typo__p" v-if="loading">
            <div class="spinner sm spinner-primary mt-3"></div>
          </div>
        </div>
      </b-modal>
    </div>

    <div>
      <b-modal ref="edit-modal" hide-footer :title="$t('editCustomer')">
        <div class="d-block" v-if="current_customer">
          <b-form-group :label="$t('name')" class="text-6">
            <b-form-input
              class="form-control"
              type="text"
              @input="$v.current_customer.name.$touch"
              v-model="current_customer.name"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.current_customer.name.$dirty">
              <span
                class="form__alert"
                v-if="!$v.current_customer.name.required"
                >{{ $t("validation.required") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.current_customer.name.minLength"
                >{{ $t("validation.nameMinLen") }}</span
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
                v-model="editCalling_code"
                disabled
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group :label="$t('phoneNum')" class="text-3 col-md-9">
              <b-form-input
                class="form-control"
                type="text"
                @input="$v.current_customer.phone.$touch"
                v-model="current_customer.phone"
                required
              ></b-form-input>
              <p class="errors" v-if="$v.current_customer.phone.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.current_customer.phone.required"
                  >{{ $t("validation.required") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.current_customer.phone.numeric"
                  >{{ $t("validation.numeric") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.current_customer.phone.minLength"
                  >{{ $t("validation.phoneMinLen") }}</span
                >
                <span class="form__alert" v-if="!$v.current_customer.phone.maxLength">
                  {{ $t("validation.phoneMinLen") }}
                </span>
              </p>
            </b-form-group>
          </div>
          <div class="row">
            <b-form-group :label="$t('password')" class="text-6 col-md-6">
              <b-form-input
                class="form-control"
                type="password"
                @input="$v.current_customer.password.$touch"
                v-model="current_customer.password"
                required
              ></b-form-input>
              <p class="errors" v-if="$v.current_customer.password.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.current_customer.password.required"
                  >{{ $t("validation.required") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.current_customer.password.minLength"
                  >{{ $t("validation.passMinLen") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.current_customer.password.maxLength"
                  >{{ $t("validation.passMaxLen") }}</span
                >
              </p>
            </b-form-group>
            <b-form-group :label="$t('confirmPass')" class="text-6 col-md-6">
              <b-form-input
                class="form-control"
                type="password"
                @input="$v.current_customer.password_confirmation.$touch"
                v-model="current_customer.password_confirmation"
                required
              ></b-form-input>
              <p
                class="errors"
                v-if="$v.current_customer.password_confirmation.$dirty"
              >
                <span
                  class="form__alert"
                  v-if="
                    !$v.current_customer.password_confirmation.sameAsPassword
                  "
                  >{{ $t("validation.passIdentical") }}</span
                >
              </p>
            </b-form-group>
          </div>

          <b-button
            type="submit"
            tag="button"
            @click.prevent="updateCustomer"
            class="btn-rounded btn-block mt-4"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.current_customer.$invalid"
          >
            {{ $t("update") }}
          </b-button>
          <b-button
            class="btn-rounded btn-block mt-4"
            block
            @click="hideEditModal"
            >{{ $t("cancel") }}
          </b-button>
          <div v-once class="typo__p" v-if="loading">
            <div class="spinner sm spinner-primary mt-3"></div>
          </div>
        </div>
      </b-modal>
    </div>

    <div>
      <b-modal
        ref="active-modal"
        hide-footer
        :title="$t('disableConfirmation')"
      >
        <div v-if="current_customer">
          <div class="d-block text-center">
            <h3 v-if="current_customer.is_disabled == 0">
              {{ $t("sureMsgSuspend") }}
            </h3>
            <h3 v-else>
              {{ $t("enableMsg") }}
            </h3>
          </div>
          <b-button class="mt-3" block @click="hideActivateModal"
            >Cancel</b-button
          >
          <b-button
            v-if="current_customer.is_disabled == 0"
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
            {{ $t("enable") }}
          </b-button>
        </div>
      </b-modal>
    </div>

    <div>
      <b-modal ref="hold-modal" hide-footer :title="$t('disableConfirmation')">
        <div v-if="current_customer">
          <div class="d-block text-center">
            {{ $t("sureMsgSuspend") }}
          </div>
          <div class="row">
            <b-form-group :label="$t('time_in_hours')" class="text-3 col-md-9">
              <b-form-input
                class="form-control"
                type="number"
                min="1"
                v-model="block_hours"
                required
              ></b-form-input>
            </b-form-group>
          </div>

          <b-button class="mt-3" block @click="hideHoldModal">Cancel</b-button>
          <b-button
            v-if="current_customer.is_disabled == 0"
            class="mt-3"
            variant="outline-danger"
            block
            @click="holdUser"
          >
            {{ $t("hold") }}
          </b-button>
        </div>
      </b-modal>
    </div>
    <div>
      <b-modal ref="my-modal" hide-footer :title="$t('deleteConfirm')">
        <div class="d-block text-center">
          <h3>
            {{ $t("sureMsg") }}<strong>{{ this.current_customer.name }}</strong
            >?
          </h3>
        </div>
        <div class="text-right">
          <b-button
            class="btn btn-primary mt-4 mr-3"
            variant="outline-danger"
            @click="deleteCustomer"
            >{{ $t("delete") }}
          </b-button>
          <b-button class="btn btn-primary mt-4" @click="hideDeleteModal"
            >{{ $t("cancel") }}
          </b-button>
        </div>
      </b-modal>
    </div>
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
    title: "reviews",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  inject: ["file_url"],
  data() {
    return {
      roleName: "",
      adminPermissionsList: [],
      modalShow: false,
      countries: [],
      block_hours: 1,
      cities: [],
      from: 1,
      to: 15,
      serverParams: {
        page: 1,
        perPage: 15,
      },
      searchtext: "",
      customer: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        city_id: "",
        phone: "",
        country_code: "",
        is_disabled: "",
        profile_photo_path: "",
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
          label: this.$t("email"),
          field: "email",
        },
        {
          label: this.$t("country"),
          field: "city.country.name",
        },
        {
          label: this.$t("city"),
          field: "city.name",
        },
        {
          label: this.$t("status"),
          field: "disable",
          html: true,
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
      totalRecords: 0,
      rows: [],
      image: {},
      current_customer: {
        name: "",
        city_id: this.editSelectedcountry,
        phone: "",
        country_code: this.editCalling_code,
        is_disabled: "",
        profile_photo_path: "",
        id: "",
      },
      new_customer: {},
      calling_code: "",
      editCalling_code: "",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      selectedcountry: "",
      editSelectedcountry: "",
      loadingRequest: false,
    };
  },

  validations: {
    new_customer: {
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
    },

    current_customer: {
      name: {
        required,
        minLength: minLength(2),
      },
      phone: {
        required,
        numeric,
        minLength: minLength(9),
        maxLength: maxLength(9),
      },
      password: {
        minLength: minLength(6),
        maxLength: maxLength(25),
      },
      password_confirmation: {
        minLength: minLength(6),
        maxLength: maxLength(25),
        sameAsPassword: sameAs("password"),
      },
    },
  },
  created() {
    // mount for define role name and permissions if admin login
    var role_name = localStorage.getItem("role");
    var adminPermissions = localStorage.getItem("adminPermissions");
    this.roleName = role_name;
    this.adminPermissionsList = adminPermissions;
  },
  mounted() {
    this.show();
    this.getCountries();
    // this.getCities(3);
  },
  // watcher for search term value
  watch: {
    searchtext: function (newval) {
      if (newval.length > 1) {
        this.show(newval);
      }
    },
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
        url = `customers?searchTerm=${searchTerm}`;
        this.updateParams({ page: 1 });
      } else {
        url = `customers`;
      }
      axios
        .get(url, { headers: this.headers, params: this.serverParams })
        .then((response) => {
          console.log(response.data);
          this.totalRecords = response.data.users.total;
          this.to = response.data.users.to || 0;
          this.from = response.data.users.from || 0;
          this.rows = response.data.users.data.map((item) => ({
            ...item,
            photo: `<image width="80" height="80" src="${this.file_url}${item.image}"/>`,
            disable:
              item.is_disabled === 1
                ? `<p>${this.$t("disabled")}</p>`
                : `<p>${this.$t("active")}</p>`,
          }));

          console.log(this.rows);
        })

        .catch((errors) => {
          console.log(errors);
        });
    },
    showDeleteModal(customer) {
      this.current_customer = customer;
      this.$refs["my-modal"].show();
    },
    hideDeleteModal() {
      this.current_customer = {};
      this.$refs["my-modal"].hide();
    },
    showEditModal(customer) {
      this.current_customer = customer;
      this.current_customer.profile_photo_path = null;
      this.editSelectedcountry = this.current_customer.city_id;
      this.getCode();

      console.log(this.current_customer);
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.$refs["edit-modal"].hide();
    },
    showCreateModal() {
      this.new_customer = {};
      console.log(this.current_customer);
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_customer = {};
      this.$refs["create-modal"].hide();
    },
    showActivateModal(customer) {
      this.current_customer = customer;
      this.$refs["active-modal"].show();
    },
    hideActivateModal() {
      this.current_customer = {};
      this.$refs["active-modal"].hide();
    },
    showHoldModal(customer) {
      this.current_customer = customer;
      this.$refs["hold-modal"].show();
    },
    hideHoldModal() {
      this.current_customer = {};
      this.$refs["hold-modal"].hide();
    },
    updateCustomer() {
      this.loading = true; // set this before running anything
      this.$v.$touch(); //it will validate all fields

      axios
        .put(
          `customers/${this.current_customer.id}`,
          {
            name: this.current_customer.name,
            city_id: this.editSelectedcountry,
            phone: this.current_customer.phone,
            country_code: this.editCalling_code,
            password: this.current_customer.password,
            password_confirmation: this.current_customer.password_confirmation,
            is_disabled: this.current_customer.is_disabled,
            profile_photo_path: this.current_customer.profile_photo_path,
            id: this.current_customer.id,
          },
          {
            headers: this.headers,
          }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "update completed successfully");
          this.show();
          this.hideEditModal();
          this.loading = false;
        })
        .catch((errors) => {
          
            const Err = errors.response.data.errors;
            console.log(Err);
            for (const el in Err) {
              Err[el].map((item) => {
                this.makeToast("warning", item);
              });
            }
          
          this.loading = false;
        });
    },
    async deleteCustomer() {
      this.loadingRequest = true; // set this before running anything

      await axios
        .delete(`customers/${this.current_customer.id}`, {
          headers: this.headers,
        })
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
    createCustomer() {
      this.loadingRequest = true; // set this before running anything
      this.$v.$touch(); //it will validate all fields

      let fd;
      fd = new FormData();
      fd.append("name", this.new_customer.name);
      fd.append("email", this.new_customer.email);
      fd.append("password", this.new_customer.password);
      fd.append(
        "password_confirmation",
        this.new_customer.password_confirmation
      );
      fd.append("city_id", this.new_customer.city_id);
      fd.append("phone", this.new_customer.phone);
      fd.append("country_code", this.calling_code);
      fd.append("disable", this.new_customer.is_disabled);
      if (this.new_customer.profile_photo_path != null) {
        fd.append("image", this.new_customer.profile_photo_path);
      }

      const headers = {
        Authorization: "Bearer " + localStorage.getItem("token"),

        "Content-Type": "multipart/form-data",
      };
      axios
        .post("customers", fd, { headers })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "create completed successfully");
          this.hideCreateModal();
          this.show();
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
    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
    changeStatus() {
      this.loadingRequest = true; // set this before running anything

      axios
        .post(
          `users/change-status/${this.current_customer.id}`,
          {},
          { headers: this.headers }
        )
        .then((response) => {
          this.makeToast("warning", response.data.message);
          this.hideActivateModal();
          this.show();
          this.loading = false;
        })
        .catch((errors) => {
          this.makeToast("warning", errors.response.data);
          this.loading = false;
        });
    },
    holdUser() {
      this.loadingRequest = true; // set this before running anything

      axios
        .post(
          `users/change-status/${this.current_customer.id}`,
          { hour: this.block_hours },
          { headers: this.headers }
        )
        .then((response) => {
          this.makeToast("warning", response.data.message);
          this.hideHoldModal();
          this.show();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          this.makeToast("warning", errors.response.data);
          this.loadingRequest = false;
        });
    },
    getCountries() {
      this.loadingRequest = true; // set this before running anything

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

          let countryID = this.countries.filter(
            (item) => item.name == 'Sverige'
          );
          countryID.map((item) => {
            this.selectedcountry = item.id;
          });
          this.getCities(this.selectedcountry);
          console.log(this.countries);
          this.loadingRequest = false;
        })
        .catch((errors) => {
          console.log(errors);
          this.loadingRequest = false;
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
      let codeSelectedcountry = this.countries.filter(
        (item) => item.id == this.selectedcountry
      );
      codeSelectedcountry.map((item) => {
        this.calling_code = item.calling_code;
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
