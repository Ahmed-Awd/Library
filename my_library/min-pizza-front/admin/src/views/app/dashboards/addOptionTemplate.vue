<template>
  <div class="main-content">
    <breadcumb :page="$t('addOptionTemp')" :folder="$t('datatables')" />
    <!-- forms -->
    <div class="card mb-4">
      <div class="card-body">
        <!-- update option template -->
        <form>
          <div class="row">
            <div class="col-12">
              <!-- primary options -->
              <div class="card">
                <div class="card-body">
                  <h3 class="text-primary">
                    {{ $t("primary") }} {{ $t("option") }}
                  </h3>
                  <div
                    class="form-check d-inline-block"
                    v-for="category in primaryOptions"
                    :key="category.id"
                  >
                    <label class="form-check-label ml-3" :for="category.id">
                      <input
                        class="form-check-input"
                        type="radio"
                        name="primaryCat"
                        :id="category.id"
                        :value="category.id"
                        v-model="sPrimary"
                        @click="listClickedData(category.id)"
                      />
                      {{ category.name }}
                    </label>
                  </div>
                </div>
              </div>

              <!-- secondary options -->
              <div class="card mt-3">
                <div class="card-body">
                  <h3 class="text-primary">
                    {{ $t("secondary") }} {{ $t("option") }}
                  </h3>
                  <div
                    class="form-check d-inline-block"
                    v-for="category in secondaryOptions"
                    :key="'secondary' + category.id"
                  >
                    <label
                      class="form-check-label ml-3"
                      :for="'secondary' + category.id"
                    >
                      <input
                        class="form-check-input"
                        type="checkbox"
                        name="secondaryCat"
                        :id="'secondary' + category.id"
                        :value="category.id"
                        v-model="sCheckbox"
                      />
                      {{ category.name }}
                    </label>
                  </div>
                </div>
              </div>

              <!-- add option template name -->
              <div class="card mt-3">
                <div class="card-body">
                  <h3 class="text-primary">
                    {{ $t("optionTemplate") }}
                  </h3>

                  <input
                    type="text"
                    id="templateName"
                    class="form-control"
                    v-model="optionTemplateName"
                    :placeholder="$t('templateName')"
                  />
                </div>
              </div>

              <!-- primary option table -->
              <div class="card mt-3">
                <div class="card-body">
                  <h3 class="text-primary">
                    {{ $t("primary") }} {{ $t("option") }}
                  </h3>
                  <div class="table-responsive">
                    <table
                      class="display table table-striped table-bordered"
                      id="feature_disable_table"
                      style="width: 100%"
                    >
                      <thead>
                        <tr>
                          <th>option items</th>
                          <th>price</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="option in optionsPrimary" :key="option.id">
                          <td>{{ option.name }}</td>
                          <td>{{ option.price }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- accordion -->
              <div class="accordion mt-3" role="tablist">
                <b-card
                  no-body
                  class="mb-1"
                  v-for="(item, index) in cardList"
                  :key="item.id"
                >
                  <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button
                      class="btn"
                      block
                      v-b-toggle="'accordion' + item.id"
                      variant="default"
                      >{{ item.name }}</b-button
                    >
                  </b-card-header>

                  <b-collapse
                    :id="'accordion' + item.id"
                    :visible="index == 0 ? true : false"
                    accordion="my-accordion"
                    role="tabpanel"
                  >
                    <b-card-body>
                      <div class="row">
                        <div class="col-3 side">
                          <div>
                            <h4 class="text-primary">{{ item.name }}</h4>
                          </div>
                          <div>
                            <ul class="items_secondary">
                              <li
                                v-for="option in item.options"
                                :key="option.id"
                              >
                                {{ option.name }}
                              </li>
                            </ul>
                          </div>
                        </div>
                        <div class="col-9">
                          <div>
                            <b-tabs pills>
                              <b-tab
                                :title="tab.name"
                                :active="index == 0 ? true : false"
                                v-for="(tab, index) in item.primary"
                                :key="tab.id"
                              >
                                <span>{{ $t("sentence") }}</span
                                ><br />
                                <label
                                  class="form-check-label ml-3 mb-3"
                                  for="default"
                                >
                                  <input
                                    class="form-check-input"
                                    id="default"
                                    type="checkbox"
                                    name="default"
                                    v-model="item.use_default"
                                  />
                                  {{ $t("default") }} {{ item.use_default }}
                                </label>

                                <div class="inputs_price">
                                  <div class="tab-body">
                                    <div
                                      class="d-flex"
                                      v-for="option in item.options"
                                      :key="option.id"
                                    >
                                      <input
                                        @input="
                                          updateItemPrice(
                                            $event,
                                            option,
                                            tab,
                                            item
                                          )
                                        "
                                        :value="option.price"
                                        class="form-control mt-2"
                                        :disabled="item.use_default == 1"
                                      />
                                    </div>
                                  </div>
                                </div>
                              </b-tab>
                            </b-tabs>
                          </div>
                        </div>
                      </div>
                    </b-card-body>
                  </b-collapse>
                </b-card>
              </div>
            </div>

            <div class="col-md-12 mt-4">
              <button class="btn btn-primary" @click.prevent="createTemplate">
                {{ $t("save") }}
              </button>
            </div>
          </div>
        </form>
      </div>
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
    // here we check if option is primary or no to display it in primary selection (radio buttons)
    primaryOptions() {
      let x = [];
      if (this.categories) {
        this.categories.map((cat) => {
          if (cat.is_primary == 1) {
            x.push(cat);
          }
        });
      }
      return x;
    },
    // else if options isn't primary we add it in secondary options as check box
    secondaryOptions() {
      let y = [];
      if (this.categories) {
        this.categories.map((cat) => {
          if (cat.is_primary == 0) {
            y.push(cat);
          }
        });
      }
      return y;
    },
    selectedSec() {
      let x = [];
      if (this.categories) {
        this.categories.map((el) => {
          if (el.is_primary == 0 && this.sCheckbox.includes(el.id)) {
            x.push(el);
          }
        });
      }
      return x;
    },
  },
  data() {
    return {
      cardList: [],
      newData: {
        option_secondaries: [
          {
            secondary_option_id: null,
            primary_option_value_id: null,
            secondary_option_value_id: null,
            price: null,
            use_default: null,
          },
        ],
      },

      // checkedDefault: false,
      disabledInputs: false,
      template: [],
      sCheckbox: [],
      optionsPrimary: "",
      optionSecondries: "",
      optionTemplateName: "",
      innerSecondary: [],
      selectedOption: {
        id: "",
        primaryOption: "",
        secondaryOptions: [],
        optionSecondries: [],
      },

      categories: [],
      newSelected: [],
      current_option: {},
      new_option: {},
      category_data: {
        id: "",
        is_primary: 0,
        max_selectable: "",
        name: "",
        selection: "",
        // enable,disable .. 1--> enable, 0--> disable
        status: "",
      },

      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
        "Accept-Language": localStorage.getItem("lang"),
        Accept: "application/json",
      },
      optionTemplate_id: this.$route.params.id,
    };
  },
  mounted() {
    this.listCategory();
    this.listTemplate();
  },
  watch: {
    // secondary options
    sCheckbox: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.cardList = [];
          this.newData.option_secondaries = [];
          newVal.map((el) => {
            this.listClickedSecondary(el);
          });
          // get data for secondaries
        }
      },
    },
    optionsPrimary: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          this.newData.option_secondaries = [];
          this.cardList.map((el) => {
            el.primary = newVal;
            // console.log("ssss", el.options);
            el.primary.map((pEl) => {
              el.options.map((item) => {
                this.newData.option_secondaries.push({
                  secondary_option_id: el.id,
                  primary_option_value_id: pEl.id,
                  secondary_option_value_id: item.id,
                  price: item.price,
                });
              });
            });
          });
        }
      },
    },
    cardList: {
      immediate: true,
      handler(newVal) {
        if (newVal) {
          // this.newData.push({
          // });
        }
      },
    },
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
        .get("option-categories", {
          headers: this.headers,
        })
        .then((response) => {
          this.categories = response.data.categories;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    // when change an option primary and secondary display its data in table
    listClickedData(id) {
      axios
        .get(`option-categories/${id}`, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.optionsPrimary = response.data.category.option_values;
          // console.log("primary", this.optionsPrimary);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    updateItemPrice(e, secondary, primary, item) {
      let x = this.newData.option_secondaries.filter((el) => {
        return (
          primary.id == el.primary_option_value_id &&
          secondary.id == el.secondary_option_value_id
        );
      });
      x[0].price = e.target.value;
      console.log("price", secondary);
    },
    
    // when change a secondary display its data in table
    listClickedSecondary(id) {
      axios
        .get(`option-categories/${id}`, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.cardList.push({
            id: response.data.category.id,
            name: response.data.category.name,
            options: response.data.category.option_values,
            primary: this.optionsPrimary,
            use_default: 0,
          });
          this.optionsPrimary.map((pEl) => {
            response.data.category.option_values.map((el) => {
              this.newData.option_secondaries.push({
                secondary_option_id: response.data.category.id,
                primary_option_value_id: pEl.id,
                secondary_option_value_id: el.id,
                price: el.price,
                // use_default: false,
              });
            });
          });
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    // create option template
    createTemplate() {
      let form = new FormData();
      form.append("name", this.optionTemplateName);
      form.append("primary_option_id", this.sPrimary);
      this.newData.option_secondaries.map((item, index) => {
        form.append(
          `option_secondaries[${index}][secondary_option_id]`,
          item.secondary_option_id
        );
        form.append(
          `option_secondaries[${index}][primary_option_value_id]`,
          item.primary_option_value_id
        );
        form.append(
          `option_secondaries[${index}][secondary_option_value_id]`,
          item.secondary_option_value_id
        );
        if (this.checkedDefault) {
          form.append(`option_secondaries[${index}][price]`, 0);
          form.append(`option_secondaries[${index}][use_default]`, 1);
        } else {
          form.append(`option_secondaries[${index}][price]`, item.price);
          form.append(`option_secondaries[${index}][use_default]`, 0);
        }
      });
      axios
        .post("option-template", form, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", this.$t("successOpertion"));
        })
        .catch((errors) => {
          console.log(errors);
          this.makeToast("warning", this.$t("failedOpertion"));
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
/* pretty radio */
input[type="radio"] {
  -webkit-appearance: none;
  width: 15px;
  height: 15px;
  border: 1px solid darkgray;
  border-radius: 50%;
}

input[type="radio"]:hover {
  box-shadow: 0 0 5px 0px orange inset;
}

input[type="radio"]:before {
  content: "";
  display: block;
  width: 60%;
  height: 60%;
  margin: 20% auto;
  border-radius: 50%;
}
input[type="radio"]:checked:before {
  background: #8ac054;
}

/* pretty checkbox */
input[type="checkbox"] {
  -webkit-appearance: none;
  width: 15px;
  height: 15px;
  border: 1px solid darkgray;
}

input[type="checkbox"]:hover {
  box-shadow: 0 0 5px 0px orange inset;
}

input[type="checkbox"]:before {
  content: "";
  display: block;
  width: 60%;
  height: 60%;
  margin: 20% auto;
}
input[type="checkbox"]:checked:before {
  background: #8ac054;
}
.card-header {
  padding: 0 !important;
}
.inputs_price {
  width: 25%;
}
ul {
  list-style: none;
}
.items_secondary {
  margin-top: 8em;
}
.items_secondary li {
  height: 42px;
}
</style>
