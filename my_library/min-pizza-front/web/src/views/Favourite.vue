<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("myFav") }}</h1>
          <h4>{{ $t("min_pizza") }}</h4>
        </div>
      </div>
    </div>
    <div class="about-content white-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-12 col-12">
            <Pannel />
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-12">
            <div v-if="loading && pageLoad == 0" class="loader white-bg">
              <Circle></Circle>
            </div>
            <div v-else>
              <div class="item-block"
                v-for="(item, index) in favItems"
                :key="index"
              >
                <a
                    href="#"
                    class="delete-favourite-block"
                    @click.prevent="deleteItem(item.id, index)"
                    ><i class="fas fa-times"></i
                  ></a>
                <div
                  class="favourite-block"
                  data-bs-toggle="modal"
                  data-bs-target="#exampleModal"
                  @click.prevent="getItemData(item.id)"
                >
                  <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                      <img :src="`${url}${item.image}`" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                      <h5>{{ item.name }}</h5>
                      <router-link :to="`/restaurantMenu/${item.category.menu.restaurant.id}`">
                      <p class="rest-name">
                        {{ item.category.menu.restaurant.name }}
                      </p>
                      </router-link>
                      <div class="clearfix">
                        <p class="rest-descption">{{ item.description }}</p>
                      </div>
                    </div>
                    <div
                      class="col-lg-3 col-md-3 col-sm-6 col-6 align-self-end justify-content-end"
                    >
                      <div class="text-end">
                        <span class="price"
                          >{{ item.price }} {{ item.currency }}</span
                        >
                        <button
                          type="button"
                          class="cart"
                          :disabled="item.is_available == 0 ? true : false"
                        >
                          <span
                            class=""
                            ><img src="images/icon-shopbag.png"
                          /></span>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <div
              :class="
                favItems.length == total ? 'text-center hidden' : 'text-center'
              "
            >
              <button
                class="btn btn-primary blue-btn mt-3"
                @click="onPageChange"
              >
                {{ $t("loadMore") }}
              </button>
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
                    <span class="price green-color"
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
              <h5 class="mt-5">{{ $t("choose") }}</h5>
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
                </div>
              </div>
              <div class="addtion-block" v-if="itemData.primaries">
                <!-- {{ itemData.secondaries }}
                <hr />
                {{ itemData.selectedSecondary }}
                <hr />-->
                <!-- {{ itemData.secondaryArr }} -->
                <div v-for="sec in itemData.secondaries" :key="sec.name">
                  <h6>{{ sec.name }}</h6>
                  <div
                    class="form-check mt-2"
                    v-for="el in sec.secondary_option_value"
                    :key="el.option_secondary_id"
                  >
                    <input
                      class="form-check-input"
                      type="checkbox"
                      name="flexRadioDefault"
                      :id="el.option_secondary_id"
                      v-model="itemData.selectedSecondary"
                      :value="el.option_secondary_id"
                      @change="getSecondaryArr(sec.id)"
                    />
                    <label
                      class="form-check-label"
                      :for="el.option_secondary_id"
                    >
                      {{ el.name }}
                    </label>

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
                          getSecondaryArr(sec.id);
                        "
                      >
                        <i class="fas fa-plus"></i>
                      </button>

                      <input
                        type="text"
                        min="1"
                        :max="el.max_count * itemData.count"
                        v-model="el.qnt"
                        title="Qty"
                        class="input-text qty text"
                        v-if="el.qnt"
                      />

                      <input
                        type="text"
                        min="1"
                        :max="el.max_count * itemData.count"
                        value="1"
                        title="Qty"
                        class="input-text qty text"
                        v-else
                      />

                      <button
                        type="button"
                        value="-"
                        class="btn minus act-btn"
                        :disabled="el.qnt == el.min_count"
                        @click="
                          el.qnt--;
                          getSecondaryArr(sec.id);
                        "
                      >
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="addition-block">
                <div class="mb-3">
                  <label class="form-label">{{ $t("add_notes") }}</label>
                  <textarea
                    class="form-control"
                    :placeholder="$t('add_notes')"
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
                    :disabled="itemData.primaries == []"
                    @click="addCart()"
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
                      class="input-text qty text"
                      v-model="itemData.count"
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
  </section>
  
</template>

<script>
import { defineComponent } from "vue";
import Header from "@/components/Header.vue"; // @ is an alias to /src
 // @ is an alias to /src
import Pannel from "@/components/Pannel.vue"; // @ is an alias to /src
import axios from "axios";
import {Circle} from 'vue-loading-spinner'

export default defineComponent({
  components: {
    Header,
    Pannel,
    Circle
  },
  data() {
    return {
      headers: {
        "Accept-Language": localStorage.getItem("appLang"),
      },
      disable: false,
      favItems: [],
      pageSize: 15,
      currentPage: 1,
      serverParams: {
        page: 1,
        perPage: 15,
      },
      total: 0,
      url: localStorage.getItem("imgURL"),
      itemData: [],
      restID: "",
      restaurantCurrency: "",
      orders: JSON.parse(localStorage.getItem("myOrder")) || [],
      loading: false,
      pageLoad: 0,
    };
  },
  mounted() {
    this.getFav();
  },
  methods: {
    onPageChange(params) {
      this.serverParams = Object.assign(
        {},
        this.serverParams,
        this.serverParams.page++
      );
      this.getFav();
    },
    getFav() {
      this.loading = true;
      axios
        .get("my-favourite-items", {
          params: this.serverParams,
        })
        .then((response) => {
          console.log("fav", response.data);

          response.data.favoriteItems.data &&
            (this.favItems = [
              ...this.favItems,
              ...response.data.favoriteItems.data,
            ]);
          this.total = response.data.favoriteItems.total;
          this.loading = false;
          this.pageLoad = 1;
        })
        .catch((errors) => {
          console.log(errors.data);
          this.loading = false;
        });
    },
    // Remove Fvourite Item
    async deleteItem(itemId, index) {
      await axios
        .post(`items/favourite/${itemId}`)
        .then((response) => {
          // this.getFav();
          // this.$delete(this.favItems, index)
          this.favItems.splice(index, 1);
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },

    // get item data in modal
    getItemData(id) {
      axios
        .get(`menu/item/${id}`)
        .then((response) => {
          this.itemData = response.data.item;
          this.itemData = {
            ...response.data.item,
            count: 1,
            primaries: "",
            secondaries: [],
            selectedSecondary: [],
            notes: null,
            secondaryArr: [],
          };
          this.restID = response.data.item.restaurant_id;
          this.restaurantCurrency = response.data.item.currency;
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },

    // get options when choose size
    getOptions() {
      this.itemData.secondaries = this.itemData.primaries.secondary_option;
    },

    getSecondaryArr(id) {
      this.itemData.secondaryArr = [];
      let x = [];
      this.itemData.secondaries.map((el) => {
        if (el.id == id) {
          el.secondary_option_value.map((item) => {
            if (
              this.itemData.selectedSecondary.includes(item.option_secondary_id)
            ) {
              x.push({
                sec_id: el.id,
                item_id: item.option_secondary_id,
                quantity: item.qnt ? item.qnt : 1,
                option_name: item.name,
              });
            }
          });
        }
      });
      this.itemData.secondaryArr = x;
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
              });
            } else {
              x.push({
                item_id: item.option_secondary_id,
                sec_id: el.id,
                quantity: item.qnt ? item.qnt : 1,
              });
            }
          }
        });
      });
      this.itemData.secondaryArr = x;
    },

    // add item to local storage
    addCart() {
      const cartItem = {
        restaurant_id: this.restID,
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
        restaurantCurrency: this.restaurantCurrency,
      };
      this.orders.push(cartItem);
      localStorage.setItem("myOrder", JSON.stringify(this.orders));
    },
  },
});
</script>
