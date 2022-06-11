<template>
  <div class="main-content">
    <breadcumb :page="$t('menu')" :folder="'Restaurant Menu'" />
    <vue-good-table
      :columns="columns"
      :line-numbers="false"
      :search-options="{ enabled: true, placeholder: $t('searchTable') }"
      mode="records"
      :pagination-options="{ enabled: true, perPageDropdownEnabled: false }"
      styleClass="tableOne vgt-table"
      :selectOptions="{
        enabled: false,
        selectionInfoClass: 'table-alert__box',
      }"
      :rows="rows"
    >
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'">
          <button
            class="btn btn-outline-primary text-black text-10 p-1 mr-2"
            :disabled="loading"
            @click.prevent="showCreateModal(props.row)"
          >
            {{ $t("addOffer") }}
            {{ props.row.button }}
          </button>
        </span>
      </template>
    </vue-good-table>

    <b-modal ref="create-modal" hide-footer :title="$t('create')">
      <div class="d-block" v-if="current_menuitem">
        <b-form-group :label="$t('name')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            v-model="current_row.name"
            disabled
          />
        </b-form-group>
        <b-form-group :label="$t('price')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            v-model="current_row.item_price"
            disabled
          />
        </b-form-group>

        <b-form-group :label="$t('new') + ' ' + $t('price')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.new_offer.new_price.$touch"
            v-model="new_offer.new_price"
            required
          />
          <p class="errors" v-if="$v.new_offer.new_price.$dirty">
            <span
              class="form__alert"
              v-if="!$v.new_offer.new_price.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.new_offer.new_price.numeric"
              >{{ $t("validation.numeric") }} </span
            >
           </p>
        </b-form-group>

        <b-form-group :label="$t('priority')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.new_offer.rank.$touch"
            v-model="new_offer.rank"
            required
          />
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
            @click.prevent="createNew"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.new_offer.$invalid"
          >
            {{ $t("save") }}</b-button
          >
          <b-button
            class="btn btn-primary mt-4"
            @click.prevent="hideCreateModal"
          >
            {{ $t("cancel") }}
          </b-button>
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3" />
        </div>
      </div>
    </b-modal>
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
  between,
} from "vuelidate/lib/validators";
export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Menu",
  },
  inject: ["file_url"],
  data() {
    return {
      modalShow: false,
      offer: {
        id: "",
        new_price: "",
        rank: "",
        start_date: "",
        end_date: "",
      },
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),

        Accept: "application/json",
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
          label: this.$t("image"),
          field: "photo",
          html: true,
        },
        {
          label: this.$t("description"),
          field: "description",
          width: "500px",
        },
        {
          label: this.$t("price"),
          field: "item_price",
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
      image: {},
      current_menuitem: {
        category_id: "",
        name: "",
        price: "",
        id: "",
      },
      new_offer: {},
      current_row: {},
      loadingRequest: false,
      category_id: window.location.pathname.split("/")[4],
    };
  },
  validations: {
    new_offer: {
      new_price: {
        required,
        numeric,
      },
      rank: {
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
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  mounted() {
    this.listData();
    console.log(this.current_menuitem);
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.listData();
    },
    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },

    listData() {
      axios
        .get(`menu/category/items/${this.category_id}`, {
          headers: this.headers,
        })
        .then((response) => {
          // this.totalRecords = response.data.items.total;
          // this.to = response.data.items.to || 0;
          // this.from = response.data.items.from || 0;

          console.log(response.data.items);
          this.rows = response.data.items.map((item) => ({
            ...item,
            photo: `<image width="60" height="60" src="${this.file_url}${item.image}"/>`,
            // description: item.description.substring(0, 100),
            item_price: `${item.price} ${item.currency}`,
          }));
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    createNew() {
      this.$v.$touch(); //it will validate all fields
      this.loadingRequest = true; // set this before running anything

      axios
        .post(
          "item-offers",
          {
            item_id: this.new_offer.id,
            new_price: this.new_offer.new_price,
            rank: this.new_offer.rank,
            start_date: this.new_offer.start_date,
            end_date: this.new_offer.end_date,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "create completed successfully");
          this.hideCreateModal();
          this.listData();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          this.makeToast("warning", "create failed");
          this.hideCreateModal();
          this.loadingRequest = false;
        });
    },

    showCreateModal(item) {
      this.current_row = item;
      this.new_offer = this.offer;
      this.new_offer.id = this.current_row.id;
      console.log(this.new_offer);
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.current_row = {};
      this.new_offer = {};
      this.$refs["create-modal"].hide();
    },
  },
};
</script>
