<template>
  <div class="main-content">
    <breadcumb :page="$t('contact_us')" :folder="$t('datatables')" />

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
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'">
          <button
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            @click.prevent="showEditModal(props.row)"
          >
            <i class="i-Repeat text-25 text-success mr-2"></i>
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

    <div>
      <b-modal ref="edit-modal" hide-footer :title="$t('replay')">
        <div class="d-block">
          <b-form-group :label="$t('email')" class="text-6">
            <b-form-input
              class="form-control"
              type="email"
              v-model="current_row.email"
              disabled
              readonly
            ></b-form-input>
          </b-form-group>
          <b-form-group :label="$t('client_message')" class="text-6">
            <b-form-textarea
              class="form-control"
              rows="3"
              v-model="current_row.message"
              disabled
              readonly
            ></b-form-textarea>
          </b-form-group>
          <b-form-group :label="$t('subject')" class="text-6">
            <b-form-input
              class="form-control"
              type="text"
              @input="$v.current_row.replay_subject.$touch"
              v-model="current_row.replay_subject"
              required
            ></b-form-input>
            <p class="errors" v-if="$v.current_row.replay_subject.$dirty">
              <span
                class="form__alert"
                v-if="!$v.current_row.replay_subject.required"
                >{{ $t("validation.required") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.current_row.replay_subject.minLength"
                >{{ $t("validation.replyMinLen") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.current_row.replay_subject.maxLength"
                >{{ $t("validation.replyMaxLen") }}</span
              >
            </p>
          </b-form-group>
          <b-form-group :label="$t('message')" class="text-6">
            <b-form-textarea
              class="form-control"
              rows="5"
              @input="$v.current_row.replay_message.$touch"
              v-model="current_row.replay_message"
              required
            ></b-form-textarea>
            <p class="errors" v-if="$v.current_row.replay_message.$dirty">
              <span
                class="form__alert"
                v-if="!$v.current_row.replay_message.required"
                >{{ $t("validation.required") }}</span
              >
              <span
                class="form__alert"
                v-if="!$v.current_row.replay_message.minLength"
                >{{ $t("validation.rplyMsg") }}</span
              >
            </p>
          </b-form-group>
          <b-button
            type="submit"
            tag="button"
            @click.prevent="replay"
            class="btn-rounded btn-block mt-4"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.current_row.$invalid"
          >
            {{ $t("replay") }}
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
      <b-modal ref="my-modal" hide-footer :title="$t('deleteConfirm')">
        <div class="d-block text-center">
          <h3>
            {{ $t("sureMsg") }}
            <strong></strong> ?
          </h3>
        </div>
        <b-button class="mt-3" block @click="hideDeleteModal"
          >{{ $t("cancel") }}
        </b-button>
        <b-button class="mt-3" variant="outline-danger" block @click="Destroy"
          >{{ $t("delete") }}
        </b-button>
      </b-modal>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
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
      searchtext: "",
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
          label: this.$t("email"),
          field: "email",
        },
        {
          label: this.$t("phone"),
          field: "phone",
        },
        {
          label: this.$t("status"),
          field: "status",
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
      current_row: {},
      loadingRequest: false,

      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
    };
  },
  watch: {
    searchtext: function (newval) {
      if (newval.length > 1) {
        this.show(newval);
      } else {
        this.show();
      }
    },
  },
  validations: {
    current_row: {
      replay_subject: {
        required,
        minLength: minLength(4),
        maxLength: maxLength(100),
      },
      replay_message: {
        required,
        minLength: minLength(5),
      },
    },
  },
  mounted() {
    this.show();
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
        url = `feedback?searchTerm=${searchTerm}`;
      } else {
        url = `feedback`;
      }
      axios
        .get(url, { headers: this.headers, params: this.serverParams })
        .then((response) => {
          this.totalRecords = response.data.feedbacks.total;
          this.to = response.data.feedbacks.to || 0;
          this.from = response.data.feedbacks.from || 0;
          this.rows = response.data.feedbacks.data.map((i) => {
            var item = {};
            item = i;
            if (i.new == 1) {
              item.status = "<span style='color:forestgreen'>New</span>";
            } else {
              item.status = "<span style='color:darkred'>Seen</span>";
            }
            return item;
          });

          console.log(this.rows);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showDeleteModal(row) {
      this.current_row = row;
      this.$refs["my-modal"].show();
    },
    hideDeleteModal() {
      this.current_row = {};
      this.$refs["my-modal"].hide();
    },
    showEditModal(row) {
      this.current_row = row;
      this.markRead(row);
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.current_row = {};
      this.show();
      this.$refs["edit-modal"].hide();
    },
    async Destroy() {
      this.loadingRequest = true; // set this before running anything

      await axios
        .delete(`feedback/${this.current_row.id}`, { headers: this.headers })
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
    replay() {
      this.loadingRequest = true; // set this before running anything

      this.$v.$touch(); //it will validate all fields

      axios
        .post(
          `/feedback/replay/${this.current_row.id}`,
          {
            replay_subject: this.current_row.replay_subject,
            replay_message: this.current_row.replay_message,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
          this.hideEditModal();
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
    markRead(row) {
      axios
        .get(`feedback/${row.id}`, { headers: this.headers })
        .then((response) => {
          console.log(response.data);
          row.new = 0;
        })
        .catch((errors) => {
          console.log(errors.data);
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
<style scoped>
.loading-btn {
  text-decoration: none !important;
  margin: 1px !important;
}
.searchInput {
  width: 25em;
}
</style>
