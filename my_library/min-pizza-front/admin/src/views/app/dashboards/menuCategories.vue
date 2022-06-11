<template>
  <div class="main-content">
    <breadcumb
      :page="this.$t('sidebar.menu') + ' ' + this.$t('sidebar.categories')"
      :folder="this.$t('datatables')"
    />
    <vue-good-table
      :columns="columns"
      :line-numbers="false"
      :search-options="{ enabled: true, placeholder: $t('searchTable') }"
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
          >{{ this.$t("addNew") }}</b-button
        >
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'" class="d-inline-flex">
          <router-link
            :to="{ path: `/app/dashboards/restaurantMenu/${props.row.id}` }"
            class="btn btn-outline-primary text-black text-10 p-1 mr-2"
          >
            {{ $t("items") }}
            <!-- {{ props.row.id }} -->
          </router-link>
          <a @click.prevent="showCategoryModal(props.row)">
            <i class="i-Information text-22 text-primary mr-2" />
            {{ props.row.button }}</a
          >
          <a @click.prevent="showEditModal(props.row)">
            <i class="i-Eraser-2 text-22 text-primary mr-2" />
            {{ props.row.button }}</a
          >
          <a @click.prevent="showDeleteModal(props.row)">
            <i class="i-Close-Window text-22 text-danger" />
            {{ props.row.button }}</a
          >
        </span>
      </template>
    </vue-good-table>

    <b-modal
      ref="create-modal"
      hide-footer
      :title="this.$t('create') + ' ' + this.$t('category')"
    >
      <div class="d-block">
        <b-form-group
          :label="this.$t('category') + ' ' + this.$t('name')"
          class="text-6"
        >
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.new_category.name.$touch"
            v-model="new_category.name"
            required
          />
          <p class="errors" v-if="$v.new_category.name.$dirty">
            <span class="form__alert" v-if="!$v.new_category.name.required">{{
              $t("validation.required")
            }}</span>
            <span class="form__alert" v-if="!$v.new_category.name.minLength">{{
              $t("validation.nameMinLen")
            }}</span>
            <span class="form__alert" v-if="!$v.new_category.name.maxLength">{{
              $t("validation.nameMaxLen")
            }}</span>
          </p>
        </b-form-group>
        <b-form-group :label="this.$t('description')" class="text-6">
          <b-form-textarea
            class="form-control"
            type="text"
            rows="4"
            @input="$v.new_category.description.$touch"
            v-model="new_category.description"
            required
          />
          <p class="errors" v-if="$v.new_category.description.$dirty">
            <span
              class="form__alert"
              v-if="!$v.new_category.description.required"
              >{{ $t("validation.required") }}</span
            >
          </p>
        </b-form-group>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="createCategory"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loading || $v.new_category.$invalid"
            >{{ this.$t("create") }}</b-button
          >
          <b-button
            class="btn btn-primary mt-4"
            @click.prevent="hideCreateModal"
            >{{ this.$t("cancel") }}</b-button
          >
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3" />
        </div>
      </div>
    </b-modal>

    <b-modal ref="delete-modal" hide-footer :title="this.$t('deleteConfirm')">
      <div class="d-block">
        <h3>
          {{ this.$t("deleteCat") }}
          <strong>{{ this.current_category.name }}</strong>
          {{ this.$t("category") }} ?
        </h3>
      </div>
      <div class="text-right">
        <b-button
          class="btn btn-primary mt-4 mr-3"
          variant="outline-danger"
          @click="deleteCategory"
          >{{ this.$t("delete") }}</b-button
        >
        <b-button
          class="btn btn-primary mt-4"
          @click.prevent="hideDeleteModal"
          >{{ this.$t("create") }}</b-button
        >
      </div>
    </b-modal>

    <b-modal
      ref="edit-modal"
      hide-footer
      :title="this.$t('update') + ' ' + this.$t('category')"
    >
      <div class="d-block">
        <b-form-group
          :label="this.$t('category') + ' ' + this.$t('name')"
          class="text-6"
        >
          <b-form-input
            class="form-control"
            type="text"
            @input="$v.current_category.name.$touch"
            v-model="current_category.name"
            required
          />
          <p class="errors" v-if="$v.current_category.name.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_category.name.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_category.name.minLength"
              >{{ $t("validation.nameMinLen") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_category.name.maxLength"
              >{{ $t("validation.nameMaxLen") }}</span
            >
          </p>
        </b-form-group>
        <b-form-group :label="this.$t('description')" class="text-6">
          <b-form-textarea
            class="form-control"
            type="text"
            rows="4"
            v-model="current_category.description"
            required
          />
        </b-form-group>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="updateCategory"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loading || $v.current_category.$invalid"
            >{{ this.$t("update") }}</b-button
          >
          <b-button
            class="btn btn-primary mt-4"
            @click.prevent="hideEditModal"
            >{{ this.$t("cancel") }}</b-button
          >
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3" />
        </div>
      </div>
    </b-modal>

    <b-modal ref="category-modal" hide-footer :title="this.$t('category')">
      <div class="d-block">
        <b-card no-body class="overflow-hidden" v-if="current_category.name">
          <div class="text-left">
            <b-card-body>
              <b-card-text>
                <div class="text-left">
                  <h4>
                    <strong>{{ this.$t("name") }}: </strong
                    >{{ current_category.name }}
                  </h4>
                </div>
              </b-card-text>
            </b-card-body>
          </div>
          <div class="text-justify">
            <b-card-body :title="this.$t('description')">
              {{ this.current_category.description }}
            </b-card-body>
          </div>
        </b-card>
        <div class="text-right">
          <b-button
            class="btn btn-primary mt-4 mr-3"
            variant="outline-danger"
            @click.prevent="hideCategoryModal"
            >{{ this.$t("close") }}</b-button
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
import { maxLength, minLength, required } from "vuelidate/lib/validators";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Menu Categories",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  data() {
    return {
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
          label: this.$t("description"),
          field: "description",
          width: "500px",
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
      category: {
        name: "",
        description: "",
      },
      new_category: {},
      current_category: {
        name: "",
        description: "",
      },
      restaurant_id: this.$route.params.restaurant,
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),

        Accept: "application/json",
      },
    };
  },

  validations: {
    new_category: {
      name: {
        required,
        minLength: minLength(2),
        maxLength: maxLength(50),
      },
      description: {
        required,
      },
    },
    current_category: {
      name: {
        required,
        minLength: minLength(2),
        maxLength: maxLength(50),
      },
    },
  },

  mounted() {
    this.show();
  },
  methods: {
    show() {
      axios
        .get(`menu/restaurant/categories/${this.restaurant_id}`, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.rows = response.data.categories.map((item) => ({
            ...item,
          }));
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
    showCategory() {
      axios
        .get(`menu/category/${this.current_category.id}`, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
    createCategory() {
      let fd;
      fd = new FormData();
      fd.append("name", this.new_category.name);
      fd.append("description", this.new_category.description);
      axios
        .post(`menu/category/${this.restaurant_id}`, fd, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "create completed successfully");
          this.hideCreateModal();
          this.show();
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", "create failed");
          this.hideCreateModal();
        });
    },
    updateCategory() {
      axios
        .patch(
          `menu/category/${this.current_category.id}`,
          {
            name: this.current_category.name,
            description: this.current_category.description,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "update completed successfully");
          this.hideEditModal();
          this.show();
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", "update failed");
        });
    },
    async deleteCategory() {
      await axios
        .delete(`menu/category/${this.current_category.id}`, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "delete completed successfully");
          this.hideDeleteModal();
          this.show();
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", "delete failed");
          this.hideDeleteModal();
        });
    },
    showDeleteModal(category) {
      this.current_category = category;
      this.$refs["delete-modal"].show();
    },
    hideDeleteModal() {
      this.current_category = {};
      this.$refs["delete-modal"].hide();
    },
    showEditModal(category) {
      this.current_category = category;
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.show();
      this.current_category = {};
      this.$refs["edit-modal"].hide();
    },
    showCreateModal() {
      this.new_category = {};
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_category = {};
      this.$refs["create-modal"].hide();
    },
    showCategoryModal(category) {
      this.current_category = category;
      this.showCategory();
      this.$refs["category-modal"].show();
    },
    hideCategoryModal() {
      this.current_category = {};
      this.$refs["category-modal"].hide();
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
.d-inline-flex {
  display: inline-flex;
  align-items: center;
}
</style>
