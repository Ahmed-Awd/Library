<template>
  <div
    class="modal fade meal-detail-modal"
    id="editItemInCart"
    tabindex="-1"
    aria-labelledby="editItemInCartLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body" v-if="itemData && itemData.name">
          <div class="meal-photo">
            <img :src="`${url}${itemData.image}`" />
          </div>
          <div class="modal-cont">
            <div class="row mb-3">
              <div class="col-lg-9 col-md-9 col-sm-12 col-12">
                <h5>{{ itemData.name }}</h5>
                <p class="rest-name">{{ itemData.restaurant_name }}</p>
              </div>
              <div
                class="col-lg-3 col-md-3 col-sm-12 col-12 align-self-start justify-content-end"
              >
                <div class="text-end">
                  <span class="price green-color" v-if="itemData.current_offer"
                    >{{ itemData.current_offer.new_price }}
                    {{ itemData.currency }}
                  </span>
                  <span class="price green-color" v-else
                    >{{ itemData.price }} {{ itemData.currency }}
                  </span>
                </div>
              </div>
            </div>
            <h5 class="uppercase">{{ $t("details") }}</h5>
            <p>
              {{ itemData.description }}
            </p>
            <h5
              class="mt-5"
              v-if="
                itemData.option_template_id != null ||
                  itemData.option_categories != null
              "
            >
              {{ $t("choose") }}
            </h5>
            <OptionTemplateEdit
              v-if="
                selectedOrder.itemName &&
                  selectedOrder.order_items &&
                  itemData &&
                  itemData.option_templates != null
              "
            />
            <CategoryOptionsEdit
              v-if="
                selectedOrder.itemName &&
                  itemData &&
                  itemData.option_categories != null
              "
            />

            <div class="addition-block">
              <div class="mb-3">
                <label class="form-label">{{ $t("add_notes") }}</label>
                <p class="note">{{ $t("addNotesNote") }}</p>
                <textarea
                  class="form-control"
                  :placeholder="$t('notePlaceholder')"
                  rows="3"
                  v-model="itemData.notes"
                ></textarea>
              </div>
            </div>

            <div class="row add-to-cart-row">
              <div class="col-lg-7 col-md-7 col-sm-12 col-12">
                <button
                  type="button"
                  class="btn btn-primary blue-btn add-to-cart-btn clearfix w-100"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                  @click="
                    updateSelectedItemInCart();
                    $emit('edited');
                  "
                  :disabled="
                    maxSelectableNum || maxSelectableNumCat || primariesNum
                  "
                >
                  <span class="float-center">{{ $t("save") }}</span>
                </button>
              </div>
              <div
                class="col-lg-5 col-md-5 col-sm-12 col-12 align-self-start justify-content-end"
              >
                <div class="quantity buttons_added">
                  <button
                    type="button"
                    value="+"
                    class="btn plus act-btn"
                    @click="
                      itemData.count++;
                      getSecondaryArrCount();
                    "
                  >
                    <i class="fas fa-plus"></i>
                  </button>
                  <input
                    type="text"
                    min="1"
                    max=""
                    class="input-text qty text valid_qty"
                    v-model="itemData.count"
                    disabled
                  />
                  <button
                    type="button"
                    value="+"
                    class="btn minus act-btn"
                    :disabled="itemData.count == 1"
                    @click="
                      itemData.count--;
                      getSecondaryArrCount();
                    "
                  >
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import CategoryOptionsEdit from "./CategoryOptionsEdit.vue";
import OptionTemplateEdit from "./OptionTemplateEdit.vue";
import {
  primariesNum,
  selectedItemData,
  selectedOrder,
  updateSelectedItemInCart,
} from "./edit-selected-order";

export default defineComponent({
  name: "EditItem",
  components: { CategoryOptionsEdit, OptionTemplateEdit },
  setup() {
    return {
      itemData: selectedItemData,
      primariesNum,
      selectedOrder,
      updateSelectedItemInCart,
    };
  },
  emit: ["edited"],
  data() {
    return {
      maxSelectableNum: false,
      maxSelectableNumCat: false,
      url: localStorage.getItem("imgURL"),
    };
  },
  methods: {
    getSecondaryArrCount() {
      let x: any[] = [];
      this.itemData.secondaries.map((el) => {
        el.secondary_option_value.map((item) => {
          if (
            this.itemData.selectedSecondary.includes(item.option_secondary_id)
          ) {
            if (!(item.max_count > 1 || this.itemData.count > 1)) {
              x.push({
                sec_id: el.id,
                item_id: item.option_secondary_id,
                quantity: 1,
                option_name: item.name,
                price: item.price,
              });
            } else {
              x.push({
                sec_id: el.id,
                item_id: item.option_secondary_id,
                quantity:
                  item.qnt <= item.max_count * this.itemData.count
                    ? item.qnt
                    : 1,
                option_name: item.name,
                price: item.price,
              });
            }
          }
        });
      });
      this.itemData.secondaryArr = x;
    },
    getSecondaryArr(id, max_sel) {
      this.itemData.secondaryArr = [];
      this.itemData.secondaries.map((el) => {
        el.secondary_option_value.map((item) => {
          if (
            this.itemData.selectedSecondary.includes(item.option_secondary_id)
          ) {
            this.itemData.secondaryArr.push({
              sec_id: el.id,
              item_id: item.option_secondary_id,
              quantity:
                item.qnt <= item.max_count * this.itemData.count ? item.qnt : 1,
              option_name: item.name,
              price: item.price,
            });
          }
        });
      });
      if (this.itemData.secondaryArr.length > max_sel) {
        this.maxSelectableNum = true;
      } else {
        this.maxSelectableNum = false;
      }
    },
  },
});
</script>
