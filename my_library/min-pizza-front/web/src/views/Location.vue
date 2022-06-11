<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("addressess") }}</h1>
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
            <div
              class="address-block"
              v-for="address in addresses"
              :key="address.id"
            >
              <div class="clearfix address-title">
                <div class="form-check float-left">
                  <input
                    class="form-check-input"
                    type="radio"
                    name="flexRadioDefault"
                    id="flexRadioDefault1"
                    v-bind:value="address.id"
                    v-model="isSelected"
                    :checked="address.is_default"
                  />
                  <label class="form-check-label" for="flexRadioDefault1">
                    {{ address.title }}
                  </label>
                </div>
                <router-link
                  class="address-edit"
                  :to="`/EditLocation/${address.id}`"
                >
                  <img src="images/icon-edit.png" />
                </router-link>
              </div>
              <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                  <div id="map" class="location-map" v-if="address.lat">
                    <GMapMap
                      :center="{
                        lat: parseFloat(address.lat),
                        lng: parseFloat(address.lng),
                      }"
                      :zoom="7"
                      map-type-id="terrain"
                      style="width: 500px; height: 300px"
                    >
                      <GMapCluster>
                        <GMapMarker
                          :key="index"
                          v-for="(m, index) in [
                            {
                              position: {
                                lat: parseFloat(address.lat),
                                lng: parseFloat(address.lng),
                              },
                            },
                          ]"
                          :position="m.position"
                          :clickable="true"
                          :draggable="true"
                          @click="center = m.position"
                          @dragend="getMarkerLocation($event)"
                        />
                      </GMapCluster>
                    </GMapMap>
                  </div>
                </div>
                <div
                  class="col-lg-8 col-md-8 col-sm-12 col-12 align-self-center"
                >
                  <p class="font-weight-bold">{{ address.description }}</p>
                  <p class="font-weight-normal">{{ address.Apartment }}</p>
                  <p class="font-weight-light">{{ address.translated_type }}</p>
                </div>
              </div>
              <hr class="Location-hr" />
            </div>

            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <router-link
                  to="/addAddress"
                  class="btn custom-btn w-100 mm-b-3"
                >
                  Add Address
                </router-link>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <button
                  class="btn btn-primary blue-btn w-100"
                  @click="setDefaultAddress"
                >
                  Set as defualt
                </button>
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

export default defineComponent({
  components: {
  Header,
    Pannel,
  },
  data() {
    return {
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
      addresses: [],
      isSelected: "",
    };
  },
  mounted() {
    this.getAddresses();
  },
  methods: {
    getAddresses() {
      axios
        .get("addresses/")
        .then((response) => {
          this.addresses = response.data.addresses;
          console.log(response.data.addresses);
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
    setDefaultAddress() {
      console.log(this.isSelected);
      if (this.isSelected != "") {
        axios
          .post("set-default-address/" + this.isSelected)
          .then(() => {
            this.$toast.success("You have a default address", {
              position: "top-right",
            });
            this.$router.push("/Location");
          })
          .catch((errors) => {
            console.log(errors.data);
          });
      }
    },
  },
});
</script>
