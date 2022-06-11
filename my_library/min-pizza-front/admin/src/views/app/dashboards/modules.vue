<template>
  <div class="main-content">
    <breadcumb :page="$t('modules')" :folder="$t('datatables')" />
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

    <div>
      <b-modal
        ref="edit-modal"
        hide-footer
        :title="this.$t('edit') + ' ' + this.$t('module')"
      >
        <div class="d-block">
          <b-form-group :label="this.$t('key')" class="text-6">
            <b-form-input
              class="form-control"
              type="text"
              v-model="current_module.key"
              disabled
              required
            ></b-form-input>
          </b-form-group>
          <b-form-group :label="this.$t('status')" class="text-6">
            <button
              :class="
                current_module.value === 1 ? 'btn btn-info' : 'btn btn-danger'
              "
              @click="statusValue"
            >
              {{ current_module.value === 1 ? this.$t("on") : this.$t("off") }}
            </button>
            <span class="ml-2 text-muted">{{ $t("changeStutus") }}</span>
          </b-form-group>
          <b-button
            type="submit"
            tag="button"
            @click.prevent="changeStatus"
            class="btn-rounded btn-block mt-4"
            variant="primary mt-2"
            :disabled="loadingRequest"
          >
            {{ this.$t("update") }}
          </b-button>
          <b-button
            class="btn-rounded btn-block mt-4"
            block
            @click="hideEditModal"
          >
            {{ this.$t("cancel") }}</b-button
          >
          <div v-once class="typo__p" v-if="loading">
            <div class="spinner sm spinner-primary mt-3"></div>
          </div>
        </div>
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
    title: "Sliders",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  inject: ["file_url"],
  data() {
    return {
      module: {
        key: "",
        value: "",
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
          label: this.$t("status"),
          field: "status",
          html: true,
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
      current_slider: {},
      current_module: {},
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
        .get("module", {
          headers: this.headers,
          params: this.serverParams,
        })
        .then((response) => {
          this.rows = response.data.modules.map((item) => ({
            ...item,
            status:
              item.value === 1
                ? `<span class="true">${this.$t("on")}</span>`
                : `<span class="false">${this.$t("off")}</span>`,
          }));
          //   this.rows = response.data.sliders;
          console.log(this.rows);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    showEditModal(modules) {
      this.current_module = modules;
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.current_module = {};
      this.$refs["edit-modal"].hide();
    },

    statusValue() {
      this.current_module.value === 1
        ? (this.current_module.value = 0)
        : (this.current_module.value = 1);
      console.log(this.current_module.value);
    },

    changeStatus() {
      this.loadingRequest = true; // set this before running anything

      axios
        .post(
          `module`,
          {
            key: this.current_module.key,
            value: this.current_module.value,
          },
          { headers: this.headers }
        )
        .then((response) => {
          this.makeToast("success", "status switched");
          this.hideEditModal();
          this.show();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          this.makeToast("warning", errors.response.data);
          this.hideEditModal();
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
.true {
  background-color: blue;
  color: #fff;
  padding: 5px;
  border-radius: 5px;
}
.false {
  background-color: red;
  color: #fff;
  padding: 5px;
  border-radius: 5px;
}
.loading-btn {
  text-decoration: none !important;
  margin: 1px !important;
}
</style>
