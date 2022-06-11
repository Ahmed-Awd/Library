<template>
  <div class="main-content">
    <breadcumb :page="$t('new-order')" :folder="$t('new-order')" />
    <!-- <b-form> -->
    <b-row>
      <b-col md="12">
        <b-card class="mb-30">
          <div class="row">
            <b-form-group :label="$t('restaurant')" class="text-6 col-md-6">
              <select class="form-control" @change="getItems($event)">
                <option disabled selected></option>
                <option
                  v-for="restaurant in restaurants"
                  :key="restaurant.id"
                  :value="restaurant.id"
                >
                  {{ restaurant.name }}
                </option>
              </select>
            </b-form-group>
            <b-form-group :label="$t('user')" class="text-6 col-md-6">
              <b-form-select
                class="form-control"
                v-model="user_id"
                :options="customers"
                text-field="name"
                value-field="id"
              >
              </b-form-select>
            </b-form-group>
          </div>
          <div class="row">
            <b-form-group :label="$t('address')" class="text-6 col-md-9">
              <b-form-select
                class="form-control"
                :options="addresses"
                v-model="address_id"
                text-field="title"
                value-field="id"
                required
              ></b-form-select>
            </b-form-group>
            <div class="col-md-3 align-self-end mb-2">
              <button
                class="btn btn-danger"
                type="button"
                @click="showAddAddressModal()"
              >
                {{ $t("add") }}
              </button>
            </div>
            <b-form-group
              :label="$t('ServiceInfoType')"
              class="text-6 col-md-4"
            >
              <b-form-select
                class="form-control"
                :options="types"
                v-model="serviceID"
                required
              ></b-form-select>
            </b-form-group>
          </div>
          <h4 class="mt-3 text-primary">{{ $t("items") }}</h4>
          <div class="row">
            <div class="col-md-3">
              <b-form-group :label="$t('categories')" class="text-6">
                <select class="form-control" @change="selectTab($event)">
                  <option disabled selected></option>
                  <option
                    v-for="item in itemsCategories"
                    :key="item.id"
                    :value="item.name"
                  >
                    {{ item.name }}
                  </option>
                </select>
              </b-form-group>
            </div>
            <div class="col-md-3">
              <b-form-group :label="$t('item')" class="text-6">
                <select class="form-control" @change="getItemData($event)">
                  <option disabled selected></option>
                  <option
                    v-for="item in selectedCatItems"
                    :key="item.id"
                    :value="item.id"
                  >
                    {{ item.name }}
                    <span class="sm_font"
                      >({{ item.price }} {{ restCurrency }})</span
                    >
                  </option>
                </select>
              </b-form-group>
            </div>
            <div class="col-md-3">
              <!-- item quantity -->
              <label>{{ $t("quantity") }}</label>
              <div>
                <div class="quantity buttons_added d-flex align-items-center">
                  <button
                    type="button"
                    value="+"
                    class="btn plus act-btn"
                    @click="
                      itemData.count++;
                      getSecondaryArrCount();
                    "
                  >
                    <i class="text-15 i-Add"></i>
                  </button>
                  <input
                    type="text"
                    min="1"
                    max=""
                    class="
                      input-text
                      qty
                      text
                      valid_qty
                      form-control
                      text-center
                    "
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
                    <i class="text-15 i-Remove"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- add to cart (to save items if they are >1) -->
            <div class="col-md-3">
              <b-button
                class="btn btn-primary mt-4"
                @click="
                  addCart();
                  calc();
                "
                :disabled="serviceID === ''"
              >
                <i class="nav-icon i-Add-Cart text-20 mr-1"></i
                >{{ $t("add") }}</b-button
              >
            </div>
          </div>
          <!-- display options depending on choosing item -->
          <div class="row">
            <div class="col-12">
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
                    <span class="main_color ml-2 sm_font"
                      >({{ el.price }} {{ itemData.currency }})</span
                    >
                  </div>
                </div>
                <hr />
                <div class="addtion-block" v-if="itemData.primaries">
                  <div v-for="sec in itemData.secondaries" :key="sec.name">
                    <h6>{{ sec.name }}</h6>
                    <div
                      class="form-check mt-2 d-flex align-items-center"
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
                        @change="getSecondaryArr(sec.id, sec.max_selectable)"
                      />
                      <label
                        class="form-check-label"
                        :for="el.option_secondary_id"
                      >
                        {{ el.name }}
                        <!-- --{{ el.qnt }}-- {{ el.max_count }} --
                        {{ itemData.count }} -->
                      </label>

                      <span class="main_color ml-2 sm_font"
                        >({{ el.price }} {{ itemData.currency }})</span
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
                            el.qnt++;
                            getSecondaryArr(sec.id, sec.max_selectable);
                          "
                        >
                          <i class="text-15 i-Add"></i>
                        </button>
                        <!-- {{ el.qnt }} - {{ el.max_count }} {{ itemData.count }} -->
                        <input
                          type="text"
                          min="1"
                          :max="el.max_count * itemData.count"
                          v-model="el.qnt"
                          @input="
                            el.qnt <= el.max_count * itemData.count ? el.qnt : 1
                          "
                          title="Qty"
                          class="input-text qty text valid_qty"
                          v-if="el.qnt <= el.max_count * itemData.count"
                          disabled
                        />
                        <button
                          type="button"
                          value="-"
                          class="btn minus act-btn"
                          :disabled="el.qnt == el.min_count"
                          @click.prevent="
                            el.qnt--;
                            getSecondaryArr(sec.id, sec.max_selectable);
                          "
                        >
                          <i class="text-15 i-Remove"></i>
                        </button>
                      </div>
                    </div>
                    <!-- max selctabble err msg -->
                    <span v-if="maxSelectableNum"
                      ><span class="errMsg"
                        >{{ $t("selectNum") }} {{ sec.max_selectable }}
                        {{ $t("option") }}</span
                      ></span
                    >
                    <hr class="my-3" />
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
                      :id="el.id"
                      @change="addCatValues()"
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
                  <div
                    v-for="el in itemData.categories"
                    :key="el.id"
                    class="mt-3"
                  >
                    <!-- {{ maxSelectableNumCat }} -->
                    <h6>
                      {{ el.name
                      }}<span class="ms-2"
                        >( {{ $t("choose") }} {{ el.max_selectable }}
                        {{ $t("option") }} )</span
                      >
                    </h6>
                    <div class="row mx-0">
                      <div class="col-12">
                        <div v-for="value in el.option_values" :key="value.id">
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
                              );
                              addCatValues();
                            "
                          />
                          <label class="form-check-label ms-2" :for="value.id">
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
                            <!-- {{ value.qnt }} -->

                            <button
                              type="button"
                              value="+"
                              class="btn plus act-btn"
                              :disabled="
                                value.qnt >= value.max_count * itemData.count
                              "
                              @click="value.qnt ? value.qnt++ : (value.qnt = 2)"
                            >
                              <i class="text-15 i-Add"></i>
                            </button>

                            <input
                              type="text "
                              min="1"
                              :max="value.max_count * itemData.count"
                              v-model="value.qnt"
                              title="Qty"
                              class="input-text qty text valid_qty"
                              v-if="
                                value.qnt <= value.max_count * itemData.count
                              "
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
                              <i class="text-15 i-Remove"></i>
                            </button>
                          </div>
                        </div>
                        <!-- max selctabble err msg -->
                        <span v-if="maxSelectableNumCat"
                          ><span class="errMsg"
                            >{{ $t("selectNum") }} {{ el.max_selectable }}
                            {{ $t("option") }}</span
                          ></span
                        >
                      </div>
                    </div>

                    <hr />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-5">
              <b-form-group :label="$t('note')" class="text-6">
                <textarea
                  rows="3"
                  class="form-control"
                  v-model="new_order.note"
                ></textarea>
              </b-form-group>
            </div>
          </div>
          <hr />

          <!-- show order -->
          <div class="row" v-if="orders.length != 0">
            <div class="col-12">
              <h4 class="text-primary">{{ $t("order") }}</h4>
              <div v-for="(order, index) in orders" :key="order.id">
                <span class="deleteItem mr-2" @click="removeItem(index)">
                  <i class="text-20 i-Remove-Cart"></i>
                </span>
                <span class="order-item-no text-primary"
                  >{{ order.item_qty }} x</span
                >
                <span class="ml-1">{{ order.itemName }}</span>
              </div>
              <!-- {{ orders }} -->
            </div>
          </div>
          <hr />

          <!-- get price (calculations) -->
          <div class="row" v-if="orders.length != 0">
            <div class="col-12">
              <h4 class="text-primary">{{ $t("getPrice") }}</h4>
            </div>
            <div class="col-12">
              <div class="d-flex">
                <span class="mb-0 text-primary">{{ $t("subtotal") }} :</span>
                <span class="ml-2">
                  {{ parseFloat(subTotal).toFixed(2) }} {{ restCurrency }}
                </span>
              </div>
              <div class="d-flex">
                <span class="mb-0 text-primary">{{ $t("minPrice") }} :</span>
                <span class="ml-2">
                  {{ parseFloat(minPrice).toFixed(2) }} {{ restCurrency }}
                </span>
              </div>
              <div class="d-flex">
                <span class="mb-0 text-primary">{{ $t("deliveryFee") }} :</span>
                <span class="ml-2">
                  {{ parseFloat(deliveryFee).toFixed(2) }} {{ restCurrency }}
                </span>
              </div>
              <div class="d-flex">
                <span class="mb-0 text-primary">{{ $t("totalTax") }} :</span>
                <span class="ml-2">
                  {{ parseFloat(total_tax).toFixed(2) }} {{ restCurrency }}
                </span>
              </div>
              <div class="d-flex">
                <span class="mb-0 text-primary">{{ $t("total") }} :</span>
                <span class="ml-2">
                  {{ parseFloat(total).toFixed(2) }} {{ restCurrency }}
                </span>
              </div>
            </div>
          </div>

          <!-- buttons -->
          <div class="text-right">
            <b-button
              type="submit"
              tag="button"
              class="btn btn-primary mt-4 mr-2"
              variant="primary mt-2"
              @click="placeOrder()"
              :disabled="orders.length == 0"
            >
              {{ $t("create") }}
            </b-button>
            <b-button class="btn btn-primary mt-4 mr-2">{{
              $t("cancel")
            }}</b-button>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <!-- new address modal -->
    <b-modal
      ref="create-address-modal"
      hide-footer
      title="New Address"
      class="modal"
    >
      <div class="row">
        <div class="form-group col-sm-6">
          <label for="" class="col-form-label">{{ $t("type") }}</label>
          <b-form-select
            class="form-control"
            v-model="new_address.type"
            :options="addressType"
            text-field="text"
            value-field="value"
          >
          </b-form-select>
        </div>
        <div class="form-group col-sm-6">
          <label for="" class="col-form-label">{{ $t("zipCode") }}</label>
          <input
            type="text"
            class="form-control"
            v-model="new_address.ZIP_code"
          />
        </div>
      </div>
      <div class="row">
        <div class="form-group col-sm-12">
          <label for="" class="col-form-label">{{ $t("address") }}</label>
          <input type="text" class="form-control" v-model="new_address.title" />
        </div>
      </div>
      <div class="row">
        <div class="form-group col-sm-6">
          <label for="" class="col-form-label">{{ $t("area") }}</label>
          <input type="text" class="form-control" v-model="new_address.area" />
        </div>
        <div class="form-group col-sm-6">
          <label for="" class="col-form-label">{{ $t("Apartment") }}</label>
          <input
            type="text"
            class="form-control"
            v-model="new_address.Apartment"
          />
        </div>
      </div>
      <div class="row">
        <div class="form-group col-sm-12">
          <label for="" class="col-form-label">{{ $t("description") }}</label>
          <input
            type="text"
            class="form-control"
            v-model="new_address.description"
          />
        </div>
      </div>
      <div class="row">
        <b-form-group :label="$t('searchPin')" class="text-6 col-md-12">
          <GmapAutocomplete @place_changed="setPlace" class="form-control" />
        </b-form-group>
      </div>
      <GmapMap
        :center="center"
        :zoom="12"
        :clickable="true"
        :draggable="true"
        map-type-id="terrain"
        style="width: 100%; height: 300px; margin: 20px auto"
        @click="setMarker"
      >
        <GmapMarker
          :key="index"
          v-for="(m, index) in markers"
          :position="m.position"
          @click="center = m.position"
        />
      </GmapMap>
      <div class="text-right">
        <b-button
          type="submit"
          tag="button"
          @click.prevent="createAddress"
          class="btn btn-primary mt-4 mr-3"
          variant="primary mt-2"
        >
          {{ $t("create") }}
        </b-button>
        <b-button class="btn btn-primary mt-4" @click="hideAddAddressModal">{{
          $t("cancel")
        }}</b-button>
      </div>
    </b-modal>
  </div>
</template>
<script>
import axios from "axios";
// import option from "./option.vue";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Orders",
  },
  props: {
    defaultLocation: Object,
  },
  data() {
    return {
      modalShow: false,
      from: 1,
      to: 15,
      serverParams: {
        page: 1,
        perPage: 15,
      },
      totalRecords: 0,
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
        Accept: "application/json",
      },
      items: [],
      new_order: {},
      types: [
        { value: 0, text: "takeaway" },
        { value: 1, text: "delivery" },
      ],
      addresses: [],
      restaurants: [],
      new_address: {},
      center: { lat: 45.508, lng: -73.587 },
      currentPlace: null,
      markers: [],
      marker: {},
      places: [],
      addressType: [
        { value: "home", text: "Home" },
        { value: "work", text: "Work" },
        { value: "other", text: "Other" },
      ],
      customers: [],
      itemsCategories: [],
      selectedCatItems: [],
      restID: "",
      itemData: [],
      restCurrency: "",
      maxSelectableNum: "",
      orders: [],
      subTotal: "",
      total: "",
      deliveryFee: "",
      total_tax: "",
      minPrice: "",
      address_id: "",
      serviceID: "",
      user_id: "",
      catCount: "",
    };
  },
  watch: {
    itemData: function () {
      this.$forceUpdate();
    },
  },
  mounted() {
    this.getAddresses();
    this.geolocate();
    this.getUser();
    this.getRestaurant();
  },
  methods: {
    getAddresses() {
      axios
        .get("addresses", { headers: this.headers })
        .then((response) => {
          this.addresses = response.data.addresses;
          console.log(this.addresses);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showAddAddressModal() {
      this.new_address = {};
      console.log(this.new_address);
      this.$refs["create-address-modal"].show();
    },
    hideAddAddressModal() {
      this.new_address = {};
      this.$refs["create-address-modal"].hide();
    },
    geolocate: function () {
      navigator.geolocation.getCurrentPosition((position) => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
      });
      this.markers.push({ position: this.center });
    },
    setPlace(place) {
      this.currentPlace = place;
      console.log(place);
      this.address = place.formatted_address;
      this.addMarker();
    },
    addMarker(event) {
      if (this.currentPlace) {
        const marker = {
          lat: this.currentPlace.geometry.location.lat(),
          lng: this.currentPlace.geometry.location.lng(),
        };
        this.markers[0].position = marker;
        this.center = marker;
        this.currentPlace = null;
      }
    },
    setMarker(event) {
      console.log(event);
      const marker = {
        lat: event.latLng.lat(),
        lng: event.latLng.lng(),
      };
      this.markers[0].position = marker;
      this.center = marker;
      this.location = event.latLng;
      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location: this.location }, (results, status) => {
        console.log(results);
        if (status === "OK" && results.length > 0) {
          this.address = results[0].formatted_address;
          console.log(results[0]);
          for (var i = 0; i < results[0].address_components.length; i++) {
            for (
              var j = 0;
              j < results[0].address_components[i].types.length;
              j++
            ) {
              if (results[0].address_components[i].types[j] == "postal_code") {
                this.new_restaurant.ZIP_code =
                  results[0].address_components[i].long_name;
              }
            }
          }
        } else {
          console.error("Geocoding request status: " + status);
          console.error(results);
        }
      });
    },
    createAddress() {
      let fd;
      fd = new FormData();
      fd.append("title", this.new_address.title);
      fd.append("area", this.new_address.area);
      fd.append("description", this.new_address.description);
      fd.append("ZIP_code", this.new_address.ZIP_code);
      fd.append("Apartment", this.new_address.Apartment);
      fd.append("type", this.new_address.type);
      fd.append("lat", this.markers[0].position.lat);
      fd.append("lng", this.markers[0].position.lng);
      axios
        .post("addresses", fd, { headers: this.headers })
        .then((response) => {
          this.makeToast("success", $t("successOpertion"));
          this.getAddresses();
          this.hideAddAddressModal();
        })
        .catch((errors) => {
          const ErrMsg = errors.response.data.message;
          const Err = errors.response.data.errors;
          console.log(Err);
          for (const el in Err) {
            Err[el].map((item) => {
              this.makeToast("warning", item);
            });
          }
          // console.log(errors.data);
          // this.makeToast("warning", "create failed");
        });
    },
    getUser() {
      axios
        .get("customers", { headers: this.headers })
        .then((response) => {
          this.customers = response.data.users.data;
          console.log(this.customers);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    getRestaurant() {
      axios
        .get("restaurants/all/lite", {
          headers: this.headers,
        })
        .then((response) => {
          this.restaurants = response.data.restaurants;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    getItems(event) {
      this.restID = event.target.value;
      // restaurant id is event.target.value
      console.log("id", this.restID);

      axios
        .get(`menu/${this.restID}`, { headers: this.headers })
        .then((response) => {
          this.itemsCategories = response.data.data.categories;
          this.restCurrency = response.data.currency;
          this.restaurantData();
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },

    // selected category tab
    selectTab(event) {
      // name of selected cat is event.target.value
      this.selectedCat = event.target.value;
      //   get category items
      axios
        .get(`menu/${this.restID}`)
        .then((response) => {
          const catItems = response.data.data.categories;
          let x = catItems.filter((el) => {
            return el.name == event.target.value;
          });
          this.selectedCatItems = x[0].items;
        })

        .catch((errors) => {
          console.log(errors.data);
        });
    },

    // get item data in modal
    getItemData(event) {
      this.maxSelectableNum = false;
      this.maxSelectableNumCat = false;
      console.log("id", event.target.value);
      axios
        .get(`menu/item/${event.target.value}`)
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
            categories: [],
            catOptions: [],
            catOptionsArr: [],
          };
          console.log("item", this.itemData);
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },

    // get options when choose size
    getOptions() {
      const _data = this.itemData.primaries.secondary_option.map((el) => {
        return (el = {
          ...el,
          secondary_option_value: el.secondary_option_value.map((item) => {
            return (item = {
              ...item,
              qnt: 1,
            });
          }),
        });
      });
      this.itemData.secondaries = _data;
    },

    getSecondaryArr(id, max_sel) {
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
      this.itemData.secondaryArr = x;
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
      //  condition if diff restaurants
      if (
        this.orders.length != 0 &&
        cartItem.restaurant_id != this.orders[0].restaurant_id
      ) {
        this.makeToast("warning", this.$t("samRes"));
      } else {
        this.orders.push(cartItem);
        // this.makeToast("success", this.$t("itemAdded"));
      }
    },

    // calculate your order
    calc() {
      let fd;
      fd = new FormData();
      fd.append("address_id", this.address_id);
      fd.append("restaurant_id", this.restID);
      fd.append("service_info_type", this.serviceID);
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
        .post("admin/orders/calc-order-item", fd, { headers: this.headers })
        .then((response) => {
          this.subTotal = response.data.data.sub_total;
          this.deliveryFee = response.data.data.delivery_fee;
          this.total = response.data.data.total_amount;
          this.total_tax = response.data.data.total_tax;
          this.minPrice = response.data.data.min_order_price;
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
              } else {
                this.makeToast("warning", item);
              }
            });
          }
        });
    },

    // remove item from cart
    removeItem(index) {
      this.orders.splice(index, 1);
      if (this.orders.length != 0) {
        // calc when remove item
        this.calc();
      }
    },

    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },

    // place order
    placeOrder() {
      let fd;
      fd = new FormData();
      fd.append("user_id", this.user_id);
      fd.append("address_id", this.address_id);
      fd.append("restaurant_id", this.orders[0].restaurant_id);
      fd.append("service_info_type", this.serviceID);
      if (this.discount_code_id) {
        fd.append("discount_code_id", this.discount_code_id);
      }
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
        .post("admin/orders", fd, { headers: this.headers })
        .then((response) => {
          this.orderID = response.data.order.id;
          // this.orderPlaced = true;
          this.makeToast("success", this.$t("orderPlaced"));
        })
        .catch((errors) => {
          if (errors) {
            const errMsg = errors.response.data.message;
            this.makeToast("warning", errMsg);
          }
        });
    },

    // here we add qnt option to el.option_values to read it from virtualDom
    addCatValues() {
      this.itemData.categories.map((el) => {
        el.option_values = el.option_values.map((item) => {
          return (item = {
            ...item,
            qnt: 1,
          });
        });
      });
    },

    // check max selectable number in cat
    checkMaxSelectable(selectedCat, id, max_sel) {
      this.catCount = 0;
      selectedCat.map((el) => {
        console.log(el.id);
        if (el.option_category_id == id) {
          this.catCount++;
          if (this.catCount > max_sel) {
            this.maxSelectableNumCat = true;
          } else {
            this.maxSelectableNumCat = false;
          }
        }
      });
    },
  },
};
</script>
<style>
label.checkbox {
  margin-left: 1.1rem;
}

.delete-btn {
  cursor: pointer;
}

.mb-10 {
  margin-bottom: 10px;
}

.d-inline-flex {
  display: inline-flex;
  align-items: center;
}
.modal-backdrop {
  z-index: 1000 !important;
}
.pac-container {
  z-index: 1055 !important;
}
.sm_font {
  font-size: 0.9em !important;
}
.main_color {
  color: #8ac054;
}
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
.minus,
.plus {
  background-color: transparent;
}
.deleteItem {
  color: #ef4444;
  cursor: pointer;
}
.errMsg {
  color: #ef4444;
}
.valid_qty {
  text-align: center;
}
</style>
