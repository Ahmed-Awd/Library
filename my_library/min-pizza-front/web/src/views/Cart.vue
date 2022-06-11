<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("cart") }}</h1>
          <h4>{{ $t("min_pizza") }}</h4>
        </div>
      </div>
    </div>
    <div class="about-content white-bg">
      <div class="container">
        <div class="row" v-if="myOrders.length != 0">
          <!-- start order price -->
          <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="copoun-div mb-2">
              <div>
                <label class="form-label" for="country"
                  ><h5>{{ $t("discount_code") }}</h5></label
                >
                <select
                  class="form-select"
                  id="country"
                  v-model="discount_code_id"
                  @change="setCode()"
                >
                  <option
                    v-for="code in discountCodes"
                    :key="code"
                    :value="code.id"
                  >
                    {{ code.code }}
                  </option>
                </select>
              </div>
            </div>
            <div class="copoun-div">
              <div>
                <label class="form-label" for="country"
                  ><h5>{{ $t("addressess") }}</h5></label
                >
                <select
                  class="form-select"
                  id="country"
                  v-model="state.address_id"
                  @change="setAddress()"
                >
                  <option
                    v-for="address in addresses"
                    :key="address.id"
                    :value="address.id"
                  >
                    {{ address.title }} ({{ address.translated_type }})
                  </option>
                </select>
              </div>
              <div class="text-center mt-2">
                <button
                  type="button"
                  class="btn btn-primary custom-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#addressModal"
                >
                  {{ $t("addAddress") }}
                </button>
              </div>
              <span class="error" v-if="v$.address_id.$error">
                {{ v$.address_id.$errors[0].$message }}
              </span>
            </div>
            <div class="service_type my-3 ">
              <div class="d-flex">
                <h5>{{ $t("serviceType") }} :</h5>
                <div
                  class="form-check ml-3"
                  v-for="service in services"
                  :key="service"
                >
                  <input
                    type="radio"
                    class="form-check-input"
                    name="restCheckbox"
                    v-model="state.serviceID"
                    :value="service.id"
                    :id="service.id"
                    @change="
                      is_service_type = true;
                      setServiceID();
                    "
                  />
                  <label class="form-check-label" :for="service.id">{{
                    service.name
                  }}</label>
                </div>
              </div>
              <span class="error" v-if="v$.serviceID.$error">
                {{ v$.serviceID.$errors[0].$message }}
              </span>
            </div>
            <div class="calculations my-4">
              <button
                type="button"
                class="btn btn-primary blue-btn"
                :disabled="myOrders.length == 0"
                @click.prevent="calc()"
              >
                {{ $t("getPrice") }}
              </button>
            </div>

            <!-- order calc -->
            <div
              class="order-history-block"
              v-if="price && myOrders.length != 0"
            >
              <h5>{{ $t("order_details") }}</h5>
              <table class="table">
                <tbody>
                  <tr>
                    <th scope="row">{{ $t("min_price") }}</th>
                    <td class="text-end">
                      {{ parseFloat(min_order_price).toFixed(2) }}
                      {{ restaurantCurrency }}
                    </td>
                  </tr>
                  <tr v-if="min_order_ad">
                    <th scope="row">{{ $t("min_price_ad") }}</th>
                    <td class="text-end">
                      {{ parseFloat(min_order_ad).toFixed(2) }}
                      {{ restaurantCurrency }}
                    </td>
                  </tr>
                  <tr v-if="min_order_ad">
                    <td colspan="2">
                      <span class="note red-color"
                        >{{ $t("difrenceMsg") }}
                        {{ parseFloat(min_order_price).toFixed(2) }}
                        {{ restaurantCurrency }}</span
                      >
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">{{ $t("subtotal") }}</th>
                    <td class="text-end green-color">
                      {{ parseFloat(subTotal).toFixed(2) }}
                      {{ restaurantCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">{{ $t("delivery_fee") }}</th>
                    <td class="text-end green-color">
                      {{ parseFloat(delivery_fee).toFixed(2) }}
                      {{ restaurantCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">{{ $t("discount") }}</th>
                    <td class="text-end green-color">
                      {{ parseFloat(discount).toFixed(2) }}
                      {{ restaurantCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">{{ $t("total_tax") }}</th>
                    <td class="text-end green-color">
                      {{ parseFloat(total_tax).toFixed(2) }}
                      {{ restaurantCurrency }}
                    </td>
                  </tr>

                  <tr>
                    <th scope="row">
                      <h5>
                        {{ $t("total") }}
                        <span class="small">({{ $t("moms") }} )</span>
                      </h5>
                    </th>
                    <td class="text-end green-color">
                      <h5 class="green-color">
                        {{ parseFloat(total_amount).toFixed(2) }}
                        {{ restaurantCurrency }}
                      </h5>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- start order data -->
          <div class="col-lg-8 col-md-8 col-sm-12 col-12">
            <div>
              <div
                class="favourite-block"
                v-for="(order, index) in myOrders"
                :key="order.itemName"
              >
                <div class="row">
                  <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                    <img :src="`${url}${order.itemImg}`" />
                  </div>
                  <div class="col-lg-5 col-md-5 col-sm-6 col-5">
                    <h5 class="mb-0">
                      <span>{{ order.itemName }}</span>
                      &nbsp;
                      <button
                        type="button"
                        @click="showEditModal(order)"
                        class="btn plus act-btn"
                      >
                        <i class="fas fa-pencil-alt"></i>
                      </button>
                    </h5>

                    <p class="rest-name">{{ order.restaurantName }}</p>
                    <p
                      class="meal-detal pe-1 mb-0 option_det"
                      v-for="option in order.order_items"
                      :key="option.id"
                    >
                      <span class="order-item-no">{{ option.quantity }} x</span>
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
                    <a
                      class="remove-cart-item"
                      @click.prevent="removeItem(index)"
                      ><img src="images/icon-tras.png" />
                      {{ $t("removeItem") }}</a
                    >
                  </div>
                  <div
                    class="col-lg-4 col-md-4 col-sm-6 col-7 align-self-center justify-content-end text-end"
                  >
                    <div class="quantity buttons_added cart-quan">
                      <button
                        type="button"
                        value="+"
                        class="btn plus act-btn"
                        @click="
                          order.item_qty++;
                          updateItemQty();
                        "
                      >
                        <i class="fas fa-plus"></i>
                      </button>
                      <input
                        type="text"
                        min="1"
                        name="quantity"
                        v-model="order.item_qty"
                        title="Qty"
                        class="input-text qty text valid_qty"
                        size="4"
                        pattern=""
                        inputmode=""
                        disabled
                      />
                      <button
                        type="button"
                        class="btn minus act-btn"
                        @click="
                          order.item_qty--;
                          updateItemQty();
                        "
                        :disabled="order.item_qty == 1"
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
        <div class="row mb-5" v-else>
          <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <img src="images/warning.png" alt="" />
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <!-- empty cart -->
            <div class="error-div">
              <h1 class="green-h">{{ $t("min_pizza") }}</h1>
              <p>{{ $t("noItemsCart") }}</p>
            </div>
          </div>
        </div>

        <!-- buttons -->
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <router-link
              to="/"
              class="btn btn-primary blue-btn w-100 mm-b-3"
              @click="removeCashedCode()"
              >{{ $t("continue_shopping") }}</router-link
            >
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <router-link
              to="/Checkout"
              class="btn btn-primary custom-btn w-100"
              :class="{
                disabled:
                  myOrders.length == 0 || !is_address || !is_service_type,
              }"
              >{{ $t("checkout") }}</router-link
            >
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

    <!-- Add Address Modal -->
    <div
      class="modal fade evaluation-modal address-modal"
      id="addressModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      ref="addressModal"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-body">
            <div class="footer-title sec-title">
              <h5>{{ $t("addAdress") }}</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="add-location-map">
              <div id="map" class="full-location-map">
                <GMapMap
                  :center="center"
                  :zoom="12"
                  map-type-id="terrain"
                  style="width: 500px; height: 300px"
                  @click="setMarker"
                >
                  <GMapCluster>
                    <GMapMarker
                      :key="index"
                      v-for="(m, index) in markers"
                      :position="m.position"
                      :clickable="true"
                      :draggable="true"
                      @click="center = m.position"
                      @dragend="getMarkerLocation($event)"
                    />
                  </GMapCluster>
                </GMapMap>
              </div>
              <div class="row justify-content-center search-location">
                <div class="col-lg-7 col-md-8 col-sm-9 col-9">
                  <form v-on:submit.prevent>
                    <GMapAutocomplete
                      placeholder="Find Location"
                      @place_changed="setPlace"
                      name="search"
                      class="form-control"
                      @keydown.enter.prevent
                      :position="markers[0].position"
                      ref="address"
                    >
                    </GMapAutocomplete>
                  </form>
                </div>
                <div class="col-lg-1 col-md-2 col-sm-3 col-3">
                  <button
                    type="button"
                    class="btn git-location-btn"
                    @click="geolocate"
                  >
                    <!-- <img src="images/icon-gps.png" /> -->
                    <i class="fas fa-map-marker-alt"></i>
                  </button>
                </div>
              </div>
            </div>
            <form>
              <div class="mb-3">
                <label class="form-label">{{ $t("title") }}</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="astate.title"
                />
                <span class="error" v-if="av$.title.$error">
                  {{ av$.title.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-3">
                <label class="form-label">{{ $t("description") }}</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="astate.description"
                  disabled
                />
                <span class="error" v-if="av$.description.$error">
                  {{ av$.description.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-3">
                <label class="form-label">{{ $t("area") }}</label>
                <input type="text" class="form-control" v-model="astate.area" />
                <span class="error" v-if="av$.area.$error">
                  {{ av$.area.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-3">
                <label class="form-label">{{ $t("locationType") }}</label>
                <select
                  class="form-select"
                  aria-label="Default select example"
                  v-model="astate.type"
                >
                  <option selected></option>
                  <option value="home">{{ $t("optionHome") }}</option>
                  <option value="work">{{ $t("optionWork") }}</option>
                  <option value="other">{{ $t("optionOther") }}</option>
                </select>
                <span class="error" v-if="av$.type.$error">
                  {{ av$.type.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-3">
                <label class="form-label">{{ $t("portCode") }}</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="astate.ZIP_code"
                />
                <span class="error" v-if="av$.ZIP_code.$error">
                  {{ av$.ZIP_code.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-3">
                <label class="form-label">{{ $t("apartment") }}</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="astate.Apartment"
                />
              </div>
              <!-- <p class="note mb-0">Delivery Address</p>
                        <p>Full Delivery Address</p> -->
              <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <button
                    class="btn btn-primary blue-btn w-100"
                    @click.prevent="save()"
                  >
                    {{ $t("save") }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <EditItem v-if="selectedOrder && selectedOrder.itemName" />
  </section>
</template>

<script>
import { defineComponent } from "vue";
import Header from "@/components/Header.vue"; // @ is an alias to /src
import EditItem from "./Cart/EditItem.vue";
// @ is an alias to /src
import axios from "axios";
import useVuelidate from "@vuelidate/core";
import { reactive, computed } from "vue";
import { required, minLength, maxLength } from "@vuelidate/validators";
import {
  primariesNum,
  selectedItemData,
  selectedOrder,
  fetchItemData,
  myOrders,
} from "./Cart/edit-selected-order";

export default defineComponent({
  components: {
    Header,
    EditItem,
  },
  data() {
    return {
      discountCodes: [],
      addresses: [],
      restID: "",
      restaurantCurrency: "",
      price: false,
      url: localStorage.getItem("imgURL"),
      services: [
        {
          id: 0,
          name: this.$t("takeaway"),
        },
        {
          id: 1,
          name: this.$t("delivery"),
        },
      ],
      min_order_price: "",
      min_order_ad: "",
      subTotal: "",
      delivery_fee: "",
      total_amount: "",
      discount: "",
      total_tax: "",
      discount_code_id: "",
      is_address: false,
      is_service_type: false,
      farDistance: false,
      // array to put items which with unavalible options
      itemsErrOptions: [],
      deletedItems: [],
      // address
      center: { lat: 51.093048, lng: 6.84212 },
      currentPlace: null,
      address: null,
      markers: [
        {
          position: {
            lat: 51.093048,
            lng: 6.84212,
          },
        },
      ],
      places: [],
    };
  },
  setup() {
    const state = reactive({
      serviceID: "",
      address_id: "",
    });

    const rules = computed(() => {
      return {
        serviceID: { required },
        address_id: { required },
      };
    });

    const v$ = useVuelidate(rules, state);

    const astate = reactive({
      title: "",
      lat: null,
      lng: null,
      area: null,
      description: "",
      ZIP_code: "",
      Apartment: null,
      type: "",
    });

    const arules = computed(() => {
      return {
        title: { required, minLength: minLength(3), maxLength: maxLength(100) },
        lat: { required },
        lng: { required },
        type: { required },
        description: { required },
        area: { maxLength: maxLength(30) },
        Apartment: { maxLength: maxLength(30) },
        ZIP_code: {
          required,
          minLength: minLength(3),
          maxLength: maxLength(15),
        },
      };
    });

    const av$ = useVuelidate(arules, astate);

    return {
      itemData: selectedItemData,
      primariesNum,
      selectedOrder,
      state,
      v$,
      astate,
      av$,
      myOrders,
    };
  },
  mounted() {
    this.discountCode();
    this.getOrder();
    this.getAddresses();
    // this.geolocate();
  },
  methods: {
    // remove cashed code
    removeCashedCode() {
      localStorage.removeItem("discCode");
    },
    // get discount codes
    discountCode() {
      axios
        .get("my/discount-codes")
        .then((response) => {
          console.log(response.data);
          this.discountCodes = response.data.codes;
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },

    // get my orders from local storage
    getOrder() {
      this.myOrders = JSON.parse(localStorage.getItem("myOrder"));
      if (this.myOrders && this.myOrders.length != 0) {
        this.restID = this.myOrders[0].restaurant_id;
        this.restaurantCurrency = this.myOrders[0].restaurantCurrency;
      }
    },

    // get addresses
    getAddresses() {
      axios
        .get("addresses/")
        .then((response) => {
          this.addresses = response.data.addresses;
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },

    // calculate your order
    calc() {
      const result = this.v$.$validate();
      let fd;
      fd = new FormData();
      fd.append("address_id", this.state.address_id);
      fd.append("restaurant_id", this.myOrders[0].restaurant_id);
      fd.append("service_info_type", this.state.serviceID);
      fd.append("payment_method", 1);
      fd.append("discount_code_id", this.discount_code_id);
      this.myOrders.map((item, index) => {
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
          console.log("yes", item.catOptions);
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
      if (!this.v$.$error) {
        axios
          .post("orders/calc-order-item", fd)
          .then((response) => {
            this.price = true;
            console.log("calc", response.data);
            this.min_order_price = response.data.data.min_order_price;
            this.min_order_ad =
              response.data.data.minimum_order_adjustment_amount;
            this.subTotal = response.data.data.sub_total;
            this.delivery_fee = response.data.data.delivery_fee;
            this.total_amount = response.data.data.total_amount;
            this.discount = response.data.data.coupon_discount;
            this.total_tax = response.data.data.total_tax;
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
                  this.deletedItems.push(this.myOrders[optionErrIdx]);
                  // remove items with unavalible options from cart
                  this.myOrders.splice(optionErrIdx, 1);
                  localStorage.setItem(
                    "myOrder",
                    JSON.stringify(this.myOrders)
                  );
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
      }
    },

    // remove item from cart
    removeItem(index) {
      console.log("index", index);
      this.myOrders.splice(index, 1);
      localStorage.setItem("myOrder", JSON.stringify(this.myOrders));
    },

    // update itemQty in local storage
    updateItemQty() {
      localStorage.setItem("myOrder", JSON.stringify(this.myOrders));
    },

    // set address id in local storage
    setAddress() {
      localStorage.setItem("addressID", this.state.address_id);
      this.is_address = true;
    },

    // set address id in local storage
    setCode() {
      if (this.discount_code_id) {
        localStorage.setItem("discCode", this.discount_code_id);
      } else {
        localStorage.removeItem("discCode");
      }
    },

    // set service type id in local storage
    setServiceID() {
      localStorage.setItem("service_id", this.state.serviceID);
    },

    // address map
    save() {
      const result = this.av$.$validate();
      if (!this.av$.$error) {
        axios
          .post("addresses", this.astate)
          .then((response) => {
            this.$toast.success(response.data.message, {
              position: "top-right",
            });
            this.getAddresses();
            this.hideModal();
          })
          .catch((errors) => {
            if (errors.response.data.errors) {
              const Err = errors.response.data.errors;
              for (const el in Err) {
                Err[el].map((item) => {
                  this.$toast.error(item, {
                    position: "top-right",
                  });
                });
              }
            } else {
              const errMsg = errors.response.data.message;
              this.$toast.error(errMsg, {
                position: "top-right",
              });
            }
          });
      }
    },
    getMarkerLocation(event) {
      console.log(event);
      const marker = {
        lat: event.latLng.lat(),
        lng: event.latLng.lng(),
      };
      this.state.lat = event.latLng.lat();
      this.state.lng = event.latLng.lng();

      this.markers[0].position = marker;
      this.center = marker;
      this.location = event.latLng;
      console.log(event.latLng);
      // eslint-disable-next-line no-undef
      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location: this.location }, (results, status) => {
        console.log(results);
        if (status === "OK" && results.length > 0) {
          this.address = results[0].formatted_address;
          this.state.description = results[0].formatted_address;
          this.state.area = results[0].address_components[1].long_name;
          console.log(results[0]);
          for (let i = 0; i < results[0].address_components.length; i++) {
            for (
              let j = 0;
              j < results[0].address_components[i].types.length;
              j++
            ) {
              if (results[0].address_components[i].types[j] == "postal_code") {
                this.state.ZIP_code =
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
    setPlace(place) {
      console.log(place);
      this.currentPlace = place;
      this.address = place.formatted_address;
      this.astate.description = place.formatted_address;
      this.astate.area = place.address_components[0].long_name;
      this.addMarker();
    },
    addMarker() {
      if (this.currentPlace) {
        this.astate.lat = this.currentPlace.geometry.location.lat();
        this.astate.lng = this.currentPlace.geometry.location.lng();
        this.astate.ZIP_code = "";

        const marker = {
          lat: this.currentPlace.geometry.location.lat(),
          lng: this.currentPlace.geometry.location.lng(),
        };

        this.markers[0].position = marker;
        this.center = marker;
        this.location = marker;
        // eslint-disable-next-line no-undef
        let geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: this.location }, (results, status) => {
          console.log(results);
          if (status === "OK" && results.length > 0) {
            this.address = results[0].formatted_address;
            this.astate.description = results[0].formatted_address;
            this.astate.area = results[0].address_components[1].long_name;
            console.log(results[0]);
            for (let i = 0; i < results[0].address_components.length; i++) {
              for (
                let j = 0;
                j < results[0].address_components[i].types.length;
                j++
              ) {
                if (
                  results[0].address_components[i].types[j] == "postal_code"
                ) {
                  this.state.ZIP_code =
                    results[0].address_components[i].long_name;
                }
              }
            }
          } else {
            console.error("Geocoding request status: " + status);
            console.error(results);
          }
        });
        this.currentPlace = null;
      }
    },
    geolocate: function() {
      navigator.geolocation.getCurrentPosition((position) => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        this.markers[0].position = this.center;
        this.location = this.center;
        this.getStreetAddressFrom(
          position.coords.latitude,
          position.coords.longitude
        );
      });
      this.addMarker();
    },
    setMarker(event) {
      this.astate.ZIP_code = "";
      console.log(event);
      const marker = {
        lat: event.latLng.lat(),
        lng: event.latLng.lng(),
      };
      this.markers[0].position = marker;
      this.center = marker;
      this.location = event.latLng;

      this.astate.lat = event.latLng.lat();
      this.astate.lng = event.latLng.lng();

      // eslint-disable-next-line no-undef
      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location: this.location }, (results, status) => {
        console.log(results);
        if (status === "OK" && results.length > 0) {
          this.address = results[0].formatted_address;
          this.astate.description = results[0].formatted_address;
          this.astate.area = results[0].address_components[1].long_name;
          this.$refs.address.$refs.input.value = results[0].formatted_address;
          console.log(results[0]);
          for (let i = 0; i < results[0].address_components.length; i++) {
            for (
              let j = 0;
              j < results[0].address_components[i].types.length;
              j++
            ) {
              if (results[0].address_components[i].types[j] == "postal_code") {
                this.astate.ZIP_code =
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
    getStreetAddressFrom(lat, long) {
      let googleURl =
        "https://maps.googleapis.com/maps/api/geocode/json?language=" +
        localStorage.getItem("appLang") +
        "&latlng=" +
        lat +
        "," +
        long +
        "&key=AIzaSyB_-R-N4JMQQfUveMj6YAZPrHgbldFkTSg&libraries=places";

      let httpWithOutHeader = axios;
      delete httpWithOutHeader.defaults.headers;

      httpWithOutHeader
        .get(googleURl)
        .then((response) => {
          this.astate.description = response.data.results[0].formatted_address;
          this.astate.area =
            response.data.results[0].address_components[1].long_name;
          this.$refs.address.$refs.input.value =
            response.data.results[0].formatted_address;
        })
        .catch((er) => {
          console.log(er);
        });
    },
    showEditModal(order) {
      this.selectedOrder = {};
      setTimeout(() => {
        this.selectedOrder = order;
        fetchItemData();
      }, 70);
    },
    hideModal() {
      window.$("#addressModal").modal("hide");
    },
  },
});
</script>
<style>
.remove-cart-item {
  cursor: pointer;
}
.disabled {
  opacity: 0.5;
  pointer-events: none;
  background-color: #000;
  border-color: #000;
}
.valid_qty {
  opacity: 1;
  background-color: unset !important;
  color: #000;
}
/* .address-modal {
  z-index: 1050 !important; 
}
.modal-backdrop {
  z-index: 1040 !important; 
} */
.pac-container.pac-logo {
  z-index: 1051 !important; /* 1000 */
}
</style>
