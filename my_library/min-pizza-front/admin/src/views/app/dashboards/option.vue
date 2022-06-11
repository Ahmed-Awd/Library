<template>
  <div class="main-content">
    <breadcumb :page="$t('option')" :folder="$t('datatables')" />
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
          @click="$router.push('addCategory')"
        >
          {{ $t("addNew") }}
        </b-button>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'">
          <router-link :to="{ path: `updateOption/${props.row.id}` }">
            <i class="i-Eraser-2 text-22 text-success mr-2"></i>
            {{ props.row.button }}</router-link
          >
          <button
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            @click.prevent="showDeleteModal(props.row)"
          >
            <i class="i-Close-Window text-25 text-danger"></i>
            {{ props.row.button }}
          </button>
        </span>
        <!-- <span v-else-if="props.column.field == 'status'">
          <label class="switch pr-5 switch-success mr-3">
            <input type="checkbox" :checked="props.row.status === 1" /><span
              class="slider"
            ></span>
          </label>
        </span> -->
      </template>
    </vue-good-table>
    <!-- delete option -->
    <div>
      <b-modal ref="my-modal" hide-footer :title="$t('deleteConf')">
        <div class="d-block text-center">
          <h3>{{ $t("deleteOption") }}</h3>
        </div>
        <b-button class="mt-3" block @click="hideDeleteModal">{{
          $t("cancel")
        }}</b-button>
        <b-button
          class="mt-3"
          variant="outline-danger"
          block
          @click="deleteOption"
          >{{ $t("delete") }}
        </b-button>
      </b-modal>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Option",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  inject: ["file_url"],
  data() {
    return {
      option: {
        name: "",
      },
      columns: [
        {
          label: "ID",
          field: "id",
        },
        {
          label: "Option name",
          field: "name",
        },
        {
          label: "Type",
          field: "selection",
        },
        {
          label: "Primary",
          field: "is_primary",
          html: true,
        },
        // {
        //   label: "Status",
        //   field: "status",
        //   html: true,
        // },
        {
          label: "Created on",
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
      image: "",
      current_option: {},
      new_option: {},
      loadingRequest: false,
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),

        Accept: "application/json",
      },
    };
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
    show() {
      axios
        .get("option-categories", {
          headers: this.headers,
        })
        .then((response) => {
          this.rows = response.data.categories;
          console.log(response);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showDeleteModal(option) {
      this.current_option = option;
      this.$refs["my-modal"].show();
    },
    hideDeleteModal() {
      this.current_option = {};
      this.$refs["my-modal"].hide();
    },
    async deleteOption() {
      this.loadingRequest = true; // set this before running anything

      await axios
        .delete(`option-categories/${this.current_option.id}`, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
          this.hideDeleteModal();
          this.show();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", this.$t("failedOpertion"));
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
<style>
.loading-btn {
  text-decoration: none !important;
  margin: 1px !important;
}
</style>
