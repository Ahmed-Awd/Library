<template>
  <div class="main-content">
    <breadcumb :page="'Taxes'" :folder="'Datatables'" />
    <!-- <div class="wrapper"> -->
    <vue-good-table
      :columns="columns"
      :line-numbers="false"
      :search-options="{
        enabled: true,
        placeholder: $t('searchTable'),
      }"
      :pagination-options="{
        enabled: true,
        perPage: 15,
        mode: 'records',
        perPageDropdownEnabled: false,
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
          {{ this.$t("add") }} {{ this.$t("tax") }}
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
      :title="this.$t('create') + ' ' + this.$t('tax')"
    >
      <div class="d-block">
        <b-form-group
          :label="this.$t('tax') + ' ' + this.$t('name')"
          class="text-6"
        >
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.new_tax.name.$touch"
            v-model="new_tax.name"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.new_tax.name.$dirty">
            <span class="form__alert" v-if="!$v.new_tax.name.required">{{
              $t("validation.required")
            }}</span>
            <span class="form__alert" v-if="!$v.new_tax.name.minLength">{{
              $t("validation.nameMinLen3")
            }}</span>
            <span class="form__alert" v-if="!$v.new_tax.name.maxLength">{{
              $t("validation.questionMaxLen")
            }}</span>
          </p>
        </b-form-group>
        <b-form-group :label="this.$t('value')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.new_tax.percentage.$touch"
            v-model="new_tax.percentage"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.new_tax.percentage.$dirty">
            <span class="form__alert" v-if="!$v.new_tax.percentage.required">{{
              $t("validation.required")
            }}</span>
            <span class="form__alert" v-if="!$v.new_tax.percentage.numeric">{{
              $t("validation.numeric")
            }}</span>
            <span class="form__alert" v-if="!$v.new_tax.percentage.between">{{
              $t("validation.between")
            }}</span>
          </p>
        </b-form-group>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="createNew"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.new_tax.$invalid"
          >
            {{ this.$t("save") }}
          </b-button>
          <b-button class="btn btn-primary mt-4" @click="hideCreateModal">{{
            this.$t("cancel")
          }}</b-button>
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3"></div>
        </div>
      </div>
    </b-modal>

    <b-modal ref="delete-modal" hide-footer :title="this.$t('deleteConfirm')">
      <div class="d-block">
        <h3>
          {{ this.$t("deleteCat") }}
          <strong>{{ this.current_row.name }}</strong> {{ this.$t("tax") }} ?
        </h3>
      </div>
      <div class="text-right">
        <b-button class="mt-3 mr-3" @click="hideDeleteModal">{{
          this.$t("cancel")
        }}</b-button>
        <b-button class="mt-3" variant="outline-danger" @click="deleteRaw"
          >Delete
        </b-button>
      </div>
    </b-modal>

    <b-modal
      ref="edit-modal"
      hide-footer
      :title="this.$t('edit') + ' ' + this.$t('tax')"
    >
      <div class="d-block">
        <b-form-group :label="this.$t('name')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.current_row.name.$touch"
            v-model="current_row.name"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.current_row.name.$dirty">
            <span class="form__alert" v-if="!$v.current_row.name.required">{{
              $t("validation.required")
            }}</span>
            <span class="form__alert" v-if="!$v.current_row.name.minLength">{{
              $t("validation.nameMinLen3")
            }}</span>
            <span class="form__alert" v-if="!$v.current_row.name.maxLength">{{
              $t("validation.questionMaxLen")
            }}</span>
          </p>
        </b-form-group>
        <b-form-group :label="this.$t('value')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.current_row.percentage.$touch"
            v-model="current_row.percentage"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.current_row.percentage.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_row.percentage.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_row.percentage.numeric"
              >{{ $t("validation.numeric") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_row.percentage.between"
              >{{ $t("validation.between") }}</span
            >
          </p>
        </b-form-group>
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
          <b-button class="btn btn-primary mt-4" @click="hideEditModal">{{
            this.$t("cancel")
          }}</b-button>
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3"></div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import axios from "axios";
import {
  required,
  numeric,
  minLength,
  maxLength,
  between,
} from "vuelidate/lib/validators";
export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Taxes",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  data() {
    return {
      tax: {
        name: "",
        percentage: "",
      },
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("name") + " " + this.$t("tak"),
          field: "name",
        },
        {
          label: this.$t("value"),
          field: "percentage",
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
      new_tax: {},
      loadingRequest: false,

      current_row: {
        name: "",
        percentage: "",
        id: "",
      },
    };
  },

  validations: {
    new_tax: {
      name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(200),
      },
      percentage: {
        required,
        numeric,
        between: between(1, 99),
      },
    },
    current_row: {
      name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(200),
      },
      percentage: {
        required,
        numeric,
        between: between(1, 99),
      },
    },
  },

  mounted() {
    this.listData();
  },
  methods: {
    listData() {
      axios
        .get("taxes", {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
          },
        })
        .then((response) => {
          this.rows = response.data.taxes;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    createNew() {
      this.loadingRequest = true; // set this before running anything

      this.$v.$touch(); //it will validate all fields

      const headers = {
        Authorization: "Bearer " + localStorage.getItem("token"),
      };
      axios
        .post(
          "/taxes",
          {
            name: this.new_tax.name,
            percentage: this.new_tax.percentage,
          },
          { headers }
        )
        .then((response) => {
          this.makeToast("success", this.$t("successOpertion"));
          this.hideCreateModal();
          this.listData();
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
    showCreateModal() {
      this.new_tax = {};
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_tax = {};
      this.$refs["create-modal"].hide();
      this.listData();
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
        .delete(`taxes/${this.current_row.id}`, {
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
    showEditModal(tax) {
      this.current_row = tax;
      // this.current_row.percentage = null;
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.current_row = {};
      this.$refs["edit-modal"].hide();
      this.listData();
    },
    updateRaw() {
      this.loadingRequest = true; // set this before running anything
      this.$v.$touch(); //it will validate all fields
      axios
        .patch(
          `taxes/${this.current_row.id}`,
          {
            name: this.current_row.name,
            percentage: this.current_row.percentage,
          },
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token"),
            },
          }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
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
  },
};
</script>
<style>
.loading-btn {
  text-decoration: none !important;
  margin: 1px !important;
}
</style>
