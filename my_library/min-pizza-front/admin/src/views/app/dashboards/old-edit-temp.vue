<template>
  <div class="main-content">
    <breadcumb :page="$t('updateOptionTemp')" :folder="$t('datatables')" />
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
                            <h4 class="text-primary">
                              {{ item.name }}
                            </h4>
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
                                    v-model="checkedDefault"
                                    true-value="1"
                                    false-value="0"
                                  />
                                  {{ $t("default") }}
                                </label>

                                <div class="inputs_price">
                                  <div class="tab-body">
                                    <div
                                      :key="index"
                                      v-for="(x, index) in tab.secondary_option"
                                    >
                                      <div v-if="x.id == item.id">
                                        <div
                                          class="d-flex"
                                          v-for="option in x.secondary_option_value"
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
                                            :disabled="checkedDefault == 1"
                                          />
                                        </div>
                                      </div>
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
              <button
                class="btn btn-primary"
                @click.prevent="updateTemplate"
                :disabled="counter >= 1"
              >
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
      counter: 0,
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

      checkedDefault: 0,
      disabledInputs: false,
      template: [],
      sCheckbox: [],
      sPrice: [],
      sPrimary: "",
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
          console.log(newVal);
          this.newData.option_secondaries.length = 0;
          setTimeout(() => {
            this.cardList.map((el) => {
              console.log("cardlist id =>", el);
              el.primary = newVal;
              console.log("el options=>", el.options);
              console.log("el-primary->", el.primary);
              el.primary.map((pEl) => {
                console.log("watch ->", pEl);
                pEl.secondary_option.map((sOption) => {
                  console.log("s option =>", sOption);
                  if (sOption.id === el.id) {
                    sOption.secondary_option_value.map((soChild) => {
                      console.log(
                        `${soChild.id}|${sOption.name}:${soChild.name}=>`,
                        soChild.price
                      );
                      el.options.map((elOption) => {
                        if (elOption.id == soChild.id) {
                          // console.log("sss");
                          this.newData.option_secondaries.push({
                            secondary_option_id: el.id,
                            secondary_option_name: el.name,
                            primary_option_value_id: pEl.id,
                            primary_option_value_name: pEl.name,
                            secondary_option_value_id: soChild.id,
                            secondary_option_value_name: soChild.name,
                            price: soChild.price,
                            use_default: 0,
                          });
                        }
                      });
                    });
                  }
                });
              });
            });
          }, 500);
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
    // option template data
    listTemplate() {
      axios
        .get(`option-template/${this.optionTemplate_id}`, {
          headers: this.headers,
        })
        .then((response) => {
          // secondary options id (init)
          this.sCheckbox = response.data.secondary_options_selected;
          this.sPrimary = response.data.option_template.primary_option_id;
          // template options
          this.template = response.data.option_template;
          // * options primary*//
          this.optionsPrimary = this.template.optionSecondaries;
          this.optionTemplateName = this.template.name;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    // get id for selected new
    getID(id) {
      this.selectedOption.id = id;
      this.listClickedData();
    },
    // when change an option primary and secondary display its data in table
    listClickedData(id) {
      axios
        .get(`option-categories/${id}`, {
          headers: this.headers,
        })
        .then((response) => {
          this.optionsPrimary = response.data.category.option_values;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    updateItemPrice(e, secondary, primary, item) {
      console.log("p", primary);
      console.log("s", secondary);
      console.log("i", item);
      let x = this.newData.option_secondaries.filter((el) => {
        return (
          primary.id == el.primary_option_value_id &&
          secondary.id == el.secondary_option_value_id
        );
      });
      // console.log(this.newData.option_secondaries);
      console.log(x);
      // secondary.price = e.target.value;
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
          console.log("data", response.data);

          this.cardList.push({
            id: response.data.category.id,
            name: response.data.category.name,
            options: response.data.category.option_values,
            primary: this.optionsPrimary,
            use_default: 0,
          });
          this.cardList.map((ss) => {
            this.optionsPrimary[0].secondary_option.map((el) => {
              if (ss.id == el.id) {
                this.checkedDefault = el.use_default;
              }
            });
          });

          this.optionsPrimary.map((pEl) => {
            response.data.category.option_values.map((el) => {
              // this.newData.option_secondaries.push({
              //   secondary_option_id: response.data.category.id,
              //   primary_option_value_id: pEl.id,
              //   secondary_option_value_id: el.id,
              //   price: el.price,
              //   use_default: 0,
              // });
            });
          });
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    // update option template
    updateTemplate() {
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
        if (this.checkedDefault == 1) {
          form.append(`option_secondaries[${index}][price]`, 0);
          form.append(`option_secondaries[${index}][use_default]`, 1);
        } else {
          form.append(`option_secondaries[${index}][price]`, item.price);
          form.append(`option_secondaries[${index}][use_default]`, 0);
        }
      });
      form.append("_method", "patch");
      axios
        .post(`option-template/${this.$route.params.id}`, form, {
          headers: this.headers,
        })
        .then((response) => {
          this.makeToast("success", this.$t("successOpertion"));
          this.$router.go(0);
          this.counter++;
        })
        .catch((errors) => {
          const Err = errors.response.data.errors;
          console.log(Err);
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
