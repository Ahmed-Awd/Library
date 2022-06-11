<template>
  <div class="main-content">
    <breadcumb :page="$t('workTime')" :folder="$t('workTime')" />
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
          {{ $t("edit") }} {{ $t("workTime") }}
        </b-button>
      </div>
    </vue-good-table>
    <b-modal ref="create-modal" hide-footer title="Edit WorkTimes">
      <div class="d-block">
        <div v-for="(worktime, index) in worktimes">
          <div class="row">
            <div class="col-md-11">
              <div class="row">
                <b-form-group :label="$t('day')" class="text-6 col-md-4">
                  <b-form-select
                    class="form-control"
                    v-model="worktime.day_id"
                    :options="days"
                    text-field="name"
                    value-field="id"
                  >
                  </b-form-select>
                </b-form-group>
                <b-form-group :label="$t('from')" class="text-6 col-md-4">
                  <vue-timepicker
                    format="HH:mm:ss"
                    v-model="worktime.open_from"
                    value=""
                  ></vue-timepicker>
                </b-form-group>
                <b-form-group label="To" class="text-6 col-md-4">
                  <vue-timepicker
                    format="HH:mm:ss"
                    v-model="worktime.open_to"
                    value=""
                  ></vue-timepicker>
                </b-form-group>
              </div>
            </div>
            <div class="col-md-1 align-self-center pl-0">
              <i
                class="i-Close-Window text-21 text-danger delete-btn"
                @click="deleteRow(index)"
              ></i>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <label class="checkbox checkbox-success">
                <input
                  type="checkbox"
                  value="1"
                  v-model="worktime.takeaway"
                  v-bind:true-value="1"
                  v-bind:false-value="0"
                />
                <span>Takeaway</span>
                <span class="checkmark"></span>
              </label>
            </div>
            <div class="col-sm-4">
              <label class="checkbox checkbox-success">
                <input
                  type="checkbox"
                  value="1"
                  v-model="worktime.delivery"
                  v-bind:true-value="1"
                  v-bind:false-value="0"
                />
                <span>Delivery</span>
                <span class="checkmark"></span>
              </label>
            </div>
          </div>
        </div>
        <button class="btn btn-danger m-1" type="button" @click="addRow()">
          {{ $t("add") }}
        </button>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="createNew"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest"
            >Create</b-button
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
          field: "day.name",
        },
        {
          label: this.$t("openFrom"),
          field: "open_from",
        },
        {
          label: this.$t("openTo"),
          field: "open_to",
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
      image: {},
      worktimes: [
        {
          day_id: "",
          open_from: "",
          open_to: "",
          takeaway: 0,
          delivery: 0,
          status: 1,
        },
      ],
      resturant_id: window.location.pathname.split("/")[4],
      new_worktime: {},
      days: [],
      loadingRequest: false,
    };
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  mounted() {
    this.listData();
    this.getDayes();
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
        .get(`restaurants/worktime/${this.resturant_id}`, {
          headers: this.headers,
        })
        .then((response) => {
          this.rows = response.data.worktimes;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showCreateModal() {
      this.new_worktime = {};
      console.log(this.rows);
      (this.worktimes = this.rows), this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_worktime = {};
      this.$refs["create-modal"].hide();
    },
    createNew() {
      this.loadingRequest = true; // set this before running anything

      let timesArr = this.worktimes;

      // return;
      axios
        .post(
          `/restaurants/worktime/${this.resturant_id}`,
          { Worktimes: timesArr },
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
    getDayes() {
      axios
        .get("days", { headers: this.headers })
        .then((response) => {
          this.days = response.data.days;
          console.log(this.days);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    addRow() {
      this.worktimes.push({
        day_id: "",
        open_from: "",
        open_to: "",
        takeaway: 0,
        delivery: 0,
        status: 1,
      });
    },
    deleteRow(index) {
      this.worktimes.splice(index, 1);
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
</style>
