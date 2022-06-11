<template>
  <div class="main-content">
    <breadcumb :page="$t('addCat')" :folder="$t('datatables')" />
    <!-- forms -->
    <div class="card mb-4">
      <div class="card-body">
        <!-- add category form -->
        <form>
          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label for="picker1">{{ $t("type") }}</label>
              <select class="form-control" v-model="newCategory.selection">
                <option value="single" selected>
                  {{ $t("singleSelction") }}
                </option>
                <option value="multiple">
                  {{ $t("multiSelction") }}
                </option>
              </select>
            </div>

            <div
              class="
                col-md-6
                form-group
                mb-3
                d-flex
                flex-column
                justify-content-end
              "
              v-if="newCategory.selection === 'single'"
            >
              <label class="checkbox checkbox-success">
                <input
                  type="checkbox"
                  v-model="newCategory.is_primary"
                  true-value="1"
                  false-value="0"
                  @change="
                    disabled = !disabled;
                    newCategory.max_selectable =
                      newCategory.is_primary == 1
                        ? 1
                        : newCategory.max_selectable;
                  "
                /><span>{{ $t("primary") }}</span
                ><span class="checkmark"></span>
              </label>
            </div>
          </div>
          <div class="row justify-content-end align-items-center">
            <div class="col-md-5 form-group mb-3">
              <label for="name">{{ $t("name") }}</label>
              <input
                class="form-control"
                id="name"
                type="text"
                v-model="newCategory.name"
              />
            </div>

            <div class="col-md-5 form-group mb-3">
              <label for="selectionNum">{{ $t("maxSelect") }}</label>
              <input
                class="form-control"
                id="selectionNum"
                type="number"
                min="1"
                v-model="newCategory.max_selectable"
                :disabled="disabled && newCategory.selection == 'single'"
              />
            </div>
            <div class="col-md-2">
              <button
                :disabled="loading"
                class="btn btn-primary"
                @click.prevent="addCategory"
              >
                {{ $t("add") }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- add option -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="card-title">{{ $t("addOptionDetail") }}</div>
        <form action="action">
          <div class="form-group row">
            <label
              class="ul-form__label ul-form--margin col-lg-1 col-form-label"
              for="name"
              >{{ $t("itemName") }}</label
            >
            <div class="col-lg-5">
              <input
                class="form-control"
                id="name"
                type="text"
                v-model="newOption.name"
                :disabled="disabledAdded"
              />
            </div>
            <label
              class="ul-form__label ul-form--margin col-lg-1 col-form-label"
              for="price"
              >{{ $t("price") }}</label
            >
            <div class="col-lg-5">
              <input
                class="form-control"
                id="price"
                type="number"
                v-model="newOption.price"
                :disabled="disabledAdded"
              />
            </div>
          </div>

          <div
            class="form-group row"
            v-if="newCategory.selection === 'multiple'"
          >
            <label
              class="ul-form__label ul-form--margin col-lg-1 col-form-label"
              for="minQty"
              >{{ $t("minQty") }}</label
            >
            <div class="col-lg-3">
              <input
                class="form-control"
                id="minQty"
                type="number"
                min="1"
                v-model="newOption.minQty"
                :disabled="disabledAdded"
              />
            </div>
            <label
              class="ul-form__label ul-form--margin col-lg-1 col-form-label"
              for="maxQty"
              >{{ $t("maxQty") }}</label
            >
            <div class="col-lg-3">
              <input
                class="form-control"
                id="maxQty"
                type="number"
                v-model="newOption.maxQty"
                :disabled="disabledAdded"
              />
            </div>
          </div>

          <div class="card-footer">
            <div class="mc-footer">
              <div class="row">
                <div class="col-lg-12">
                  <button
                    class="btn btn-primary m-1"
                    type="button"
                    :disabled="disabledAdded"
                    @click.prevent="addOption"
                  >
                    {{ $t("add") }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- table to display options -->
    <vue-good-table
      :columns="columns"
      :line-numbers="false"
      :search-options="{
        enabled: false,
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
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'">
          <button
            class="loading-btn btn btn-link"
            :disabled="loading"
            @click.prevent="showDeleteModal(props.row)"
          >
            <i class="i-Close-Window text-25 text-danger"></i>
            {{ props.row.button }}
          </button>
        </span>
      </template>
    </vue-good-table>
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
      disabled: false,
      disabledAdded: true,
      option: {
        name: "",
      },
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("items"),
          field: "name",
        },
        {
          label: this.$t("priceTax"),
          field: "price",
        },

        {
          label: this.$t("action"),
          field: "button",
          html: true,
          tdClass: "text-right",
          thClass: "text-right",
        },
      ],
      rows: [],
      newCategory: {
        id: "",
        is_primary: 0,
        max_selectable: "",
        name: "",
        selection: "",
        // enable,disable .. 1--> enable, 0--> disable
        // status: 0,
      },
      newOption: {
        id: "",
        name: "",
        price: "",
        // status: 0,
        maxQty: 1,
        minQty: 1,
      },
      loading: false,

      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),

        Accept: "application/json",
      },
    };
  },

  mounted() {},
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.show();
      this.listCategory();
    },

    // add new category
    addCategory() {
      this.loading = true; // set this before running anything

      axios
        .post(
          "/option-categories",
          {
            name: this.newCategory.name,
            selection: this.newCategory.selection,
            is_primary: this.newCategory.is_primary,
            max_selectable: this.newCategory.max_selectable,
            // status: this.newCategory.status,
          },
          { headers: this.headers }
        )
        .then((response) => {
          this.newCategory.id = response.data.category.id;
          this.makeToast("success", this.$t("successOpertion"));
          this.disabledAdded = false;
          this.loading = false;
        })
        .catch((errors) => {
          this.makeToast("warning", this.$t("failedOpertion"));
          console.log(errors);
          this.loading = false;
        });
    },

    // add new option
    addOption() {
      this.loading = true; // set this before running anything

      axios
        .post(
          "/option-value",
          {
            name: this.newOption.name,
            price: this.newOption.price,
            option_category_id: this.newCategory.id,
            min_count: this.newOption.minQty,
            max_count: this.newOption.maxQty,
            // status: this.newOption.status,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
          this.show();
          this.loading = false;
        })
        .catch((errors) => {
          this.makeToast("warning", this.$t("failedOpertion"));
          console.log(errors);
          this.loading = false;
        });
    },

    // list options
    show() {
      axios
        .get(`option-value/category/${this.newCategory.id}`, {
          headers: this.headers,
        })
        .then((response) => {
          this.rows = response.data.values;
          console.log(this.rows);
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
    showEditModal(option) {
      this.current_option = option;
      console.log(this.current_option);
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.current_option = {};
      this.$refs["edit-modal"].hide();
    },
    updateOption() {
      this.loading = true; // set this before running anything

      axios
        .patch(
          `option-value/${this.current_option.id}`,
          {
            name: this.current_option.name,
            price: this.current_option.price,
            option_category_id: this.option_id,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
          this.hideEditModal();
          this.loading = false;
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", this.$t("failedOpertion"));
          this.hideEditModal();
          this.loading = false;
        });
    },
    async deleteOption() {
      this.loading = true; // set this before running anything

      await axios
        .delete(`option-value/${this.current_option.id}`, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
          this.hideDeleteModal();
          this.show();
          this.loading = false;
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", this.$t("failedOpertion"));
          this.hideDeleteModal();
          this.loading = false;
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
<style></style>
