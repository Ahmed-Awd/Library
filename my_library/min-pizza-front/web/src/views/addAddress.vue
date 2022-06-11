<template>
  <Header />
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
          <h1>{{ $t("address") }}</h1>
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
                    >
                    </GMapAutocomplete>
                  </form>
                </div>
                <div class="col-lg-1 col-md-2 col-sm-3 col-3">
                  <button type="button" class="btn git-location-btn" @click="geolocate">
                    <img src="images/icon-gps.png" />
                  </button>
                </div>
              </div>
            </div>
            <form>
              <div class="mb-3">
                <label class="form-label">{{ $t("title") }}</label>
                <input type="text" class="form-control" v-model="state.title" />
                <span class="error" v-if="v$.title.$error">
                  {{ v$.title.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-3">
                <label class="form-label">{{ $t("description") }}</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="state.description"
                  disabled
                />
                <span class="error" v-if="v$.description.$error">
                  {{ v$.description.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-3">
                <label class="form-label">{{ $t("area") }}</label>
                <input type="text" class="form-control" v-model="state.area" />
                <span class="error" v-if="v$.area.$error">
                  {{ v$.area.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-3">
                <label class="form-label">{{ $t("locationType") }}</label>
                <select
                  class="form-select"
                  aria-label="Default select example"
                  v-model="state.type"
                >
                  <option selected></option>
                  <option value="home">{{ $t("optionHome") }}</option>
                  <option value="work">{{ $t("optionWork") }}</option>
                  <option value="other">{{ $t("optionOther") }}</option>
                </select>
                <span class="error" v-if="v$.type.$error">
                  {{ v$.type.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-3">
                <label class="form-label">{{ $t("portCode") }}</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="state.ZIP_code"
                />
                <span class="error" v-if="v$.ZIP_code.$error">
                  {{ v$.ZIP_code.$errors[0].$message }}
                </span>
              </div>
              <div class="mb-3">
                <label class="form-label">{{ $t("apartment") }}</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="state.Apartment"
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
  </section>
  
</template>

<script>
import { defineComponent } from "vue";
import Header from "@/components/Header.vue"; // @ is an alias to /src
 // @ is an alias to /src
import Pannel from "@/components/Pannel.vue"; // @ is an alias to /src
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import { reactive, computed } from "vue";
import { required, minLength, maxLength } from "@vuelidate/validators";
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
    };
  },

  setup() {
    const state = reactive({
      title: "",
      lat: null,
      lng: null,
      area: null,
      description: "",
      ZIP_code: "",
      Apartment: null,
      type: "",
    });

    const rules = computed(() => {
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

    const v$ = useVuelidate(rules, state);

    return {
      state,
      v$,
    };
  },
   mounted() {
    this.geolocate();
  },
  methods: {
    save() {
      const result = this.v$.$validate();
      if (!this.v$.$error) {
        axios
          .post("addresses", this.state)
          .then((response) => {
            this.$toast.success(response.data.message, {
              position: "top-right",
            });
            this.$router.push("/Location");
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
      this.currentPlace = place;
      this.address = place.formatted_address;
      this.state.description = place.formatted_address;
      this.state.area = place.address_components[0].long_name;
      this.addMarker();
    },
    addMarker() {
      if (this.currentPlace) {
        this.state.lat = this.currentPlace.geometry.location.lat();
        this.state.lng = this.currentPlace.geometry.location.lng();
        this.state.ZIP_code = "";

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
            this.state.description = results[0].formatted_address;
            this.state.area = results[0].address_components[1].long_name;
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
      });
      this.addMarker();
    },
    setMarker(event) {
      this.state.ZIP_code = "";
      console.log(event);
      const marker = {
        lat: event.latLng.lat(),
        lng: event.latLng.lng(),
      };
      this.markers[0].position = marker;
      this.center = marker;
      this.location = event.latLng;

      this.state.lat = event.latLng.lat();
      this.state.lng = event.latLng.lng();

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
                this.state.ZIP_code = results[0].address_components[i].long_name;
              }
            }
          }
        } else {
          console.error("Geocoding request status: " + status);
          console.error(results);
        }
      });
    },
  },
  
});
</script>

<style></style>
