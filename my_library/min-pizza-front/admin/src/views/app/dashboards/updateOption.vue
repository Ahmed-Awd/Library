<template>
  <div class="main-content">
    <breadcumb :page="$t('updateOption')" :folder="$t('datatables')" />
    <!-- forms -->
    <div class="card mb-4">
      <div class="card-body">
        <!-- update category form -->
        <form>
          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label for="picker1">{{ $t("type") }}</label>
              <select
                class="form-control"
                disabled
                v-model="category_data.selection"
              >
                <option value="single" :selected="category_data.selection">
                  {{ $t("singleSelction") }}
                </option>
                <option value="multiple" :selected="category_data.selection">
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
            >
              <label class="checkbox checkbox-success">
                <input
                  type="checkbox"
                  v-model="category_data.is_primary"
                  true-value="1"
                  false-value="0"
                  @change="
                    disabled = !disabled;
                    category_data.max_selectable =
                      category_data.is_primary == 1
                        ? 1
                        : category_data.max_selectable;
                  "
                /><span>{{ $t("primary") }}</span
                ><span class="checkmark"></span>
              </label>
            </div>
            <!-- <div
              class="
                col-md-6
                form-group
                mb-3
                d-flex
                flex-column
                justify-content-end
              "
            >
              <label class="switch pr-5 switch-success mr-3"
                ><span>{{ $t("enable/disable") }}</span>
                <input
                  type="checkbox"
                  v-model="category_data.status"
                  true-value="1"
                  false-value="0"
                  :checked="category_data.status === 1"
                /><span class="slider"></span>
              </label>
            </div> -->
          </div>
          <div class="row justify-content-end align-items-center">
            <div class="col-md-5 form-group mb-3">
              <label for="name">{{ $t("name") }}</label>
              <input
                class="form-control"
                id="name"
                type="text"
                v-model="category_data.name"
              />
            </div>

            <div class="col-md-5 form-group mb-3">
              <label for="selectionNum">{{ $t("maxSelect") }}</label>
              <input
                class="form-control"
                id="selectionNum"
                type="number"
                min="1"
                v-model="category_data.max_selectable"
                :disabled="disabled"
              />
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary" @click.prevent="updateCategory">
                {{ $t("save") }}
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
            <div class="col-lg-3">
              <input
                class="form-control"
                id="name"
                type="text"
                v-model="newOption.name"
              />
            </div>
            <label
              class="ul-form__label ul-form--margin col-lg-1 col-form-label"
              for="price"
              >{{ $t("price") }}</label
            >
            <div class="col-lg-3">
              <input
                class="form-control"
                id="price"
                type="number"
                v-model="newOption.price"
              />
            </div>

            <!-- <div class="col-lg-3">
              <div class="input-group mb-2">
                <label class="switch pr-5 switch-success mr-3"
                  ><span>{{ $t("enable/disable") }}</span>
                  <input
                    type="checkbox"
                    v-model="newOption.status"
                    true-value="1"
                    false-value="0"
                  /><span class="slider"></span>
                </label>
              </div>
            </div> -->
          </div>

          <div class="form-group row" v-if="category_data.is_primary === 0">
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

    <!-- <div class="wrapper"> -->
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
          <a href="" @click.prevent="showEditModal(props.row)">
            <i class="i-Eraser-2 text-22 text-success mr-2"></i>
            {{ props.row.button }}</a
          >
          <a href="" @click.prevent="showDeleteModal(props.row)">
            <i class="i-Close-Window text-22 text-danger"></i>
            {{ props.row.button }}</a
          >
        </span>
      </template>
    </vue-good-table>
    <!-- delete modal -->
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
    <!-- edit modal -->
    <div>
      <b-modal ref="edit-modal" hide-footer :title="$t('edit')">
        <div class="d-block">
          <b-form-group :label="$t('optionName')" class="text-6">
            <b-form-input
              class="form-control"
              type="text"
              v-model="current_option.name"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group :label="$t('price')" class="text-6">
            <b-form-input
              class="form-control"
              type="number"
              v-model="current_option.price"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group :label="$t('minQty')" class="text-6">
            <b-form-input
              class="form-control"
              type="number"
              v-model="current_option.min_count"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group :label="$t('maxQty')" class="text-6">
            <b-form-input
              class="form-control"
              type="number"
              v-model="current_option.max_count"
              required
            ></b-form-input>
          </b-form-group>

          <b-button
            type="submit"
            tag="button"
            @click.prevent="updateOption"
            class="btn-rounded btn-block mt-4"
            variant="primary mt-2"
            :disabled="loading"
          >
            {{ $t("update") }}
          </b-button>
          <b-button
            class="btn-rounded btn-block mt-4"
            block
            @click="hideEditModal"
            >{{ $t("cancel") }}</b-button
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
    title: "Option",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  inject: ["file_url"],
  data() {
    return {
      disabled: true,
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
      category: [],
      image: "",
      current_option: {},
      new_option: {},
      category_data: {
        id: "",
        is_primary: 0,
        max_selectable: "",
        name: "",
        selection: "",
        // enable,disable .. 1--> enable, 0--> disable
        // status: "",
      },
      newOption: {
        id: "",
        name: "",
        price: "",
        // status: 0,
        maxQty: 1,
        minQty: 1,
      },
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),

        Accept: "application/json",
      },
      option_id: this.$route.params.id,
    };
  },
  mounted() {
    this.show();
    console.log(this.option_id);
    this.listCategory();
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.show();
      this.listCategory();
    },
    // list category data
    listCategory() {
      axios
        .get(`option-categories/${this.option_id}`, {
          headers: this.headers,
        })
        .then((response) => {
          this.category = response.data.category;
          this.category_data.name = this.category.name;
          this.category_data.selection = this.category.selection;
          this.category_data.is_primary = this.category.is_primary;
          this.category_data.max_selectable = this.category.max_selectable;
          // this.category_data.status = this.category.status;
          console.log(this.category.is_primary, this.category.max_selectable);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    // update category
    updateCategory() {
      axios
        .put(
          `option-categories/${this.option_id}`,
          {
            name: this.category_data.name,
            selection: this.category_data.selection,
            is_primary: this.category_data.is_primary,
            // status: this.category_data.status,
            max_selectable: this.category_data.max_selectable,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
          this.hideEditModal();
        })
        .catch((errors) => {
          console.log(errors);
          this.makeToast("warning", this.$t("failedOpertion"));
          this.hideEditModal();
        });
    },

    // add new option
    addOption() {
      axios
        .post(
          "/option-value",
          {
            name: this.newOption.name,
            price: this.newOption.price,
            option_category_id: this.option_id,
            min_count: 1,
            max_count: 1,
            // status: this.newOption.status,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
          this.show();
        })
        .catch((errors) => {
          this.makeToast("warning", this.$t("failedOpertion"));
          console.log(errors);
        });
    },

    // list options
    show() {
      axios
        .get(`option-value/category/${this.option_id}`, {
          headers: this.headers,
        })
        .then((response) => {
          this.rows = response.data.values;
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
      axios
        .patch(
          `option-value/${this.current_option.id}`,
          {
            name: this.current_option.name,
            price: this.current_option.price,
            option_category_id: this.option_id,
            min_count: this.current_option.min_count,
            max_count: this.current_option.max_count,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
          this.hideEditModal();
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", this.$t("failedOpertion"));
          this.hideEditModal();
        });
    },
    async deleteOption() {
      await axios
        .delete(`option-value/${this.current_option.id}`, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
          this.hideDeleteModal();
          this.show();
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", this.$t("failedOpertion"));
          this.hideDeleteModal();
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
