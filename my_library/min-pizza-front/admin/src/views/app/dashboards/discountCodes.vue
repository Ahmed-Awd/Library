<template>
  <div class="main-content">
    <breadcumb
      :page="this.$t('sidebar.discountCodes')"
      :folder="this.$t('datatables')"
    />
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
          {{ this.$t("add") }} {{ this.$t("code") }}
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
    <b-modal
      ref="create-modal"
      hide-footer
      :title="this.$t('create') + ' ' + this.$t('sidebar.discountCodes')"
    >
      <div class="d-block">
        <div class="row">
          <b-form-group :label="this.$t('type')" class="text-6 col-md-6">
            <b-form-select
              class="form-control"
              v-model="new_code.type"
              @input="$v.new_code.type.$touch"
              :options="types"
              text-field="name"
              value-field="id"
            >
            </b-form-select>
              <p class="errors" v-if="$v.new_code.type.$dirty">
            <span class="form__alert" v-if="!$v.new_code.type.required">{{
              $t("validation.required")
            }}</span>
          </p>
          </b-form-group>
        
          <b-form-group :label="this.$t('user')" class="text-6 col-md-6">
            <v-select
              label="name"
              :reduce="(option) => option.id"
              :options="users"
              @input="$v.new_code.user_id.$touch"
              v-model="new_code.user_id"
            ></v-select>
             <p class="errors" v-if="$v.new_code.user_id.$dirty">
            <span class="form__alert" v-if="!$v.new_code.user_id.required">{{
              $t("validation.required")
            }}</span>
          </p>
          </b-form-group>
         
        </div>
        <div class="row">
          <b-form-group
            :label="this.$t('amountOfDiscount')"
            class="text-6 col-md-4"
          >
            <b-form-input
              class="form-control"
              type="number"
              min="1"
              @input="$v.new_code.amount.$touch"
              v-model="new_code.amount"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.new_code.amount.$dirty">
              <span class="form__alert" v-if="!$v.new_code.amount.required">{{
                $t("validation.required")
              }}</span>
              <span class="form__alert" v-if="!$v.new_code.amount.minvalue">{{
                $t("validation.minValue")
              }}</span>
            </p>
          </b-form-group>

          <b-form-group
            :label="this.$t('numberOfUsage')"
            class="text-6 col-md-4"
          >
            <b-form-input
              class="form-control"
              type="number"
              min="1"
              @input="$v.new_code.max_usage.$touch"
              v-model="new_code.max_usage"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.new_code.max_usage.$dirty">
              <span
                class="form__alert"
                v-if="!$v.new_code.max_usage.required"
                >{{ $t("validation.required") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.new_code.max_usage.minvalue"
                >{{ $t("validation.minValue") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.new_code.max_usage.maxValue"
                >{{ $t("validation.maxValue") }}</span
              >
            </p>
          </b-form-group>
          <b-form-group :label="this.$t('theMinPrice')" class="text-6 col-md-4">
            <b-form-input
              class="form-control"
              type="number"
              min="1"
              @input="$v.new_code.min_order_price.$touch"
              v-model="new_code.min_order_price"
              required
            ></b-form-input>

            <p class="errors" v-if="$v.new_code.min_order_price.$dirty">
              <span
                class="form__alert"
                v-if="!$v.new_code.min_order_price.required"
                >{{ $t("validation.required") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.new_code.min_order_price.minvalue"
                >{{ $t("validation.minValue") }}</span
              >
            </p>
          </b-form-group>
        </div>
        <div class="row">
          <b-form-group :label="this.$t('restaurant')" class="text-6 col-md-8">
            <v-select
              label="name"
              :reduce="(option) => option.id"
              :options="restaurants"
              @input="$v.new_code.restaurant_id.$touch"
              v-model="new_code.restaurant_id"
            ></v-select>
            <p class="errors" v-if="$v.new_code.restaurant_id.$dirty">
              <span
                class="form__alert"
                v-if="!$v.new_code.restaurant_id.required"
                >{{ $t("validation.required") }}</span
              >
            </p>
          </b-form-group>
        </div>
        <div class="row">
          <b-form-group :label="this.$t('start_date')" class="text-6 col-md-6">
            <b-form-datepicker
              id="start-datepicker"
              @input="$v.new_code.start_date.$touch"
              v-model="new_code.start_date"
              class="mb-2"
            ></b-form-datepicker>
            <p class="errors" v-if="$v.new_code.start_date.$dirty">
              <span
                class="form__alert"
                v-if="!$v.new_code.start_date.required"
                >{{ $t("validation.required") }}</span
              >
            </p>
          </b-form-group>
          <b-form-group :label="this.$t('end_date')" class="text-6 col-md-6">
            <b-form-datepicker
              id="end-datepicker"
              @input="$v.new_code.end_date.$touch"
              v-model="new_code.end_date"
              :min="new_code.start_date"
              class="mb-2"
            ></b-form-datepicker>
            <p class="errors" v-if="$v.new_code.end_date.$dirty">
              <span class="form__alert" v-if="!$v.new_code.end_date.required">{{
                $t("validation.required")
              }}</span>
            </p>
          </b-form-group>
        </div>

        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="createNew"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.new_code.$invalid"
          >
            {{ this.$t("save") }}
          </b-button>
          <b-button class="btn btn-primary mt-4" @click="hideCreateModal">
            {{ this.$t("cancel") }}
          </b-button>
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3"></div>
        </div>
      </div>
    </b-modal>
    <b-modal ref="delete-modal" hide-footer :title="this.$t('deleteConfirm')">
      <div class="d-block">
        <h3>
          {{ this.$t("deleteCat") }} <strong>{{ current_row.name }}</strong>
          {{ this.$t("code") }} ?
        </h3>
      </div>
      <div class="text-right">
        <b-button class="mt-3 mr-3" @click="hideDeleteModal">Cancel</b-button>
        <b-button class="mt-3" variant="outline-danger" @click="deleteRaw">
          {{ this.$t("delete") }}
        </b-button>
      </div>
    </b-modal>
    <b-modal
      ref="edit-modal"
      hide-footer
      :title="this.$t('edit') + ' ' + this.$t('sidebar.discountCodes')"
    >
      <div class="d-block">
        <div class="row">
          <b-form-group :label="this.$t('restaurant')" class="text-6 col-md-8">
            <b-form-select
              class="form-control"
              v-model="current_row.restaurant_id"
              :options="restaurants"
              text-field="name"
              value-field="id"
              @input="$v.current_row.restaurant_id.$touch"
            >
            </b-form-select>
            <p class="errors" v-if="$v.current_row.restaurant_id.$dirty">
              <span
                class="form__alert"
                v-if="!$v.current_row.restaurant_id.required"
                >{{ $t("validation.required") }}</span
              >
            </p>
          </b-form-group>
        </div>
        <div class="row">
          <b-form-group :label="this.$t('theMinPrice')" class="text-6 col-md-4">
            <b-form-input
              class="form-control"
              type="number"
              min="1"
              v-model="current_row.min_order_price"
              @input="$v.current_row.min_order_price.$touch"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.current_row.min_order_price.$dirty">
              <span
                class="form__alert"
                v-if="!$v.current_row.min_order_price.required"
                >{{ $t("validation.required") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.current_row.min_order_price.minvalue"
                >{{ $t("validation.minValue") }}</span
              >
            </p>
          </b-form-group>
          <b-form-group
            :label="this.$t('amountOfDiscount')"
            class="text-6 col-md-4"
          >
            <b-form-input
              class="form-control"
              type="number"
              min="1"
              v-model="current_row.amount"
              @input="$v.current_row.amount.$touch"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.current_row.amount.$dirty">
              <span
                class="form__alert"
                v-if="!$v.current_row.amount.required"
                >{{ $t("validation.required") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.current_row.amount.minvalue"
                >{{ $t("validation.minValue") }}</span
              >
            </p>
          </b-form-group>
          <b-form-group
            :label="this.$t('numberOfUsage')"
            class="text-6 col-md-4"
          >
            <b-form-input
              class="form-control"
              type="number"
              min="1"
              @input="$v.current_row.max_usage.$touch"
              v-model="current_row.max_usage"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.current_row.max_usage.$dirty">
              <span
                class="form__alert"
                v-if="!$v.current_row.max_usage.required"
                >{{ $t("validation.required") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.current_row.max_usage.minvalue"
                >{{ $t("validation.minValue") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.current_row.max_usage.maxValue"
                >{{ $t("validation.maxValue") }}</span
              >
            </p>
          </b-form-group>
        </div>
        <div class="row">
          <b-form-group :label="this.$t('start_date')" class="text-6 col-md-6">
            <b-form-datepicker
              id="start-datepicker"
              v-model="current_row.start_date"
              class="mb-2"
            ></b-form-datepicker>
          </b-form-group>
          <b-form-group :label="this.$t('end_date')" class="text-6 col-md-6">
            <b-form-datepicker
              id="end-datepicker"
              v-model="current_row.end_date"
              :min="current_row.start_date"

              class="mb-2"
            ></b-form-datepicker>
          </b-form-group>
        </div>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="updateRaw"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.current_row.$invalid"
          >
            {{ this.$t("update") }}
          </b-button>
          <b-button class="btn btn-primary mt-4" @click="hideEditModal">
            {{ this.$t("cancel") }}</b-button
          >
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3"></div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import vue from "vue";
import { mapGetters, mapActions } from "vuex";
import axios from "axios";
import {
  required,
  numeric,
  minLength,
  maxLength,
  minValue,
  maxValue,
} from "vuelidate/lib/validators";

export default {
  metaInfo: {
    title: "Discount Codes",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  data() {
    return {
      searchtext: "",
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("code"),
          field: "code",
        },
        {
          label: this.$t("customer"),
          field: "user.name",
        },
        {
          label: this.$t("type"),
          field: "type",
        },
        {
          label: this.$t("amount"),
          field: "amount",
        },
        {
          label: "max" + " " + this.$t("usage"),
          field: "max_usage",
        },
        {
          label: this.$t("start_date"),
          field: "start_date",
        },
        {
          label: this.$t("start_end"),
          field: "end_date",
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
      totalRecords: 0,
      from: 1,
      to: 15,
      serverParams: {
        page: 1,
        perPage: 15,
      },
      new_code: {
        restaurant_id : null
      },
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),

        Accept: "application/json",
      },
      types: ["rate", "fixed"],
      users: [],
      loadingRequest: false,
      restaurants: [{ id: null, name: "all restaurants" }],
      current_row: {
        name: "",
        percentage: "",
        id: "",
      },
    };
  },
  validations: {
    new_code: {
      type: {
        required,
      },
      user_id: {
        required,
      },
      amount: {
        required,
        numeric,
        minvalue: minValue(1),
      },
      max_usage: {
        required,
        minvalue: minValue(1),
        maxValue: maxValue(99),
      },
      min_order_price: {
        required,
        numeric,
        minvalue: minValue(1),
      },
      restaurant_id: {
      },
      start_date: {
        required,
      },
      end_date: {
        required,
      },
    },

    current_row: {
      type: {
        required,
      },
      user_id: {
        required,
      },
      amount: {
        required,
        numeric,
        minvalue: minValue(1),
      },
      max_usage: {
        required,
        minvalue: minValue(1),
        maxValue: maxValue(99),
      },
      min_order_price: {
        required,
        numeric,
        minvalue: minValue(1),
      },
      restaurant_id: {
      },
      start_date: {
        required,
      },
      end_date: {
        required,
      },
    },
  },
  // watcher for search term value
  watch: {
    searchtext: function (newval) {
      if (newval.length > 1) {
        this.listData(newval);
      } else {
        this.listData();
      }
    },
  },

  mounted() {
    this.listData();
    this.listUsers();
    this.getRestaurants();
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.listData();
    },
    async onSortChange(params) {
      console.log(params);
      await this.updateParams({sort_by : params[0].field});
      await this.updateParams({sort_type : params[0].type});
      this.listData();
    },
    listData(searchTerm) {
      let url;
      if (searchTerm) {
        url = `discount-codes?searchTerm=${searchTerm}`;
      } else {
        url = `discount-codes`;
      }
      axios
        .get(url, {
          headers: this.headers,
          params: this.serverParams,
        })
        .then((response) => {
          this.rows = response.data.codes.data;
          this.totalRecords = response.data.codes.total;
          this.to = response.data.codes.to || 0;
          this.from = response.data.codes.from || 0;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    listUsers() {
      axios
        .get("users/list/all/customer", {
          headers: this.headers,
        })
        .then((response) => {
          this.users = response.data.users;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    getRestaurants() {
      axios
        .get(`restaurants/all/lite`, { headers: this.headers })
        .then((response) => {
          console.log(response.data);
          this.restaurants = [
            this.restaurants[0],
            ...response.data.restaurants,
          ];
          console.log(this.restaurants);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    createNew() {
      this.loadingRequest = true; // set this before running anything
      this.$v.$touch(); //it will validate all fields

      console.log(this.new_code);
      axios
        .post("/discount-codes", this.new_code, { headers: this.headers })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "create completed successfully");
          this.hideCreateModal();
          this.listData();
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
    showCreateModal() {
      this.new_code = {
        restaurant_id : null
      };
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.$refs["create-modal"].hide();
      this.new_code = {};
    },
    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
    async deleteRaw() {
      this.loadingRequest = true; // set this before running anything

      const headers = {
        Authorization: "Bearer " + localStorage.getItem("token"),
      };
      await axios
        .delete(`discount-codes/${this.current_row.id}`, {
          headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "delete completed successfully");
          this.hideDeleteModal();
          this.listData();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          this.makeToast("warning", "delete failed");
          this.hideDeleteModal();
          this.loadingRequest = false;
        });
    },
    showDeleteModal(tax) {
      this.current_row = tax;
      this.$refs["delete-modal"].show();
    },
    hideDeleteModal() {
      this.current_row = {};
      this.$refs["delete-modal"].hide();
    },
    showEditModal(code) {
      this.current_row = code;
      // this.current_row.percentage = null;
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.$refs["edit-modal"].hide();
      this.current_row = {};
      this.listData();
    },
    updateRaw() {
      this.loadingRequest = true; // set this before running anything
        this.$v.$touch(); //it will validate all fields

      axios
        .patch(`discount-codes/${this.current_row.id}`, this.current_row, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
          },
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "update completed successfully");
          this.hideEditModal();
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
