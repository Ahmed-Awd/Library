<template>
  <div class="main-content">
    <breadcumb :page="$t('sidebar.categories')" :folder="$t('datatables')" />
    <!-- <div class="wrapper"> -->
    <vue-good-table
      :columns="columns"
      :line-numbers="false"
      :search-options="{ enabled: true, placeholder: 'Search this table' }"
      mode="any"
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
          {{ this.$t("addCat") }}
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

    <b-modal ref="create-modal" hide-footer :title="$t('createCategory')">
      <div class="d-block">
        <b-form-group :label="$t('name')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.new_category.name.$touch"
            v-model="new_category.name"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.new_category.name.$dirty">
            <span class="form__alert" v-if="!$v.new_category.name.required">{{
              $t("validation.required")
            }}</span>
          </p>
        </b-form-group>
        <b-form-group :label="$t('image')" class="text-6">
          <b-form-file
            class="form-control"
            accept="image/jpeg, image/png"
            v-model="new_category.image"
          ></b-form-file>
        </b-form-group>
        <b-form-group class="text-6">
          <b-form-checkbox
            v-model="new_category.is_active"
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
            @click.prevent="createCategory"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.new_category.$invalid"
          >
            {{ this.$t("create") }}
          </b-button>
          <b-button class="btn btn-primary mt-4" @click="hideCreateModal">
            {{ this.$t("cancel") }}</b-button
          >
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3"></div>
        </div>
      </div>
    </b-modal>

    <b-modal ref="edit-modal" hide-footer :title="$t('editCategory')">
      <div class="d-block">
        <b-form-group :label="$t('name')" class="text-6">
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.current_category.name.$touch"
            v-model="current_category.name"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.current_category.name.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_category.name.required"
              >{{ $t("validation.required") }}</span
            >
          </p>
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
            :disabled="loadingRequest || $v.current_category.$invalid"
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

    <b-modal ref="my-modal" hide-footer title="Delete Confirmation">
      <div class="d-block">
        <h3>
          {{ this.$t("deleteCat") }}

          <strong>{{ this.current_category.name }}</strong>
          {{ this.$t("category") }}?
        </h3>
      </div>
      <div class="text-right">
        <b-button
          class="btn btn-primary mt-4 mr-3"
          variant="outline-danger"
          @click="deleteCategory"
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
import {maxLength, minLength, required} from "vuelidate/lib/validators";

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
      modalShow: false,
      category: {
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
          label: this.$t("categoryImage"),
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
      rows: [],
      current_category: {
        name: "",
        image: "",
        id: "",
      },
      new_category: {},
      loadingRequest: false,
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
    };
  },
  validations: {
    new_category: {
      name: {
        required,
      },
    },
    current_category: {
      name: {
        required,
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
    show() {
      axios
        .get("admin/categories", {
          headers: this.headers,
          params: this.serverParams,
        })
        .then((response) => {
          this.rows = response.data.categories.map((item) => ({
            ...item,
            photo: `<image width="80" height="80" src="${this.file_url}${item.image}"/>`,
          }));
          console.log(this.rows);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showDeleteModal(category) {
      this.current_category = category;
      this.$refs["my-modal"].show();
    },
    hideDeleteModal() {
      this.current_category = {};
      this.$refs["my-modal"].hide();
    },
    showEditModal(category) {
      this.current_category = category;
      this.current_category.image = null;
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.current_category = {};
      this.$refs["edit-modal"].hide();
    },
    showCreateModal() {
      this.new_category = {
        is_active: 1,
        image: null,
      };
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_category = {};
      this.$refs["create-modal"].hide();
    },
    updateCategory() {
      this.loadingRequest = true; // set this before running anything

      this.$v.$touch(); //it will validate all fields
      let fd;
      fd = new FormData();
      if (this.current_category.name != null) {
        fd.append("name", this.current_category.name);
      }
      if (this.current_category.image != null) {
        fd.append("image", this.current_category.image);
      }
      if (this.current_category.is_active != null) {
        fd.append("is_active", this.current_category.is_active);
      }
      fd.append("_method", "patch");
      axios
        .post(`categories/${this.current_category.id}`, fd, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "update completed successfully");
          this.show();
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
    async deleteCategory() {
      this.loadingRequest = true; // set this before running anything

      await axios
        .delete(`categories/${this.current_category.id}`, {
          headers: this.headers,
        })
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
    createCategory() {
      this.loadingRequest = true; // set this before running anything
      console.log(this.new_category);
      this.$v.$touch(); //it will validate all fields
      let fd;
      fd = new FormData();
      if (this.new_category.name != null) {
        fd.append("name", this.new_category.name);
      }
      if (this.new_category.image != null) {
        fd.append("image", this.new_category.image);
      }
      if (this.new_category.is_active != null) {
        fd.append("is_active", this.new_category.is_active);
      }
      axios
        .post("/categories", fd, { headers: this.headers })
        .then((response) => {
          this.makeToast("success", "create completed successfully");
          this.hideCreateModal();
          this.show();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          console.log("err", this.$v.$invalid);
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
