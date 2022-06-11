<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("following") }}</h1>
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
              <div class="follow-container"
               v-for="restaurant in followings"
                :key="restaurant.id"
              >
                <a
                    class="delete-favourite-block"
                    @click.prevent="unFollowBtn(restaurant.id)"
                  >
                    <i class="fas fa-times"></i
                  ></a>
              
              <router-link :to="`/restaurantMenu/${restaurant.id}`"
              >
              <div
                class="rest-block"
               
              >
                <img :src="`${url}${restaurant.image}`" class="card-img" />
                <div class="overlay-img">
                  <span
                    class="badge rounded-pill free-bage"
                    v-if="restaurant.delivery_price.type == 'free'"
                  >
                    <img src="images/free_delivery.png" />
                  </span>
                  <div class="rest-desc">
                    <div class="row">
                      <div class="col-md-10 col-sm-8 col-8 align-self-end">
                        <h5>
                          {{ restaurant.name }}
                        </h5>
                        <span class="star-count"
                          ><img src="images/icon-star.png" />
                          {{ restaurant.rate ? restaurant.rate : 0 }}</span
                        >
                      </div>
                      <div class="col-md-2 col-md-2 col-sm-4 col-4 pl-0">
                        <div class="dist">
                          <img src="images/fast-delivery.png" />
                          <span class="red-color" v-if="restaurant.status_id != 1">{{ restaurant.status.name }}</span>
                          <span v-else>{{
                            restaurant.prepare_order_time
                              ? parseFloat(
                                  restaurant.prepare_order_time
                                ).toFixed(2)
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
import { Circle } from "vue-loading-spinner";

export default defineComponent({
  data() {
    return {
      url: localStorage.getItem("imgURL"),
      followings: [],
      loading: false,
      pageLoad: 0,
    };
  },
  components: {
    Header,
    Pannel,
    Circle,
  },
  mounted() {
    this.getFollowing();
  },
  methods: {
    //   get following items
    getFollowing() {
      this.loading = true;
      axios
        .get("my-followed-restaurants")
        .then((response) => {
          console.log(response.data.myFollows.data);
          this.followings = response.data.myFollows.data;
          this.loading = false;
        })
        .catch((errors) => {
          console.log(errors.data);
          this.loading = false;
          this.pageLoad = 1;
        });
    },
    unFollowBtn(restaurantId) {
      axios
        .post(`restaurants/follow/${restaurantId}`)
        .then(() => {
          this.getFollowing();
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
  },
});
</script>

<style scoped>
.card-img {
  width: 100%;
  object-fit: cover;
}
</style>
