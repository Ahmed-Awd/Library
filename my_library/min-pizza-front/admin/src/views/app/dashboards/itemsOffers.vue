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
        <router-link tag="a" to="/app/dashboards/itemOfferCategories">
          <b-button variant="primary" class="btn-rounded">
            {{ $t("addNew") }}
          </b-button>
        </router-link>
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
    <b-modal ref="edit-modal" hide-footer :title="$t('edit')">
      <div class="d-block">
        <b-form-group :label="$t('name')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            v-model="current_row.name"
            disabled
          />
        </b-form-group>

        <b-form-group :label="'Original' + ' ' + $t('price')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            v-model="current_row.originalPrice"
            disabled
          />
        </b-form-group>

        <b-form-group :label="$t('price')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            v-model="current_row.new_price"
            @input="$v.current_row.new_price.$touch"
            required
          />
           <p class="errors" v-if="$v.current_row.new_price.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_row.new_price.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_row.new_price.numeric"
              >{{ $t("validation.numeric") }} </span
            >
           </p>
        </b-form-group>
        <b-form-group :label="$t('priority')" class="text-6">
          <b-form-input
            class="form-control"
            type="number"
            v-model="current_row.rank"
            @input="$v.current_row.rank.$touch"
            required
          />
          <p class="errors" v-if="$v.current_row.rank.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_row.rank.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_row.rank.between"
              >{{ $t("validation.rateMinLen") }} 1 {{ $t("and") }} 500</span
            >
          </p>
        </b-form-group>
        <b-form-group :label="$t('start_date')" class="text-6">
          <b-form-datepicker
              class="form-control"
              @input="$v.current_row.start_date.$touch"
              v-model="current_row.start_date"
          ></b-form-datepicker>
          <p class="errors" v-if="$v.current_row.start_date.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_row.start_date.required"
              >{{ $t("validation.required") }}</span
            >
          </p>
        </b-form-group>
        <b-form-group :label="$t('end_date')" class="text-6">
          <b-form-datepicker
              class="form-control"
              @input="$v.current_row.end_date.$touch"
              v-model="current_row.end_date"
              :min="current_row.start_date"
          ></b-form-datepicker>
          <p class="errors" v-if="$v.current_row.end_date.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_row.end_date.required"
              >{{ $t("validation.required") }}</span
            >
          </p>
        </b-form-group>

        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="createAndEditNew"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest"
            >{{ $t("save") }}</b-button
          >
          <b-button
            class="btn btn-primary mt-4"
            @click.prevent="hideEditModal"
            >{{ $t("cancel") }}</b-button
          >
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3" />
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
        new_price: "",
        rank: "",
        start_date: "",
        end_date: "",
      },
      columns: [
        {
          label: this.$t("id"),
          field: "item_id",
        },
        {
          label: this.$t("name"),
          field: "name",
        },
        {
          label: this.$t("image"),
          field: "photo",
          html: true,
        },

        {
          label: this.$t("price"),
          field: "new_price",
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
      current_row: {},
      loadingRequest: false,
    };
  },
  validations: {
    current_row: {
      new_price: {
        required,
        numeric,
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
  },
  mounted() {
    this.listData();
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
        url = `item-offers?searchTerm=${searchTerm}`;
      } else {
        url = `item-offers`;
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
            rank: item.item.rank,
            name: item.item.name,
            originalPrice: item.item.price,
            photo: `<image width="80" height="80" src="${this.file_url}${item.item.image}"/>`,
          }));
          console.log(response.data);

          this.totalRecords = response.data.offers.total;
          this.to = response.data.offers.to || 0;
          this.from = response.data.offers.from || 0;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    createAndEditNew() {
      this.$v.$touch(); //it will validate all fields
      this.loadingRequest = true; // set this before running anything

      const headers = {
        Authorization: "Bearer " + localStorage.getItem("token"),
      };
      axios
        .post(
          "/item-offers",
          {
            item_id: this.current_row.item_id,
            new_price: this.current_row.new_price,
            rank: this.current_row.rank,
            start_date: this.current_row.start_date,
            end_date: this.current_row.end_date,
          },
          { headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "create completed successfully");
          this.hideEditModal();
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
        .delete(`item-offers/${this.current_row.id}`, {
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
  },
};
</script>
<style>
.loading-btn {
  text-decoration: none !important;
  margin: 1px !important;
}
.searchInput {
  width: 25em;
}
</style>
