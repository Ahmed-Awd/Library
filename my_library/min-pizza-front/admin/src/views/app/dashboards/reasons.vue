<template>
  <div class="main-content">
    <breadcumb :page="this.$t('reasons')" :folder="this.$t('datatables')" />
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
          {{ this.$t("add") }} {{ this.$t("reason") }}
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
      :title="this.$t('create') + ' ' + this.$t('reason')"
    >
      <div class="d-block">
        <b-form-group :label="this.$t('reason')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.new_reason.name.$touch"
            v-model="new_reason.name"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.new_reason.name.$dirty">
            <span
              class="form__alert"
              v-if="!$v.new_reason.name.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.new_reason.name.minLength"
              >{{ $t("validation.rplyMsg") }}</span
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
            :disabled="loadingRequest || $v.new_reason.$invalid"
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
          {{ this.$t("deleteCat") }} <strong>{{ current_row.reason }}</strong>
          {{ this.$t("reason") }} ?
        </h3>
      </div>
      <div class="text-right">
        <b-button class="mt-3 mr-3" @click="hideDeleteModal">{{
          this.$t("cancel")
        }}</b-button>
        <b-button class="mt-3" variant="outline-danger" @click="deleteRaw"
          >{{ this.$t("delete") }}
        </b-button>
      </div>
    </b-modal>
    <b-modal
      ref="edit-modal"
      hide-footer
      :title="this.$t('edit') + ' ' + this.$t('reason')"
    >
      <div class="d-block">
        <b-form-group :label="this.$t('reason')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.current_row.reason.$touch"
            v-model="current_row.reason"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.current_row.reason.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_row.reason.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_row.reason.minLength"
              >{{ $t("validation.rplyMsg") }}</span
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
import vue from "vue";
import { mapGetters, mapActions } from "vuex";
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
    title: "Reasons",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  data() {
    return {
      reason: {
        name: "",
      },
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("reason"),
          field: "reason",
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
      loadingRequest: false,
      new_reason: {},
      current_row: {
        name: "",
        id: "",
      },
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),

        Accept: "application/json",
      },
    };
  },
  validations: {
    new_reason: {
      name: {
        required,
        minLength: minLength(5),
      }
    },
    current_row: {
      reason: {
        required,
        minLength: minLength(5),
      }
    },
  },
  mounted() {
    this.listData();
  },
  methods: {
    listData() {
      axios
        .get("reasons", {
          headers: this.headers,
        })
        .then((response) => {
          this.rows = response.data.reasons;
          console.log(this.rows);
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
          "/reasons",
          {
            reason: this.new_reason.name,
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
    showCreateModal() {
      this.new_reason = {};
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_reason = {};
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

      await axios
        .delete(`reasons/${this.current_row.id}`, {
          headers: this.headers,
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
    showDeleteModal(reason) {
      this.current_row = reason;
      console.log(this.current_row);
      this.$refs["delete-modal"].show();
    },
    hideDeleteModal() {
      this.current_row = {};
      this.$refs["delete-modal"].hide();
    },
    showEditModal(reason) {
      this.current_row = reason;
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.current_row = {};
      this.$refs["edit-modal"].hide();
      this.listData();
    },
    updateRaw() {
      this.$v.$touch(); //it will validate all fields
      this.loadingRequest = true; // set this before running anything

      axios
        .patch(
          `reasons/${this.current_row.id}`,
          {
            reason: this.current_row.reason,
          },
          {
            headers: this.headers,
          }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "update completed successfully");
          this.listData();
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
              this.loadingRequest = false;
            }
          
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
