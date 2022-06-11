<template>
  <div class="main-content">
    <breadcumb :page="$t('restaurantList')" :folder="$t('datatables')" />
    <!-- search -->
    <input
      class="form-control mb-2 searchInput"
      :placeholder="$t('searchTable')"
      type="text"
      v-model="searchtext"
    />

    <vue-good-table
      :columns="columns"
      mode="remote"
      :line-numbers="false"
      :totalRows="totalRecords"
      :search-options="{
        enabled: true,
        placeholder: $t('searchTable'),
        externalQuery: searchtext,
      }"
      @on-page-change="onPageChange"
      @on-sort-change="onSortChange"
      :pagination-options="{
        enabled: true,
        perPage: 15,
        perPageDropdownEnabled: false,
        infoFn: (params) => `${from} - ${to} of ${totalRecords}`,
      }"
      styleClass="tableOne vgt-table"
      :selectOptions="{
        enabled: false,
        selectionInfoClass: 'table-alert__box',
      }"
      :rows="rows"
    >
      <div slot="table-actions" class="mb-3">
        <b-button
          variant="primary"
          class="btn-rounded"
          to="/app/dashboards/addResturant"
        >
          {{ $t("addNew") }}
        </b-button>
      </div>

      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'" class="d-inline-flex">
          <router-link
            v-if="
              roleName == 'super_admin' ||
              (roleName == 'admin' &&
                adminPermissionsList.includes('view orders'))
            "
            :to="{ path: `/app/dashboards/orders/${props.row.id}` }"
            class="btn btn-outline-primary text-black text-10 p-1 mr-2"
          >
            {{ $t("orders") }}
          </router-link>

          <router-link
            v-if="
              roleName == 'super_admin' ||
              (roleName == 'admin' &&
                adminPermissionsList.includes('list restaurant ratings'))
            "
            :to="{ path: `restaurant/ratings/${props.row.id}` }"
            class="btn btn-outline-primary text-black text-10 p-1 mr-2"
          >
            {{ $t("rating") }}
          </router-link>
          <a
            v-if="
              roleName == 'super_admin' ||
              (roleName == 'admin' &&
                adminPermissionsList.includes('control owner'))
            "
            href="javascript:void(0)"
            @click="
              loginOwner(props.row.owner_id, props.row.id, props.row.name)
            "
          >
            <i class="i-Checked-User text-21"></i>
            {{ props.row.button }}</a
          >
          <router-link
            :to="{ path: `/app/dashboards/resturantSetting/${props.row.id}` }"
          >
            <i class="i-Gear-2 text-22 text-warning mr-2"></i>
            {{ props.row.button }}</router-link
          >
          <router-link
            :to="{ path: `/app/dashboards/workTimes/${props.row.id}` }"
          >
            <i class="i-Clock text-22 text-success mr-2"></i>
            {{ props.row.button }}</router-link
          >
          <router-link
            v-if="
              roleName == 'super_admin' ||
              (roleName == 'admin' &&
                adminPermissionsList.includes('manage holidays'))
            "
            :to="{ path: `/app/dashboards/holiday/${props.row.id}` }"
          >
            <i class="i-Lock-2 text-22 text-success mr-2"></i>
            {{ props.row.button }}</router-link
          >
          <button
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            @click.prevent="showPhonesModal(props.row)"
          >
            <i class="i-Telephone text-22 text-raised mr-2"></i>
          </button>

          <button
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            @click.prevent="showEditModal(props.row)"
          >
            <i class="i-Eraser-2 text-25 text-success mr-2"></i>
            {{ props.row.button }}
          </button>
          <button
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            @click.prevent="showDeleteModal(props.row)"
          >
            <i class="i-Close-Window text-25 text-danger"></i>
            {{ props.row.button }}
          </button>
        </span>
      </template>
    </vue-good-table>
    <b-modal
      ref="my-modal"
      hide-footer
      title="Delete Confirmation"
      class="modal"
    >
      <div class="d-block">
        <h3>{{ $t("deleteRestaurant") }}</h3>
      </div>
      <div class="text-right">
        <b-button class="mt-3 mr-3" @click="hideDeleteModal">{{
          $t("cancel")
        }}</b-button>
        <b-button class="mt-3" variant="outline-danger" @click="deleteRaw"
          >{{ $t("delete") }}
        </b-button>
      </div>
    </b-modal>
    <b-modal ref="edit-modal" hide-footer title="Update Restaurant">
      <div class="row">
        <div class="form-group col-sm-12">
          <label class="col-form-label">{{ $t("restaurantName") }}</label>
          <input
            type="text"
            class="form-control"
            placeholder="Resturant Name"
            @input="$v.current_row.name.$touch"
            v-model="current_row.name"
          />
          <p class="errors" v-if="$v.current_row.name.$dirty">
                <span class="form__alert" v-if="!$v.current_row.name.required">{{
                  $t("validation.required")
                }}</span>
                <span class="form__alert" v-if="!$v.current_row.name.minLength">{{
                  $t("validation.nameMinLen3")
                }}</span>
                <span class="form__alert" v-if="!$v.current_row.name.maxLength">{{
                  $t("validation.passMaxLen")
                }}</span>
            </p>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-sm-6">
          <label for="" class="col-form-label">{{ $t("companyNum") }}</label>
          <input
            type="text"
            class="form-control"
            placeholder="Restaurant No"
            @input="$v.current_row.company_number.$touch"
            v-model="current_row.company_number"
          />
          <p class="errors" v-if="$v.current_row.company_number.$dirty">
                <span class="form__alert" v-if="!$v.current_row.company_number.required">{{
                  $t("validation.required")
                }}</span>
                <!-- <span class="form__alert" v-if="!$v.current_row.company_number.numeric">{{
                  $t("validation.numeric")
                }}</span> -->
            </p>
        </div>
        <div class="form-group col-sm-6">
          <label for="" class="col-form-label">{{ $t("companyName") }}</label>
          <input
            type="text"
            class="form-control"
            placeholder="Company Name"
            @input="$v.current_row.company_name.$touch"
            v-model="current_row.company_name"
          />
          <p class="errors" v-if="$v.current_row.company_name.$dirty">
                <span class="form__alert" v-if="!$v.current_row.company_name.required">{{
                  $t("validation.required")
                }}</span>
                <span class="form__alert" v-if="!$v.current_row.company_name.minLength">{{
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
            @input="$v.current_row.owner.name.$touch"
            v-model="current_row.owner.name"
          />
          <p class="errors" v-if="$v.current_row.owner.name.$dirty">
            <span class="form__alert" v-if="!$v.current_row.owner.name.required">{{
              $t("validation.required")
            }}</span>
            <span class="form__alert" v-if="!$v.current_row.owner.name.minLength">{{
              $t("validation.nameMinLen3")
            }}</span>
            <span class="form__alert" v-if="!$v.current_row.owner.name.maxLength">{{
              $t("validation.ownerMaxLen")
            }}</span>
        </p>
        </div>
        <div class="form-group col-sm-6">
          <label class="col-form-label">{{
            $t("is_restaurant_visible")
          }}</label>
          <b-form-select
            class="form-control"
            @input="$v.current_row.is_active.$touch"
            v-model="current_row.is_active"
            :options="active_types"
            text-field="name"
            value-field="value"
          >
          </b-form-select>
          <p class="errors" v-if="$v.current_row.is_active.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_row.is_active.required"
              >{{ $t("validation.required") }}</span
            >
          </p>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-12">
          <label class="col-form-label">{{ $t("ownerEmail") }}</label>
          <input
              type="text"
              class="form-control"
              placeholder="Owner Name"
              @input="$v.current_row.owner.email.$touch"
              v-model="current_row.owner.email"
          />
          <p class="errors" v-if="$v.current_row.owner.email.$dirty">
            <span class="form__alert" v-if="!$v.current_row.owner.name.required">{{
                $t("validation.required")
              }}</span>
            <span class="form__alert" v-if="!$v.current_row.owner.name.minLength">{{
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
            @input="$v.current_row.password.$touch"
            v-model="current_row.password"
            required
          ></b-form-input>
          <p class="errors" v-if="$v.current_row.password.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_row.password.minLength"
              >{{ $t("validation.passMinLen") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_row.password.maxLength"
              >{{ $t("validation.passMaxLen") }}</span
            >
          </p>
        </b-form-group>
        <b-form-group :label="$t('confirmPass')" class="text-6 col-md-6">
          <b-form-input
            class="form-control"
            type="password"
            @input="$v.current_row.password_confirmation.$touch"
            v-model="current_row.password_confirmation"
            required
          ></b-form-input>
          <p
                class="errors"
                v-if="$v.current_row.password_confirmation.$dirty"
              >
                <span
                  class="form__alert"
                  v-if="!$v.current_row.password_confirmation.sameAsPassword"
                  >{{ $t("validation.passIdentical") }}</span
                >
              </p>
        </b-form-group>
      </div>
      <b-form-group :label="$t('restaurantLogo')" class="text-6">
        <b-form-file
          class="form-control"
          accept="image/jpeg, image/png"
          v-model="current_row.logo"
          required
        ></b-form-file>
      </b-form-group>
      <b-form-group :label="$t('restaurantImage')" class="text-6">
        <b-form-file
          class="form-control"
          accept="image/jpeg, image/png"
          v-model="current_row.image"
          required
        ></b-form-file>
      </b-form-group>
      <div class="row">
        <div class="form-group col-sm-6">
          <label for="" class="col-form-label">{{ $t("vat") }}</label>
          <input
            type="text"
            class="form-control"
            placeholder="Vat"
            v-model="current_row.vat"
          />
        </div>
        <div class="form-group col-sm-6">
          <label for="" class="col-form-label">{{ $t("zipCode") }}</label>
          <input
            type="text"
            class="form-control"
            placeholder="Zip Code"
            v-model="current_row.ZIP_code"
          />
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-12 col-form-label">{{
          $t("address")
        }}</label>
        <div class="col-sm-12">
          <input
            type="text"
            class="form-control"
            placeholder="Address"
            v-model="current_row.address"
            disabled
          /><p class="errors" v-if="$v.current_row.address.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.current_row.address.required"
                  >{{ $t("validation.required") }}</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.current_row.address.maxLength"
                  >{{ $t("validation.addressMaxLen") }}</span
                >
          </p>
        </div>
      </div>
      <div class="row">
        <b-form-group :label="$t('searchPin')" class="text-6 col-md-12">
          <GmapAutocomplete @place_changed="setPlace" class="form-control" />
        </b-form-group>
      </div>
      <GmapMap
        :center="center"
        :zoom="12"
        :clickable="true"
        :draggable="true"
        map-type-id="terrain"
        style="width: 100%; height: 250px; margin: 20px auto"
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
        <b-form-group :label="$t('theMinPrice')" class="text-6 col-md-6">
          <input
            type="text"
            class="form-control"
            placeholder="Min Order Price"
            v-model="current_row.min_order_price"
          />
        </b-form-group>
        <b-form-group :label="$t('prepareOrderTime')" class="text-6 col-md-6">
          <input
            type="text"
            class="form-control"
            placeholder="Prepare Order Time"
            @input="$v.current_row.prepare_order_time.$touch"
            v-model="current_row.prepare_order_time"
          />
        </b-form-group>
        <p class="errors" v-if="$v.current_row.prepare_order_time.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.current_row.prepare_order_time.between"
                  >{{ $t("validation.rateMinLen") }} 1 {{ $t("and") }} 120</span
                >
                <span
                  class="form__alert"
                  v-if="!$v.current_row.prepare_order_time.required"
                  >{{ $t("validation.required") }}</span
                >
              </p>
      </div>
      <div class="form-group">
        <label for="" class="col-form-label">{{ $t("status") }}</label>
        <b-form-select
          class="form-control"
          @input="$v.current_row.status_id.$touch"
          v-model="current_row.status_id"
          :options="status"
          text-field="name"
          value-field="id"
        >
        </b-form-select>
        <p class="errors" v-if="$v.current_row.status_id.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.current_row.status_id.required"
                  >{{ $t("validation.required") }}</span
                >
              </p>
      </div>
      <div class="row">
        <b-form-group :label="$t('DeliveryType')" class="text-3 col-md-4">
          <b-form-select
            class="form-control"
            :options="types"
            @input="$v.current_row.delivery_price.type"

            v-model="current_row.delivery_price.type"
            value-field="value"
            text-field="text"
          ></b-form-select>
          <p class="errors" v-if="$v.current_row.delivery_price.type.$dirty">
                <span
                  class="form__alert"
                  v-if="!$v.current_row.delivery_price.type.required"
                  >{{ $t("validation.required") }}</span
                >
              </p>
        </b-form-group>
        <b-form-group
          :label="$t('value')"
          class="text-3 col-md-4"
          v-if="current_row.delivery_price.type == 'percent'"
        >
          <input
            type="text"
            class="form-control"
            placeholder=""
            v-model="current_row.delivery_price.value"
          />
        </b-form-group>
      </div>
      <div v-if="current_row.delivery_price.type == 'per_kilometer'">
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
                @click="deleteDisRow(index)"
              ></i>
            </div>
          </div>
        </div>
        <button class="btn btn-danger m-1" type="button" @click="addDisRow()">
          {{ $t("add") }}
        </button>
      </div>

      <b-form-group>
        <div id="selector">
          <div class="form-group">
            <label for="" class="col-form-label">{{
              $t("restaurantCategories")
            }}</label>
            <multiselect
              :multiple="true"
              id="id"
              v-model="current_row.categories"
              :options="categories"
              tag-placeholder="Add this as new tag"
              placeholder="Search or add a tag"
              label="name"
              track-by="id"
              :taggable="true"
            >
            </multiselect>
            <p class="errors" v-if="$v.current_row.categories.$dirty">
                  <span
                    class="form__alert"
                    v-if="!$v.current_row.categories.required"
                    >{{ $t("validation.required") }}</span
                  >
                </p>
          </div>
        </div>
      </b-form-group>
      <b-form-group>
            <div id="selector2">
              <div class="form-group">
                <label class="col-form-label">{{$t("paymentMethod")}}</label>
                <multiselect
                  :multiple="true"
                  id="id2"
                  v-model="current_row.payment_methods"
                  :options="payment"
                  tag-placeholder="Add this as new tag"
                  placeholder="Search or add a tag"
                  label="name"
                  track-by="id"
                  :taggable="true"
                >
                </multiselect>
              </div>
            </div>
          </b-form-group>
      <b-form-group :label="$t('schedulingOrder')" class="text-6">
        <b-form-checkbox
          v-model="current_row.scheduling_order"
          name="scheduling_order"
          value="1"
          unchecked-value="0"
        >
          {{ $t("schedulingOrder") }}
        </b-form-checkbox>
      </b-form-group>
      <div class="text-right">
        <b-button
          type="submit"
          tag="button"
          @click.prevent="updateRaw"
          class="btn btn-primary mt-4 mr-3"
          variant="primary mt-2"
          :disabled="loadingRequest || $v.current_row.$invalid"
        >
          {{ $t("update") }}
        </b-button>
        <b-button class="btn btn-primary mt-4" @click="hideEditModal">{{
          $t("cancel")
        }}</b-button>
      </div>
    </b-modal>
    <b-modal ref="phones-modal" hide-footer title="Restaurant Phones">
      <div class="d-block">
        <div v-for="(phone, index) in phones" :key="index">
          <div class="row">
            <div class="col-md-11">
              <div class="form-group">
                <label for="" class="col-form-label">{{
                  $t("phoneNum")
                }}</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Phone"
                  v-model="phone.phone"
                />
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
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="createPhone"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
            :disabled="loadingRequest"
            >{{ $t("save") }}</b-button
          >
          <b-button
            class="btn btn-primary mt-4"
            @click.prevent="hidePhonesModal"
            >{{ $t("cancel") }}</b-button
          >
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3" />
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
import Multiselect from "vue-multiselect";
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
    title: "Restaurant",
  },
  components: { Multiselect },
  props: {
    defaultLocation: Object,
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  // inject: ["file_url"],
  data() {
    return {
      roleName: "",
      adminPermissionsList: [],
      from: 1,
      to: 15,
      serverParams: {
        page: 1,
        perPage: 15,
      },
      totalRecords: 0,
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("restaurantName"),
          field: "name",
        },
        {
          label: this.$t("restaurantNum"),
          field: "company_number",
        },
        {
          label: this.$t("address"),
          field: "address",
        },
        {
          label: this.$t("rating"),
          field: "ratings_avg_rate",
        },
        {
          label: this.$t("status"),
          field: "status",
          html: true,
        },
        {
          label: this.$t("button"),
          field: "button",
          html: true,
          tdClass: "text-right",
          thClass: "text-right",
        },
      ],
      rows: [],
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
      current_row: {
        status: "",
        ratings_avg_rate: "",
        address: "",
        company_number: "",
        logo: "",
        image: "",
        company_name: "",
        name: "",
        id: "",
        owner: {
          name: "",
          email: "",
        },
        delivery_price: {
          kilometer: "",
          value: "",
          type: "",
        },
        lat: "",
        lng: "",
      },
      raw: {
        company_name: "",
        company_number: "",
        address: "",
        ratings_avg_rate: "",
        status: "",
        owner_id: "",
      },
      types: [
        { value: "percent", text: "Percent" },
        { value: "free", text: "Free" },
        { value: "per_kilometer", text: "Per Kilometer" },
      ],
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
        Accept: "application/json",

        "Content-Type": "application/json",
      },
      searchtext: "",
      center: {},
      currentPlace: null,
      markers: [],
      marker: {},
      places: [],
      countries: [],
      cities: [],
      status: [],
      image: {},
      logo: {},
      address: "",
      value: [],
      categories: [],
      cat: [],
      phones: [],
      resturant_id: "",
      phonesArr: [],
      loadingRequest: false,
      kilometers: [
        {
          up_to: "",
          price: "",
        },
      ],
      payment: [],
      paymentMethods: [],
    };
  },
  validations: {
    current_row: {
      name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(25),
      },
      address: {
        required,
        maxLength: maxLength(240),
      },
      status_id: {
        required,
      },
      owner: {
        name:{
          required,
          minLength: minLength(3),
          maxLength: maxLength(40),
        },
        email:{
          required,
          email,
        },
      },

      password: {
        minLength: minLength(6),
        maxLength: maxLength(25),
      },
      password_confirmation: {
        minLength: minLength(6),
        maxLength: maxLength(25),
        sameAsPassword: sameAs("password"),
      },
      delivery_price: {
        type: {
        required,
        }
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
      categories: {
        required,
      },
      is_active: {
        required,
      }
    },
    // phone: {
    //   phone: {
    //     required,
    //     numeric,
    //     minLength: minLength(9),
    //     maxLength: maxLength(9)
    //   },
    // }
  },
  created() {
    // mount for define role name and permissions if admin login
    var role_name = localStorage.getItem("role");
    var adminPermissions = localStorage.getItem("adminPermissions");
    this.roleName = role_name;
    this.adminPermissionsList = adminPermissions;
  },
  mounted() {
    this.listData();
    this.getCountries();
    this.getStatus();
    this.geolocate();
    this.getcategories();
    this.getPayment();
  },
  // watcher for search term value
  watch: {
    searchtext: function (newval) {
      if (newval.length > 0) {
        this.listData(newval);
      } else {
        this.listData();
      }
    },
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.listData();
    },
    async onSortChange(params) {
      console.log(params);
      await this.updateParams({orderBy : params[0].field});
      await this.updateParams({orderType : params[0].type});
      this.listData();
    },
    listData(searchTerm) {
      let url;
      if (searchTerm) {
        url = `admin/get-restaurants?name=${searchTerm}`;
      } else {
        url = `admin/get-restaurants`;
      }
      axios
        .get(url, {
          headers: this.headers,
          params: this.serverParams,
        })
        .then((response) => {
          console.log(response.data);
          this.totalRecords = response.data.restaurants.total;
          this.to = response.data.restaurants.to || 0;
          this.from = response.data.restaurants.from || 0;
          this.rows = response.data.restaurants.data.map((item) => ({
            ...item,
            status: `<span class="badge badge-danger ${item.status.name}">${item.status.name}</span>`,
            ratings_avg_rate: parseFloat(item.ratings_avg_rate),
            address: item.address.substring(0, 100)+".....",
          }));
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showDeleteModal(raw) {
      this.current_row = raw;
      this.$refs["my-modal"].show();
    },
    hideDeleteModal() {
      this.current_row = {};
      this.$refs["my-modal"].hide();
    },
    async deleteRaw() {
      const headers = {
        Authorization: "Bearer " + localStorage.getItem("token"),
      };
      await axios
        .delete(`restaurants/${this.current_row.id}`, {
          headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "delete completed successfully");
          this.hideDeleteModal();
          this.listData();
        })
        .catch((errors) => {
          this.makeToast("warning", "delete failed");
          this.hideDeleteModal();
        });
    },
    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
    loginOwner(id, restId, name) {
      axios
        .post(
          `/get/user/token/${id}`,
          {
            user: id,
          },
          {
            headers: this.headers,
          }
        )
        .then((response) => {
          console.log("role", response.data.user.roles[0].name);
          let oldToken = localStorage.getItem("token");
          localStorage.setItem("oldToken", oldToken);
          localStorage.setItem("token", response.data.access_token);
          localStorage.setItem("user", response.data.user);
          localStorage.setItem("role", response.data.user.roles[0].name);
          localStorage.setItem("restaurantID", restId);
          localStorage.setItem("restaurantName", name);
          window.location.href = `restaurant/menu/categories/${restId}`;
        })
        .catch((errors) => {
          this.message = "Invalid email or password";
          this.makeToast("warning", this.message);
        });
    },
    geolocate: function () {
      navigator.geolocation.getCurrentPosition((position) => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
      });
      console.log(this.center);
      // this.markers.push({ position: this.center });
    },
    setPlace(place) {
      this.currentPlace = place;
      console.log(place);
      this.address = place.formatted_address;
      this.current_row.address = place.formatted_address;
      this.addMarker();
    },
    addMarker() {
      if (this.currentPlace) {
        const marker = {
          lat: this.currentPlace.geometry.location.lat(),
          lng: this.currentPlace.geometry.location.lng(),
        };
        this.markers[0].position = marker;
        this.center = marker;
        console.log(this.currentPlace.geometry.location);
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
      console.log(this.markers[0]);
      this.location = event.latLng;
      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location: this.location }, (results, status) => {
        console.log(results);
        if (status === "OK" && results.length > 0) {
          this.address = results[0].formatted_address;
          this.current_row.address = results[0].formatted_address;
          console.log(results[0]);
          for (var i = 0; i < results[0].address_components.length; i++) {
            for (
              var j = 0;
              j < results[0].address_components[i].types.length;
              j++
            ) {
              if (results[0].address_components[i].types[j] == "postal_code") {
                this.current_row.ZIP_code =
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
    getCountries() {
      axios
        .get("countries", { headers: this.headers })
        .then((response) => {
          this.countries = response.data.countries;
          console.log(this.countries);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    getCities(city) {
      axios
        .get(`cities/${city}`, { headers: this.headers })
        .then((response) => {
          this.cities = response.data.cities;
          console.log(this.cities);
        })
        .catch((errors) => {
          console.log(errors);
        });
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
    showEditModal(raw) {
      this.current_row = raw;
      this.kilometers = this.current_row.delivery_distances;
      this.current_row.image = null;
      this.current_row.logo = null;
      this.address = this.current_row.address;

      this.center = {
        lat: parseFloat(this.current_row.lat),
        lng: parseFloat(this.current_row.lng),
      };
      this.markers= [{ position: this.center }];
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.current_row = {};
      this.$refs["edit-modal"].hide();
    },
    updateRaw() {
      this.$v.$touch(); //it will validate all fields
      this.loadingRequest = true;

      let fd;
      fd = new FormData();
      fd.append("name", this.current_row.name);
      fd.append("company_number", this.current_row.company_number);
      fd.append("company_name", this.current_row.company_name);
      fd.append("owner_name", this.current_row.owner.name);
      fd.append("owner_email", this.current_row.owner.email);
      if (this.current_row.logo != null) {
        fd.append("logo", this.current_row.logo);
      }
      if (this.current_row.image != null) {
        fd.append("image", this.current_row.image);
      }
      if(typeof this.current_row.vat !== "undefined"){
        fd.append("vat", this.current_row.vat);
      }
      if(typeof this.current_row.ZIP_code !== "undefined"){
        fd.append("ZIP_code", this.current_row.ZIP_code);
      }
      fd.append("address", this.address);
      fd.append("is_active", this.current_row.is_active);
      if (this.current_row.password) {
        fd.append("password", this.current_row.password);
        fd.append(
          "password_confirmation",
          this.current_row.password_confirmation
        );
      }
      fd.append("status_id", this.current_row.status_id);
      // fd.append("city_id", this.current_row.city_id);
      fd.append("prepare_order_time", this.current_row.prepare_order_time);
      fd.append("min_order_price", this.current_row.min_order_price);
      fd.append("delivery_type", this.current_row.delivery_price.type);
      fd.append("delivery_value", this.current_row.delivery_price.value);

      fd.append(
        "delivery_kilometer",
        this.current_row.delivery_price.kilometer
      );
      if (this.markers[0].position.lat) {
        fd.append("lat", this.markers[0].position.lat);
        fd.append("lng", this.markers[0].position.lng);
      } else {
        fd.append("lat", this.current_row.lat);
        fd.append("lng", this.current_row.lng);
      }

      let selectedCategories = this.current_row.categories;
      for (var i = 0; i < selectedCategories.length; i++) {
        this.cat.push(selectedCategories[i].id);
      }
      for (var i = 0; i < this.cat.length; i++) {
        fd.append("categories[]", this.cat[i]);
      }

      let selectedPayment = [];
      for (var i = 0; i < this.current_row.payment_methods.length; i++) {
        selectedPayment.push(this.current_row.payment_methods[i].id);
      }

      fd.append("scheduling_order", this.current_row.scheduling_order);
      fd.append("_method", "patch");
      console.log(this.current_row.delivery_price.type);
      axios
        .post(`restaurants/${this.current_row.id}`, fd, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.loadingRequest = false;
          this.createDistances(this.current_row.id,this.current_row.delivery_price.type);
          this.createPayments(this.current_row.id,selectedPayment);
          this.makeToast("success", "update completed successfully");
          this.hideEditModal();
        })
        .catch((errors) => {
          
            const ErrMsg = errors.response.data.message;
            const Err = errors.response.data.errors;
            console.log(Err);
            for (const el in Err) {
              Err[el].map((item) => {
                this.makeToast("warning", item);
              });
              this.loadingRequest = false;
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
            // this.makeToast("success", "create completed successfully");
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
        //this.makeToast("success", "create completed successfully");
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
    showPhonesModal(raw) {
      this.current_row = raw;
      this.phones = this.current_row.phones;
      this.resturant_id = this.current_row.id;
      this.$refs["phones-modal"].show();
    },
    addRow() {
      this.phones.push({
        phone: "",
      });
    },
    deleteRow(index) {
      this.phones.splice(index, 1);
    },
    hidePhonesModal() {
      this.phones = {};
      this.$refs["phones-modal"].hide();
    },
    createPhone() {
      console.log(this.phones);
      for (var i = 0; i < this.phones.length; i++) {
        this.phonesArr.push(this.phones[i].phone);
      }
      // return;
      this.loadingRequest = true; // set this before running anything

      axios
        .post(
          `/restaurants/update-phones/${this.resturant_id}`,
          { phones: this.phonesArr },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "create completed successfully");
          this.hidePhonesModal();
          this.listData();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          const ErrMsg = errors.response.data.message;
          const Err = errors.response.data.errors;
          console.log(Err);
          for (const el in Err) {
            Err[el].map((item) => {
              this.makeToast("warning", item);
            });
            this.loadingRequest = false;
          }
          // console.log(errors.data);
          // this.makeToast("warning", "create failed");
          this.hidePhonesModal();
        });
    },
    addDisRow() {
      this.kilometers.push({
        up_to: "",
        price: "",
      });
    },
    deleteDisRow(index) {
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
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
.d-inline-flex {
  display: inline-flex;
  align-items: center;
}
.modal {
  z-index: 1001 !important;
}
.modal-backdrop {
  z-index: 1000 !important;
}
.pac-container {
  z-index: 1055 !important;
}
.delete-btn {
  cursor: pointer;
}
.mb-10 {
  margin-bottom: 10px;
}
.loading-btn {
  text-decoration: none !important;
  padding: 1px !important;
}
.searchInput {
  width: 25em;
}
.badge.open {
  color: #fff;
  background-color: #10b981;
}
</style>
