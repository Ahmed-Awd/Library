<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("restaurants") }}</h1>
          <h4>{{ $t("min_pizza") }}</h4>
        </div>
      </div>
    </div>
    <div v-if="pageLoad" class="loader white-bg">
      <Circle></Circle>
    </div>
    <div v-if="pageLoad == false">
      <div v-if="rows.length != 0">
        <div class="search-content">
          <div class="cat-div">
            <div class="container">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-12 mt-3">
                  <Carousel
                    :settings="settings"
                    :breakpoints="breakpoints"
                    class="parteners-carousel"
                  >
                    <Slide
                      v-for="category in restaurant_cat"
                      :key="category.name"
                    >
                      <div
                        class="carousel__item"
                        @click="selectCat(category.id)"
                      >
                        <div class="partener-item">
                          <div class="item-img">
                            <img :src="`${url}${category.image}`" />
                          </div>
                          <h5>{{ category.name }}</h5>
                        </div>
                      </div>
                    </Slide>
                    <template #addons>
                      <Navigation />
                    </template>
                  </Carousel>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="white-bg cat-filter">
          <div class="container">
            <div class="row">
              <div class="col-md-12 col-12">
                <div class="open-filter">
                  <i class="fas fa-filter"></i>{{ $t("filtter") }}
                </div>
              </div>
              <div class="col-md-4 col-sm-12 col-12">
                <div class="search-block filter-blo mt-0 hide-mob">
                  <form action="">
                    <input
                      type="text"
                      placeholder="Find food or restaurant"
                      name="search"
                      class="form-control"
                      v-model="retName"
                    />
                    <button
                      type="button"
                      @click="fillter"
                      class="btn search-btn"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </form>
                  <div class="footer-title sec-title mt-4">
                    <h5>{{ $t("restType") }}</h5>
                    <hr />
                  </div>
                  <div
                    class="form-check"
                    v-for="type in types"
                    :key="type.value"
                  >
                    <input
                      type="radio"
                      class="form-check-input"
                      name="restCheckbox"
                      v-model="restType"
                      :value="type.value"
                      :id="type.value"
                    />
                    <label class="form-check-label" :for="type.value">{{
                      type.text
                    }}</label>
                  </div>
                  <div class="footer-title sec-title mt-4">
                    <h5>{{ $t("delivery") }}</h5>
                    <hr />
                  </div>
                  <div
                    class="form-check"
                    v-for="del in delivery"
                    :key="del.value"
                  >
                    <input
                      type="radio"
                      class="form-check-input"
                      name="deliveryCheckbox"
                      v-model="deliveryValue"
                      :value="del.value"
                      :id="del.value"
                    />
                    <label class="form-check-label" :for="del.value">{{
                      del.text
                    }}</label>
                  </div>
                  <div class="footer-title sec-title mt-4">
                    <h5>{{ $t("rating") }}</h5>
                    <hr />
                  </div>
                  <div
                    class="form-check"
                    v-for="rate in rating"
                    :key="rate.value"
                  >
                    <input
                      type="radio"
                      class="form-check-input"
                      name="rateCheckbox"
                      v-model="rateVal"
                      :value="rate.value"
                      :id="rate.value"
                    />
                    <label class="form-check-label" :for="rate.value">
                      <img src="images/icon-starb.png" />{{ rate.text }}
                    </label>
                  </div>
                  <div class="footer-title sec-title mt-4">
                    <h5>{{ $t("distance") }}</h5>
                    <hr />
                  </div>
                  <div class="range-wrap">
                    <div
                      class="range-value"
                      ref="rangeV"
                      :style="{
                        left: `calc(${((distance - 1) * 100) /
                          (100 - 1)}% + (${10 - distance * 0.2}px))`,
                      }"
                    >
                      <span>{{ total }} Km</span>
                    </div>
                    <input
                      ref="range"
                      type="range"
                      min="1"
                      max="100"
                      step="1"
                      v-model="distance"
                    />
                  </div>
                  <div class="text-center mt-3">
                    <button
                      type="button"
                      class="btn btn-primary blue-btn"
                      @click="fillter"
                    >
                      {{ $t("search") }}
                    </button>
                  </div>
                </div>
              </div>
              <div class="col-md-8 col-sm-12 col-12">
                <div v-if="loading" class="loader white-bg">
                  <Circle></Circle>
                </div>
                <div class="row" v-else-if="rows.length > 0">
                  <div
                    class="col-lg-6 col-md-12 col-sm-12 col-12"
                    v-for="row in rows"
                    :key="row.id"
                  >
                    <div class="follow-container">
                      <span
                        v-if="user"
                        class="badge rounded-pill bg-danger"
                        :class="row.is_followed ? 'bg-white' : ''"
                        @click="follow(row.id)"
                      >
                        {{ row.is_followed ? $t("unfollow") : $t("follow") }}
                      </span>
                      <router-link :to="`/restaurantMenu/${row.id}`">
                        <div class="rest-block">
                          <img :src="`${url}${row.image}`" />
                          <div class="overlay-img">
                            <span
                              class="badge rounded-pill free-bage"
                              v-if="row.delivery_price.type == 'free'"
                            >
                              <img src="images/free_delivery.png" />
                            </span>
                            <div class="offline" v-if="row.mode == 'offline'">
                              <img src="images/offline.png" alt="" />
                            </div>
                            <div class="rest-desc">
                              <div class="row">
                                <div
                                  class="col-md-10 col-sm-8 col-8 align-self-end"
                                >
                                  <h5>{{ row.name }}</h5>
                                  <span class="star-count"
                                    ><img src="images/icon-star.png" />
                                    {{
                                      row.rate
                                        ? parseFloat(row.rate).toFixed(0)
                                        : 0
                                    }}</span
                                  >
                                </div>
                                <div
                                  class="col-md-2 col-md-2 col-sm-4 col-4 pl-0"
                                >
                                  <div class="dist">
                                    <img src="images/fast-delivery.png" />
                                    <span
                                      class="red-color"
                                      v-if="row.status_id != 1"
                                      >{{ row.status.name }}</span
                                    >
                                    <span v-else>{{
                                      row.my_deleivery_time
                                        ? parseFloat(
                                            row.my_deleivery_time
                                          ).toFixed(0) + " min"
                                        : 0
                                    }}</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </router-link>
                    </div>
                  </div>
                  <div
                    :class="
                      rows.length == totalRecords ? 'text-center hidden' : 'text-center'
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
      </div>
      <div  v-else-if="rows.length == 0 && searchClicked">
        <div class="search-content">
          <div class="cat-div">
            <div class="container">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-12 mt-3">
                  <Carousel
                    :settings="settings"
                    :breakpoints="breakpoints"
                    class="parteners-carousel"
                  >
                    <Slide
                      v-for="category in restaurant_cat"
                      :key="category.name"
                    >
                      <div
                        class="carousel__item"
                        @click="selectCat(category.id)"
                      >
                        <div class="partener-item">
                          <div class="item-img">
                            <img :src="`${url}${category.image}`" />
                          </div>
                          <h5>{{ category.name }}</h5>
                        </div>
                      </div>
                    </Slide>
                    <template #addons>
                      <Navigation />
                    </template>
                  </Carousel>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="white-bg cat-filter">
          <div class="container">
            <div class="row">
              <div class="col-md-12 col-12">
                <div class="open-filter">
                  <i class="fas fa-filter"></i>{{ $t("filtter") }}
                </div>
              </div>
              <div class="col-md-4 col-sm-12 col-12">
                <div class="search-block filter-blo mt-0 hide-mob">
                  <form action="">
                    <input
                      type="text"
                      placeholder="Find food or restaurant"
                      name="search"
                      class="form-control"
                      v-model="retName"
                    />
                    <button
                      type="button"
                      @click="fillter"
                      class="btn search-btn"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </form>
                  <div class="footer-title sec-title mt-4">
                    <h5>{{ $t("restType") }}</h5>
                    <hr />
                  </div>
                  <div
                    class="form-check"
                    v-for="type in types"
                    :key="type.value"
                  >
                    <input
                      type="radio"
                      class="form-check-input"
                      name="restCheckbox"
                      v-model="restType"
                      :value="type.value"
                      :id="type.value"
                    />
                    <label class="form-check-label" :for="type.value">{{
                      type.text
                    }}</label>
                  </div>
                  <div class="footer-title sec-title mt-4">
                    <h5>{{ $t("delivery") }}</h5>
                    <hr />
                  </div>
                  <div
                    class="form-check"
                    v-for="del in delivery"
                    :key="del.value"
                  >
                    <input
                      type="radio"
                      class="form-check-input"
                      name="deliveryCheckbox"
                      v-model="deliveryValue"
                      :value="del.value"
                      :id="del.value"
                    />
                    <label class="form-check-label" :for="del.value">{{
                      del.text
                    }}</label>
                  </div>
                  <div class="footer-title sec-title mt-4">
                    <h5>{{ $t("rating") }}</h5>
                    <hr />
                  </div>
                  <div
                    class="form-check"
                    v-for="rate in rating"
                    :key="rate.value"
                  >
                    <input
                      type="radio"
                      class="form-check-input"
                      name="rateCheckbox"
                      v-model="rateVal"
                      :value="rate.value"
                      :id="rate.value"
                    />
                    <label class="form-check-label" :for="rate.value">
                      <img src="images/icon-starb.png" />{{ rate.text }}
                    </label>
                  </div>
                  <div class="footer-title sec-title mt-4">
                    <h5>{{ $t("distance") }}</h5>
                    <hr />
                  </div>
                  <div class="range-wrap">
                    <div
                      class="range-value"
                      ref="rangeV"
                      :style="{
                        left: `calc(${((distance - 1) * 100) /
                          (100 - 1)}% + (${10 - distance * 0.2}px))`,
                      }"
                    >
                      <span>{{ total }} Km</span>
                    </div>
                    <input
                      ref="range"
                      type="range"
                      min="1"
                      max="100"
                      step="1"
                      v-model="distance"
                    />
                  </div>
                  <div class="text-center mt-3">
                    <button
                      type="button"
                      class="btn btn-primary blue-btn"
                      @click="fillter"
                    >
                      {{ $t("search") }}
                    </button>
                  </div>
                </div>
              </div>
              <div class="col-md-8 col-sm-12 col-12">
                <div v-if="loading" class="loader white-bg">
                  <Circle></Circle>
                </div>
                <div
                  class="row"
                >
                  <div class="col-lg-9 col-md-4 col-sm-12 col-12">
                    <div class="error-div">
                      <h1 class="green-h">{{ $t("min_pizza") }}</h1>
                      <p>{{ $t("emptyRestSearch") }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="white-bg" v-else-if="rows.length == 0 && !searchClicked">
        <div class="container">
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12 align-self-center">
              <img src="images/warning.png" />
            </div>
            <div class="col-lg-5 col-md-4 col-sm-12 col-12">
              <div class="error-div">
                <h1 class="green-h">{{ $t("min_pizza") }}</h1>
                <p>{{ $t("emptyRest") }}</p>
                <router-link to="/" class="btn blue-btn">{{
                  $t("homePage")
                }}</router-link>
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
import axios from "axios";
import { Carousel, Navigation, Slide } from "vue3-carousel";
import "vue3-carousel/dist/carousel.css";
import { Circle } from "vue-loading-spinner";

export default defineComponent({
  components: {
    Header,
    Carousel,
    Slide,
    Navigation,
    Circle,
  },
  inject: ["file_url"],
  data() {
    return {
      user: localStorage.getItem("customerToken"),
      url: localStorage.getItem("imgURL"),
      pageSize: 15,
      currentPage: 1,
      serverParams: {
        page: 1,
        perPage: 15,
      },
      rows: [],
      types: [
        { value: false, text: "All" },
        { value: "takeaway", text: "Takeway" },
        { value: "delivery", text: "Delivery" },
      ],
      delivery: [
        { value: false, text: "All" },
        { value: "true", text: "Free" },
      ],
      rating: [
        { value: false, text: "All" },
        { value: "1", text: "1" },
        { value: "2", text: "2" },
        { value: "3", text: "3" },
        { value: "4", text: "4" },
        { value: "5", text: "5" },
        { value: "6", text: "6" },
      ],
      restaurant_cat: [],
      // carousel settings
      settings: {
        itemsToShow: 1,
        snapAlign: "center",
      },
      // breakpoints are mobile first
      // any settings not specified will fallback to the carousel settings
      breakpoints: {
        100: {
          itemsToShow: 1,
          snapAlign: "center",
        },
        // 700px and up
        700: {
          itemsToShow: 3.5,
          snapAlign: "center",
        },
        // 1024 and up
        1024: {
          itemsToShow: 6,
          snapAlign: "start",
        },
      },
      restType: false,
      rateVal: false,
      deliveryValue: false,
      distance: 5,
      // range : this.$refs["range"],
      selectedCat: false,
      retName: "",
      loading: false,
      pageLoad: false,
      searchNo: 0,
      MoreButtonClicked: false,
      searchClicked: false,
    };
  },
  mounted() {
    this.listData();
    this.getCategories();
  },
  methods: {
    onPageChange(params) {
      this.MoreButtonClicked = true;
      this.serverParams = Object.assign(
        {},
        this.serverParams,
        this.serverParams.page++
      );
      this.searchsedListData();
    },
    async searchsedListData() {
      // this.searchNo = 1;
      this.loading = true;
      var lat = localStorage.getItem("lat");
      var lng = localStorage.getItem("lng");
      let url;
      if (this.retName) {
        url = `restaurants?lat=${lat}&lng=${lng}&name=${this.retName}&rest_type=${this.restType}&free_delivery=${this.deliveryValue}&rate=${this.rateVal}&distance=${this.distance}&category=${this.selectedCat}`;
      } else {
        url = `restaurants?lat=${lat}&lng=${lng}&rest_type=${this.restType}&free_delivery=${this.deliveryValue}&rate=${this.rateVal}&distance=${this.distance}&category=${this.selectedCat}`;
      }
      if(!this.MoreButtonClicked){
         this.serverParams = Object.assign(
            {},
            this.serverParams,
            this.serverParams.page = 1,
          );
      }
      axios
        .get(url, {
          params: this.serverParams,
        })
        .then((response) => {
          console.log(response.data);
          this.totalRecords = response.data.restaurants.total;
          this.to = response.data.restaurants.to || 0;
          this.from = response.data.restaurants.from || 0;
          if(this.MoreButtonClicked){
            response.data.restaurants.data && 
            (this.rows = [
              ...this.rows, 
              ...response.data.restaurants.data
            ]);
            this.MoreButtonClicked = false;
          }else{
            this.rows = response.data.restaurants.data
          }
          // this.rows = response.data.restaurants.data.map((item) => ({
          //   ...item,
          //   rating: parseFloat(item.ratings_avg_rate),
          // }));
          
          this.loading = false;
          console.log(this.rows);
        })
        .catch((errors) => {
          console.log(errors);
          this.loading = false;
        });
    },
    listData() {
      this.pageLoad = true;
      var lat = localStorage.getItem("lat");
      var lng = localStorage.getItem("lng");
      let url;
      if (this.retName) {
        url = `restaurants?lat=${lat}&lng=${lng}&name=${this.retName}&rest_type=${this.restType}&free_delivery=${this.deliveryValue}&rate=${this.rateVal}&distance=${this.distance}&category=${this.selectedCat}`;
      } else {
        url = `restaurants?lat=${lat}&lng=${lng}&rest_type=${this.restType}&free_delivery=${this.deliveryValue}&rate=${this.rateVal}&distance=${this.distance}&category=${this.selectedCat}`;
      }
      if(!this.MoreButtonClicked){
         this.serverParams = Object.assign(
            {},
            this.serverParams,
            this.serverParams.page = 1,
          );
      }
      axios
        .get(url, {
          params: this.serverParams,
        })
        .then((response) => {
          // console.log(response.data);
          this.totalRecords = response.data.restaurants.total;
          this.to = response.data.restaurants.to || 0;
          this.from = response.data.restaurants.from || 0;
          // this.rows = response.data.restaurants.data.map((item) => ({
          //   ...item,
          //   rating: parseFloat(item.ratings_avg_rate),
          // }));
          response.data.restaurants.data && 
            (this.rows = [
              ...this.rows, 
              ...response.data.restaurants.data
            ]);
          this.pageLoad = false;
          console.log(this.rows);
        })
        .catch((errors) => {
          console.log(errors);
          this.pageLoad = false;
        });
    },
    // follow and unfollow restaurant
    follow(restID) {
      axios
        .post(`restaurants/follow/${restID}`)
        .then((response) => {
          this.searchsedListData();
          // this.isFollowed = !this.isFollowed;
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
    // get restaurant categories
    getCategories() {
      axios
        .get("categories")
        .then((response) => {
          this.restaurant_cat = response.data.categories;
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
    // selected category tab
    selectCat(catID) {
      this.selectedCat = catID;
      this.searchsedListData();
    },
    fillter() {
      this.searchClicked = true;
      console.log(this.distance);
      this.searchsedListData();
    },
  },
  computed: {
    total: function() {
      return this.distance;
    },
  },
});
</script>
