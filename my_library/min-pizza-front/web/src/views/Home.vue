<template>
  <Header />

  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
          <h1>{{ $t("homeTitle") }}</h1>
          <h4>{{ $t("min_pizza") }}</h4>
        </div>
      </div>
      <div class="search-block">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-md-7 col-sm-12 col-12 mm-b-3">
            <form class="relative-div">
              <GMapAutocomplete
                  ref="address"
                  @place_changed="getAddressData"
                  name="search"
                  class="form-control"
                  placeholder="Please type your address"
                  @keydown.enter.prevent
                >
              </GMapAutocomplete>
              <img src="images/icon-gps.png" class="gps-ico" />
              <router-link to="/Search"><img src="images/icon-search.png" class="serch-ico" /> </router-link>
            </form>
          </div>
          <div class="col-lg-2 col-md-5 col-sm-12 col-12 text-start">
            <button
              type="button"
              class="btn filter-btn blue-btn"
              @click="geolocate"
            >
              {{ $t("findGps") }}
            </button>
          </div>
          <div class="col-lg-8 col-md-12 col-sm-12 col-12 mt-5 home-map">
            <div id="map" class="full-location-map ">
                <GMapMap
                  :center="center"
                  :zoom="12"
                  map-type-id="terrain"
                  style="width: 500px; height: 300px"
                  @click="setMarker"
                  ref="myMap"
                >
                  <GMapCluster>
                    <GMapMarker
                      :key="index"
                      v-for="(m, index) in markers"
                      :position="m.position"
                      :clickable="true"
                      :draggable="true"
                      @click="center = m.position"
                    />
                  </GMapCluster>
                </GMapMap>
            </div>
            <router-link
              type="button"
              class="btn blue-btn"
              to="/Search"
            >
              {{ $t("search") }}
            </router-link>
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
// import VueGoogleAutocomplete from "vue-google-autocomplete";
import axios from "axios";
// import VueGoogleMaps from "@fawmi/vue-google-maps";

export default defineComponent({
  name: "Home",
  components: {
    Header,
    // VueGoogleMaps,
  },
  data() {
    return {
      address: "",
      lat: "",
      lng: "",
      center: {},
      currentPlace: null,
      markers: [
        {
          position: {
            lat: 51.093048,
            lng: 6.84212,
          },
        },
      ],
      places: [],
      gpsAddress: "",
      showMap: false,
    };
  },
  mounted() {
    // To demonstrate functionality of exposed component functions
    // Here we make focus on the user input
    // this.$refs.address.focus();
    // this.geolocate();
  },

  methods: {
    /**
     * When the location found
     * @param {Object} addressData Data of the found location
     * @param {Object} placeResultData PlaceResult object
     * @param {String} id Input container ID
     */
    getAddressData: function(addressData) {
      this.address = addressData;
      this.lng = addressData.geometry.location.lng();
      this.lat = addressData.geometry.location.lat();
      localStorage.setItem("lat", this.lat);
      localStorage.setItem("lng", this.lng);
      this.currentPlace = addressData;
      this.addMarker();
    },
    getRestaurants() {
      this.$router.push('/Search');
    },
    geolocate: function() {
      navigator.geolocation.getCurrentPosition((position) => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        this.markers[0].position = this.center;
        this.location = this.center;
      
        // this.$refs.myMap.click()
        // this.$refs.myMap.$el.click()
        // this.$refs.address.$refs.input.value = results[0].formatted_address;
      
        this.getStreetAddressFrom(position.coords.latitude,position.coords.longitude);
      });
      
      this.addMarker();
    },
    setMarker(event) {
      console.log(event);
      const marker = {
        lat: event.latLng.lat(),
        lng: event.latLng.lng(),
      };
      this.markers[0].position = marker;
      this.center = marker;
      this.location = event.latLng;

      this.lat = event.latLng.lat();
      this.lng = event.latLng.lng();

      localStorage.setItem("lat", this.lat);
      localStorage.setItem("lng", this.lng);

      // eslint-disable-next-line no-undef
      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location: this.location }, (results, status) => {
        if (status === "OK" && results.length > 0) {
          // this.gpsAddress = results[0].formatted_address;
          this.$refs.address.$refs.input.value = results[0].formatted_address;
        } else {
          console.error("Geocoding request status: " + status);
          console.error(results);
        }
      });
    },
    addMarker() {
      if (this.currentPlace) {
        this.lat = this.currentPlace.geometry.location.lat();
        this.lng = this.currentPlace.geometry.location.lng();

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
          if (status === "OK" && results.length > 0) {
            console.log(results );
          } else {
            console.error("Geocoding request status: " + status);
            console.error(results);
          }
        });
        this.currentPlace = null;
      }
    },
    getStreetAddressFrom(lat, long) {
      let googleURl =  "https://maps.googleapis.com/maps/api/geocode/json?language="+localStorage.getItem("appLang")+"&latlng=" +lat +"," +long +"&key=AIzaSyB_-R-N4JMQQfUveMj6YAZPrHgbldFkTSg&libraries=places";
      // console.log(axios.defaults)
      let httpWithOutHeader = axios;
      delete httpWithOutHeader.defaults.headers;

      httpWithOutHeader.get(googleURl)
      .then((response) => {
        this.$refs.address.$refs.input.value =  response.data.results[0].formatted_address;
        console.log( this.$refs.address.$refs.input.value)
        console.log("response" , response)

      }).catch(er => {
          console.log(er)
      })
    },
  },
});
</script>
