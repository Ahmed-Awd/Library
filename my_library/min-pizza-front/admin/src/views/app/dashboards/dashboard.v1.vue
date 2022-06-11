<template>
  <!-- ============ Body content start ============= -->
  <div class="main-content">
    <breadcumb :page="$t('dashboard')" :folder="$t('dashboard')" />
    <!-- start top counts -->
    <b-row>
      <b-col lg="4" md="6" sm="12">
        <b-card
          class="card-icon-bg card-icon-bg-primary o-hidden mb-30 text-center"
        >
          <i class="i-Add-User"></i>
          <div class="content">
            <p class="text-muted mt-2 mb-0">{{ $t("customers") }}</p>
            <p class="text-primary text-24 line-height-1 mb-2">
              {{ totalRecords }}
            </p>
          </div>
        </b-card>
      </b-col>
      <b-col lg="4" md="6" sm="12">
        <b-card
          class="card-icon-bg card-icon-bg-primary o-hidden mb-30 text-center"
        >
          <i class="i-Financial"></i>
          <div class="content">
            <p class="text-muted mt-2 mb-0">{{ $t("restaurants") }}</p>
            <p class="text-primary text-24 line-height-1 mb-2">
              {{ totalResNum }}
            </p>
          </div>
        </b-card>
      </b-col>
      <b-col lg="4" md="6" sm="12">
        <b-card
          class="card-icon-bg card-icon-bg-primary o-hidden mb-30 text-center"
        >
          <i class="i-Checkout-Basket"></i>
          <div class="content">
            <p class="text-muted mt-2 mb-0">{{ $t("orders") }}</p>
            <p class="text-primary text-24 line-height-1 mb-2">
              {{ totalRecordsOrders }}
            </p>
          </div>
        </b-card>
      </b-col>
      <!-- <b-col lg="3" md="6" sm="12">
        <b-card
          class="card-icon-bg card-icon-bg-primary o-hidden mb-30 text-center"
        >
          <i class="i-Money-2"></i>
          <div class="content">
            <p class="text-muted mt-2 mb-0">{{ $t("expense") }}</p>
            <p class="text-primary text-24 line-height-1 mb-2">$1200</p>
          </div>
        </b-card>
      </b-col> -->
    </b-row>
    <!-- end top counts -->

    <!-- start tabs -->
    <div>
      <b-card no-body>
        <b-tabs pills card>
          <!-- start customers tab -->
          <b-tab :title="$t('customers')" active
            ><b-card-text>
              <vue-good-table
                :columns="columnsCustomer"
                :line-numbers="false"
                :totalRows="totalRecords"
                :search-options="{
                  enabled: true,
                  placeholder: $t('searchTable'),
                }"
                @on-page-change="onPageChange"
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
                :rows="customers"
              >
              </vue-good-table>
              <div class="mt-3">
                <div class="d-flex align-items-center">
                  <h5 class="text-primary">Total number of new customers:</h5>
                  <h6 class="pl-2">{{ customersData.new_customer }}</h6>
                </div>
                <div class="d-flex align-items-center">
                  <h5 class="text-primary">
                    Total number of customers have more than an order:
                  </h5>
                  <h6 class="pl-2">
                    {{ customersData.has_more_order_customer }}
                  </h6>
                </div>
                <div class="d-flex align-items-center">
                  <h5 class="text-primary">
                    Total number of customers have an order:
                  </h5>
                  <h6 class="pl-2">
                    {{ customersData.has_order_customer }}
                  </h6>
                </div>
              </div>
            </b-card-text></b-tab
          >
          <!-- end customers tab -->

          <!-- start orders tab -->
          <b-tab :title="$t('orders')">
            <b-card-text>
              <!-- inline form for filtering -->
              <div>
                <b-form class="mb-4 align-items-end">
                  <div class="row">
                    <b-col lg="3" md="3" sm="12">
                      <b-form-group :label="$t('orderType')" class="text-6">
                        <b-form-select
                          id="inline-form-custom-select-pref"
                          class="mb-2 mr-sm-2 mb-sm-0"
                          v-model="orderType"
                          :options="orderTypes"
                        ></b-form-select>
                      </b-form-group>
                    </b-col>

                    <b-col lg="3" md="3" sm="12">
                      <b-form-group :label="$t('filterBy')" class="text-6">
                        <b-form-select
                          id="inline-form-custom-select-pref"
                          class="mb-2 mr-sm-2 mb-sm-0"
                          v-model="orderDate"
                          :options="orderDates"
                        ></b-form-select>
                      </b-form-group>
                    </b-col>

                    <b-col lg="3" md="3" sm="12">
                      <b-form-group :label="$t('orderStatus')" class="text-6">
                        <b-form-select
                          id="inline-form-custom-select-pref"
                          class="mb-2 mr-sm-2 mb-sm-0"
                          v-model="orderStatus"
                          :options="statusOfOrders"
                        ></b-form-select>
                      </b-form-group>
                    </b-col>

                    <b-col lg="3" md="3" sm="12" class="">
                      <b-form-group
                        :label="$t('restaurantName')"
                        class="text-6"
                      >
                        <b-form-select
                          id="inline-form-custom-select-pref"
                          class="mb-2 mr-sm-2 mb-sm-0"
                          v-model="restaurantID"
                          :options="restaurantSelect"
                          text-field="name"
                          value-field="id"
                        ></b-form-select>
                      </b-form-group>
                    </b-col>

                    <b-col lg="3" md="3" sm="12" class="mt-2">
                      <b-form-group :label="$t('from')" class="text-6">
                        <date-picker
                          v-model="dateFrom"
                          valueType="format"
                        ></date-picker>
                      </b-form-group>
                    </b-col>

                    <b-col lg="3" md="3" sm="12" class="mt-2">
                      <b-form-group label="To" class="text-6 ml-2">
                        <date-picker
                          v-model="dateTo"
                          valueType="format"
                        ></date-picker>
                      </b-form-group>
                    </b-col>

                    <b-col
                      lg="6"
                      md="6"
                      sm="12"
                      class="text-right align-self-end mb-2 pb-2"
                    >
                      <b-button
                        class="ml-2 mr-2 small-btn"
                        variant="primary"
                        @click="listOrdersData()"
                        >{{ $t("search") }}</b-button
                      >
                      <download-excel
                        class="btn btn-primary small-btn"
                        :data="orders"
                        :fields="ordersFields"
                        worksheet="Orders"
                        name="orders.xls"
                      >
                        <i class="i-File-Excel text-18 mr-2"></i>
                        {{ $t("exportExcel") }}
                      </download-excel>
                      <b-button
                        class="ml-2 mr-2 small-btn"
                        variant="primary"
                        @click="generatePDF"
                        >Print
                      </b-button>
                    </b-col>
                  </div>
                </b-form>
              </div>
              <!-- datatable -->
              <vue-good-table
                ref="pdf"
                id="pdfDive"
                :columns="columnsOrders"
                :line-numbers="false"
                :totalRows="totalRecordsOrders"
                :search-options="{
                  enabled: false,
                  placeholder: $t('searchTable'),
                }"
                :pagination-options="{
                  enabled: paginationOption,
                  perPage: 15,
                  mode: 'records',
                  perPageDropdownEnabled: false,
                }"
                styleClass="tableOne vgt-table"
                :selectOptions="{
                  enabled: false,
                  selectionInfoClass: 'table-alert__box',
                }"
                :rows="orders"
              >
                <div slot="emptystate">
                  <center>{{ this.$t("apply_filter") }}</center>
                </div>
              </vue-good-table>
            </b-card-text></b-tab
          >
          <!-- end orders tab -->

          <!-- start restaurants tab -->
          <b-tab :title="$t('restaurants')">
            <b-card-text>
              <vue-good-table
                :columns="columnsRestaurant"
                :line-numbers="false"
                :totalRows="totalRecordsRest"
                :search-options="{
                  enabled: true,
                  placeholder: $t('searchTable'),
                }"
                @on-page-change="onPageChange"
                :pagination-options="{
                  enabled: true,
                  perPage: 15,
                  perPageDropdownEnabled: false,
                  infoFn: (params) =>
                    `${fromRes} - ${toRes} of ${totalRecordsRest}`,
                }"
                styleClass="tableOne vgt-table"
                :selectOptions="{
                  enabled: false,
                  selectionInfoClass: 'table-alert__box',
                }"
                :rows="restaurants"
              >
                <template slot="table-row" slot-scope="props">
                  <span v-if="props.column.field == 'button'">
                    <router-link
                      :to="{ path: `/app/dashboards/orders/${props.row.id}` }"
                      class="text-black text-10 p-1 mr-2"
                    >
                      <i class="i-Eye text-22 text-warning mr-2"></i
                    ></router-link>
                  </span>
                </template>
              </vue-good-table>
              <div class="d-flex align-items-center mt-3">
                <h5 class="text-primary">Total number of new restaurants:</h5>
                <h6 class="pl-2">{{ restaurantData.new_restaurant }}</h6>
              </div>
            </b-card-text></b-tab
          >
          <!-- end restaurant tab -->
        </b-tabs>
      </b-card>
    </div>

    <!-- end tabs -->
  </div>
  <!-- ============ Body content End ============= -->
</template>
<script>
import axios from "axios";
import { mapGetters } from "vuex";
import VueTimepicker from "vue2-timepicker";
import "vue2-timepicker/dist/VueTimepicker.css";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import html2pdf from "html2pdf.js";

export default {
  components: { VueTimepicker, DatePicker, html2pdf },

  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Dashboard",
  },
  inject: ["file_url"],
  data() {
    return {
      customers: [],
      // all data of customers
      customersData: [],
      restaurants: [],
      restaurantData: [],
      orders: [],
      searchtext: "",
      orderType: "all",
      orderTypes: [
        { value: "all", text: "All" },
        { value: "0", text: "Takeway" },
        { value: "1", text: "Delivery" },
      ],
      orderDate: "",
      orderDates: [
        { value: "", text: "All" },
        { value: "today", text: "Today" },
        { value: "yesterday", text: "Yesterday" },
        { value: "this-week", text: "This week" },
        { value: "prev-week", text: "Previous week" },
        { value: "this-month", text: "This month" },
        { value: "prev-month", text: "Previous month" },
      ],
      orderStatus: "",
      statusOfOrders: [
        { value: "", text: "All" },
        { value: 9, text: "Rejected" },
        { value: 1, text: "Pending" },
        { value: 7, text: "Delivered" },
        { value: 2, text: "Accepted" },
      ],
      // date range
      dateFrom: null,
      dateTo: null,
      orderDateRange: "",
      restaurantSelect: [{ id: "", name: "All" }],
      restaurantID: "",
      totalResNum: "",

      latlng: [],

      serverParams: {
        page: 1,
        perPage: 15,
      },
      // customers
      totalRecords: 0,
      // restaurants
      totalRecordsRest: 0,
      // orders
      totalRecordsOrders: 0,
      from: 1,
      to: 15,
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
        Accept: "application/json",
      },
      columnsCustomer: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("name"),
          field: "name",
        },
        {
          label: this.$t("email"),
          field: "email",
        },
        {
          label: this.$t("phone"),
          field: "phone",
        },
        {
          label: this.$t("ordersCnt"),
          field: "orders_count",
        },
        {
          label: this.$t("totalAmount"),
          field: "orders_sum_total_amount",
        },
      ],
      columnsRestaurant: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("name"),
          field: "name",
        },
        {
          label: this.$t("image"),
          field: "image",
          html: true,
        },
        {
          label: this.$t("totalOrders"),
          field: "orders_count",
        },
        {
          label: this.$t("restOrders"),
          field: "button",
          html: true,
          tdClass: "text-right",
          thClass: "text-right",
        },
      ],
      columnsOrders: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("customerName"),
          field: "user.name",
        },
        {
          label: this.$t("restaurantName"),
          field: "restaurant.name",
        },
        {
          label: this.$t("driverName"),
          field: "driver.name",
        },
        {
          label: this.$t("subTotal"),
          field: "sub_total",
        },
        {
          label: this.$t("discountCode"),
          field: "discount_code.code",
        },

        {
          label: this.$t("status"),
          field: "status",
          html: true,
        },
        {
          label: this.$t("restaurantAddress"),
          field: "restaurant.address",
        },
        {
          label: this.$t("customerAddress"),
          field: "address.area",
        },
        {
          label: this.$t("date"),
          field: "created_at",
          type: "date",
          dateInputFormat: "yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'",
          dateOutputFormat: "yyyy-MM-dd HH:mm",
        },

        {
          label: this.$t("totalAmount"),
          field: "total_amount",
        },
      ],
      customersFields: {
        "Customer Name": "user.name",
        Email: "email",
        Telephone: "phone",
        "Order Count": "orders_count",
        Total: "orders_sum_total_amount",
      },
      toOrder: 0,
      fromOrder: 15,
      fromRes: 0,
      toRes: 15,
      ordersFields: {
        "Customer Name": "user.name",
        "Restaurant Name": "restaurant.name",
        "Driver Name": "driver.name",
        Subtotal: "sub_total",
        "Discount Code": "discount_code.code",
        Status: "status",
        "Restaurant Address": "restaurant.address",
        "Castomer Address": "address.area",
        "Order Date": "date",
        Total: "total_amount",
      },
      paginationOption: true,
    };
  },
  mounted() {
    this.listCustomersData();
    this.listRestaurantsData();
    this.listDataRes();
    this.listData();
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.listCustomersData();
      this.listRestaurantsData();
      // this.listOrdersData();
    },
    // list customers data
    listCustomersData() {
      axios
        .post(
          "admin/report/customers",
          {},
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token"),
            },
            params: this.serverParams,
          }
        )
        .then((response) => {
          this.customers = response.data.customer.data.map((item) => ({
            ...item,
          }));
          this.customersData = response.data.data;
          this.totalRecords = response.data.customer.total;
          this.to = response.data.customer.to || 0;
          this.from = response.data.customer.from || 0;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    // list resturants data
    listRestaurantsData(searchTerm) {
      let url;
      if (searchTerm) {
        url = `admin/report/restaurants?searchTerm=${searchTerm}`;
      } else {
        url = `admin/report/restaurants`;
      }
      axios
        .post(
          url,
          {},
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token"),
            },
            params: this.serverParams,
          }
        )
        .then((response) => {
          this.restaurants = response.data.restaurant.data.map((item) => ({
            ...item,
            image: `<image width="50" height="50" src="${this.file_url}${item.image}"/>`,
          }));
          this.restaurantData = response.data.data;
          this.totalRecordsRest = response.data.restaurant.total;
          this.toRes = response.data.restaurant.to || 0;
          this.fromRes = response.data.restaurant.from || 0;
          this.totalResNum = response.data.data.all_restaurant;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    // list orders data
    listOrdersData() {
      if (this.dateTo != null && this.dateFrom != null) {
        this.orderDateRange = `${this.dateFrom},${this.dateTo}`;
      }
      console.log(this.orderDateRange);
      let filterVar = {
        type: this.orderType,
        range: this.orderDate,
        status: this.orderStatus,
        restaurant: this.restaurantID,
        from: this.dateFrom,
        to: this.dateTo,
      };
      console.log(filterVar);
      axios
        .get("admin/export/orders", {
          headers: this.headers,
          params: filterVar,
        })
        .then((response) => {
          // this.totalRecords = response.data.orders.total;
          this.orders = response.data.orders.map((item) => ({
            ...item,
            status: `<span class="badge badge-success badge-${item.order_status.id}">${item.order_status.name}</span>`,
          }));
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    // list data of restaurants to display it in selectbox
    listDataRes() {
      axios
        .get("restaurants/all/lite", {
          headers: this.headers,
        })
        .then((response) => {
          for (
            let index = 0;
            index < response.data.restaurants.length;
            index++
          ) {
            this.restaurantSelect.push(response.data.restaurants[index]);
          }
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    generatePDF() {
      this.paginationOption = false;
      var element = document.getElementById("pdfDive");
      // console.log(element.columns)
      var opt = {
        margin: 0.25,
        filename: "orders.pdf",
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: {
          unit: "in",
          format: "letter",
          orientation: "portrait",
        },
        pagebreak: { mode: ["avoid-all", "css", "legacy"] },
      };

      html2pdf().set(opt).from(element).save();

      setTimeout(() => (this.paginationOption = true), 2000);
      // this.paginationOption = true;
    },
    listData() {
      axios
        .get("admin/count-of/orders", {
          headers: this.headers,
        })
        .then((response) => {
          this.totalRecordsOrders = response.data.count;
          console.log(response.data);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
  },
  // watcher for search term value
  watch: {
    searchtext: function (newval) {
      if (newval.length > 1) {
        this.listRestaurantsData(newval);
      }
    },
  },
};
</script>
<style scoped>
.echarts {
  width: 100%;
  height: 100%;
}
.badge-1 {
  background-color: #ffc107;
}
.badge-6 {
  background-color: #52495a;
}
.badge-4 {
  background-color: #003473;
}
.badge-5 {
  background-color: #8b5cf6;
}
.badge-9 {
  background-color: #f44336;
}
fieldset div {
  width: 100% !important;
}
.form-inline .form-group div,
.form-inline .input-group,
.form-inline .custom-select {
  width: 100%;
}
.searchInput {
  width: 25em;
}
</style>
