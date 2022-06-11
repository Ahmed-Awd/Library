<template>
  <div class="main-content">
    <breadcumb :page="$t('admin')" :folder="$t('datatables')" />
    <input
      class="form-control mb-2 searchInput"
      :placeholder="$t('searchTable')"
      type="text"
      v-model="searchtext"
    />
    <!-- start table -->

    <vue-good-table
      :columns="columns"
      mode="remote"
      :totalRows="totalRecords"
      :line-numbers="false"
      :search-options="{
        enabled: true,
        placeholder: 'Search this table',
        externalQuery: searchtext,
      }"
      @on-sort-change="onSortChange"
      @on-page-change="onPageChange"
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
      :rows="admins"
    >
      <div slot="table-actions" class="mb-3">
        <b-button
          variant="primary"
          class="btn-rounded"
          to="/app/dashboards/createAdmin"
          :disabled="loadingRequest"
        >
          {{ this.$t("addNew") }}
        </b-button>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'">
          <router-link
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            :to="{ path: `/app/dashboards/updateAdmin/${props.row.id}` }"
          >
            <i class="i-Eraser-2 text-25 text-success mr-2"></i>
            {{ props.row.button }}
          </router-link>
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
    <!-- end table -->

    <!-- start edit modal -->
    <!-- <b-modal ref="edit-modal" hide-footer :title="$t('editCategory')">
      <div class="d-block">
        <b-form-group :label="$t('name')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            v-model="current_category.name"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group :label="$t('image')" class="text-6">
          <b-form-file
            class="form-control"
            accept="image/jpeg, image/png"
            v-model="current_category.image"
            required
          ></b-form-file>
        </b-form-group>
        <b-form-group :label="$t('isActive')" class="text-6">
          <b-form-checkbox
            v-model="current_category.is_active"
            name="is_active"
            value="1"
            unchecked-value="0"
          >
            {{ this.$t("isCategoryAvailable") }}
          </b-form-checkbox>
        </b-form-group>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="updateCategory"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loading"
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
    </b-modal> -->
    <!-- end edit modal -->

    <!-- start delete modal -->
    <b-modal ref="my-modal" hide-footer :title="$t('deleteConfirm')">
      <div class="d-block">
        <h3>
          {{ this.$t("deleteMsg") }}

          <strong>{{ this.current_admin.name }}</strong>
          {{ this.$t("admin") }}?
        </h3>
      </div>
      <div class="text-right">
        <b-button
          class="btn btn-primary mt-4 mr-3"
          variant="outline-danger"
          @click="deleteAdmin"
          >{{ this.$t("delete") }}</b-button
        >
        <b-button class="btn btn-primary mt-4" @click="hideDeleteModal">{{
          this.$t("cancel")
        }}</b-button>
      </div>
    </b-modal>
    <!-- end delete modal -->
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Admin",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  inject: ["file_url"],
  data() {
    return {
      searchtext: "",
      modalShow: false,
      from: 1,
      to: 15,
      totalRecords: 0,
      serverParams: {
        page: 1,
        perPage: 15,
      },
      admin: {
        name: "",
        is_active: "",
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
          label: this.$t("adminImage"),
          field: "photo",
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
      admins: [],
      current_admin: {
        name: "",
        image: "",
        id: "",
      },
      new_admin: {},
      country_code: "",
      countries: [],
      cities: [],
      selectedcountry: "",

      permissionVal: [],
      permissionsData: [],

      loadingRequest: false,
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
    };
  },
  // watcher for search term value
  watch: {
    searchtext: function (newval) {
      if (newval.length > 1) {
        this.show(newval);
      } else {
        this.show();
      }
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
      await this.updateParams({sort_by: params[0].field});
      await this.updateParams({sort_type: params[0].type});
      this.show();
    },
    show(searchTerm) {
      let url;
      if (searchTerm) {
        url = `admin/user?searchTerm=${searchTerm}`;
      } else {
        url = `admin/user`;
      }
      axios
        .get(url, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
          },
          params: this.serverParams,
        })
        .then((response) => {
          this.admins = response.data.admins.data.map((item) => ({
            ...item,
            photo: `<image width="80" height="80" src="${this.file_url}${item.image}"/>`,
          }));
          this.totalRecords = response.data.admins.total;
          this.to = response.data.admins.to || 0;
          this.from = response.data.admins.from || 0;
          console.log(response.data.admins);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    showDeleteModal(admin) {
      this.current_admin = admin;
      this.$refs["my-modal"].show();
    },

    hideDeleteModal() {
      this.current_admin = {};
      this.$refs["my-modal"].hide();
    },

    // delete admin
    async deleteAdmin() {
      this.loadingRequest = true; // set this before running anything

      await axios
        .delete(`admin/user/${this.current_admin.id}`, {
          headers: this.headers,
        })
        .then((response) => {
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
  padding: 5px !important;
}
.searchInput {
  width: 25em;
}
</style>
