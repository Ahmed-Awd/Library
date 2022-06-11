<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("menu") }}</h1>
          <h4>{{ $t("min_pizza") }}</h4>
        </div>
      </div>
    </div>
    <RestaurantBreadcrumb
      :retaurantData="restaurant"
      :status="status"
      :isFollowed="isFollowed"
      :deliveryData="deliveryData"
      :user="user"
      @follow="follow(restaurant.id)"
    />
    <!-- categories tabs -->
    <div class="search-content p-0 meals-tabs">
      <div class="cat-div">
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-12 mt-3">
              <ul class="nav nav-pills mb-0" id="pills-tab" role="tablist">
                <li
                  class="nav-item mx-2"
                  role="presentation"
                  v-for="category in restaurant_cat"
                  :key="category.name"
                  @click="selectTab(category.name)"
                >
                  <button
                    class="nav-link"
                    :class="selectedCat == category.name ? 'active' : ''"
                    :id="`pills-${category.name}-tab`"
                    data-bs-toggle="pill"
                    type="button"
                    role="tab"
                    aria-controls="pills-home"
                    aria-selected="true"
                  >
                    {{ category.name }}
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Menu -->
    <div class="white-bg cat-filter">
      <div class="container">
        <div class="row">
          <!-- checkout card -->
          <div class="col-md-4 col-sm-12 col-12">
            <div class="search-block filter-blo mt-0">
              <div class="footer-title sec-title">
                <h5>{{ $t("your_order") }}</h5>
                <hr />
              </div>
              <div
                class="delivery-block"
                v-for="(order, index) in orders"
                :key="order"
              >
                <div class="row align-item-center">
                  <div class="col-7">
                    <div class="clearfix">
                      <span class="order-item-no">{{ order.item_qty }} x</span>
                      <div class="float-right mb-0">
                        <span class="meal-name"
                          >{{ order.itemName }}
                          <img
                            class="edit"
                            src="images/icon-edit.png"
                            @click="geteditItem(order)"
                        /></span>
                        <p
                          class="meal-detal pe-1 mb-0 option_det"
                          v-for="option in order.order_items"
                          :key="option.id"
                        >
                          <span class="order-item-no"
                            >{{ option.quantity }} x</span
                          >
                          {{ option.option_name }} ({{
                            option.price * option.quantity
                          }}
                          {{ order.restaurantCurrency }})
                        </p>

                        <p
                          class="meal-detal pe-1 mb-0 option_det"
                          v-for="option in order.catOptions"
                          :key="option.id"
                        >
                          <span class="order-item-no"
                            >{{ option.qnt ? option.qnt : 1 }} x</span
                          >
                          {{ option.name }} ({{
                            option.price * option.qnt ? option.qnt : 1
                          }}
                          {{ order.restaurantCurrency }})
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-5 justify-content-end text-end">
                    <span class="meal-price" v-if="order.new_price"
                      >{{ order.new_price * order.item_qty
                      }}{{ order.restaurantCurrency }}</span
                    >
                    <span class="meal-price" v-else
                      >{{ order.item_price * order.item_qty
                      }}{{ order.restaurantCurrency }}</span
                    >
                    <a
                      class="delete-favourite-block"
                      @click.prevent="removeItem(index)"
                      ><i class="fas fa-times"></i
                    ></a>
                  </div>
                </div>
                <hr />
              </div>

              <div class="delivery-block" v-if="orders.length != 0">
                <div class="row">
                  <div class="col-md-8 col-sm-8 col-8">
                    <p class="mb-0">{{ $t("subtotal") }}</p>
                  </div>
                  <div
                    class="col-md-4 col-sm-4 col-4 justify-content-end text-end"
                  >
                    <h5>
                      {{ parseFloat(subTotal).toFixed(2) }}
                      {{ restaurantCurrency }}
                    </h5>
                  </div>
                </div>
                <div class="text-center mt-3">
                  <router-link to="/cart" class="btn btn-primary blue-btn"
                    >{{ $t("cart") }}
                  </router-link>
                </div>
              </div>
            </div>
            <div class="open-filter">
              <i class="fas fa-filter"></i>
            </div>
          </div>
          <!-- menu items -->
          <div class="col-md-8">
            <div class="tab-content" id="pills-tabContent">
              <div
                class="tab-pane fade show active"
                id="pills-home"
                role="tabpanel"
                :aria-labelledby="`pills-${selectedCat}-tab`"
              >
                <div class="row">
                  <div v-if="loading" class="loader white-bg">
                    <Circle></Circle>
                  </div>
                  <div v-else>
                    <div
                      class="col-12 meals-view"
                      v-if="selectedCatItems.length != 0"
                    >
                      <div
                        class="follow-container"
                        v-for="item in selectedCatItems"
                        :key="item.id"
                      >
                        <a
                          class="delete-favourite-block add-to-fav"
                          @click="favBtn(item.id)"
                        >
                          <i
                            class="fas fa-heart red-color"
                            :class="item.is_favourite ? 'red' : ''"
                          ></i
                        ></a>
                        <div
                          class="favourite-block"
                          data-bs-toggle="modal"
                          data-bs-target="#exampleModal"
                          @click.prevent="getItemData(item.id)"
                        >
                          <span class="item-offer" v-if="item.current_offer">
                            {{ $t("offers") }}
                          </span>
                          <div class="row py-2">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                              <img :src="`${url}${item.image}`" />
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                              <h5>{{ item.name }}</h5>
                              <!-- <p class="rest-name">{{ restaurantName }}</p> -->
                              <p class="rest-name">{{ item.description }}</p>
                            </div>
                            <div
                              class="col-lg-3 col-md-3 col-sm-6 col-6 align-self-end justify-content-end"
                            >
                              <div class="text-end">
                                <div>
                                  <span class="price" v-if="!item.current_offer"
                                    >{{ item.price }} {{ item.currency }}
                                  </span>
                                  <span class="price oldPrice" v-else
                                    >{{ item.price }} {{ item.currency }}
                                  </span>
                                  <span
                                    class="price ms-2"
                                    v-if="item.current_offer"
                                    >{{ item.current_offer.new_price }}
                                    {{ item.currency }}
                                  </span>
                                </div>

                                <button
                                  class="cart"
                                  data-bs-toggle="modal"
                                  data-bs-target="#exampleModal"
                                  @click.prevent="getItemData(item.id)"
                                >
                                  <img src="images/icon-shopbag.png" />
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div
                      class="col-12 text-center"
                      v-if="loading == false && selectedCatItems.length == 0"
                    >
                      <img src="images/warning.png" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- add new item modal -->
    <div
      class="modal fade meal-detail-modal"
      id="exampleModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-body">
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
                    <span
                      class="price green-color"
                      v-if="itemData.current_offer"
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
              <input type="text" v-model="itemData.id" hidden />
              <h5
                class="mt-5"
                v-if="
                  itemData.option_template_id != null ||
                    itemData.option_categories != null
                "
              >
                {{ $t("choose") }}
              </h5>
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
                      @change="getOptions()"
                      :id="el.id"
                    />
                    <label class="form-check-label" :for="el.id">
                      {{ el.name }}
                    </label>
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

                      <span class="maxMsg ms-2"
                        >( {{ $t("choose") }} {{ sec.max_selectable }}
                        {{ $t("option") }} )</span
                      >
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
                        v-model="itemData.selectedSecondary"
                        :value="el.option_secondary_id"
                        @change="getSecondaryArr(sec.id, sec.max_selectable)"
                      />
                      <label
                        class="form-check-label"
                        :for="el.option_secondary_id"
                      >
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
                          @input="
                            el.qnt <= el.max_count * itemData.count ? el.qnt : 1
                          "
                          title="Qty"
                          class="input-text qty text valid_qty"
                          v-if="el.qnt <= el.max_count * itemData.count"
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
                              itemData.selectedSecondary.includes(
                                el.option_secondary_id
                              )
                          "
                        >
                          <span class="errMsg"
                            >{{ $t("selectNum") }} {{ sec.max_selectable }}
                            {{ $t("option") }}</span
                          ></span
                        >
                      </p>
                    </div>

                    <hr />
                  </div>
                </div>
              </div>

              <!-- if item have option category -->
              <div v-else-if="itemData.option_categories != null">
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
                      v-model="itemData.categories"
                      :value="el"
                      @change="getOptionsValue(el.id)"
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
                  <!-- {{ itemData.categories }}
                  <hr />
                  {{ maxSelectableNumCat }}
                  <hr />
                  {{ itemData.catOptions }} -->

                  <div v-for="el in itemData.categories" :key="el.id">
                    <!-- {{ maxSelectableNumCat }} -->
                    <h6>
                      <!-- {{ el.id }} -->
                      {{ el.name }}
                      <span class="ms-2"
                        >( {{ $t("choose") }} {{ el.max_selectable }}
                        {{ $t("option") }} )</span
                      >
                    </h6>

                    <div
                      v-for="(value, index) in el.option_values"
                      :key="value.id"
                    >
                      <!-- {{ value }} -->

                      <input
                        class="form-check-input"
                        type="checkbox"
                        name="flexRadioDefault"
                        v-model="itemData.catOptions"
                        :value="value"
                        :id="value.id"
                        @change="
                          checkMaxSelectable(
                            itemData.catOptions,
                            el.id,
                            el.max_selectable
                          )
                        "
                      />
                      <label class="form-check-label ms-2" :for="value.id">
                        <!-- {{ value.option_category_id }} -->
                        {{ value.name }}
                        <span class="main_color ms-1 sm_font"
                          >({{ value.price }} {{ itemData.currency }})</span
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
                          :disabled="
                            value.qnt >= value.max_count * itemData.count
                          "
                          @click="value.qnt ? value.qnt++ : (value.qnt = 2)"
                        >
                          <i class="fas fa-plus"></i>
                        </button>

                        <input
                          type="text"
                          min="1"
                          :max="value.max_count * itemData.count"
                          v-model="value.qnt"
                          title="Qty"
                          class="input-text qty text valid_qty"
                          v-if="value.qnt <= value.max_count * itemData.count"
                          disabled
                        />

                        <input
                          type="text"
                          min="1"
                          :max="value.max_count * itemData.count"
                          :value="(value.qnt = 1)"
                          title="Qty"
                          class="input-text qty text valid_qty"
                          disabled
                          v-else
                        />

                        <button
                          type="button"
                          value="-"
                          class="btn minus act-btn"
                          :disabled="value.qnt == value.min_count"
                          @click="value.qnt--"
                        >
                          <i class="fas fa-minus"></i>
                        </button>
                      </div>

                      <!-- max selctable err msg -->
                      <div
                        v-if="index === el.option_values.length - 1"
                        class="mt-2"
                      >
                        <div
                          v-for="(item, index) in itemData.catOptions"
                          :key="item.id"
                        >
                          <!-- {{ item.option_category_id }} -- {{ item.name }} -->
                          <!-- {{ itemData.catOptions }} -- {{ el }} -->
                          <p v-if="index === itemData.catOptions.length - 1">
                            <span
                              v-if="
                                maxSelectableNumCat &&
                                  item.option_category_id ==
                                    value.option_category_id
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
              </div>

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
                <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                  <button
                    type="button"
                    class="btn btn-primary blue-btn add-to-cart-btn clearfix w-100"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    @click="addCart()"
                    :disabled="
                      maxSelectableNum || maxSelectableNumCat || primariesNum
                    "
                  >
                    <span class="float-left">{{ $t("addCart") }}</span>
                  </button>
                </div>
                <div
                  class="col-lg-4 col-md-4 col-sm-12 col-12 align-self-start justify-content-end"
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
    <EditItem
      @edited="updateOrders()"
      v-if="selectedOrder && selectedOrder.itemName"
    />
    <!-- if diff restaurant -->
    <div
      class="modal fade filter-modal order-done"
      id="diffResModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <div class="footer-title sec-title">
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="text-center mt-5 mb-5">
              <p class="text-center large mb-5">{{ $t("emptyCart") }}</p>
              <div class="buttons d-flex">
                <button
                  type="button"
                  class="button btn-primary blue-btn whit-btn custom_width"
                  @click="replaceItem()"
                >
                  {{ $t("replaceItem") }}
                </button>
                <button
                  type="button"
                  class="button btn-primary blue-btn whit-btn custom_width"
                  @click="hideModal()"
                >
                  {{ $t("cancel") }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- order with unavailable options -->
    <div
      class="modal fade filter-modal order-done"
      id="errOptionModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <div class="footer-title sec-title">
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="text-center mt-5 mb-5">
              <p class="text-center large mb-5">
                <span v-for="deletedItem in deletedItems" :key="deletedItem.id">
                  <!-- {{ deletedItem }}
                  <hr />
                  {{ deletedItems }} -->
                  {{ $t("item") }} {{ deletedItem.itemName }}
                  {{ $t("deleted") }}
                  {{
                    deletedItem.order_items[0]
                      ? deletedItem.order_items[0].option_name
                      : ""
                  }}
                  {{ $t("unavailble") }}<br />
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { defineComponent } from "vue";
import Header from "@/components/Header.vue"; // @ is an alias to /src
// @ is an alias to /src
import axios from "axios";
import { Circle } from "vue-loading-spinner";
import RestaurantBreadcrumb from "@/components/RestaurantBreadcrumb.vue";
import EditItem from "./Cart/EditItem.vue";
import {
  selectedRestaurantCurrency,
  primariesNum,
  selectedItemData,
  selectedOrder,
  fetchItemData,
} from "./Cart/edit-selected-order";

export default defineComponent({
  setup() {
    return {
      editedItemData: selectedItemData,
      primariesNum,
      selectedOrder,
    };
  },
  data() {
    return {
      restaurant_cat: [],
      selectedCat: "",
      restID: "",
      restaurantName: "",
      restaurantRate: "",
      deliveryType: "",
      deliveryVal: "",
      orderTime: "",
      selectedCatItems: [],
      url: localStorage.getItem("imgURL"),
      itemData: [],
      secondaryOptions: [],
      item_size: "",
      secondaryQty: 1,
      addedSecondaryOptions: [],
      orders: JSON.parse(localStorage.getItem("myOrder")) || [],
      restaurantCurrency: "",
      isFollowed: false,
      user: localStorage.getItem("customerToken"),
      subTotal: "",
      editedItemOrder: [],
      loading: true,
      newOrder: [],
      status: [],
      deliveryData: [],
      restaurant: [],
      maxSelectableNum: false,
      maxSelectableNumCat: false,
      // array to put items which with unavalible options
      itemsErrOptions: [],
      deletedItems: [],
      sameItem: false,
      // array to push cat to check max selectable
      demoCatArr: [],
      catCount: 0,
    };
  },

  components: {
    Header,
    Circle,
    RestaurantBreadcrumb,
    EditItem,
  },

  mounted() {
    this.getItems();
    this.restaurantData();
  },
  methods: {
    updateOrders() {
      this.orders = JSON.parse(localStorage.getItem("myOrder")) || [];
    },
    //   get restaurant items
    async getItems() {
      this.loading = true;
      axios
        .get(`menu/${this.$route.params.id}`)
        .then((response) => {
          // console.log("res", response.data);
          this.restID = response.data.data.restaurant.id;
          this.deliveryType = response.data.data.restaurant.delivery_price.type;
          this.deliveryVal = response.data.data.restaurant.delivery_price.value;
          this.restaurant_cat = response.data.data.categories;
          this.restaurantName = response.data.data.restaurant.name;
          this.restaurantRate = response.data.data.restaurant.rate;
          this.orderTime = response.data.data.restaurant.prepare_order_time;
          this.restaurantCurrency = response.data.currency;
          selectedRestaurantCurrency.value = response.data.currency;
          this.selectedCat = this.restaurant_cat[0].name;
          if (this.orders.length != 0) {
            this.calc();
          }
          this.loading = false;
          // to display items when loaded
          this.selectTab(this.selectedCat);
        })
        .catch((errors) => {
          console.log(errors.data);
          this.loading = false;
        });
    },

    // selected category tab
    selectTab(tabName) {
      this.selectedCat = tabName;
      //   get category items
      this.loading = true;
      axios
        .get(`menu/${this.$route.params.id}`)
        .then((response) => {
          const catItems = response.data.data.categories;
          let x = catItems.filter((el) => {
            return el.name == tabName;
          });
          this.selectedCatItems = x[0].items;
          this.loading = false;
        })

        .catch((errors) => {
          console.log(errors.data);
          this.loading = false;
        });
    },

    // favourite & unfavourite item
    favBtn(itemId) {
      if (this.user) {
        axios
          .post(`items/favourite/${itemId}`)
          .then((response) => {
            this.selectTab(this.selectedCat);
          })
          .catch((errors) => {
            console.log(errors.data);
          });
      } else {
        this.$router.push("/Login");
      }
    },

    // get item data in modal
    getItemData(id) {
      // to removw max selectable msg
      this.maxSelectableNum = false;
      this.maxSelectableNumCat = false;
      this.itemData = {};
      axios
        .get(`menu/item/${id}`)
        .then((response) => {
          this.itemData = response.data.item;
          this.itemData = {
            ...response.data.item,
            // total count
            count: 1,
            // option primary
            primaries: "",
            // secondaries depending on primary
            secondaries: [],
            // secondares which i selected
            selectedSecondary: [],
            notes: null,
            // data of secondary which i select(array of object)
            secondaryArr: [],

            categories: [],
            catOptions: [],
            catOptionsArr: [],
          };
          console.log("item", this.itemData);
          if (this.itemData.option_template_id == null) {
            this.primariesNum = false;
          } else {
            this.primariesNum = true;
          }
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },

    // get options when choose size
    getOptions() {
      this.primariesNum = false;
      this.itemData.secondaries = this.itemData.primaries.secondary_option;
    },

    // get option value for option category
    getOptionsValue(id) {
      console.log("cat", this.itemData.categories, "id", id);

      // this.itemData.values = this.itemData.primaries.secondary_option;
    },

    getSecondaryArr(id, max_sel) {
      // this.itemData.secondaryArr = [];
      this.itemData.secondaries.map((el) => {
        if (el.id == id) {
          el.secondary_option_value.map((item) => {
            if (
              this.itemData.selectedSecondary.includes(
                item.option_secondary_id
              ) &&
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
            }
          });
        }
      });
      if (this.itemData.secondaryArr.length > max_sel) {
        this.maxSelectableNum = true;
      } else {
        this.maxSelectableNum = false;
      }
    },

    // fn to change qty when + or -
    getSecondaryArrCount() {
      let x = [];
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

    // add item to local storage
    addCart() {
      console.log(this.itemData.primaries);
      const cartItem = {
        restaurant_id: this.itemData.restaurant_id,
        item_id: this.itemData.id,
        item_qty: this.itemData.count,
        primary_value_option_id: this.itemData.primaries.id,
        primary_option_value_quantity: 1,
        note: this.itemData.notes,
        order_items: this.itemData.secondaryArr,
        itemName: this.itemData.name,
        restaurantName: this.itemData.restaurant_name,
        itemImg: this.itemData.image,
        item_price: this.itemData.price,
        // condition if item has offer
        ...(this.itemData.current_offer && {
          new_price: this.itemData.current_offer.new_price,
        }),
        restaurantCurrency: this.restaurantCurrency,
        categories: this.itemData.categories,
        catOptions: this.itemData.catOptions,
      };

      // check if we have same cartItem in cart or no
      this.orders.some((item, index) => {
        if (
          JSON.stringify(cartItem) == JSON.stringify(item) ||
          (cartItem.item_id == item.item_id &&
            JSON.stringify(cartItem.order_items) ==
              JSON.stringify(item.order_items) &&
            JSON.stringify(cartItem.item_qty) != JSON.stringify(item.item_qty))
        ) {
          this.orders[index].item_qty += cartItem.item_qty;
          localStorage.setItem("myOrder", JSON.stringify(this.orders));
          this.sameItem = true;
          return true;
        } else {
          this.sameItem = false;
        }
      });
      if (this.orders.length == 0 && this.sameItem == false) {
        this.orders.push(cartItem);
        localStorage.setItem("myOrder", JSON.stringify(this.orders));
        //  calc when add new item
        this.calc();
      } else if (
        this.orders.length != 0 &&
        cartItem.restaurant_id == this.orders[0].restaurant_id &&
        this.sameItem == false
      ) {
        this.orders.push(cartItem);
        localStorage.setItem("myOrder", JSON.stringify(this.orders));
        //  calc when add new item
        this.calc();
      } else if (
        this.orders.length != 0 &&
        cartItem.restaurant_id != this.orders[0].restaurant_id
      ) {
        window.$("#diffResModal").modal("show");
        this.newOrder.push(cartItem);
      }
    },

    // check max selectable number in cat
    checkMaxSelectable(selectedCat, id, max_sel) {
      this.catCount = 0;
      console.log("map", selectedCat, "", id);
      selectedCat.map((el) => {
        console.log(el.id);
        if (el.option_category_id == id) {
          this.catCount++;
          if (this.catCount > max_sel) {
            console.log("errrr");
            this.maxSelectableNumCat = true;
          } else {
            this.maxSelectableNumCat = false;
          }
        }
      });

      // if (selectedCat.option_category_id == id) {
      //   this.demoCatArr.push(selectedCat);
      //   console.log("array", this.demoCatArr);
      //   if (this.catCount > max_sel){

      //     this.maxSelectableNumCat = true;
      //   }
      //   console.log(this.catCount);
      // }
    },

    // hide diff res modal
    hideModal() {
      window.$("#diffResModal").modal("hide");
    },

    // get item data which want to edit
    geteditItem(order) {
      this.selectedOrder = {};
      setTimeout(() => {
        this.selectedOrder = order;
        fetchItemData();
      }, 70);
    },

    // update order
    updateOrder() {
      this.orders = JSON.parse(localStorage.getItem("myOrder"));
      const cartItem = {
        item_id: this.editedItemData.id,
        item_qty: this.editedItemData.count,
        note: this.editedItemData.notes,
      };
      this.orders.map((item, index) => {
        if (
          item.item_id == cartItem.item_id &&
          index == this.editedItemData.editedIndex
        ) {
          item.note = cartItem.note;
          item.item_qty = cartItem.item_qty;
        }
      });
      localStorage.setItem("myOrder", JSON.stringify(this.orders));
      //  calc when update
      this.calc();
    },

    // get restaurant data
    restaurantData() {
      axios
        .get(`restaurants/${this.$route.params.id}`)
        .then((response) => {
          this.isFollowed = response.data.restaurant.is_followed;
          this.status = response.data.restaurant.status;
          this.restaurant = response.data.restaurant;
          this.status = response.data.restaurant.status;
          this.deliveryData = response.data.restaurant.delivery_price;
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },

    // follow and unfollow restaurant
    follow(restID) {
      axios
        .post(`restaurants/follow/${restID}`)
        .then((response) => {
          this.isFollowed = !this.isFollowed;
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },

    // remove item from cart
    removeItem(index) {
      this.orders.splice(index, 1);
      localStorage.setItem("myOrder", JSON.stringify(this.orders));
      if (this.orders.length != 0) {
        // calc when remove item
        this.calc();
      }
    },

    // delete cart
    replaceItem() {
      this.orders = [];
      this.orders = this.newOrder;
      localStorage.setItem("myOrder", JSON.stringify(this.orders));
      // calc when replace item
      this.calc();
      window.$("#diffResModal").modal("hide");
    },

    // calculate your order
    calc() {
      let fd;
      fd = new FormData();
      fd.append("address_id", 37);
      fd.append("restaurant_id", this.restID);
      fd.append("service_info_type", 0);
      fd.append("payment_method", 1);
      this.orders.map((item, index) => {
        fd.append(`order_items[${index}][item_id]`, item.item_id);
        fd.append(`order_items[${index}][quantity]`, item.item_qty);
        fd.append(`order_items[${index}][note]`, item.note);
        if (item.primary_value_option_id) {
          fd.append(
            `order_items[${index}][primary_option_value_id]`,
            item.primary_value_option_id
          );
          fd.append(`order_items[${index}][primary_option_quantity]`, 1);
          item.order_items.map((el, idx) => {
            fd.append(
              `order_items[${index}][template_selected_options][${idx}][option_secondary_id]`,
              el.item_id
            );
            fd.append(
              `order_items[${index}][template_selected_options][${idx}][quantity]`,
              el.quantity
            );
          });
        } else if (item.categories) {
          // console.log("yes", item);
          item.catOptions.map((el, idx) => {
            fd.append(
              `order_items[${index}][selected_options][${idx}][option_vlaue_id]`,
              el.id
            );
            fd.append(
              `order_items[${index}][selected_options][${idx}][quantity]`,
              el.qnt ? el.qnt : 1
            );
          });
        }
      });
      axios
        .post("orders/calc-order-item", fd)
        .then((response) => {
          this.subTotal = response.data.data.sub_total;
        })
        .catch((errors) => {
          const Err = errors.response.data.errors;
          console.log("err", Err);
          for (const el in Err) {
            Err[el].map((item) => {
              if (
                item ==
                "some options are unavailable ,please reselect your options again"
              ) {
                let optionErrArr = el.split(".");
                let optionErrIdx = optionErrArr[1];
                this.itemsErrOptions.push(parseInt(optionErrIdx) + 1);
                this.deletedItems.push(this.orders[optionErrIdx]);
                // remove items with unavalible options from cart
                this.orders.splice(optionErrIdx, 1);
                localStorage.setItem("myOrder", JSON.stringify(this.orders));
              } else {
                this.$toast.error(item, {
                  position: "top-right",
                });
              }
            });
          }
          if (this.itemsErrOptions.length != 0) {
            window.$("#errOptionModal").modal("show");
            console.log("rrr", this.itemsErrOptions);
          }
        });
    },
  },
});
</script>
<style scoped>
.red {
  color: #e11919;
}

.edit {
  cursor: pointer;
}
.sm_font {
  font-size: 0.8em;
}
.valid_qty {
  opacity: 1;
  background-color: unset !important;
  color: #000;
}
.option_det {
  line-height: 1;
  font-size: 0.8em;
  color: #717171;
}
.custom_width {
  width: 175px;
}
.oldPrice {
  color: #f23737;
  font-size: 0.85em;
  -webkit-text-decoration-line: line-through;
  text-decoration-line: line-through;
}
.errMsg {
  color: #f23737;
  font-size: 0.9em;
}
.maxMsg {
  color: #717171;
  font-size: 0.9em;
}
</style>
