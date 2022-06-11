<template>
  <div class="main-content">
    <breadcumb :page="$t('holidays')" :folder="$t('holidays')" />
    <vue-good-table
      :columns="columns"
      :line-numbers="false"
      :search-options="{ enabled: true, placeholder: 'Search this table' }"
      mode="records"
      :pagination-options="{ enabled: true, perPageDropdownEnabled: false }"
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
    <b-modal ref="create-modal" hide-footer :title="$t('holidays')">
      <div class="d-block">
        <div class="row">
          <b-form-group :label="$t('day')" class="text-6 col-md-6">
            <b-form-select
              class="form-control"
              v-model="new_holiday.day"
              :options="days"
              text-field="value"
              value-field="id"
            >
            </b-form-select>
          </b-form-group>
          <b-form-group :label="$t('month')" class="text-6 col-md-6">
            <b-form-select
              class="form-control"
              v-model="new_holiday.month"
              :options="month"
              text-field="value"
              value-field="id"
            >
            </b-form-select>
          </b-form-group>
        </div>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="createNew"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest"
            >{{ $t("create") }}</b-button
          >
          <b-button
            class="btn btn-primary mt-4"
            @click.prevent="hideCreateModal"
            >{{ $t("cancel") }}</b-button
          >
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3" />
        </div>
      </div>
    </b-modal>
    <b-modal ref="edit-modal" hide-footer :title="$t('holidays')">
      <div class="d-block">
        <div class="row">
          <b-form-group :label="$t('day')" class="text-6 col-md-6">
            <b-form-select
              class="form-control"
              v-model="current_holiday.day"
              :options="days"
              text-field="value"
              value-field="id"
            >
            </b-form-select>
          </b-form-group>
          <b-form-group :label="$t('month')" class="text-6 col-md-6">
            <b-form-select
              class="form-control"
              v-model="current_holiday.month"
              :options="month"
              text-field="value"
              value-field="id"
            >
            </b-form-select>
          </b-form-group>
        </div>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="updateRow"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest"
            >{{ $t("update") }}</b-button
          >
          <b-button
            class="btn btn-primary mt-4"
            @click.prevent="hideCreateModal"
            >{{ $t("cancel") }}</b-button
          >
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3" />
        </div>
      </div>
    </b-modal>
    <b-modal ref="my-modal" hide-footer title="Delete Confirmation">
      <div class="d-block">
        <h3>
          {{ this.$t("deleteCat") }}

          {{ this.$t("day") }}?
        </h3>
      </div>
      <div class="text-right">
        <b-button
          class="btn btn-primary mt-4 mr-3"
          variant="outline-danger"
          @click="deleteRow"
          >{{ this.$t("delete") }}</b-button
        >
        <b-button class="btn btn-primary mt-4" @click="hideDeleteModal">{{
          this.$t("cancel")
        }}</b-button>
      </div>
    </b-modal>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
import VueTimepicker from "vue2-timepicker";
import "vue2-timepicker/dist/VueTimepicker.css";

export default {
  components: { VueTimepicker },
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Setting",
  },
  components: {
    VueTimepicker,
  },
  data() {
    return {
      modalShow: false,
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
          label: this.$t("day"),
          field: "holiday",
        },
        {
          label: this.$t("createdOn"),
          field: "created_at",
          type: "date",
          dateInputFormat: "yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'",
          dateOutputFormat: "yyyy-MM-dd",
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
      resturant_id: window.location.pathname.split("/")[4],
      new_holiday: {},
      days: [
        { value: "1", id: 1 },
        { value: "2", id: 2 },
        { value: "3", id: 3 },
        { value: "4", id: 4 },
        { value: "5", id: 5 },
        { value: "6", id: 6 },
        { value: "7", id: 7 },
        { value: "8", id: 8 },
        { value: "9", id: 9 },
        { value: "10", id: 10 },
        { value: "11", id: 11 },
        { value: "12", id: 12 },
        { value: "13", id: 13 },
        { value: "14", id: 14 },
        { value: "15", id: 15 },
        { value: "16", id: 16 },
        { value: "17", id: 17 },
        { value: "18", id: 18 },
        { value: "19", id: 19 },
        { value: "20", id: 20 },
        { value: "21", id: 21 },
        { value: "22", id: 22 },
        { value: "23", id: 23 },
        { value: "24", id: 24 },
        { value: "25", id: 25 },
        { value: "26", id: 26 },
        { value: "27", id: 27 },
        { value: "28", id: 28 },
        { value: "29", id: 29 },
        { value: "30", id: 30 },
        { value: "31", id: 31 },
      ],
      month: [
        { value: "Jan", id: 1 },
        { value: "Feb", id: 2 },
        { value: "MArc", id: 3 },
        { value: "Abril", id: 4 },
        { value: "May", id: 5 },
        { value: "June", id: 6 },
        { value: "July", id: 7 },
        { value: "Aug", id: 8 },
        { value: "Sep", id: 9 },
        { value: "Oct", id: 10 },
        { value: "Nov", id: 11 },
        { value: "Dec", id: 12 },
      ],
      current_holiday: {},
      loadingRequest: false,
    };
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  mounted() {
    this.listData();
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.listData();
    },
    listData() {
      axios
        .get(`holidays/of-restaurant/${this.resturant_id}`, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data.holidays);
          this.rows = response.data.holidays.map((item) => ({
            ...item,
            holiday: `${item.day} / ${item.month.toLocaleString("en-us", {
              month: "long",
            })}`,
          }));
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showCreateModal() {
      this.new_holiday = {};
      console.log(this.rows);
      (this.worktimes = this.rows), this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_holiday = {};
      this.$refs["create-modal"].hide();
    },
    createNew() {
      this.loadingRequest = true; // set this before running anything

      axios
        .post(
          `/holiday`,
          {
            day: this.new_holiday.day,
            month: this.new_holiday.month,
            restaurant_id: parseInt(this.resturant_id),
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
          console.log(errors.data);
          this.makeToast("warning", "create failed");
          this.hideCreateModal();
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
    addRow() {
      this.worktimes.push({
        day_id: "",
        open_from: "",
        open_to: "",
        takeaway: 0,
        delivery: 0,
        status: 0,
      });
    },
    deleteRow(index) {
      this.worktimes.splice(index, 1);
    },
    showDeleteModal(row) {
      this.current_holiday = row;
      this.$refs["my-modal"].show();
    },
    hideDeleteModal() {
      this.current_holiday = {};
      this.$refs["my-modal"].hide();
    },
    showEditModal(row) {
      this.current_holiday = row;
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.current_holiday = {};
      this.$refs["edit-modal"].hide();
    },
    async deleteRow() {
      this.loadingRequest = true; // set this before running anything

      await axios
        .delete(`holiday/${this.current_holiday.id}`, {
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
          console.log(errors.data);
          this.makeToast("warning", "delete failed");
          this.hideDeleteModal();
          this.loadingRequest = false;
        });
    },
    updateRow() {
      this.loadingRequest = true; // set this before running anything

      axios
        .patch(
          `/holiday/${this.current_holiday.id}`,
          {
            day: this.current_holiday.day,
            month: this.current_holiday.month,
            restaurant_id: parseInt(this.resturant_id),
          },
          { headers: this.headers }
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
.vue__time-picker input.display-time {
  width: 100% !important;
  border-radius: 5px;
}
.vue__time-picker {
  display: block !important;
  width: 100%;
}
.vue__time-picker-dropdown,
.vue__time-picker .dropdown {
  width: 100% !important;
}
.vue__time-picker-dropdown .select-list,
.vue__time-picker .dropdown .select-list {
  width: 100% !important;
}
.delete-btn {
  cursor: pointer;
}
.loading-btn {
  text-decoration: none !important;
  margin: 1px !important;
}
</style>
