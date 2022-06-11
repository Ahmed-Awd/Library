<template>
  <div class="main-content">
    <breadcumb
      :page="this.$t('sidebar.menu')"
      :folder="this.$t('restaurant') + ' ' + this.$t('sidebar.menu')"
    />
    <vue-good-table
      :columns="columns"
      :line-numbers="false"
      :search-options="{ enabled: true, placeholder: this.$t('searchTable') }"
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
        >
          {{ this.$t("addNew") }}
        </b-button>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'">
          <a href="" @click.prevent="showEditModal(props.row)">
            <i class="i-Eraser-2 text-20 text-success mr-2"></i>
            {{ props.row.button }}</a
          >
          <a href="" @click.prevent="showDeleteModal(props.row)">
            <i class="i-Close-Window text-20 text-danger"></i>
            {{ props.row.button }}</a
          >
        </span>
      </template>
    </vue-good-table>

    <!-- create modal -->

    <b-modal
      ref="create-modal"
      hide-footer
      :title="
        this.$t('addNew') +
        ' ' +
        this.$t('sidebar.menu') +
        ' ' +
        this.$t('item')
      "
    >
      <!-- tabs -->
      <div>
        <b-tabs content-class="mt-3">
          <b-tab :title="$t('menuInfo')" active>
            <div class="d-block">
              <b-form-input
                class="form-control"
                type="text"
                v-model="category_id"
                hidden
              />
              <b-form-group
                :label="this.$t('item') + ' ' + this.$t('name')"
                class="text-6"
              >
                <b-form-input
                  class="form-control"
                  type="text"
                  @input="$v.$touch()"
                  v-model="new_menuitem.name"
                  required
                />
                <p class="errors" v-if="$v.new_menuitem.name.$dirty">
                  <span
                    class="form__alert"
                    v-if="!$v.new_menuitem.name.required"
                    >{{ $t("validation.required") }}</span
                  >
                  <span
                    class="form__alert"
                    v-if="!$v.new_menuitem.name.minLength"
                    >{{ $t("validation.nameMinLen") }}</span
                  >
                  <span
                    class="form__alert"
                    v-if="!$v.new_menuitem.name.maxLength"
                    >{{ $t("validation.nameMaxLen") }}</span
                  >
                </p>
              </b-form-group>
              <b-form-group
                :label="this.$t('item') + ' ' + this.$t('image')"
                class="text-6"
              >
                <b-form-file
                  class="form-control"
                  accept="image/jpeg, image/png"
                  v-model="new_menuitem.image"
                  required
                ></b-form-file>
              </b-form-group>
              <div class="row">
                <b-form-group :label="this.$t('price')" class="text-6 col-md-6">
                  <b-form-input
                    class="form-control"
                    type="number"
                    min="1"
                    @input="$v.new_menuitem.price.$touch"
                    v-model="new_menuitem.price"
                    required
                  />
                  <p class="errors" v-if="$v.new_menuitem.price.$dirty">
                    <span
                      class="form__alert"
                      v-if="!$v.new_menuitem.price.required"
                      >{{ $t("validation.required") }}</span
                    >
                  </p>
                </b-form-group>
                <b-form-group
                  :label="this.$t('currency')"
                  class="text-6 col-md-6"
                >
                  <b-form-input
                    class="form-control"
                    type="text"
                    v-model="currency"
                    disabled
                    required
                  />
                </b-form-group>
              </div>
              <b-form-group :label="this.$t('description')" class="text-6">
                <b-form-textarea
                  class="form-control"
                  type="text"
                  rows="4"
                  v-model="new_menuitem.description"
                  required
                />
              </b-form-group>
              <div class="row">
                <b-form-group :label="this.$t('tax')" class="text-6 col-md-6">
                  <b-form-select
                    class="form-control"
                    v-model="new_menuitem.tax_id"
                    :options="taxes"
                    text-field="name"
                    value-field="id"
                  >
                  </b-form-select>
                </b-form-group>
                <b-form-group
                  :label="'Alcohol' + ' ' + this.$t('percentage')"
                  class="text-6 col-md-6"
                >
                  <b-form-input
                    class="form-control"
                    type="text"
                    v-model="new_menuitem.alcohol_percentage"
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
                  :disabled="loading || $v.new_menuitem.$invalid"
                  >{{ this.$t("create") }}
                </b-button>
                <b-button
                  class="btn btn-primary mt-4"
                  @click.prevent="hideCreateModal"
                  >{{ this.$t("cancel") }}
                </b-button>
              </div>
              <div v-once class="typo__p" v-if="loading">
                <div class="spinner sm spinner-primary mt-3" />
              </div>
            </div>
          </b-tab>

          <!-- option category -->
          <b-tab
            :title="$t('option')"
            :disabled="new_menuitem.option_template_id != null"
          >
            <label for="" class="col-form-label">{{
              $t("availableOptiopns")
            }}</label>
            <multiselect
              :multiple="true"
              id="id"
              v-model="optionCatValAdd"
              :options="optionCategories"
              tag-placeholder="Add this as new tag"
              placeholder="Search or add a tag"
              label="name"
              track-by="id"
              :taggable="true"
            >
            </multiselect>
          </b-tab>

          <!-- option template -->
          <b-tab
            :title="$t('optionTemp')"
            :disabled="optionCatValAdd.length != 0"
          >
            <b-form-group
              :label="this.$t('availableTemplates')"
              class="text-6 col-md-6"
            >
              <div class="d-flex justify-content-end">
                <b-form-select
                  class="form-control"
                  v-model="new_menuitem.option_template_id"
                  :options="optionTemplates"
                  text-field="name"
                  value-field="id"
                >
                </b-form-select>
                <i
                  class="i-Close-Window text-15 text-danger ml-2"
                  @click="deleteOptionTemplateAdd()"
                ></i>
              </div>
            </b-form-group>
          </b-tab>
        </b-tabs>
      </div>
    </b-modal>

    <!-- edit modal -->
    <b-modal
      ref="edit-modal"
      hide-footer
      :title="
        this.$t('edit') + ' ' + this.$t('sidebar.menu') + ' ' + this.$t('item')
      "
    >
      <!-- tabs -->
      <div>
        <b-tabs content-class="mt-3">
          <b-tab :title="$t('menuInfo')" active>
            <div class="d-block" v-if="current_menuitem">
              <b-form-input
                class="form-control"
                type="text"
                v-model="category_id"
                hidden
              />
              <b-form-group
                :label="this.$t('item') + ' ' + this.$t('name')"
                class="text-6"
              >
                <b-form-input
                  class="form-control"
                  type="text"
                  @input="$v.$touch()"
                  v-model="current_menuitem.name"
                  required
                />
                <p class="errors" v-if="$v.current_menuitem.name.$dirty">
                  <span
                    class="form__alert"
                    v-if="!$v.current_menuitem.name.required"
                    >{{ $t("validation.required") }}</span
                  >
                </p>
              </b-form-group>
              <b-form-group
                :label="this.$t('item') + ' ' + this.$t('image')"
                class="text-6"
              >
                <b-form-file
                  class="form-control"
                  accept="image/jpeg, image/png"
                  v-model="current_menuitem.image"
                  required
                ></b-form-file>
              </b-form-group>
              <div class="row">
                <b-form-group :label="this.$t('price')" class="text-6 col-md-6">
                  <b-form-input
                    class="form-control"
                    min="1"
                    type="number"
                    v-model="current_menuitem.price"
                    required
                  />
                </b-form-group>
                <b-form-group
                  :label="this.$t('currency')"
                  class="text-6 col-md-6"
                >
                  <b-form-input
                    class="form-control"
                    type="text"
                    v-model="currency"
                    required
                    disabled
                  />
                </b-form-group>
              </div>
              <b-form-group :label="this.$t('description')" class="text-6">
                <b-form-textarea
                  class="form-control"
                  type="text"
                  rows="4"
                  v-model="current_menuitem.description"
                  required
                />
              </b-form-group>
              <div class="row">
                <b-form-group :label="this.$t('tax')" class="text-6 col-md-6">
                  <b-form-select
                    class="form-control"
                    v-model="current_menuitem.tax_id"
                    :options="taxes"
                    text-field="name"
                    value-field="id"
                  >
                  </b-form-select>
                </b-form-group>
                <b-form-group
                  :label="'Alcohol' + ' ' + this.$t('percentage')"
                  class="text-6 col-md-6"
                >
                  <b-form-input
                    class="form-control"
                    type="text"
                    @input="$v.$touch()"
                    v-model="current_menuitem.alcohol_percentage"
                    required
                  />
                  <p
                    class="errors"
                    v-if="$v.current_menuitem.alcohol_percentage.$dirty"
                  >
                    <span
                      class="form__alert"
                      v-if="!$v.current_menuitem.alcohol_percentage.required"
                      >{{ $t("validation.required") }}</span
                    >
                    <span
                      class="form__alert"
                      v-if="!$v.current_menuitem.alcohol_percentage.numeric"
                      >{{ $t("validation.numeric") }}</span
                    >
                    <span
                      class="form__alert"
                      v-if="!$v.current_menuitem.alcohol_percentage.between"
                      >{{ $t("validation.betweenAlcohol") }}</span
                    >
                  </p>
                </b-form-group>
              </div>
              <div class="text-right">
                <b-button
                  type="submit"
                  tag="button"
                  @click.prevent="updateItem"
                  class="btn btn-primary mt-4 mr-3"
                  variant="primary mt-2"
                  :disabled="loading || $v.current_menuitem.$invalid"
                  >{{ this.$t("save") }}</b-button
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
          </b-tab>

          <!-- option category -->
          <b-tab
            :title="$t('option')"
            :disabled="current_menuitem.option_template_id != null"
          >
            <label for="" class="col-form-label">{{
              $t("availableOptiopns")
            }}</label>
            <multiselect
              :multiple="true"
              id="id"
              v-model="optionCatVal"
              :options="optionCategories"
              tag-placeholder="Add this as new tag"
              placeholder="Search or add a tag"
              label="name"
              track-by="id"
              :taggable="true"
              @select="onSelectOption"
              @remove="removeOption"
            >
            </multiselect>
          </b-tab>

          <!-- option template -->
          <b-tab :title="$t('optionTemp')" :disabled="optionCatVal.length != 0">
            <b-form-group
              :label="this.$t('availableTemplates')"
              class="text-6 col-md-6"
            >
              <div class="d-flex justify-content-end">
                <b-form-select
                  class="form-control"
                  v-model="current_menuitem.option_template_id"
                  :options="optionTemplates"
                  text-field="name"
                  value-field="id"
                >
                </b-form-select>
                <i
                  class="i-Close-Window text-15 text-danger ml-2"
                  @click="deleteOptionTemplate()"
                ></i>
              </div>
            </b-form-group>
          </b-tab>
        </b-tabs>
      </div>
    </b-modal>

    <!-- delete modal -->
    <b-modal ref="my-modal" hide-footer :title="this.$t('deleteConfirm')">
      <div class="d-block">
        <h3>
          {{ this.$t("deleteCat") }}
          <strong>{{ this.current_menuitem.name }}</strong
          >?
        </h3>
      </div>
      <div class="text-right">
        <b-button
          class="btn btn-primary mt-4 mr-3"
          variant="outline-danger"
          @click="deleteItem"
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
import Multiselect from "vue-multiselect";

import {
  required,
  minLength,
  maxLength,
  numeric,
  between,
} from "vuelidate/lib/validators";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Menu",
  },
  components: { Multiselect },

  inject: ["file_url"],

  data() {
    return {
      optionCatVal: [],
      optionCatValAdd: [],
      optionCatValAddFiltered: [],
      optionCategories: [],
      optionTemplates: [],
      sPrimary_option_id: "",
      template_option_id: "",
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
          label: this.$t("name"),
          field: "name",
        },
        {
          label: this.$t("image"),
          field: "photo",
          html: true,
        },
        {
          label: this.$t("description"),
          field: "description",
          width: "500px",
        },
        {
          label: this.$t("price"),
          field: "item_price",
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
      current_menuitem: {},
      item: {},
      new_menuitem: {
        option_template_id: null,
      },
      category_id: window.location.pathname.split("/")[4],
      currency: "",
      taxes: [],
    };
  },

  validations: {
    new_menuitem: {
      name: {
        required,
        minLength: minLength(2),
        maxLength: maxLength(50),
      },
      price: {
        required,
      },
    },
    current_menuitem: {
      name: {
        required,
      },
      alcohol_percentage: {
        required,
        numeric,
        between: between(0, 99),
      },
    },
  },

  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  mounted() {
    this.listData();
    this.getTaxes();
    this.listOptionCategories();
    this.listOptionTemplates();
  },
  methods: {
    removeOption(option) {
      axios
        .post(
          `menu/item/remove-option/${this.current_menuitem.id}`,
          {
            option_id: option.id,
          },
          {
            headers: this.headers,
          }
        )
        .then((response) => {
          this.makeToast("success", response.data.message);
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", response.data.message);
        });
    },
    onSelectOption(option) {
      axios
        .post(
          `menu/item/add-option/${this.current_menuitem.id}`,
          {
            option_id: option.id,
          },
          {
            headers: this.headers,
          }
        )
        .then((response) => {
          this.makeToast("success", response.data.message);
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", response.data.message);
        });
    },

    deleteOptionTemplateAdd() {
      this.new_menuitem.option_template_id = null;
    },
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.listData();
    },
    listData() {
      axios
        .get(`menu/category/items/${this.category_id}`, {
          headers: this.headers,
        })
        .then((response) => {
          this.currency = response.data.currency;
          console.log(response.data);
          this.rows = response.data.items.map((item) => ({
            ...item,
            photo: `<image width="60" height="60" src="${this.file_url}${item.image}"/>`,
            item_price: `${item.price} ${this.currency}`,
          }));
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    // api/option-categories
    listOptionCategories() {
      axios
        .get("option-categories", {
          headers: this.headers,
        })
        .then((response) => {
          this.optionCategories = response.data.categories;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    listOptionTemplates() {
      axios
        .get("option-template", {
          headers: this.headers,
        })
        .then((response) => {
          this.optionTemplates = response.data.templates;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showCreateModal() {
      this.new_menuitem = {
        alcohol_percentage: 0,
        image: null,
        price: 1,
      };
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_menuitem = {};
      this.$refs["create-modal"].hide();
    },
    showDeleteModal(item) {
      this.current_menuitem = item;
      this.$refs["my-modal"].show();
    },
    hideDeleteModal() {
      this.current_menuitem = {};
      this.$refs["my-modal"].hide();
    },
    showEditModal(item) {
      this.current_menuitem = item;
      this.current_menuitem.image = null;
      this.$refs["edit-modal"].show();
      this.getSelectedItem(item.id);
    },
    // get data of selected item
    getSelectedItem(itemID) {
      axios
        .get(`menu/item/${itemID}`, { headers: this.headers })
        .then((response) => {
          if (response.data.item.option_categories) {
            this.optionCatVal = response.data.item.option_categories;
          }
          this.current_menuitem.option_template_id =
            response.data.item.option_template_id;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    deleteOptionTemplate() {
      this.current_menuitem.option_template_id = null;
    },
    hideEditModal() {
      this.current_menuitem = {};
      this.$refs["edit-modal"].hide();
    },

    getTaxes() {
      axios
        .get("all-taxes", { headers: this.headers })
        .then((response) => {
          this.taxes = response.data.taxes;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    createNew() {
      let fd;
      fd = new FormData();
      if (this.new_menuitem.image != null) {
        fd.append("image", this.new_menuitem.image);
      }
      if (this.new_menuitem.description != null) {
        fd.append("description", this.new_menuitem.description);
      }
      if (this.new_menuitem.alcohol_percentage != null) {
        fd.append("alcohol_percentage", this.new_menuitem.alcohol_percentage);
      }
      if (this.new_menuitem.tax_id != null) {
        fd.append("tax_id", this.new_menuitem.tax_id);
      }
      fd.append("name", this.new_menuitem.name);
      fd.append("currency", this.currency);
      fd.append("price", this.new_menuitem.price);
      fd.append("category_id", this.category_id);
      if (this.optionCatValAdd.length == 0) {
        if (this.new_menuitem.option_template_id != null) {
          fd.append("option_template_id", this.new_menuitem.option_template_id);
        }
      }
      if (this.new_menuitem.option_template_id == null) {
        this.optionCatValAdd.map((item, index) => {
          fd.append(`option_id[${index}]`, item.id);
        });
      }
      console.log("arr", this.optionCatValAddFiltered);

      axios
        .post(`/menu/item/${this.category_id}`, fd, { headers: this.headers })
        .then((response) => {
          this.makeToast("success", "create completed successfully");
          this.hideCreateModal();
          this.listData();
        })
        .catch((errors) => {
          const Err = errors.response.data.errors;
          for (const el in Err) {
            Err[el].map((item) => {
              this.makeToast("warning", item);
            });
            this.loading = false;
          }
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
      let efd;
      efd = new FormData();
      if (this.current_menuitem.image != null) {
        efd.append("image", this.current_menuitem.image);
      }
      if (this.current_menuitem.description != null) {
        efd.append("description", this.current_menuitem.description);
      }
      if (this.current_menuitem.alcohol_percentage != null) {
        efd.append(
          "alcohol_percentage",
          this.current_menuitem.alcohol_percentage
        );
      }
      if (this.current_menuitem.tax_id != null) {
        efd.append("tax_id", this.current_menuitem.tax_id);
      }

      if (this.category_id != null) {
        efd.append("category_id", this.category_id);
      }

      if (this.current_menuitem.price != null) {
        efd.append("price", this.current_menuitem.price);
      }

      efd.append("name", this.current_menuitem.name);
      efd.append("currency", this.current_menuitem.currency);

      if (this.current_menuitem.option_template_id != null) {
        efd.append(
          "option_template_id",
          this.current_menuitem.option_template_id
        );
      }

      efd.append("_method", "patch");

      axios
        .post(`menu/item/${this.current_menuitem.id}`, efd, {
          headers: this.headers,
        })
        .then((response) => {
          this.makeToast("success", this.$t("successOpertion"));
          this.hideEditModal();
          this.listData();
          this.getSelectedItem();
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", this.$t("failedOpertion"));
        });
    },
    async deleteItem() {
      await axios
        .delete(`menu/item/${this.current_menuitem.id}`, {
          headers: this.headers,
        })
        .then((response) => {
          this.makeToast("success", "delete completed successfully");
          this.hideDeleteModal();
          this.listData();
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", "delete failed");
          this.hideDeleteModal();
        });
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
