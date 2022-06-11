<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("checkout") }}</h1>
          <h4>{{ $t("min_pizza") }}</h4>
        </div>
      </div>
    </div>
    <div class="about-content gray-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 col-md-7 col-sm-12 col-12">
            <!-- start payment method -->
            <h5 class="mb-3">{{ $t("payment_method") }}</h5>
            <div class="row radio-toolbar checkout-toolbar mb-4">
              <div
                class="col"
                v-for="method in restaurantPayment"
                :key="method.id"
              >
                <input
                  type="radio"
                  :id="method.id"
                  :value="method.id"
                  v-model="paymentID"
                  @change="calc()"
                />
                <label :for="method.id"
                  ><img
                    v-if="method.id == 1"
                    :src="`images/icon-payment-method.png`"
                  />
                  <img
                    v-else-if="method.id == 2"
                    :src="`images/swish-paymentb.png`"
                  />
                  <img v-else :src="`images/icon-credit-card.png`" />
                  {{ method.name }}
                </label>
              </div>
            </div>
            <div class="white-bg shadow-sm p-3 mb-4" v-if="paymentID == 2">
              <h5 class="mb-3">{{ $t("swish_payment") }}</h5>
              <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">{{
                  $t("phoneNo")
                }}</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="state.swishPhoneNo"
                  placeholder="Telx:0761665765"
                />
              </div>

              <span class="error" v-if="v$.swishPhoneNo.$error">
                {{ v$.swishPhoneNo.$errors[0].$message }}
              </span>
            </div>
            <!-- end payment method -->

            <!-- your order details -->
            <h5 class="mb-3">{{ $t("your_order") }}</h5>
            <div
              class="favourite-block white-bg shadow-sm"
              v-for="order in myOrders"
              :key="order.itemName"
            >
              <div class="row align-items-center">
                <div class="col-lg-4 col-md-3 col-sm-12 col-12">
                  <img :src="`${url}${order.itemImg}`" />
                </div>
                <div class="col-lg-5 col-md-5 col-sm-8 col-8">
                  <h5 class="mb-0">{{ order.itemName }}</h5>
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
                  <!-- <a href="#" class="remove-cart-item add-note"
                    ><img src="images/icon-edit.png" /> Add Note</a
                  > -->
                </div>
                <div
                  class="col-lg-3 col-md-4 col-sm-4 col-4 align-self-center justify-content-end text-end"
                >
                  <span class="price ml-3"
                    >{{ order.item_price }} {{ restaurantCurrency }}
                  </span>
                  <p class="small text-end">
                    {{ $t("qty") }} {{ order.item_qty }}
                  </p>
                </div>
                <div
                  class="col-lg-12 col-md-12 col-sm-12 col-12 note-div hidden"
                >
                  <label for="exampleFormControlTextarea1" class="form-label"
                    >Notes</label
                  >
                  <textarea
                    class="form-control"
                    placeholder="Notes"
                    rows="3"
                  ></textarea>
                </div>
              </div>
            </div>
          </div>
          <div
            class="offset-lg-1 col-lg-4 col-md-5 col-sm-12 col-12 align-self-center"
          >
            <div class="order-history-block white-bg shadow-sm">
              <h5>{{ $t("order_details") }}</h5>
              <table class="table">
                <tbody>
                  <tr>
                    <th scope="row">{{ $t("subtotal") }}</th>
                    <td class="text-end green-color">
                      {{ subTotal }} {{ restaurantCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">{{ $t("delivery_fee") }}</th>
                    <td class="text-end">
                      {{ delivery_fee }} {{ restaurantCurrency }}
                    </td>
                  </tr>
                  <tr class="">
                    <th scope="row" class="pb-4">{{ $t("discount") }}</th>
                    <td class="text-end red-color pb-4">
                      {{ discount }} {{ restaurantCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">
                      <h5>{{ $t("total") }}</h5>
                    </th>
                    <td class="text-end green-color">
                      <h5 class="green-color">
                        {{ total_amount }} {{ restaurantCurrency }}
                      </h5>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="address-block shadow-sm p-3 rounded white-bg">
              <div class="clearfix address-title">
                <div class="float-left">
                  <h5>{{ $t("ship_to") }}</h5>
                </div>
              </div>
              <p>{{ addresses.title }} ({{ addresses.translated_type }})</p>
              <div class="row">
                <div class="col-12 align-self-center">
                  <GMapMap
                    :center="{
                      lat: parseFloat(addresses.lat),
                      lng: parseFloat(addresses.lng),
                    }"
                    :zoom="7"
                    map-type-id="terrain"
                    style="width: 500px; height: 300px"
                  >
                  </GMapMap>
                </div>
              </div>
            </div>
            <a
              type="button"
              class="btn btn-primary blue-btn w-100 mt-5"
              @click="placeOrder()"
              :class="{
                disabled: !paymentID || orderPlaced || farDistance,
              }"
              >{{ $t("placeOrder") }}</a
            >
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- success order modal -->
  <div
    class="modal fade filter-modal order-done"
    id="successModal"
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
            <img src="images/icon-successfully2.png" />
            <h5 class="text-center mt-3">{{ $t("thankYou") }}</h5>
            <p class="text-center large mb-5">{{ $t("forOrder") }}</p>
            <router-link
              :to="`/Order/${orderID}`"
              class="button btn-primary blue-btn whit-btn"
              tag="buttopn"
              @click="hideModal()"
              >{{ $t("trackOrder") }}</router-link
            >
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
</template>

<script>
import { defineComponent } from "vue";
import Header from "@/components/Header.vue"; // @ is an alias to /src
// @ is an alias to /src
import axios from "axios";
import useVuelidate from "@vuelidate/core";
import { required, numeric, helpers } from "@vuelidate/validators";
import { reactive, computed } from "vue";

export default defineComponent({
  data() {
    return {
      myOrders: "",
      restID: "",
      restaurantCurrency: "",
      discount_code_id: localStorage.getItem("discCode"),
      serviceID: localStorage.getItem("service_id"),
      min_order_price: "",
      subTotal: "",
      delivery_fee: "",
      total_amount: "",
      discount: "",
      total_tax: "",
      url: localStorage.getItem("imgURL"),
      address_id: localStorage.getItem("addressID"),
      addresses: "",
      paymentID: "",
      orderPlaced: false,
      farDistance: false,
      restaurantPayment: [],
      // array to put items which with unavalible options
      itemsErrOptions: [],
      deletedItems: [],
    };
  },
  components: {
    Header,
  },

  setup() {
    const state = reactive({
      swishPhoneNo: "",
    });

    const phoneLen = (value) => value.length == 9;
    const rules = computed(() => {
      return {
        swishPhoneNo: {
          required,
          numeric,
          phoneLen: helpers.withMessage(
            "Phone number must be 9 numbers",
            phoneLen
          ),
        },
      };
    });

    const v$ = useVuelidate(rules, state);

    return {
      state,
      v$,
    };
  },

  watch: {
    orderPlaced: function(newVal) {
      this.orderPlaced = newVal;
    },
  },

  mounted() {
    this.getOrder();
    if (this.myOrders) {
      this.calc();
    }
    this.addressDetails();
    this.restaurantData();
  },

  methods: {
    // get my orders from local storage
    getOrder() {
      this.myOrders = JSON.parse(localStorage.getItem("myOrder"));
      if (this.myOrders && this.myOrders.length != 0) {
        this.restID = this.myOrders[0].restaurant_id;
        this.restaurantCurrency = this.myOrders[0].restaurantCurrency;
      }
    },

    // calculate your order
    calc() {
      let fd;
      fd = new FormData();
      fd.append("address_id", this.address_id);
      fd.append("restaurant_id", this.myOrders[0].restaurant_id);
      fd.append("service_info_type", this.serviceID);
      fd.append("payment_method", this.paymentID);
      if (this.discount_code_id) {
        fd.append("discount_code_id", this.discount_code_id);
      }
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
          this.price = true;
          console.log("calc", response);
          this.min_order_price = response.data.data.min_order_price;
          this.subTotal = response.data.data.sub_total;
          this.delivery_fee = response.data.data.delivery_fee;
          this.total_amount = response.data.data.total_amount;
          this.discount = response.data.data.coupon_discount;
          this.total_tax = response.data.data.total_tax;
        })
        .catch((error) => {
          this.farDistance = true;
          if (error.response.data.message) {
            const errMsg = error.response.data.message;
            this.$toast.error(errMsg, {
              position: "top-right",
            });
          } else {
            const Err = error.response.data.errors;
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
          }
        });
    },

    // get address details
    addressDetails() {
      axios
        .get(`addresses/${this.address_id}`)
        .then((response) => {
          this.addresses = response.data.data;
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },

    // place order
    placeOrder() {
      const result = this.v$.$validate();
      let fd;
      fd = new FormData();
      fd.append("address_id", this.address_id);
      fd.append("restaurant_id", this.myOrders[0].restaurant_id);
      fd.append("service_info_type", this.serviceID);
      fd.append("payment_method", this.paymentID);
      if (this.discount_code_id) {
        fd.append("discount_code_id", this.discount_code_id);
      }
      if (this.paymentID == 2) {
        fd.append("phone", this.state.swishPhoneNo);
      }
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
      if ((!this.v$.$error && this.paymentID == 2) || this.paymentID == 1) {
        axios
          .post("orders", fd)
          .then((response) => {
            this.orderID = response.data.order.id;
            this.orderPlaced = true;
            window.$("#successModal").modal("show");
            localStorage.removeItem("myOrder");
            localStorage.removeItem("discCode");
          })
          .catch((errors) => {
            if (errors.response.data.message) {
              const errMsg = errors.response.data.message;
              this.$toast.error(errMsg, {
                position: "top-right",
              });
            }
          });
      } else if (this.paymentID == 3) {
        axios
          .post("orders", fd)
          .then((response) => {
            console.log(response.data.url);
            window.location.href = response.data.url;
            // this.orderID = response.data.order.id;
            // this.orderPlaced = true;
            // window.$("#successModal").modal("show");
            localStorage.removeItem("myOrder");
            localStorage.removeItem("discCode");
          })
          .catch((errors) => {
            if (errors.response.data.message) {
              const errMsg = errors.response.data.message;
              this.$toast.error(errMsg, {
                position: "top-right",
              });
            }
          });
      }
    },

    // hide success modal
    hideModal() {
      window.$("#successModal").modal("hide");
    },

    // get restaurant data
    restaurantData() {
      axios
        .get(`restaurants/${this.myOrders[0].restaurant_id}`)
        .then((response) => {
          this.restaurantPayment = response.data.restaurant.payment_methods;
          console.log("pay", this.restaurantPayment);
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
  },
});
</script>
<style scoped>
.checkout-toolbar label {
  width: 100%;
}
.disabled {
  opacity: 0.5;
  pointer-events: none;
}
</style>
