<template>
  <div class="main-content">
    <breadcumb :page="'FQA'" :folder="this.$t('datatables')" />
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
          {{ this.$t("add") }} FQA
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

    <b-modal ref="create-modal" hide-footer :title="this.$t('create') + 'FQA'">
      <h6 class="text-primary">
        {{ $t("fqaNote") }}
      </h6>

      <div class="d-block mt-3">
        <b-form-group :label="this.$t('question')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.new_fqa.question.$touch"
            v-model="new_fqa.question"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.new_fqa.question.$dirty">
            <span class="form__alert" v-if="!$v.new_fqa.question.required">{{
              $t("validation.required")
            }}</span>
            <span class="form__alert" v-if="!$v.new_fqa.question.minLength">{{
              $t("validation.questionMinLen")
            }}</span>
            <span class="form__alert" v-if="!$v.new_fqa.question.maxLength">{{
              $t("validation.questionMaxLen")
            }}</span>
          </p>
        </b-form-group>
        <b-form-group :label="this.$t('answer')" class="text-6">
          <b-form-textarea
            rows="4"
            class="form-control"
            @input="$v.new_fqa.answer.$touch"
            v-model="new_fqa.answer"
            required
          ></b-form-textarea>
          <p class="errors" v-if="$v.new_fqa.answer.$dirty">
            <span class="form__alert" v-if="!$v.new_fqa.answer.required">{{
              $t("validation.required")
            }}</span>
            <span class="form__alert" v-if="!$v.new_fqa.answer.minLength">{{
              $t("validation.nameMinLen3")
            }}</span>
            <span class="form__alert" v-if="!$v.new_fqa.answer.maxLength">{{
              $t("validation.questionMaxLen")
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
            :disabled="loadingRequest || $v.new_fqa.$invalid"
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
          <strong>{{ current_row.question }}</strong> FQA?
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

    <b-modal ref="edit-modal" hide-footer :title="this.$t('edit') + 'FQA'">
      <div class="d-block">
        <b-form-group :label="this.$t('question')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.current_row.question.$touch"
            v-model="current_row.question"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.current_row.question.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_row.question.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_row.question.minLength"
              >{{ $t("validation.questionMinLen") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_row.question.maxLength"
              >{{ $t("validation.questionMaxLen") }}</span
            >
          </p>
        </b-form-group>

        <b-form-group :label="this.$t('answer')" class="text-6">
          <b-form-textarea
            rows="4"
            class="form-control"
            @input="$v.current_row.answer.$touch"
            v-model="current_row.answer"
            required
          ></b-form-textarea>
          <p class="errors" v-if="$v.current_row.answer.$dirty">
            <span class="form__alert" v-if="!$v.current_row.answer.required">{{
              $t("validation.required")
            }}</span>
            <span class="form__alert" v-if="!$v.current_row.answer.minLength">{{
              $t("validation.nameMinLen3")
            }}</span>
            <span class="form__alert" v-if="!$v.current_row.answer.maxLength">{{
              $t("validation.questionMaxLen")
            }}</span>
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
import axios from "axios";
import { mapGetters } from "vuex";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "FQA",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  data() {
    return {
      searchtext: "",
      fqa: {
        question: "",
        answer: "",
      },
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("question"),
          field: "question",
        },
        {
          label: this.$t("answer"),
          field: "answer",
          width: "500px",
        },
        {
          label: this.$t("createdOn"),
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
      new_fqa: {},
      loadingRequest: false,
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      serverParams: {
        page: 1,
        perPage: 15,
      },
      current_row: {},
    };
  },

  validations: {
    new_fqa: {
      question: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(200),
      },
      answer: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(200),
      },
    },

    current_row: {
      question: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(200),
      },
      answer: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(200),
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
        this.listData(newval);
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
        url = `FQA?searchTerm=${searchTerm}`;
      } else {
        url = `FQA`;
      }
      axios
        .get(url, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
          },
          params: this.serverParams,
        })
        .then((response) => {
          this.rows = response.data.questions.data;
          this.totalRecords = response.data.questions.total;
          this.to = response.data.questions.to || 0;
          this.from = response.data.questions.from || 0;
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

        Accept: "application/json",
        "Content-Type": "application/json",
      };
      axios
        .post(
          "FQA",
          {
            question: this.new_fqa.question,
            answer: this.new_fqa.answer,
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
      this.new_fqa = {};
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_fqa = {};
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
        .delete(`FQA/${this.current_row.id}`, {
          headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
          this.hideDeleteModal();
          this.listData();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          this.makeToast("warning", this.$t("failedOpertion"));
          this.hideDeleteModal();
          this.loadingRequest = false;
        });
    },
    showDeleteModal(fqa) {
      this.current_row = fqa;
      this.$refs["delete-modal"].show();
    },
    hideDeleteModal() {
      this.current_row = {};
      this.$refs["delete-modal"].hide();
    },
    showEditModal(fqa) {
      this.current_row = fqa;
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
          `FQA/${this.current_row.id}`,
          {
            question: this.current_row.question,
            answer: this.current_row.answer,
          },
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token"),
            },
          }
        )
        .then((response) => {
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
.searchInput {
  width: 25em;
}
</style>
