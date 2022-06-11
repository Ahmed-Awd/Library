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
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
              <h2>
                <strong>{{ $t("order") }}</strong>
                <span class="small-h2">#{{ order.order_number }}</span>
              </h2>
              <div class="row">
                <div class="col-md-12 mx-0">
                  <form id="msform">
                    <ul
                      id="progressbar"
                      class="canceled-progress"
                      v-if="orderStatus.id == 9"
                    >
                      <li class="active" id="account">
                        <strong>{{ $t("new") }}</strong>
                        <span><img src="images/icon-neworderw.png"/></span>
                        <div class="status-time">
                          {{ new Date(order.created_at).toLocaleTimeString() }}
                        </div>
                      </li>
                      <li>
                        <strong>{{ $t("rejected") }}</strong>
                        <span><img src="images/icon-cancel.png"/></span>
                      </li>
                    </ul>
                    <ul
                      id="progressbar"
                      v-if="orderStatus.id == 1 && order.order_type == 1"
                    >
                      <li class="active" id="account">
                        <strong>{{ $t("new") }}</strong>
                        <span><img src="images/icon-neworder.png"/></span>
                      </li>
                      <li id="personal">
                        <strong>{{ $t("inprogress") }}</strong>
                        <span><img src="images/icon-inprocess.png"/></span>
                      </li>
                      <li id="payment">
                        <strong>{{ $t("delivery") }}</strong>
                        <span><img src="images/icon-delivery.png"/></span>
                      </li>
                      <li id="confirm">
                        <strong>{{ $t("delivered") }}</strong>
                        <span><img src="images/icon-deliverd.png"/></span>
                      </li>
                    </ul>
                    <ul
                      id="progressbar"
                      v-if="orderStatus.id == 1 && order.order_type == 0"
                    >
                      <li class="active" id="account">
                        <strong>{{ $t("new") }}</strong>
                        <span><img src="images/icon-neworder.png"/></span>
                      </li>
                      <li id="personal">
                        <strong>{{ $t("inprogress") }}</strong>
                        <span><img src="images/icon-inprocess.png"/></span>
                      </li>
                      <li id="payment">
                        <strong>{{ $t("readyForTakaway") }}</strong>
                        <span><img src="images/icon-delivery.png"/></span>
                      </li>
                      <li id="confirm">
                        <strong>{{ $t("delivered") }}</strong>
                        <span><img src="images/icon-deliverd.png"/></span>
                      </li>
                    </ul>
                    <ul id="progressbar" v-if="orderStatus.id == 2">
                      <li class="active" id="account">
                        <strong>{{ $t("new") }}</strong>
                        <span><img src="images/icon-neworder.png"/></span>
                        <div class="status-time">
                          {{ new Date(order.created_at).toLocaleTimeString() }}
                        </div>
                      </li>
                      <li id="personal" class="active">
                        <strong>{{ $t("inprogress") }}</strong>
                        <span><img src="images/icon-inprocess.png"/></span>
                        <div class="status-time">
                          {{ new Date(order.updated_at).toLocaleTimeString() }}
                        </div>
                      </li>
                      <li id="payment">
                        <strong>Delivery</strong>
                        <span><img src="images/icon-delivery.png"/></span>
                      </li>
                      <li id="confirm">
                        <strong>Delivered</strong>
                        <span><img src="images/icon-deliverd.png"/></span>
                      </li>
                    </ul>
                    <ul
                      id="progressbar"
                      v-if="
                        orderStatus.id == 3 ||
                          orderStatus.id == 5 ||
                          orderStatus.id == 4
                      "
                    >
                      <li class="active" id="account">
                        <strong>{{ $t("new") }}</strong>
                        <span><img src="images/icon-neworder.png"/></span>
                        <div class="status-time">
                          {{ new Date(order.created_at).toLocaleTimeString() }}
                        </div>
                      </li>
                      <li id="personal" class="active">
                        <strong>{{ $t("inprogress") }}</strong>
                        <span><img src="images/icon-inprocess.png"/></span>
                      </li>
                      <li id="payment" class="active">
                        <strong>{{ $t("delivery") }}</strong>
                        <span><img src="images/icon-delivery.png"/></span>
                        <div class="status-time">
                          {{ new Date(order.updated_at).toLocaleTimeString() }}
                        </div>
                      </li>
                      <li id="confirm">
                        <strong>{{ $t("delivered") }}</strong>
                        <span><img src="images/icon-deliverd.png"/></span>
                      </li>
                    </ul>
                    <ul id="progressbar" v-if="orderStatus.id == 6">
                      <li class="active" id="account">
                        <strong>{{ $t("new") }}</strong>
                        <span><img src="images/icon-neworder.png"/></span>
                        <div class="status-time">
                          {{ new Date(order.created_at).toLocaleTimeString() }}
                        </div>
                      </li>
                      <li id="personal" class="active">
                        <strong>{{ $t("inprogress") }}</strong>
                        <span><img src="images/icon-inprocess.png"/></span>
                      </li>
                      <li id="payment" class="active">
                        <strong>{{ $t("readyForTakaway") }}</strong>
                        <span><img src="images/icon-delivery.png"/></span>
                        <div class="status-time">
                          {{ new Date(order.updated_at).toLocaleTimeString() }}
                        </div>
                      </li>
                      <li id="confirm">
                        <strong>{{ $t("delivered") }}</strong>
                        <span><img src="images/icon-deliverd.png"/></span>
                      </li>
                    </ul>
                    <ul
                      id="progressbar"
                      v-if="orderStatus.id == 7 && order.order_type == 1"
                    >
                      <li class="active" id="account">
                        <strong>{{ $t("new") }}</strong>
                        <span><img src="images/icon-neworder.png"/></span>
                        <div class="status-time">
                          {{ new Date(order.created_at).toLocaleTimeString() }}
                        </div>
                      </li>
                      <li id="personal" class="active">
                        <strong>{{ $t("inprogress") }}</strong>
                        <span><img src="images/icon-inprocess.png"/></span>
                      </li>
                      <li id="payment" class="active">
                        <strong>{{ $t("delivery") }}</strong>
                        <span><img src="images/icon-delivery.png"/></span>
                      </li>
                      <li id="confirm" class="active">
                        <strong>{{ $t("delivered") }}</strong>
                        <span><img src="images/icon-deliverd.png"/></span>
                        <div class="status-time">
                          {{ new Date(order.updated_at).toLocaleTimeString() }}
                        </div>
                      </li>
                    </ul>
                    <ul
                      id="progressbar"
                      v-if="orderStatus.id == 7 && order.order_type == 0"
                    >
                      <li class="active" id="account">
                        <strong>{{ $t("new") }}</strong>
                        <span><img src="images/icon-neworder.png"/></span>
                        <div class="status-time">
                          {{ new Date(order.created_at).toLocaleTimeString() }}
                        </div>
                      </li>
                      <li id="personal" class="active">
                        <strong>{{ $t("inprogress") }}</strong>
                        <span><img src="images/icon-inprocess.png"/></span>
                      </li>
                      <li id="payment" class="active">
                        <strong>{{ $t("readyForTakaway") }}</strong>
                        <span><img src="images/icon-delivery.png"/></span>
                      </li>
                      <li id="confirm" class="active">
                        <strong>{{ $t("delivered") }}</strong>
                        <span><img src="images/icon-deliverd.png"/></span>
                        <div class="status-time">
                          {{ new Date(order.updated_at).toLocaleTimeString() }}
                        </div>
                      </li>
                    </ul>
                    <!-- fieldsets -->
                    <fieldset>
                      <div class="order-history-block">
                        <h5>{{ $t("orderDetails") }}</h5>
                        <table class="table">
                          <tbody>
                            <tr>
                              <th scope="row">{{ $t("orderNumber") }}</th>
                              <td class="text-end green-color">
                                #{{ order.order_number }}
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">{{ $t("orderDate") }}</th>
                              <td class="text-end">
                                {{
                                  new Date(
                                    order.created_at
                                  ).toLocaleDateString()
                                }}
                                |
                                {{
                                  new Date(
                                    order.created_at
                                  ).toLocaleTimeString()
                                }}
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">{{ $t("totalOrder") }}</th>
                              <td class="text-end green-color">
                                {{ order.total_amount }}
                                {{ order.currency_code }}
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">
                                {{ $t("orderProceessingTime") }}
                              </th>
                              <td class="text-end">
                                {{ order.preparation_time }} min
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">{{ $t("orderType") }}</th>
                              <td class="text-end">
                                {{
                                  order.order_type == 0
                                    ? $t("takeaway")
                                    : $t("delivery")
                                }}
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">{{ $t("paymentMethods") }}</th>
                              <td class="text-end" v-if="paymentMethod">
                                {{ paymentMethod.name }}
                                <span
                                  class="sm_font"
                                  v-if="
                                    paymentMethod.name == 'bambora' &&
                                      isPay == null
                                  "
                                  >({{ $t("unpaid") }})</span
                                >
                              </td>
                            </tr>
                            <tr>
                              <th scope="row">{{ $t("resturant") }}</th>
                              <td class="text-end">{{ restaurant.name }}</td>
                            </tr>
                            <tr>
                              <th scope="row" colspan="2">
                                {{ $t("deliveryAddress") }}
                                <p class="note" v-if="address">
                                  {{ address.area }}
                                </p>
                              </th>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="order-details">
                        <h5>{{ $t("order") }}</h5>
                        <div class="row" v-for="item in items" :key="item.id">
                          <div class="col-lg-2 col-md-3 col-sm-3 col-4">
                            <img :src="`${url}${item.item.image}`" />
                          </div>
                          <div
                            class="col-lg-7 col-md-6 col-sm-6 col-5 align-self-center"
                          >
                            <span class="no">{{ item.quantity }} </span> x
                            <span>{{ item.name }}</span>
                            <span v-if="item.template_selected_options != null">
                              <p
                                class="meal-detal pe-1 mb-0 option_det"
                                v-for="option in item.template_selected_options
                                  .option_values
                                  .template_secondary_sptions_details[0]
                                  .option_values"
                                :key="option.id"
                              >
                                <span class="order-item-no"
                                  >{{ option.quantity }} x</span
                                >
                                {{
                                  option.default_secondary_option_item
                                    .secondary_option_value.name
                                }}
                                ({{
                                  option.default_secondary_option_item
                                    .secondary_option_value.price *
                                    option.quantity
                                }}
                                )
                              </p>
                            </span>
                          </div>
                          <div
                            class="col-lg-3 col-md-3 col-sm-3 col-3 justify-content-end align-self-center text-end"
                          >
                            <span class="green-color"
                              >{{ item.unit_price }} {{ order.currency_code }}
                            </span>
                          </div>
                          <hr class="Location-hr w-100" />
                        </div>
                      </div>
                      <button
                        v-if="orderStatus.id == 7"
                        type="button"
                        class="btn custom-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#ratingModal"
                      >
                        {{ $t("orderRating") }}
                      </button>
                    </fieldset>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Add Rate Modal -->
    <div
      class="modal fade evaluation-modal"
      id="ratingModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      ref="rateModal"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-body">
            <div class="footer-title sec-title">
              <h5>{{ $t("evaluation") }}</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="rest-block">
              <img src="images/pic1.png" />
              <div class="rest-desc">
                <div class="row">
                  <div class="col-md-10">
                    <h5>{{ restaurant.name }}</h5>
                    <p>{{ restaurant.address }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="star-ratings start-ratings-main">
              <star-rating
                :rounded-corners="true"
                :read-only="false"
                :rating="state.rating"
                v-model="state.rating"
                @update:rating="setRating"
                :star-size="18"
                inactive-color="#ddd"
                active-color="#8ac054"
                :padding="4"
              >
              </star-rating>
              <span class="error" v-if="v$.rating.$error">
                {{ v$.rating.$errors[0].$message }}
              </span>
            </div>
            <div class="mb-3">
              <label class="form-label">Notes</label>
              <textarea
                class="form-control"
                placeholder="Notes"
                rows="3"
                v-model="state.comment"
              ></textarea>
              <span class="error" v-if="v$.comment.$error">
                {{ v$.comment.$errors[0].$message }}
              </span>
            </div>
            <button
              type="button"
              class="btn custom-btn w-100"
              @click.prevent="evaluate()"
            >
              {{ $t("evaluation") }}
            </button>
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
import StarRating from "vue-star-rating";
import useVuelidate from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";
import { reactive, computed } from "vue";

export default defineComponent({
  components: {
    Header,
    Pannel,
    StarRating,
  },
  inject: ["file_url"],
  setup() {
    const state = reactive({
      rating: 0,
      comment: "",
    });
    const rules = computed(() => {
      return {
        rating: {
          required: helpers.withMessage(
            "The value must be between 1 & 5",
            required
          ),
        },
        comment: { required },
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
      url: localStorage.getItem("imgURL"),
      order: [],
      restaurant: [],
      address: [],
      items: [],
      orderStatus: [],
      rating: 0,
      comment: "",
      paymentMethod: [],
      isPay: "",
    };
  },
  mounted() {
    this.orderDetails();
  },
  methods: {
    orderDetails() {
      axios
        .get(`orders/${this.$route.params.id}`, { headers: this.headers })
        .then((response) => {
          this.order = response.data.order;
          this.restaurant = response.data.order.restaurant;
          this.address = response.data.order.address;
          this.items = response.data.order.order_item;
          this.orderStatus = response.data.order.order_status;
          this.paymentMethod = response.data.order.payment_method;
          this.isPay = response.data.order.payment;
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
    setRating: function(rating) {
      this.state.rating = rating;
    },
    hideModal() {
      this.state = {};
      this.$refs.rateModal.click();
    },
    evaluate() {
      const result = this.v$.$validate();
      const data = {
        rate: this.state.rating,
        comment: this.state.comment,
      };
      if (!this.v$.$error) {
        axios
          .post(`rate-order/${this.$route.params.id}`, data, {
            headers: this.headers,
          })
          .then((response) => {
            this.$toast.success(response.data.message, {
              position: "top-right",
            });
            this.hideModal();
          })
          .catch((errors) => {
            const Err = errors.response.data.errors;
            for (const el in Err) {
              Err[el].map((item) => {
                this.$toast.error(item, {
                  position: "top-right",
                });
              });
            }
          });
        // this.hideModal();
      }
    },
  },
});
</script>
<style scoped>
.sm_font {
  font-size: 0.9em;
  color: #ef1f1f;
}
</style>
