<template>
  <!-- if item have option category -->
  <div class="addtion-block">
    <div
      class="form-check"
      v-for="el in itemData.option_categories"
      :key="el.id"
    >
      <input
        class="form-check-input"
        type="checkbox"
        name="flexRadioDefault"
        :value="el"
        :checked="
          !!itemData.categories.filter((op) => op.name === el.name).length
        "
        @change="setSelectedCategory(el, $event)"
        :id="el.id"
      />
      <label class="form-check-label" :for="el.id">
        {{ el.name }}
      </label>
    </div>
  </div>
  <!-- option value -->
  <div
    class="addtion-block"
    v-if="itemData.categories && itemData.categories.length != 0"
  >
    <div v-for="el in itemData.categories" :key="el.id">
      <!-- {{ maxSelectableNumCat }} -->
      <h6>
        <!-- {{ el.id }} -->
        {{ el.name }}
        <span class="ms-2">
          ( {{ $t("choose") }} {{ el.max_selectable }} {{ $t("option") }} )
        </span>
      </h6>

      <div v-for="(value, index) in el.option_values" :key="value.id">
        <!-- {{ value }} -->

        <input
          class="form-check-input"
          type="checkbox"
          name="flexRadioDefault"
          :value="value"
          :id="value.id"
          :checked="
            !!itemData.catOptions.filter((op) => op.name === value.name).length
          "
          @change="setSelectedOptions(value, $event)"
        />
        <label class="form-check-label ms-2" :for="value.id">
          <!-- {{ value.option_category_id }} -->
          {{ value.name }}
          <span class="main_color ms-1 sm_font"
            >({{ value.price }} {{ selectedOrder.currency }})</span
          >
        </label>

        <!-- qty -->
        <div
          class="quantity d-inline-block ml-3"
          v-if="value.max_count > 1 || itemData.count > 1"
        >
          <button
            type="button"
            value="+"
            class="btn plus act-btn"
            :disabled="value.qnt >= value.max_count * itemData.count"
            @click="
              plusQauntityForValue(value);
              value.qnt--;
            "
          >
            <i class="fas fa-plus"></i>
          </button>

          <input
            type="text"
            min="1"
            :max="value.max_count * itemData.count"
            :value="
              itemData.catOptions.filter((opt) => opt.name == value.name)[0]
                ?.qnt || 1
            "
            title="Qty"
            class="input-text qty text valid_qty"
            @input="changeQauntityForValue(value, $event)"
            v-if="value.qnt <= value.max_count * itemData.count"
            disabled
          />

          <input
            type="number"
            min="1"
            :max="value.max_count * itemData.count"
            :value="
              itemData.catOptions.filter((opt) => opt.name == value.name)[0]
                ?.qnt || 1
            "
            title="Qty"
            class="input-text qty text valid_qty"
            @input="changeQauntityForValue(value, $event)"
            disabled
            v-else
          />

          <button
            type="button"
            value="-"
            class="btn minus act-btn"
            :disabled="
              itemData.catOptions.filter((opt) => opt.name == value.name)[0]
                ?.qnt == value.min_count
            "
            @click="
              minusQauntityForValue(value);
              value.qnt--;
            "
          >
            <i class="fas fa-minus"></i>
          </button>
        </div>

        <!-- max selctable err msg -->
        <div v-if="index === el.option_values.length - 1" class="mt-2">
          <div v-for="(item, index) in itemData.catOptions" :key="item.id">
            <!-- {{ item.option_category_id }} -- {{ item.name }} -->
            <!-- {{ itemData.catOptions }} -- {{ el }} -->
            <p v-if="index === itemData.catOptions.length - 1">
              <span
                v-if="
                  maxSelectableNumCat &&
                    item.option_category_id == value.option_category_id
                "
                ><span class="errMsg"
                  >{{ $t("selectNum") }} {{ el.max_selectable }}
                  {{ $t("option") }}</span
                >
                <br
              /></span>
            </p>
          </div>
        </div>
      </div>

      <hr />
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import {
  primariesNum,
  selectedItemData,
  selectedOrder,
} from "./edit-selected-order";

export default defineComponent({
  name: "CategoryOptionsEdit",
  setup() {
    return { itemData: selectedItemData, primariesNum, selectedOrder };
  },
  data() {
    return {
      maxSelectableNumCat: false,
    };
  },
  methods: {
    setSelectedCategory(el, event) {
      this.checkOrUnCheck("categories", el, event);
    },
    setSelectedOptions(el, event) {
      this.checkOrUnCheck("catOptions", el, event);
    },
    checkOrUnCheck(
      selected_array: string,
      el: Record<string, any>,
      event: Record<string, any>
    ) {
      // if element not found and the input is checked (i.e. opotion selected)
      // add it to categories
      if (
        this.itemData[selected_array].filter((itm) => itm.name === el.name)
          .length === 0 &&
        event.target.checked
      ) {
        let sizes = ["Small", "Medium", "Large"];
        if (sizes.includes(el.name)) {
          // remove current size
          let excluded = sizes.filter((opt) => el.name !== opt);
          // remove other sizes from catOptions
          this.itemData[selected_array] = this.itemData[selected_array].filter(
            (itm) => !excluded.includes(itm.name)
          );
        }
        // push el to catOptions (e.i. selected_options)
        this.itemData[selected_array].push(el);
      }
      // if input not checked
      // remove element from this.itemData[selected_array]
      if (!event.target.checked) {
        this.itemData[selected_array] = this.itemData[selected_array].filter(
          (itm) => itm.name != el.name
        );
      }
    },
    minusQauntityForValue(value) {
      this.itemData.catOptions = this.itemData.catOptions.map((opt) => {
        if (opt.id == value.id) {
          opt.qnt ? opt.qnt-- : (opt.qnt = 1);
        }
        return opt;
      });
    },
    plusQauntityForValue(value) {
      this.itemData.catOptions = this.itemData.catOptions.map((opt) => {
        if (opt.id == value.id) {
          opt.qnt ? opt.qnt++ : (opt.qnt = 1);
        }
        return opt;
      });
    },
    changeQauntityForValue(value, event) {
      this.itemData.catOptions = this.itemData.catOptions.map((opt) => {
        if (opt.id == value.id) {
          opt.qnt = Number.parseInt(event.target.value);
        }
        return opt;
      });
    },
  },
});
</script>
