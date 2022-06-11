<template>
  <div class="main-content">
    <breadcumb :page="$t('offers')" :folder="$t('datatables')" />
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
    <b-modal ref="create-modal" hide-footer :title="$t('create')">
      <div class="d-block">
        <b-form-group :label="$t('restaurant')" class="text-6">
          <b-form-select
            class="form-control"
            v-model="new_offer.restaurant_id"
            :options="restaurants"
            text-field="name"
            value-field="id"
          >
          </b-form-select>
          <p class="errors" v-if="$v.new_offer.restaurant_id.$dirty">
            <span
              class="form__alert"
              v-if="!$v.new_offer.restaurant_id.required"
              >{{ $t("validation.required") }}</span
            >
          </p>
        </b-form-group>
        <b-form-group :label="$t('discount_rate')" class="text-6">
          <b-form-input
            class="form-control"
            type="number"
            min="1"
            max="99"
            @input="$v.new_offer.rate.$touch"
            v-model="new_offer.rate"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.new_offer.rate.$dirty">
            <span
              class="form__alert"
              v-if="!$v.new_offer.rate.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.new_offer.rate.between"
              >{{ $t("validation.rateMinLen") }} 1 {{ $t("and") }} 99</span
            >
          </p>
        </b-form-group>
        <b-form-group :label="$t('priority')" class="text-6">
          <b-form-input
            class="form-control"
            type="number"
            @input="$v.new_offer.rate.$touch"
            v-model="new_offer.rank"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.new_offer.rank.$dirty">
            <span
              class="form__alert"
              v-if="!$v.new_offer.rank.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.new_offer.rank.between"
              >{{ $t("validation.rateMinLen") }} 1 {{ $t("and") }} 500</span
            >
          </p>
        </b-form-group>
        <b-form-group :label="$t('start_date')" class="text-6">
          <b-form-datepicker
              class="form-control"
              @input="$v.new_offer.start_date.$touch"
              v-model="new_offer.start_date"
          ></b-form-datepicker>
          <p class="errors" v-if="$v.new_offer.start_date.$dirty">
            <span
              class="form__alert"
              v-if="!$v.new_offer.start_date.required"
              >{{ $t("validation.required") }}</span
            >
          </p>
        </b-form-group>
        <b-form-group :label="$t('end_date')" class="text-6">
          <b-form-datepicker
              class="form-control"
              @input="$v.new_offer.end_date.$touch"
              v-model="new_offer.end_date"
              :min="new_offer.start_date"
          ></b-form-datepicker>
          <p class="errors" v-if="$v.new_offer.end_date.$dirty">
            <span
              class="form__alert"
              v-if="!$v.new_offer.end_date.required"
              >{{ $t("validation.required") }}</span
            >
          </p>
        </b-form-group>

        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="createAndEditNew(new_offer)"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.new_offer.$invalid"
          >
            {{ $t("save") }}
          </b-button>
          <b-button class="btn btn-primary mt-4" @click="hideCreateModal">{{
            $t("cancel")
          }}</b-button>
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3"></div>
        </div>
      </div>
    </b-modal>

    <b-modal ref="edit-modal" hide-footer :title="$t('edit')">
      <div class="d-block">
        <b-form-group :label="$t('restaurant')" class="text-6">
          <b-form-select
            class="form-control"
            v-model="current_row.restaurant_id"
            :options="restaurants"
            text-field="name"
            value-field="id"
          >
          </b-form-select>
        </b-form-group>
        <b-form-group :label="$t('discount_rate')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.current_row.rate.$touch"
            v-model="current_row.rate"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.current_row.rate.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_row.rate.between"
              >{{ $t("validation.rateMinLen") }} 1 {{ $t("and") }} 99</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_row.rate.numeric"
              >{{ $t("validation.numeric") }}</span
            >
          </p>
        </b-form-group>
          <b-form-group :label="$t('priority')" class="text-6">
            <b-form-input
              class="form-control"
              type="text"
              v-model="current_row.rank"
              @input="$v.current_row.rank.$touch"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.current_row.rank.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_row.rank.between"
              >{{ $t("validation.rateMinLen") }} 1 {{ $t("and") }} 500</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_row.rank.numeric"
              >{{ $t("validation.numeric") }}</span
            >
          </p>
          </b-form-group>
          <b-form-group :label="$t('start_date')" class="text-6">
            <b-form-input
              class="form-control"
              type="date"
              v-model="current_row.start_date"
              required
            ></b-form-input>
          </b-form-group>
          <b-form-group :label="$t('end_date')" class="text-6">
            <b-form-input
              class="form-control"
              type="date"
              v-model="current_row.end_date"
              required
            ></b-form-input>
          </b-form-group>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="createAndEditNew(current_row)"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.current_row.$invalid"

          >
            {{ $t("save") }}
          </b-button>
          <b-button class="btn btn-primary mt-4" @click="hideEditModal">{{
            $t("cancel")
          }}</b-button>
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3"></div>
        </div>
      </div>
    </b-modal>

    <b-modal ref="delete-modal" hide-footer title="Delete Confirmation">
      <div class="d-block">
        <h3>Are you sure you want to delete the offer ?</h3>
      </div>
      <div class="text-right">
        <b-button class="mt-3 mr-3" @click="hideDeleteModal">{{
          $t("cancel")
        }}</b-button>
        <b-button class="mt-3" variant="outline-danger" @click="deleteRaw">
          {{ $t("delete") }}
        </b-button>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import axios from "axios";
import {
  required,
  email,
  sameAs,
  numeric,
  minLength,
  maxLength,
  between,
} from "vuelidate/lib/validators";
export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Offers",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  data() {
    return {
      searchtext: "",
      offer: {
        id: "",
        rate: "",
        rank: "",
        start_date: "",
        end_date: "",
      },
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("name"),
          field: "restaurant.name",
        },
        {
          label: this.$t("image"),
          field: "photo",
          html: true,
        },
        {
          label: this.$t("start_date"),
          field: "start_date",
          type: "date",
          dateInputFormat: "yyyy-MM-dd",
          dateOutputFormat: "dd-MM-yyyy",
        },
        {
          label: this.$t("end_date"),
          field: "end_date",
          type: "date",
          dateInputFormat: "yyyy-MM-dd",
          dateOutputFormat: "dd-MM-yyyy",
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
      restaurants: [],
      new_offer: {},
      current_row: {},
      loadingRequest: false,
    };
  },
  validations: {
    new_offer: {
      restaurant_id: {
        required,
      },
      rate: {
        required,
        numeric,
        between: between(1, 99),
      },
      rank: {
        required,
        numeric,
        between: between(1, 500),
      },
      start_date: {
        required,
      },
      end_date: {
        required,
      },
    },
    current_row: {
      rate: {
        numeric,
        between: between(1, 99),
      },
      rank: {
        numeric,
        between: between(1, 500),
      },
    },
  },
  mounted() {
    this.listData();
    this.getRestaurants();
  },
  // watcher for search term value
  watch: {
    searchtext: function (newval) {
      if (newval.length > 1) {
        this.listData();
      }else{
        this.listData();
      }
    },
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
        url = `rest-offers?searchTerm=${searchTerm}`;
      } else {
        url = `rest-offers`;
      }
      axios
        .get(url, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
          },
          params: this.serverParams,
        })
        .then((response) => {
          this.rows = response.data.offers.data.map((item) => ({
            ...item,
            rank: item.restaurant.rank,
            photo: `<image width="80" height="80" src="${this.file_url}${item.restaurant.image}"/>`,
          }));
          this.totalRecords = response.data.offers.total;
          this.to = response.data.offers.to || 0;
          this.from = response.data.offers.from || 0;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    createAndEditNew(offer) {
      this.$v.$touch(); //it will validate all fields
      const headers = {
        Authorization: "Bearer " + localStorage.getItem("token"),
      };
      this.loadingRequest = true; // set this before running anything

      axios

        .post(
          "/rest-offers",
          {
            restaurant_id: offer.restaurant_id,
            rate: offer.rate,
            rank: offer.rank,
            start_date: offer.start_date,
            end_date: offer.end_date,
          },
          { headers }
        )
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
      this.new_offer = {};
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_offer = {};
      this.$refs["create-modal"].hide();
    },

    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
    async deleteRaw() {
      const headers = {
        Authorization: "Bearer " + localStorage.getItem("token"),
      };
      this.loadingRequest = true; // set this before running anything

      await axios
        .delete(`rest-offers/${this.current_row.id}`, {
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
    showDeleteModal(offer) {
      this.current_row = offer;
      this.$refs["delete-modal"].show();
    },
    hideDeleteModal() {
      this.current_row = {};
      this.$refs["delete-modal"].hide();
    },
    showEditModal(offer) {
      this.current_row = offer;
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.current_row = {};
      this.$refs["edit-modal"].hide();
    },
    getRestaurants() {
      axios
        .get(`restaurants/all/lite`, { headers: this.headers })
        .then((response) => {
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
  padding: 5px !important;
}
.searchInput {
  width: 25em;
}
</style>
