<template>
  <div class="main-content">
    <breadcumb :page="$t('setting')" :folder="$t('setting')" />
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
      <!--      <div slot="table-actions" class="mb-3">-->
      <!--        <b-button-->
      <!--            variant="primary"-->
      <!--            class="btn-rounded"-->
      <!--            @click="showCreateModal"-->
      <!--        >-->
      <!--          {{ $t('addNew') }}-->
      <!--        </b-button>-->
      <!--      </div>-->
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
        </span>
      </template>
    </vue-good-table>
    <b-modal ref="create-modal" hide-footer :title="$t('restaurantSetting')">
      <div class="d-block">
        <b-form-group label="Delivery Percentage" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            v-model="new_setting.taken_percentage_from_delivery"
            required
          />
        </b-form-group>

        <div class="row">
          <b-form-group label="Takeaway Precentage" class="text-6 col-md-12">
            <b-form-input
              class="form-control"
              type="text"
              v-model="new_setting.taken_percentage_from_takeaway"
              required
            />
          </b-form-group>
          <b-form-group label="Max Delivery Distance" class="text-6 col-md-12">
            <b-form-input
              class="form-control"
              type="text"
              v-model="new_setting.max_delivery_distance"
              required
            />
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
    <b-modal ref="edit-modal" hide-footer title="Edit Menu Item">
      <div class="d-block" v-if="current_setting">
        <b-form-group label="Delivery Percentage" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            v-model="current_setting.taken_percentage_from_delivery"
            required
          />
        </b-form-group>

        <div class="row">
          <b-form-group label="Takeaway Precentage" class="text-6 col-md-12">
            <b-form-input
              class="form-control"
              type="text"
              v-model="current_setting.taken_percentage_from_takeaway"
              required
            />
          </b-form-group>
          <b-form-group label="Max Delivery Distance" class="text-6 col-md-12">
            <b-form-input
              class="form-control"
              type="text"
              v-model="current_setting.max_delivery_distance"
              required
            />
          </b-form-group>
        </div>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="updateItem"
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
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Setting",
  },
  data() {
    return {
      modalShow: false,
      // from: 1,
      // to: 15,
      // serverParams: {
      //   page: 1,
      //   perPage: 15,
      // },
      // totalRecords: 0,
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
          label: this.$t("deliveryPercentage"),
          field: "taken_percentage_from_delivery",
        },
        {
          label: this.$t("takeawayPercentage"),
          field: "taken_percentage_from_takeaway",
        },
        {
          label: this.$t("delivery_distance"),
          field: "max_delivery_distance",
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
      current_setting: {
        taken_percentage_from_delivery: "",
        taken_percentage_from_takeaway: "",
        max_delivery_distance: "",
        id: "",
      },
      new_setting: {},
      loadingRequest: false,
      resturant_id: window.location.pathname.split("/")[4],
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
      console.log(window.location.pathname.split("/")[4]);
      axios
        .get(`restaurants/setting/${this.resturant_id}`, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data.setting);
          this.rows = response.data.setting.map((item) => ({
            ...item,
          }));
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showCreateModal() {
      this.new_menuitem = {};
      console.log(this.current_setting);
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_menuitem = {};
      this.$refs["create-modal"].hide();
    },
    showDeleteModal(item) {
      this.current_setting = item;
      this.$refs["my-modal"].show();
    },
    hideDeleteModal() {
      this.current_setting = {};
      this.$refs["my-modal"].hide();
    },
    showEditModal(item) {
      this.current_setting = item;
      // this.current_setting.image = null;
      console.log(this.current_setting);
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.current_setting = {};
      this.$refs["edit-modal"].hide();
    },
    createNew() {
      this.loadingRequest = true; // set this before running anything

      let fd;
      fd = new FormData();
      fd.append(
        "taken_percentage_from_delivery",
        this.new_setting.taken_percentage_from_delivery
      );
      fd.append(
        "taken_percentage_from_takeaway",
        this.new_setting.taken_percentage_from_takeaway
      );
      fd.append(
        "max_delivery_distance",
        this.new_setting.max_delivery_distance
      );

      axios
        .post(`/restaurants/setting/${this.resturant_id}`, fd, {
          headers: this.headers,
        })
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
    updateItem() {
      this.loadingRequest = true; // set this before running anything

      let fd;
      fd = new FormData();
      fd.append(
        "taken_percentage_from_delivery",
        this.current_setting.taken_percentage_from_delivery
      );
      fd.append(
        "taken_percentage_from_takeaway",
        this.current_setting.taken_percentage_from_takeaway
      );
      fd.append(
        "max_delivery_distance",
        this.current_setting.max_delivery_distance
      );

      axios
        .post(`/restaurants/setting/${this.resturant_id}`, fd, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "update completed successfully");
          this.hideEditModal();
          this.listData();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", "update failed");
          this.loadingRequest = false;
        });
    },
  },
};
</script>

<style>
.loading-btn {
  text-decoration: none !important;
  padding: 1px !important;
}
</style>
