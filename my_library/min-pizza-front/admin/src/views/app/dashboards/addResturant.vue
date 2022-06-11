<template>
  <div class="main-content">
    <breadcumb :page="$t('new-restaurant')" :folder="$t('new-restaurant')" />
    <!-- <b-form> -->
    <b-row>
      <b-col md="12">
        <b-card class="mb-30">
          <div class="row">
            <div class="form-group col-sm-4">
              <label for="restaurantName" class="col-form-label">{{
                  $t("restaurantName")
                }}</label>
              <input
                  type="text"
                  id="restaurantName"
                  class="form-control"
                  placeholder="Resturant Name"
                  @input="$v.new_restaurant.restaurant_name.$touch"
                  v-model="new_restaurant.restaurant_name"
              />
              <p class="errors" v-if="$v.new_restaurant.restaurant_name.$dirty">
                <span class="form__alert" v-if="!$v.new_restaurant.restaurant_name.required">{{
                    $t("validation.required")
                  }}</span>
                <span class="form__alert" v-if="!$v.new_restaurant.restaurant_name.minLength">{{
                    $t("validation.nameMinLen3")
                  }}</span>
                <span class="form__alert" v-if="!$v.new_restaurant.restaurant_name.maxLength">{{
                    $t("validation.passMaxLen")
                  }}</span>
              </p>
            </div>
            <div class="form-group col-sm-4">
              <label class="col-form-label">{{ $t("companyNum") }}</label>
              <input
                  type="text"
                  class="form-control"
                  placeholder="Company No"
                  @input="$v.new_restaurant.company_number.$touch"
                  v-model="new_restaurant.company_number"
              />
              <p class="errors" v-if="$v.new_restaurant.company_number.$dirty">
                <span class="form__alert" v-if="!$v.new_restaurant.company_number.required">{{
                    $t("validation.required")
                  }}</span>
                <!-- <span class="form__alert" v-if="!$v.new_restaurant.company_number.numeric">{{
                    $t("validation.numeric")
                  }}</span> -->
              </p>
            </div>
            <div class="form-group col-sm-4">
              <label class="col-form-label">{{ $t("companyName") }}</label>
              <input
                  type="text"
                  class="form-control"
                  placeholder="Company Name"
                  @input="$v.new_restaurant.company_name.$touch"
                  v-model="new_restaurant.company_name"
              />
              <p class="errors" v-if="$v.new_restaurant.company_name.$dirty">
                <span class="form__alert" v-if="!$v.new_restaurant.company_name.required">{{
                    $t("validation.required")
                  }}</span>
                <span class="form__alert" v-if="!$v.new_restaurant.company_name.minLength">{{
                    $t("validation.nameMinLen3")
                  }}</span>
              </p>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-6">
              <label class="col-form-label">{{ $t("ownerName") }}</label>
              <input
                  type="text"
                  class="form-control"
                  placeholder="Owner Name"
                  @input="$v.new_restaurant.owner_name.$touch"
                  v-model="new_restaurant.owner_name"
              />
              <p class="errors" v-if="$v.new_restaurant.owner_name.$dirty">
                <span class="form__alert" v-if="!$v.new_restaurant.owner_name.required">{{
                    $t("validation.required")
                  }}</span>
                <span class="form__alert" v-if="!$v.new_restaurant.owner_name.minLength">{{
                    $t("validation.nameMinLen3")
                  }}</span>
                <span class="form__alert" v-if="!$v.new_restaurant.owner_name.maxLength">{{
                    $t("validation.ownerMaxLen")
                  }}</span>
              </p>
            </div>
            <div class="form-group col-sm-6">
              <label class="col-form-label">{{ $t("ownerEmail") }}</label>
              <input
                  type="text"
                  class="form-control"
                  placeholder="Owner Email"
                  @input="$v.new_restaurant.owner_email.$touch"
                  v-model="new_restaurant.owner_email"
              />
              <p class="errors" v-if="$v.new_restaurant.owner_email.$dirty">
                <span class="form__alert" v-if="!$v.new_restaurant.owner_email.required">{{
                    $t("validation.required")
                  }}</span>
                <span class="form__alert" v-if="!$v.new_restaurant.owner_email.email">{{
                    $t("validation.emailFormat")
                  }}</span>
              </p>
            </div>
          </div>
          <div class="row">
            <b-form-group :label="$t('password')" class="text-6 col-md-6">
              <b-form-input
                  class="form-control"
                  type="password"
                  @input="$v.new_restaurant.password.$touch"
                  v-model="new_restaurant.password"
                  required
              ></b-form-input>
              <p class="errors" v-if="$v.new_restaurant.password.$dirty">
                <span
                    class="form__alert"
                    v-if="!$v.new_restaurant.password.required"
                >{{ $t("validation.required") }}</span
                >
                <span
                    class="form__alert"
                    v-if="!$v.new_restaurant.password.minLength"
                >{{ $t("validation.passMinLen") }}</span
                >
                <span
                    class="form__alert"
                    v-if="!$v.new_restaurant.password.maxLength"
                >{{ $t("validation.passMaxLen") }}</span
                >
              </p>
            </b-form-group>
            <b-form-group :label="$t('confirmPass')" class="text-6 col-md-6">
              <b-form-input
                  class="form-control"
                  type="password"
                  @input="$v.new_restaurant.password_confirmation.$touch"
                  v-model="new_restaurant.password_confirmation"
                  required
              ></b-form-input>
              <p
                  class="errors"
                  v-if="$v.new_restaurant.password_confirmation.$dirty"
              >
                <span
                    class="form__alert"
                    v-if="!$v.new_restaurant.password_confirmation.sameAsPassword"
                >{{ $t("validation.passIdentical") }}</span
                >
              </p>
            </b-form-group>
          </div>
          <div class="form-group row">
            <label class="col-sm-12 col-form-label">{{
                $t("restaurantLogo")
              }}</label>
            <div class="col-sm-12">
              <b-form-file
                  class="form-control"
                  accept="image/jpeg, image/png"
                  v-model="new_restaurant.logo"
                  required
              ></b-form-file>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-12 col-form-label">{{
                $t("restaurantImage")
              }}</label>
            <div class="col-sm-12">
              <b-form-file
                  class="form-control"
                  accept="image/jpeg, image/png"
                  v-model="new_restaurant.image"
                  required
              ></b-form-file>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-6">
              <label class="col-form-label">{{ $t("vat") }}</label>
              <input
                  type="text"
                  class="form-control"
                  placeholder="Vat"
                  v-model="new_restaurant.vat"
              />
            </div>
            <div class="form-group col-sm-6">
              <label class="col-form-label">{{ $t("zipCode") }}</label>
              <input
                  type="text"
                  class="form-control"
                  placeholder="Zip Code"
                  v-model="new_restaurant.ZIP_code"
              />
            </div>
          </div>
          <div class="row">
            <b-form-group :label="$t('searchPin')" class="text-6 col-md-12">
              <GmapAutocomplete
                  @place_changed="setPlace"
                  class="form-control"
              />
            </b-form-group>
          </div>
          <GmapMap
              :center="center"
              :zoom="12"
              :clickable="true"
              :draggable="true"
              map-type-id="terrain"
              style="width: 100%; height: 300px; margin: 20px auto"
              @click="setMarker"
          >
            <GmapMarker
                :key="index"
                v-for="(m, index) in markers"
                :position="m.position"
                @click="center = m.position"
            />
          </GmapMap>
          <div class="row">
            <b-form-group :label="$t('country')" class="text-6 col-md-6">
              <b-form-select
                  class="form-control"
                  v-model="selectedcountry"
                  :options="countries"
                  @change="getCities"
                  text-field="name"
                  value-field="id"
              >
              </b-form-select>
            </b-form-group>
            <b-form-group :label="$t('city')" class="text-6 col-md-6">
              <b-form-select
                  class="form-control"
                  @input="$v.new_restaurant.city_id.$touch"
                  v-model="new_restaurant.city_id"
                  :options="cities"
                  text-field="name"
                  value-field="id"
              >
              </b-form-select>
              <p class="errors" v-if="$v.new_restaurant.city_id.$dirty">
                <span class="form__alert" v-if="!$v.new_restaurant.city_id.required">{{
                    $t("validation.required")
                  }}</span>
              </p>
            </b-form-group>
          </div>
          <div class="row">
            <div class="form-group col-sm-4">
              <label class="col-form-label">{{ $t("countryCode") }}</label>
              <input
                  type="text"
                  class="form-control"
                  placeholder="Country Code"
                  v-model="calling_code"
                  disabled
              />
            </div>
            <div class="form-group col-sm-8">
              <label class="col-form-label">{{ $t("phoneNum") }}</label>
              <input
                  type="text"
                  class="form-control"
                  placeholder="Phone"
                  @input="$v.new_restaurant.phone.$touch"
                  v-model="new_restaurant.phone"
              />
              <p class="errors" v-if="$v.new_restaurant.phone.$dirty">
                <span
                    class="form__alert"
                    v-if="!$v.new_restaurant.phone.required"
                >{{ $t("validation.required") }}</span
                >
                <span class="form__alert" v-if="!$v.new_restaurant.phone.numeric">{{
                    $t("validation.numeric")
                  }}</span>
                <span
                    class="form__alert"
                    v-if="!$v.new_restaurant.phone.minLength"
                >{{ $t("validation.phoneMinLen") }}</span
                >
              </p>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-12 col-form-label">{{ $t("address") }}</label>
            <div class="col-sm-12">
              <input
                  type="text"
                  class="form-control"
                  placeholder="Address"
                  v-model="address"
                  disabled
              />
              <p class="errors" v-if="$v.address.$dirty">
                <span
                    class="form__alert"
                    v-if="!$v.address.required"
                >{{ $t("validation.required") }}</span
                >
                <span
                    class="form__alert"
                    v-if="!$v.address.maxLength"
                >{{ $t("validation.addressMaxLen") }}</span
                >
              </p>
            </div>
          </div>
          <div class="row">
            <b-form-group label="Min Order Price" class="text-6 col-md-6">
              <input
                  type="text"
                  class="form-control"
                  placeholder="Min Order Price"
                  v-model="new_restaurant.min_order_price"
              />
            </b-form-group>
            <b-form-group label="Prepare Order Time" class="text-6 col-md-6">
              <input
                  type="number"
                  class="form-control"
                  placeholder="Prepare Order Time"
                  @input="$v.new_restaurant.prepare_order_time.$touch"
                  v-model="new_restaurant.prepare_order_time"
              />
              <p class="errors" v-if="$v.new_restaurant.prepare_order_time.$dirty">
                <span
                    class="form__alert"
                    v-if="!$v.new_restaurant.prepare_order_time.between"
                >{{ $t("validation.rateMinLen") }} 1 {{ $t("and") }} 120</span
                >
                <span
                    class="form__alert"
                    v-if="!$v.new_restaurant.prepare_order_time.required"
                >{{ $t("validation.required") }}</span
                >
              </p>
            </b-form-group>
          </div>
          <div class="form-group">
            <label class="col-form-label">{{ $t("status") }}</label>
            <b-form-select
                class="form-control"
                @input="$v.new_restaurant.status_id.$touch"
                v-model="new_restaurant.status_id"
                :options="status"
                text-field="name"
                value-field="id"
            >
            </b-form-select>
            <p class="errors" v-if="$v.new_restaurant.status_id.$dirty">
                <span
                    class="form__alert"
                    v-if="!$v.new_restaurant.status_id.required"
                >{{ $t("validation.required") }}</span
                >
            </p>
          </div>
          <div class="row">
            <b-form-group :label="$t('DeliveryType')" class="text-3 col-md-4">
              <b-form-select
                  class="form-control"
                  :options="types"
                  @input="$v.new_restaurant.delivery_type.$touch"
                  v-model="new_restaurant.delivery_type"
                  required
              ></b-form-select>
              <p class="errors" v-if="$v.new_restaurant.delivery_type.$dirty">
                <span
                    class="form__alert"
                    v-if="!$v.new_restaurant.delivery_type.required"
                >{{ $t("validation.required") }}</span
                >
              </p>
            </b-form-group>
            <b-form-group
                label="Value"
                class="text-3 col-md-4"
                v-if="new_restaurant.delivery_type == 'percent'"
            >
              <input
                  type="text"
                  class="form-control"
                  placeholder=""
                  v-model="new_restaurant.delivery_value"
              />
            </b-form-group>
          </div>
          <div v-if="new_restaurant.delivery_type === 'per_kilometer'">
            <div v-for="(kilometer, index) in kilometers" :key="index">
              <div class="row">
                <div class="col-md-11">
                  <div class="row">
                    <b-form-group :label="$t('upTo')" class="text-3 col-md-6">
                      <input
                          type="text"
                          class="form-control"
                          placeholder=""
                          v-model="kilometer.up_to"
                      />
                    </b-form-group>
                    <b-form-group :label="$t('price')" class="text-3 col-md-6">
                      <input
                          type="text"
                          class="form-control"
                          placeholder=""
                          v-model="kilometer.price"
                      />
                    </b-form-group>
                  </div>
                </div>
                <div class="col-md-1 align-self-end pl-0 mb-10">
                  <i
                      class="i-Close-Window text-21 text-danger delete-btn"
                      @click="deleteRow(index)"
                  ></i>
                </div>
              </div>
            </div>
            <button class="btn btn-danger m-1" type="button" @click="addRow()">
              {{ $t("add") }}
            </button>
          </div>
          <b-form-group>
            <div id="selector2">
              <div class="form-group">
                <label class="col-form-label">Resturant Categories</label>
                <multiselect
                    :multiple="true"
                    id="id2"
                    v-model="value"
                    :options="categories"
                    tag-placeholder="Add this as new tag"
                    placeholder="Search or add a tag"
                    label="name"
                    track-by="id"
                    :taggable="true"
                    @select="onSelect"
                >
                </multiselect>
                <p class="errors" v-if="$v.value.$dirty">
                  <span
                      class="form__alert"
                      v-if="!$v.value.required"
                  >{{ $t("validation.required") }}</span
                  >
                </p>
              </div>
            </div>
          </b-form-group>
          <b-form-group>
            <div id="selector">
              <div class="form-group">
                <label class="col-form-label">{{$t("paymentMethod")}}</label>
                <multiselect
                    :multiple="true"
                    id="id"
                    v-model="paymentValue"
                    :options="payment"
                    tag-placeholder="Add this as new tag"
                    placeholder="Search or add a tag"
                    label="name"
                    track-by="id"
                    :taggable="true"
                    @select="paymentSelect"
                >
                </multiselect>
              </div>
            </div>
          </b-form-group>
          <div class="row">
            <div class="form-group col-sm-6">
              <label class="col-form-label">{{
                  $t("is_restaurant_visible")
                }}</label>
              <b-form-select
                  class="form-control"
                  v-model="new_restaurant.is_active"
                  @input="$v.new_restaurant.is_active.$touch"
                  :options="active_types"
                  text-field="name"
                  value-field="value"
              >
              </b-form-select>
              <p class="errors" v-if="$v.new_restaurant.is_active.$dirty">
                  <span
                      class="form__alert"
                      v-if="!$v.new_restaurant.is_active.required"
                  >{{ $t("validation.required") }}</span
                  >
              </p>
            </div>
          </div>
          <b-form-group :label="$t('schedulingOrder')" class="text-6">
            <b-form-checkbox
                v-model="new_restaurant.scheduling_order"
                name="scheduling_order"
                value="1"
                unchecked-value="0"
            >
              {{ $t("schedulingOrder") }}
            </b-form-checkbox>
          </b-form-group>
        </b-card>
      </b-col>
      <b-col md="12">
        <b-card class="mb-30">
          <div class="form-group row">
            <div class="col-sm-12">
              <b-button
                  class="btn btn-primary mt-4 btn-block"
                  variant="primary mt-2"
                  tag="button"
                  @click.prevent="createRestaurant"
                  :disabled="$v.new_restaurant.$invalid || $v.address.$invalid"

              >{{ $t("save") }}
              </b-button>
            </div>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <!-- </b-form> -->
  </div>
</template>
<script>
import axios from "axios";
import { mapGetters } from "vuex";
import Multiselect from "vue-multiselect";
// import { GoogleMap, Marker, CustomControl } from 'vue3-google-map'
import {
  required,
  email,
  sameAs,
  numeric,
  minLength,
  maxLength,
  between,
} from "vuelidate/lib/validators";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Add New Resturant",
  },
  components: { Multiselect },
  props: {
    defaultLocation: Object,
  },
  data() {
    return {
      value: [],
      categories: [],
      new_restaurant: {
        image:null,
        logo:null,
        ZIP_code:null
      },
      selectedcountry: "",
      new_phones: [],
      new_worktime: {},
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),

        Accept: "application/json",
      },
      countries: [],
      cities: [],
      status: [],
      inputs: [
        {
          country_code: "",
          phone: "",
        },
      ],
      deliverinputs: [],
      worktimes: [],
      days: [],
      // location: { lat: 45.508, lng: -73.587 },
      center: { lat: 45.508, lng: -73.587 },
      currentPlace: null,
      markers: [],
      marker: {},
      places: [],
      types: [
        { value: "percent", text: "Percent" },
        { value: "free", text: "Free" },
        { value: "per_kilometer", text: "Per Kilometer" },
      ],
      address: "",
      cat: [],
      calling_code: "",
      kilometers: [
        {
          up_to: "",
          price: "",
        },
      ],
      active_types: [
        {
          name: "yes",
          value: 1,
        },
        {
          name: "no",
          value: 0,
        },
      ],
      payment: [],
      paymentValue : [],
      paymentMethod: [],
    };
  },
  validations: {
    new_restaurant: {
      restaurant_name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(25),
      },
      city_id: {
        required,
      },
      status_id: {
        required,
      },
      owner_name:{
        required,
        minLength: minLength(3),
        maxLength: maxLength(40),
      },
      owner_email: {
        required,
        email,
      },
      password: {
        required,
        minLength: minLength(6),
        maxLength: maxLength(25),
      },
      password_confirmation: {
        minLength: minLength(6),
        maxLength: maxLength(25),
        sameAsPassword: sameAs("password"),
      },
      delivery_type: {
        required,
      },
      company_name: {
        required,
        minLength: minLength(3),
      },
      company_number: {
        required,
      },
      prepare_order_time: {
        required,
        numeric,
        between: between(1,120),
      },
      phone: {
        required,
        numeric,
        minLength: minLength(9),
        maxLength: maxLength(9)
      },
      is_active: {
        required,
      },
    },
    address: {
      required,
      maxLength: maxLength(240),
    },
    value: {
      required,
    },
  },
  mounted() {
    this.getCountries();
    // this.getCities(3);
    this.getStatus();
    this.geolocate();
    this.getcategories();
    this.getPayment();
  },
  methods: {
    onSelect(option) {
      console.log(option);
      this.cat.push(option.id);
    },
    getCountries() {
      axios
          .get("countries", { headers: this.headers })
          .then((response) => {
            this.countries = response.data.countries;
            let countryID = this.countries.filter(
              (item) => item.name == 'Sverige'
            );
            countryID.map((item) => {
              this.selectedcountry = item.id;
            });
            
            let codeSelectedcountry = this.countries.filter(
                (item) => item.id == this.selectedcountry
            );
            codeSelectedcountry.map((item) => {
              this.calling_code = item.calling_code;
            });
            this.getCities(this.selectedcountry);
          })
          .catch((errors) => {
            console.log(errors);
          });
    },
    getCode() {
      let codeSelectedcountry = this.countries.filter(
          (item) => item.id == this.editSelectedcountry
      );
      codeSelectedcountry.map((item) => {
        this.editCalling_code = item.calling_code;
      });
    },
    getCities(city) {
      axios
          .get(`cities/${city}`, { headers: this.headers })
          .then((response) => {
            this.cities = response.data.cities;
            console.log(this.cities);
            // alert('dd');
          })
          .catch((errors) => {
            console.log(errors);
          });
      for (var i = 0; i < this.countries.length; i++) {
        if (this.countries[i].id == city) {
          console.log(this.countries[i].calling_code);
          this.calling_code = this.countries[i].calling_code;
        }
      }
    },
    getStatus() {
      axios
          .get("status", { headers: this.headers })
          .then((response) => {
            this.status = response.data.status;
            console.log(this.status);
          })
          .catch((errors) => {
            console.log(errors);
          });
    },
    getcategories() {
      axios
          .get("categories", { headers: this.headers })
          .then((response) => {
            this.categories = response.data.categories;
            console.log(this.categories);
          })
          .catch((errors) => {
            console.log(errors);
          });
    },
    createRestaurant() {
      this.$v.$touch(); //it will validate all fields
      let fd;
      fd = new FormData();
      fd.append("restaurant_name", this.new_restaurant.restaurant_name);
      fd.append("company_number", this.new_restaurant.company_number);
      fd.append("company_name", this.new_restaurant.company_name);
      fd.append("owner_name", this.new_restaurant.owner_name);
      fd.append("owner_email", this.new_restaurant.owner_email);
      fd.append("password", this.new_restaurant.password);
      fd.append(
          "password_confirmation",
          this.new_restaurant.password_confirmation
      );
      fd.append("phone", this.new_restaurant.phone);
      if(this.new_restaurant.logo != null){
        fd.append("logo", this.new_restaurant.logo);
      }
      if(this.new_restaurant.image != null){
        fd.append("image", this.new_restaurant.image);
      }
      if(typeof this.new_restaurant.vat !== "undefined"){
        fd.append("vat", this.new_restaurant.vat);
      }
      if(this.new_restaurant.ZIP_code != null){
        fd.append("ZIP_code", this.new_restaurant.ZIP_code);
      }
      fd.append("address", this.address);
      fd.append("status_id", this.new_restaurant.status_id);
      fd.append("city_id", this.new_restaurant.city_id);
      fd.append("country_code", this.calling_code);
      fd.append("prepare_order_time", this.new_restaurant.prepare_order_time);
      fd.append("delivery_type", this.new_restaurant.delivery_type);
      fd.append("delivery_value", this.new_restaurant.delivery_value);
      fd.append("delivery_kilometer", this.new_restaurant.delivery_kilometer);
      fd.append("min_order_price", this.new_restaurant.min_order_price);
      fd.append("lat", this.markers[0].position.lat);
      fd.append("lng", this.markers[0].position.lng);
      fd.append("scheduling_order", this.new_restaurant.scheduling_order);
      fd.append("is_active", this.new_restaurant.is_active);
      for (var i = 0; i < this.value.length; i++) {
        fd.append("categories[]", this.value[i].id);
      }


      let selectedPayment = [];
      for (var i = 0; i < this.paymentValue.length; i++) {
        selectedPayment.push(this.paymentValue[i].id);
      }

      axios
          .post("restaurants", fd, { headers: this.headers })
          .then((response) => {
            console.log(response.data);
            var rest = response.data.restaurant;
            this.createDistances(rest, this.new_restaurant.delivery_type);
            this.createPayments(rest,selectedPayment);
            //this.makeToast("success", "create completed successfully");
          })
          .catch((errors) => {

            const ErrMsg = errors.response.data.message;
            const Err = errors.response.data.errors;
            console.log(Err);
            for (const el in Err) {
              Err[el].map((item) => {
                this.makeToast("warning", item);
              });
            }

          });

      let kilometersArr = this.kilometers;
      console.log(kilometersArr);
    },
    createDistances(restaurant, type) {
      if (type === "per_kilometer") {
        axios
            .post(
                `restaurants/update-distances/${restaurant}`,
                { distances: this.kilometers },
                { headers: this.headers }
            )
            .then((response) => {
              this.makeToast("success", "create completed successfully");
            })
            .catch((errors) => {
              const ErrMsg = errors.response.data.message;
              const Err = errors.response.data.errors;
              console.log(Err);
              for (const el in Err) {
                Err[el].map((item) => {
                  this.makeToast("warning", item);
                });
              }
            });
      } else {
        this.makeToast("success", "create completed successfully");
      }
    },

    createPayments(restaurant,methods) {
      axios
          .post(
              `restaurants/update-payments/${restaurant}`,
              {payments: methods},
              {headers: this.headers}
          )
          .then((response) => {
            //this.makeToast("success", "create completed successfully");
          }).catch((errors) => {
        const ErrMsg = errors.response.data.message;
        const Err = errors.response.data.errors;
        console.log(Err);
        for (const el in Err) {
          Err[el].map((item) => {
            this.makeToast("warning", item);
          });
        }
      });
    },
    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
    addPhone() {
      //this.inputs.push(this.inputs.length+1);
      this.inputs.push({ country_code: "", phone: "" });
    },
    addDeliverySetting() {
      this.deliverinputs.push(this.inputs.length + 1);
    },
    geolocate: function () {
      navigator.geolocation.getCurrentPosition((position) => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
      });
      this.markers.push({ position: this.center });
    },
    setPlace(place) {
      this.currentPlace = place;
      console.log(place);
      this.address = place.formatted_address;
      this.addMarker();
    },
    addMarker(event) {
      if (this.currentPlace) {
        const marker = {
          lat: this.currentPlace.geometry.location.lat(),
          lng: this.currentPlace.geometry.location.lng(),
        };
        this.markers[0].position = marker;
        this.center = marker;
        this.currentPlace = null;
      }
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
      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location: this.location }, (results, status) => {
        console.log(results);
        if (status === "OK" && results.length > 0) {
          this.address = results[0].formatted_address;
          console.log(results[0]);
          for (var i = 0; i < results[0].address_components.length; i++) {
            for (
                var j = 0;
                j < results[0].address_components[i].types.length;
                j++
            ) {
              if (results[0].address_components[i].types[j] == "postal_code") {
                this.new_restaurant.ZIP_code =
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
    addRow() {
      this.kilometers.push({
        up_to: "",
        price: "",
      });
    },
    deleteRow(index) {
      this.kilometers.splice(index, 1);
    },
    getPayment() {
      axios
          .get("admin/payment-methods", { headers: this.headers })
          .then((response) => {
            this.payment = response.data.methods;
            console.log(this.payment);
          })
          .catch((errors) => {
            console.log(errors);
          });
    },
    paymentSelect(option) {
      console.log(option);
      // this.paymentMethod.push(option.id);
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
label.checkbox {
  margin-left: 1.1rem;
}

.delete-btn {
  cursor: pointer;
}

.mb-10 {
  margin-bottom: 10px;
}

.d-inline-flex {
  display: inline-flex;
  align-items: center;
}
</style>
