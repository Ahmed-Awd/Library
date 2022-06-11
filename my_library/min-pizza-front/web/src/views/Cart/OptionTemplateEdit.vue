<template>
  <!-- if item have option template -->
  <div v-if="itemData.option_template_id != null">
    <div class="addtion-block">
      <div
        class="form-check"
        v-for="el in itemData.option_secondaries"
        :key="el.id"
      >
        <input
          class="form-check-input"
          type="radio"
          name="flexRadioDefault"
          v-model="itemData.primaries"
          :value="el"
          :checked="el.name == itemData.primaries.name"
          @change="getOptions()"
          :id="el.id"
        />
        <label class="form-check-label" :for="el.id">{{ el.name }}</label>
        <span class="main_color ms-2 sm_font"
          >{{ el.price }} {{ itemData.currency }}</span
        >
      </div>
    </div>
    <div class="addtion-block" v-if="itemData.primaries">
      <!-- {{ itemData.secondaries }}
                  <hr />
                  {{ itemData.selectedSecondary }}
            <hr />-->
      <!-- {{ itemData.secondaryArr }} -->
      <div v-for="sec in itemData.secondaries" :key="sec.name">
        <h6>
          {{ sec.name }}
          <!-- -- {{ sec.id }} -->

          <span class="maxMsg ms-2">
            ( {{ $t("choose") }} {{ sec.max_selectable }} {{ $t("option") }} )
          </span>
        </h6>

        <div
          class="form-check mt-2"
          v-for="(el, index) in sec.secondary_option_value"
          :key="el.option_secondary_id"
        >
          <input
            class="form-check-input"
            type="checkbox"
            name="flexRadioDefault"
            :id="el.option_secondary_id"
            :checked="optionValueChecked(el.option_secondary_id)"
            :value="el.option_secondary_id"
            @change="
              setSelectedSecondary(
                sec.id,
                sec.max_selectable,
                el.option_secondary_id,
                $event
              )
            "
          />
          <label class="form-check-label" :for="el.option_secondary_id">
            {{ el.name }}
            <!-- {{ el.option_category_id }} -->
          </label>

          <span class="main_color ms-2 sm_font"
            >{{ el.price }} {{ itemData.currency }}</span
          >

          <!-- start +,- option -->
          <div
            class="quantity d-inline-block ml-3"
            v-if="el.max_count > 1 || itemData.count > 1"
          >
            <button
              type="button"
              value="+"
              class="btn plus act-btn"
              :disabled="el.qnt >= el.max_count * itemData.count"
              @click="
                el.qnt ? el.qnt++ : (el.qnt = 2);
                getSecondaryArr(sec.id, sec.max_selectable);
              "
            >
              <i class="fas fa-plus"></i>
            </button>
            <input
              type="text"
              min="1"
              :max="el.max_count * itemData.count"
              :value="el.qnt"
              @input="el.qnt <= el.max_count * itemData.count ? el.qnt : 1"
              title="Qty"
              class="input-text qty text valid_qty"
              v-if="
                typeof el.qnt === 'number' &&
                  el.qnt <= el.max_count * itemData.count
              "
              disabled
            />

            <input
              type="text"
              min="1"
              :max="el.max_count * itemData.count"
              :value="(el.qnt = 1)"
              title="Qty"
              class="input-text qty text valid_qty"
              disabled
              v-else
            />

            <button
              type="button"
              value="-"
              class="btn minus act-btn"
              :disabled="el.qnt == el.min_count"
              @click="
                el.qnt--;
                getSecondaryArr(sec.id, sec.max_selectable);
              "
            >
              <i class="fas fa-minus"></i>
            </button>
          </div>
          <p
            class="mt-3"
            v-if="index === sec.secondary_option_value.length - 1"
          >
            <!-- max selctabble err msg -->
            <span
              v-if="
                maxSelectableNum &&
                  itemData.selectedSecondary.includes(el.option_secondary_id)
              "
            >
              <span class="errMsg">
                {{ $t("selectNum") }} {{ sec.max_selectable }}
                {{ $t("option") }}
              </span>
            </span>
          </p>
        </div>

        <hr />
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import {
  primariesNum,
  selectedItemData,
  selectedOrder,
  getOptions,
} from "./edit-selected-order";

export default defineComponent({
  name: "OptionTemplateEdit",
  setup() {
    return {
      selectedOrder,
      itemData: selectedItemData,
      primariesNum,
      getOptions,
    };
  },
  data() {
    return {
      maxSelectableNum: false,
    };
  },
  methods: {
    optionValueChecked(id) {
      return this.itemData.selectedSecondary.includes(id);
    },
    selectedOptionValue({ option_secondary_id }) {
      return this.selectedOrder.order_items.filter(
        ({ item_id }) => item_id == option_secondary_id
      ).length;
    },
    setSelectedSecondary(
      id: string,
      max_sel: number,
      value: number,
      event: any
    ) {
      if (event.target.checked) {
        this.itemData.selectedSecondary.push(value);
      } else {
        this.itemData.selectedSecondary = this.itemData.selectedSecondary.filter(
          (sec) => sec != value
        );
      }
      this.getSecondaryArr(id, max_sel);
    },
    getSecondaryArr(id, max_sel) {
      this.itemData.secondaryArr = [];
      this.itemData.secondaries.map((el) => {
        el.secondary_option_value.map((item) => {
          if (
            this.itemData.selectedSecondary.includes(item.option_secondary_id)
          ) {
            if (
              this.itemData.secondaryArr.findIndex(
                (x) => x.item_id == item.option_secondary_id
              ) === -1
            ) {
              this.itemData.secondaryArr.push({
                sec_id: el.id,
                item_id: item.option_secondary_id,
                quantity:
                  item.qnt <= item.max_count * this.itemData.count
                    ? item.qnt
                    : 1,
                option_name: item.name,
                price: item.price,
              });
            } else {
              this.itemData.secondaryArr[
                this.itemData.secondaryArr.findIndex(
                  (x) => x.item_id == item.option_secondary_id
                )
              ].quantity++;
            }
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
