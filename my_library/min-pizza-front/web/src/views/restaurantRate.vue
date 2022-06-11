<template>
  <Header/>
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t('restaurantRating') }}</h1>
          <h4>{{ $t("min_pizza") }}</h4>
        </div>
      </div>
    </div>
    <!-- <div class="paralex-div">
      <div class="overlay-img align-self-center">
        <div class="container">
          <span
            v-if="user"
            class="badge rounded-pill bg-danger pointer"
            :class="isFollowed ? 'bg-white' : ''"
            @click="follow(restaurant.id)"
          >
            {{ isFollowed ? $t("unfollow") : $t("follow") }}
          </span>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-12 text-center">
              <h4>{{ restaurant.name }}</h4>
              <div class="text-center">
                <span class="star-count">
                  <img src="images/icon-star.png"/>
                  {{ restaurant.rate }}
                  <img src="images/left-arrow.png" class="arrow"
                  /></span>
              </div>
            </div>
            <div class="dist">
              <img src="images/fast-delivery.png"/>
              <span class="red-color" v-if="restaurant.status_id != 1">{{ status.name }}</span>
              <span v-else>
                {{ restaurant.prepare_order_time }}
                {{ $t('min') }}</span
              >
            </div>
            <div v-if="restaurant.delivery_price">
              <span class="badge rounded-pill free-bage" v-if="restaurant.delivery_price.type == 'free'">
                <img src="images/free_delivery.png"/>
              </span>
            </div>

          </div>
        </div>
      </div>
    </div> -->
    <RestaurantBreadcrumb :retaurantData="restaurant" 
                          :status="status" 
                          :isFollowed="isFollowed" 
                          :deliveryData="deliveryData" 
                          :user="user"
                          @follow="follow(restaurant.id)"
     />
    <div class="white-bg cat-filter">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div v-if="pageLoad == 0" class="loader white-bg">
                <Circle></Circle>
            </div>
            <div v-else>
              <div v-if="restaurantRatingData.length != 0 ">
                <div
                    class="review-block"
                    v-for="(rating, index) in restaurantRatingData"
                    :key="index"
                >
                  <h5>{{ rating.user.name }}</h5>
                  <div class="star-ratings start-ratings-main">
                    <star-rating
                        :rounded-corners="true"
                        :read-only="true"
                        :rating="rating.rate"
                        :star-size="16"
                        inactive-color="#ddd"
                        active-color="#8ac054"
                        :padding="4"
                    >
                    </star-rating>
                  </div>
                  <p class="mt-4 mb-4">
                    {{ rating.comment }}
                  </p>
                  <span class="review-time"
                  ><img src="images/info-clock.png"/> {{
                      new Date(rating.updated_at).toLocaleDateString()
                    }}</span
                  >
                </div>
                <div :class="restaurantRatingData.length == total ? 'text-center hidden' : 'text-center'" > 
                    <button class="btn btn-primary blue-btn mt-3" 
                        @click="onPageChange"
                    >{{ $t("loadMore") }}</button>
                </div>
              </div>
              <div v-else>
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12 col-12 align-self-center">
                    <img src="images/warning.png" />
                  </div>
                  <div class="col-lg-5 col-md-4 col-sm-12 col-12">
                    <div class="error-div">
                      <h1 class="green-h">{{ $t("min_pizza") }}</h1>
                      <p>{{ $t("emptyRate") }}</p>
                      <router-link to="/" class="btn blue-btn">{{
                        $t("homePage")
                      }}</router-link>
                    </div>
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
import {defineComponent} from "vue";
import Header from "@/components/Header.vue"; // @ is an alias to /src
 // @ is an alias to /src
import axios from "axios";
import StarRating from 'vue-star-rating'
import {Circle} from 'vue-loading-spinner'
import RestaurantBreadcrumb from "@/components/RestaurantBreadcrumb.vue"; 

export default defineComponent({
  components: {
    Header, StarRating, Circle, RestaurantBreadcrumb
  },
  data() {
    return {
      restaurantRatingData: [],
      restaurant: {},
      pageLoad: 0,
      user: localStorage.getItem("customerToken"),
      isFollowed: false,
      status: [],
      deliveryData: [],
    };
  },
  mounted() {
    this.getOrderDetails();
    this.getRestaurantDetails();
  },
  methods: {
    onPageChange(params) {
      this.serverParams = Object.assign(
          {},
          this.serverParams,
          this.serverParams.page++
      );
      this.getOrderDetails();
    },

    getOrderDetails() {
      axios
          .get(`restaurants/rating/${this.$route.params.id}`, {
            headers: this.headers,
          })
          .then((response) => {
            response.data.ratings.data && (this.restaurantRatingData = [...this.restaurantRatingData, ...response.data.ratings.data])
            this.total = response.data.ratings.total;
            this.pageLoad = 1;
          })
          .catch((errors) => {
            console.log(errors.data);
          });
    },
    getRestaurantDetails() {
      axios
          .get(`restaurants/${this.$route.params.id}`, {
            headers: this.headers,
          })
          .then((response) => {
            console.log(response.data);
            this.restaurant = response.data.restaurant;
            this.isFollowed = response.data.restaurant.is_followed;
            this.status = response.data.restaurant.status;
            this.deliveryData = response.data.restaurant.delivery_price;
          })
          .catch((errors) => {
            this.$router.push('/404');
            console.log(errors.data);
          });
    },
    // follow and unfollow restaurant
    follow(restID) {
      axios
        .post(`restaurants/follow/${restID}`)
        .then((response) => {
          this.isFollowed = !this.isFollowed;
          console.log("follow", response.data);
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
  },
});
</script>
