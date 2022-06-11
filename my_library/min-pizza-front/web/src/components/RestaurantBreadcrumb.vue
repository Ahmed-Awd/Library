<template>
    <div class="paralex-div">
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
                <h4>
                    {{ retaurantData.name }}
                    <span class="badge rounded-pill rest-info">
                        <router-link :to="`/restaurantDetails/${retaurantData.id}`">
                            <img src="images/icon-info.png" />
                        </router-link>
                    </span>
                </h4>
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
    </div>
</template>
<script>
export default {
  props: ["retaurantData", "status", "isFollowed", "deliveryData", "user"],
  methods: {
    follow() {
        this.$emit('follow')
    }
 }
};
</script>