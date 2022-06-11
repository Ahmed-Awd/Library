<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("restaurantDetails") }}</h1>
          <h4>{{ $t("min_pizza") }}</h4>
        </div>
      </div>
    </div>
    <!-- <div class="paralex-div">
      <div class="overlay-img align-self-center">
        <div class="container ">
          <span
            v-if="user"
            class="badge rounded-pill bg-danger pointer"
            :class="isFollowed ? 'bg-white' : ''"
            @click="follow(retaurantData.id)"
          >
            {{ isFollowed ? $t("unfollow") : $t("follow") }}
          </span>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-12 text-center">
              <router-link :to="`/restaurantMenu/${retaurantData.id}`">
                <h4>{{ retaurantData.name }}</h4>
              </router-link>
              <div class="text-center">
                <span class="star-count"
                  ><img src="images/icon-star.png" />
                  {{
                    retaurantData.rate
                      ? parseFloat(retaurantData.rate).toFixed(1)
                      : 0
                  }}
                  <router-link :to="`/restaurantRate/${retaurantData.id}`" class="rate-link"
                    ><img src="images/left-arrow.png" class="arrow"
                  /></router-link>
                </span>
              </div>
            </div>
            <div class="dist">
              <img src="images/fast-delivery.png" />
              <span class="red-color" v-if="retaurantData.status_id != 1">{{ status.name }}</span>
              <span v-else>{{ retaurantData.prepare_order_time }} min</span>
            </div>
            <span
              class="rest-offer"
              v-if="retaurantData.current_offer"
            >
              {{ retaurantData.current_offer.rate }} %
            </span>
            <span
              class="badge rounded-pill free-bage"
              v-if="deliveryData.type == 'free'"
              ><img src="images/free_delivery.png"
            /></span>
          </div>
        </div>
      </div>
    </div> -->
    <RestaurantBreadcrumb :retaurantData="retaurantData" 
                          :status="status" 
                          :isFollowed="isFollowed" 
                          :deliveryData="deliveryData" 
                          :user="user"
                          @follow="follow(retaurantData.id)"
     />
    <div class="white-bg">
      <div class="det-title">
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-12 text-center">
              <p class="mb-0">{{ $t("paymentMethods") }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <ul class="nav card-nav mb-3">
              <li
                class="nav-item"
                v-for="method in paymentMethods"
                :key="method.name"
              >
                <img
                  src="images/mastercard.png"
                  v-if="method.name == 'master card'"
                />
                <img
                  src="images/visa.png"
                  v-if="method.name == 'master card'"
                />
                <img
                  src="images/payment-method.png"
                  v-if="method.name == 'cash'"
                />
                <img
                  src="images/swish-payment.png"
                  v-if="method.name == 'swish'"
                />
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="det-title">
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-12 text-center">
              <p class="mb-0">{{ $t("restaurantDetails") }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row  order-dets">
          <div class="col-md-5 col-sm-12 col-12">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <img src="images/info-delivery.png" alt="..." />
              </div>
              <div class="flex-grow-1 ms-3">
                <h5>{{ $t("prepareOrderTime") }}</h5>
                <p>{{ retaurantData.prepare_order_time }} min</p>
              </div>
            </div>
          </div>
          <div class="col-md-5 col-sm-12 col-12">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <img src="images/info-minimum.png" alt="..." />
              </div>
              <div class="flex-grow-1 ms-3">
                <h5>{{ $t("min_price") }}</h5>
                <p>{{ retaurantData.min_order_price }} {{ this.currency }}</p>
              </div>
            </div>
          </div>
          <hr />
        </div>
        <div class="row order-dets">
          <div class="col-md-5 col-sm-12 col-12">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <img src="images/info-pin.png" alt="..." />
              </div>
              <div class="flex-grow-1 ms-3">
                <h5>{{ $t("address") }}</h5>
                <p>{{ retaurantData.address }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-5 col-sm-12 col-12">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <img src="images/phone-call.png" alt="..." />
              </div>
              <div class="flex-grow-1 ms-3">
                <h5>{{ $t("phones") }}</h5>
                <div v-for="phone in phones" :key="phone.id">
                  <p>{{ phone.country_code }} / {{ phone.phone }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="det-title mt-3" v-if="days.length != 0">
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-12 text-center">
              <p class="mb-0">{{ $t("timesOfWork") }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-md-5 col-sm-12 col-12">
            <div
              class="d-flex align-items-center mb-2"
              v-for="day in days"
              :key="day"
            >
              <div class="flex-shrink-0">
                <img src="images/info-clock.png" alt="..." />
              </div>
              <div class="flex-grow-1 ms-3">
                <h5>{{ day }}</h5>
                <div v-for="time in workTimes" :key="time.id">
                  <p v-if="day == time.day.name">
                    {{ time.open_from }} To {{ time.open_from }} (
                    <span v-if="time.takeaway == 1">
                      {{ $t("takeaway") }} -
                    </span>
                    <span v-if="time.delivery == 1">
                      {{ $t("delivery") }}
                    </span>
                    )
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>   

      <!-- add delivery price -->
      <div class="det-title mt-3">
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-12 text-center">
              <p class="mb-0">{{ $t("delivery") }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row order-dets ">
          <div class="col-md-5 col-sm-12 col-12">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                <img src="images/info-delivery1.png" alt="..." />
              </div>
              <div class="flex-grow-1 ms-3">
                <h5>{{ $t("deliveryType") }}</h5>
                <p>{{ deliveryData.type }}</p>
              </div>
              <div class="d-flex" v-if="deliveryData.type != 'per_kilometer'">
                <h6 class="main_color">{{ $t("value") }} :</h6>
                <span class="ms-2">{{ deliveryData.value }}</span>
                <span class="ms-1" v-if="deliveryData.type == 'percent'"
                  >%</span
                >
                <span class="ms-1" v-else-if="deliveryData.type == 'fixed'">{{
                  currency
                }}</span>
              </div>
              <div v-else-if="deliveryData.type == 'per_kilometer'">
                <div
                  class="d-flex mt-1"
                  v-for="distance in deliveryDistances"
                  :key="distance.id"
                >
                  <span class="ms-2 main_color"
                    >{{ $t("up_to") }} {{ distance.up_to }} KM:</span
                  >
                  <span class="ms-1">{{ distance.price }} {{ currency }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="text-center mt-5">
          <button
          v-if="retaurantData.can_rate == true"
          type="button"
          class="btn custom-btn"
          data-bs-toggle="modal"
          data-bs-target="#ratingModal"
        >
          {{ $t("restaurantRating") }}
        </button>
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
                <img :src="`${url}${retaurantData.image}`" />

                <div class="rest-desc">
                  <div class="row">
                    <div class="col-md-10">
                      <h5>{{ retaurantData.name }}</h5>
                      <p>{{ retaurantData.address }}</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="star-ratings start-ratings-main">
                <star-rating
                  :rounded-corners="true"
                  :read-only="!!state.rating"
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
                  :disabled="retaurantData.my_rate"
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
                v-if="!retaurantData.my_rate"
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
    </div>
  </section>
</template>

<script>
import { defineComponent } from "vue";
import axios from "axios";
import Header from "@/components/Header.vue"; // @ is an alias to /src
import RestaurantBreadcrumb from "@/components/RestaurantBreadcrumb.vue"; // @ is an alias to /src

// @ is an alias to /src
import StarRating from "vue-star-rating";
import useVuelidate from "@vuelidate/core";
import {
  required,
  helpers,
  minLength,
  maxLength,
  minValue,
} from "@vuelidate/validators";
import { reactive, computed } from "vue";
export default defineComponent({
  components: {
    Header,
    StarRating,
    RestaurantBreadcrumb
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
          minValue: minValue(1),
        },
        comment: {
          required,
          minLength: minLength(5),
          maxLength: maxLength(100),
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
      url: "",
      retaurantData: [],
      currency: "",
      deliveryData: [],
      paymentMethods: [],
      workTimes: [],
      phones: [],
      days: [],
      rating: 0,
      comment: "",
      user: localStorage.getItem("customerToken"),
      isFollowed: false,
      status: [],
    };
  },
  mounted() {
    this.getRestaurantData();
  },
  methods: {
    getRestaurantData() {
      axios
        .get(`restaurants/${this.$route.params.id}`, { headers: this.headers })
        .then((response) => {
          this.url = localStorage.getItem("imgURL");
          this.retaurantData = response.data.restaurant;
          this.currency = response.data.currency;
          this.deliveryData = response.data.restaurant.delivery_price;
          this.deliveryDistances = response.data.restaurant.delivery_distances;
          this.paymentMethods = response.data.restaurant.payment_methods;
          this.workTimes = response.data.restaurant.work_time;
          this.phones = response.data.restaurant.phones;
          this.isFollowed = response.data.restaurant.is_followed;
          this.status = response.data.restaurant.status;
          for (let index = 0; index < this.workTimes.length; index++) {
            const element = this.workTimes[index].day.name;
            if (!this.days.some((data) => data === element)) {
              //don't exists
              this.days.push(element);
            } else {
              //exists because Jonh Doe has id 1
            }
          }
          //Set coming ratings
          if (response.data.restaurant.my_rate) {
            this.state.rating = response.data.restaurant.my_rate.rate;
            this.state.comment = response.data.restaurant.my_rate.comment;
          }

          console.log("resData", this.retaurantData);
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
    setRating: function(rating) {
      console.log(rating);
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
          .post(`restaurants/rating/${this.$route.params.id}`, data, {
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
  },
});
</script>
