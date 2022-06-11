<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("orderHistory") }}</h1>
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
          <div class="col-lg-9 col-md-9 col-sm-12 col-12 align-self-center">
            <div v-if="loading && pageLoad == 0" class="loader white-bg">
              <Circle></Circle>
            </div>
            <div v-else>
              <div
                class="order-history-block"
                v-for="(order, index) in orderHistory"
                :key="index"
              >
                <div v-if="order.length != 0">
                  <router-link
                    :to="{ name: 'Order', params: { id: order.id } }"
                  >
                    <table class="table">
                      <tbody>
                        <tr>
                          <th scope="row">
                            {{ $t("orderNumber") }}
                          </th>
                          <td class="text-end green-color">
                            # {{ order.order_number }}
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">{{ $t("orderDate") }}</th>
                          <td class="text-end">
                            {{
                              new Date(order.created_at).toLocaleDateString()
                            }}
                            |
                            {{
                              new Date(order.created_at).toLocaleTimeString()
                            }}
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">{{ $t("totalOrder") }}</th>
                          <td class="text-end green-color">
                            {{ order.total_amount }}
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">{{ $t("resturant") }}</th>
                          <td class="text-end" v-if="order.restaurant">
                            {{ order.restaurant.name }}
                          </td>
                        </tr>
                        <tr>
                          <!-- TODO: Need to know the values of each status in both languages -->
                          <th scope="row">{{ $t("orderStatus") }}</th>
                          <td class="text-end">
                            <span
                              class="badge bg-secondary "
                              :class="
                                order.order_status_id == 9
                                  ? 'red-badge'
                                  : 'green-badge'
                              "
                              v-if="
                                order.order_status.name ==
                                  order.order_status.name
                              "
                            >
                              {{ order.order_status.name }}
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </router-link>
                </div>
                <button
                  type="button"
                  class="btn custom-btn small-btn mt-2"
                  data-bs-toggle="modal"
                  data-bs-target="#reorderModal"
                  @click.prevent="getOrderData(order.id, order.restaurant.id)"
                >
                  {{ $t("reorder") }}
                </button>
              </div>
            </div>
            <div
              :class="
                orderHistory.length == total
                  ? 'text-center hidden'
                  : 'text-center'
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
    <!-- Reorder Modal -->
    <div
      class="modal fade evaluation-modal"
      id="reorderModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      ref="rateModal"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-body">
            <div class="footer-title sec-title">
              <h5>{{ $t("reorder") }}</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="order-history-block">
              <table class="table">
                <tbody>
                  <tr>
                    <th scope="row">{{ $t("orderNumber") }}</th>
                    <td class="text-end green-color">
                      #{{ order.order_number }}
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">{{ $t("totalOrder") }}</th>
                    <td class="text-end green-color">
                      {{ order.total_amount }} {{ order.currency_code }}
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">{{ $t("resturant") }}</th>
                    <td class="text-end">{{ restaurant.name }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div
              class="text-center mb-3"
              v-if="
                restaurantStatus.id == 2
              "
            >
              <p>{{ $t("restaurantCloseMSg") }}</p>
              <p>{{ moment().format("MMMM Do YYYY, h:mm:ss a") }}</p>
            </div>
            <div v-else>
            <div class="mb-3">
              <label class="form-label" for="country">{{
                $t("addressess")
              }}</label>
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
              <span class="error" v-if="v$.address_id.$error">
                {{ v$.address_id.$errors[0].$message }}
              </span>
            </div>
            <div class="mb-3">
              <label class="form-label">{{ $t("serviceType") }}</label>
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
                />
                <label class="form-check-label" :for="service.id">{{
                  service.name
                }}</label>
              </div>
              <span class="error" v-if="v$.serviceID.$error">
                {{ v$.serviceID.$errors[0].$message }}
              </span>
            </div>     
            <!-- start payment method -->
            <h5 class="mb-3">{{ $t("payment_method") }}</h5>
            <div class="row radio-toolbar checkout-toolbar mb-4">
              <div
                class="col"
                v-for="method in paymentMethods"
                :key="method.id"
              >
                <input
                  type="radio"
                  :id="`pay${method.id}`"
                  :value="method.id"
                  v-model="state.paymentID"
                />
                <label :for="`pay${method.id}`" class="w-100"
                  ><img
                    v-if="method.id == 1"
                    :src="`images/icon-payment-method.png`"
                  />
                  <img v-else :src="`images/icon-credit-card.png`" />
                  {{ method.name }}
                </label>
              </div>
              <span class="error" v-if="v$.paymentID.$error">
                {{ v$.paymentID.$errors[0].$message }}
              </span>
            </div>
            <div
              class="white-bg shadow-sm p-3 mb-4"
              v-if="state.paymentID == 2"
            >
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
            <button
              type="button"
              class="btn custom-btn w-100"
              @click.prevent="reorder(order.id)"
            >
              {{ $t("reorder") }}
            </button>
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
// import OrderHistoryItem from "@/components/profile/OrderHistoryItem.vue"; // @ is an alias to /src
import axios from "axios";
import useVuelidate from "@vuelidate/core";
import { required, numeric, helpers } from "@vuelidate/validators";
import { reactive, computed } from "vue";
import { Circle } from "vue-loading-spinner";
// import moment from 'moment';

export default defineComponent({
  components: {
    Header,
    Pannel,
    Circle,
    // moment
  },
  setup() {
    const state = reactive({
      swishPhoneNo: "",
      serviceID: "",
      address_id: "",
      paymentID: "",
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
        serviceID: {
          required,
        },
        address_id: {
          required,
        },
        paymentID: {
          required,
        },
      };
    });

    const v$ = useVuelidate(rules, state);

    return {
      state,
      v$,
    };
  },
  data() {
    return {
      headers: {
        "Accept-Language": localStorage.getItem("appLang"),
      },
      currentPage: 1,
      orderHistory: [],
      serverParams: {
        page: 1,
        perPage: 15,
      },
      total: 0,
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
      addresses: [],

      order: [],
      restaurant: [],
      address: [],
      items: [],
      orderStatus: [],
      loading: false,
      pageLoad: 0,
      restaurantStatus: {},
      restaurantID: "",
      worktimes: [],
      today: new Date().toDateString(),
      startTime: "09:00",
      endTime: "13:00",
      x: 15, // minutes interval
      ap: ["AM", "PM"],
      times: [],
      days: [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
      ],
      paymentMethods: [],
    };
  },

  mounted() {
    this.getOrderHistory();
    this.getAddresses();
    this.getTimes();
    // this.moment();
  },
  methods: {
    onPageChange(params) {
      this.serverParams = Object.assign(
        {},
        this.serverParams,
        this.serverParams.page++
      );
      this.getOrderHistory();
    },
    getOrderHistory() {
      this.loading = true;
      axios
        .get("orders", {
          headers: this.headers,
          params: this.serverParams,
        })
        .then((response) => {
          // console.log(response.data.orders.data);
          response.data.orders.data &&
            (this.orderHistory = [
              ...this.orderHistory,
              ...response.data.orders.data,
            ]);
          this.total = response.data.orders.total;
          this.loading = false;
          this.pageLoad = 1;
        })
        .catch((errors) => {
          console.log(errors.data);
          this.loading = false;
        });
    },
    hideModal() {
      this.state = {};
      this.$refs.orderModal.click();
    },
    reorder(orderID) {
      const result = this.v$.$validate();
      // if (this.state.paymentID == 2) {
      //   // /let phone = this.state.swishPhoneNo;
      //   const result = this.v$.$validate();
      // }
      let fd;
      fd = new FormData();
      fd.append("address_id", this.state.address_id);
      fd.append("service_info_type", this.state.serviceID);
      fd.append("payment_method", this.state.paymentID);
      if (this.state.paymentID == 2) {
        fd.append("phone", this.state.swishPhoneNo);
      }

      // const data = {
      //   address_id: this.address_id,
      //   service_info_type: this.serviceID,
      //   payment_method: this.paymentID,
      //   phone: this.state.swishPhoneNo,
      // };
      if (
        (!this.v$.address_id.$error &&
          !this.v$.serviceID.$error &&
          !this.v$.swishPhoneNo.$error &&
          this.state.paymentID == 2) ||
        (!this.v$.address_id.$error &&
          !this.v$.serviceID.$error &&
          this.state.paymentID == 1)
      ) {
        axios
          .post(`orders/${orderID}/reorder`, fd, { headers: this.headers })
          .then((response) => {
            this.$toast.success(response.data.message, {
              position: "top-right",
            });
            window.$("#reorderModal").modal("hide");
          })
          .catch((errors) => {
            if (errors.response.data.message) {
              const errMsg = errors.response.data.message;
              if (!this.v$.$error) {
                this.$toast.error(errMsg, {
                  position: "top-right",
                });
              }
            }
          });
      } else if (
        !this.v$.address_id.$error &&
        !this.v$.serviceID.$error &&
        this.state.paymentID == 3
      ) {
        axios
          .post(`orders/${orderID}/reorder`, fd, { headers: this.headers })
          .then((response) => {
            console.log(response.data.url);
            window.location.href = response.data.url;
          })
          .catch((errors) => {
            if (errors.response.data.message) {
              const errMsg = errors.response.data.message;
              if (!this.v$.$error) {
                this.$toast.error(errMsg, {
                  position: "top-right",
                });
              }
            }
          });
      }
      // if (!this.v$.$error) {
      //   axios
      //     .post(`orders/${orderID}/reorder`, data, { headers: this.headers })
      //     .then((response) => {
      //       this.$toast.success(response.data.message, {
      //         position: "top-right",
      //       });
      //       this.hideModal();
      //     })
      //     .catch((errors) => {
      //       const Err = errors.response.data.errors;
      //       // console.log(errors.response.data);
      //       for (const el in Err) {
      //         Err[el].map((item) => {
      //           this.$toast.error(item, {
      //             position: "top-right",
      //           });
      //         });
      //       }
      //     });
      // }
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
    // set address id in local storage
    setAddress() {
      // console.log(this.address_id);
      localStorage.setItem("addressID", this.state.address_id);
      this.is_address = true;
    },
    // get order data in modal
    getOrderData(id, restID) {
      axios
        .get(`orders/${id}`)
        .then((response) => {
          this.order = response.data.order;
          this.restaurant = response.data.order.restaurant;
          this.address = response.data.order.address;
          this.items = response.data.order.order_item;
          this.orderStatus = response.data.order.order_status;
          this.restaurantStatus = response.data.order.restaurant.status;
          this.paymentMethods = response.data.order.restaurant.payment_methods;
          console.log("order", this.paymentMethods);
        })
        .catch((errors) => {
          console.log(errors.data);
        });

      axios
        .get(`restaurants/${restID}`)
        .then((response) => {
          this.worktimes = response.data.restaurant.work_time;
          var today = new Date();
          var dayName = this.days[today.getDay()];

          for (var i = 0; i < this.worktimes.length; i++) {
            if (this.worktimes[i].day.name == dayName) {
              (this.startTime = this.worktimes[i].open_from),
                (this.endTime = this.worktimes[i].open_to),
                this.getTimes();
            }
          }
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
    getTimes() {
      // // Copy date so don't affect original
      // let d = date;
      // // Get start of next block
      // let dMins = d.getMinutes();
      // let nextMin = Math.ceil(dMins/mins) * mins;
      // console.log(nextMin);
      // // If in first minute of first block, add 1
      // if (!(dMins%mins)) nextMin += mins;
      // d.setMinutes(nextMin, 0, 0);
      // let result = [];
      // let opts = {hour:'2-digit', minute:'2-digit', hour12:true};
      // while (num--) {
      //   result.push(d.toLocaleString('en', opts));
      //   d.setMinutes(d.getMinutes() + mins);
      // }
      // return result;
      // const current = new moment();
      // for(let i=0;i<5;i++){
      //     this.times.push(current.format("HH:mm"));
      //     current.add(15, "minutes");
      // }
    },
  },
});
</script>
