<template>
  <div class="main-content">
    <breadcumb
      :page="this.$t('orders')"
      :folder="'All' + ' ' + this.$t('orders')"
    />
    <!-- <div class="wrapper"> -->
    <div class="order-det">
      <div class="order-info">
        <div class="input-block clearfix">
          <div class="label">
            {{ this.$t("order") }} {{ this.$t("number") }}:
          </div>
          <div class="value">{{ orderDetails.order_number }}</div>
        </div>
        <div class="input-block clearfix">
          <div class="label">
            {{ this.$t("restaurant") }} {{ this.$t("name") }}:
          </div>
          <div class="value">
            {{ orderDetails.restaurant ? orderDetails.restaurant.name : " " }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">
            {{ this.$t("customer") }} {{ this.$t("name") }}
          </div>
          <div class="value">
            {{ orderDetails.user ? orderDetails.user.name : " " }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">
            {{ this.$t("contact") }} {{ this.$t("number") }}
          </div>
          <div class="value">
            {{ orderDetails.user ? orderDetails.user.phone : " " }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">{{ this.$t("address") }}:</div>
          <div class="value">
            {{
              orderDetails.address
                ? orderDetails.address.area +
                  " , " +
                  orderDetails.address.description
                : ""
            }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">
            {{ this.$t("order") }} {{ this.$t("date") }} :
          </div>
          <div class="value">
            {{
              orderDetails.created_at
                ? new Date(orderDetails.created_at).toLocaleDateString()
                : " "
            }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">{{ this.$t("driverName") }} :</div>
          <div class="value">
            {{ orderDetails.driver ? orderDetails.driver.name : " " }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">{{ this.$t("driverPhone") }} :</div>
          <div class="value">
            {{ orderDetails.driver ? orderDetails.driver.phone : " " }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">{{ this.$t("deliveryTime") }}</div>
          <div class="value">
            {{ orderDetails.delivery_time ? orderDetails.delivery_time : " " }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">{{ this.$t("deliveredAt") }} :</div>
          <div class="value">
            {{ orderDetails.delivered_at ? orderDetails.delivered_at : " " }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">{{ this.$t("deliveryFee") }} :</div>
          <div class="value">
            {{
              orderDetails.delivery_fee
                ? orderDetails.delivery_fee + " " + orderDetails.currency_code
                : 0 + " " + orderDetails.currency_code
            }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">
            {{ this.$t("order") }} {{ this.$t("status") }} :
          </div>
          <div class="value">
            {{
              orderDetails.order_status ? orderDetails.order_status.name : " "
            }}
          </div>
        </div>
        <div
          class="input-block clearfix"
          v-if="orderDetails.order_status.name == 'Rejected'"
        >
          <div class="label">{{ this.$t("rejectedReason") }} :</div>
          <div class="value">
            {{ orderDetails.reason ? orderDetails.reason.reason : " " }}
          </div>
        </div>
        <div
          class="input-block clearfix"
          v-if="orderDetails.order_status.name == 'Rejected'"
        >
          <div class="label">{{ this.$t("rejectedComment") }} :</div>
          <div class="value">
            {{
              orderDetails.refuse_comment ? orderDetails.refuse_comment : " "
            }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">
            {{ this.$t("preparation") }} {{ this.$t("time") }} :
          </div>
          <div class="value">
            {{
              orderDetails.preparation_time
                ? orderDetails.preparation_time + " min"
                : " "
            }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">{{ this.$t("minimumOrder") }} :</div>
          <div class="value">
            {{
              orderDetails.min_order_price ? orderDetails.min_order_price : " "
            }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">{{ this.$t("minimumOrderAdjustment") }} :</div>
          <div class="value">
            {{
              orderDetails.minimum_order_adjustment_amount
                ? orderDetails.minimum_order_adjustment_amount
                : " "
            }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">Service {{ this.$t("type") }} :</div>
          <div class="value">
            {{ orderDetails.service_info_type == 0 ? "Teakaway" : "Delivery" }}
          </div>
        </div>
        <div class="input-block clearfix">
          <div class="label">{{ this.$t("paymentMethod") }} :</div>
          <div class="value">
            {{ orderDetails.payment_method.name }}
            <span
              class="sm_font"
              v-if="
                orderDetails.payment_method.name != 'cash' &&
                orderDetails.payment == null
              "
              >({{ $t("unpaid") }})</span
            >
          </div>
        </div>
        <div class="order-items-table clearfix">
          <div class="table-responsive">
            <table class="table order-det-table table-hover mb-4">
              <thead class="bg-gray-300">
                <tr>
                  <th>{{ this.$t("name") }}</th>
                  <th>{{ this.$t("qty") }}</th>
                  <th>{{ this.$t("note") }}</th>
                  <th>{{ this.$t("price") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in items" :key="item.id">
                  <td>{{ item.name }}</td>
                  <td>{{ item.quantity }}</td>
                  <td>{{ item.note }}</td>
                  <td class="text-left">{{ item.total }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="invoice-summary">
            <p>
              {{ this.$t("subtotal") }}:
              <span>{{
                orderDetails.sub_total
                  ? orderDetails.sub_total + " " + orderDetails.currency_code
                  : 0 + orderDetails.currency_code
              }}</span>
            </p>
            <p>
              {{ this.$t("discount") }}:
              <span>{{
                orderDetails.sub_total
                  ? orderDetails.total_discount +
                    " " +
                    orderDetails.currency_code
                  : 0 + orderDetails.currency_code
              }}</span>
            </p>
            <h5 class="font-weight-bold">
              {{ this.$t("total") }}:
              <span>{{
                orderDetails.total_amount
                  ? orderDetails.total_amount + " " + orderDetails.currency_code
                  : 0 + orderDetails.currency_code
              }}</span>
            </h5>
          </div>
        </div>
      </div>
      <div class="text-center mt-3 mb-4">
        <b-button
          v-if="orderDetails.order_status_id == '2'"
          class="ml-2 mr-2 small-btn btn btn-success"
          variant="primary"
          @click.prevent="showAssignModal()"
          ><i class="i-Bicycle text-18 mr-2"></i> {{ $t("assignDriver") }}
        </b-button>
        <b-button
          v-if="orderDetails.order_status_id == '2'"
          class="ml-2 mr-2 small-btn btn btn-danger"
          variant="primary"
          @click.prevent="showRejectModal()"
          ><i class="i-Close-Window text-18 mr-2"></i> {{ $t("reject") }}
        </b-button>
        <b-button
          v-if="changStatus.includes(orderDetails.order_status_id)"
          class="ml-2 mr-2 small-btn btn btn-info"
          variant="primary"
          @click.prevent="showStatusModal()"
          ><i class="i-Edit text-18 mr-2"></i> {{ $t("changeStatus") }}
        </b-button>
      </div>
    </div>

    <b-modal ref="reject-modal" hide-footer :title="$t('rejectOrder')">
      <div class="d-block">
        <b-form-group :label="$t('reason')" class="text-6">
          <b-form-select
            class="form-control"
            type="text"
            v-model="refused_reasons.refuse_reason"
            :options="rejectedReasonSelect"
            text-field="reason"
            value-field="id"
            required
          ></b-form-select>
        </b-form-group>
        <b-form-group :label="$t('rejectedComment')" class="text-6">
          <b-form-textarea
            class="form-control"
            type="text"
            v-model="refused_reasons.refuse_comment"
            required
          ></b-form-textarea>
        </b-form-group>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="rejectOrder"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
          >
            {{ this.$t("reject") }}
          </b-button>
          <b-button class="btn btn-primary mt-4" @click="hideRejectModal">
            {{ this.$t("cancel") }}
          </b-button>
        </div>
        <!-- <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3"></div>
        </div> -->
      </div>
    </b-modal>
    <b-modal ref="assign-modal" hide-footer :title="$t('assignDriver')">
      <div class="d-block">
        <b-form-group :label="$t('driverName')" class="text-6">
          <b-form-select
            class="form-control"
            type="text"
            v-model="drivers.user_id"
            :options="driversSelect"
            text-field="user.name"
            value-field="user_id"
            required
          ></b-form-select>
        </b-form-group>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="assignDriver"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
          >
            {{ this.$t("assign") }}
          </b-button>
          <b-button class="btn btn-primary mt-4" @click="hideAssignModal">
            {{ this.$t("cancel") }}
          </b-button>
        </div>
        <!-- <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3"></div>
        </div> -->
      </div>
    </b-modal>
    <b-modal ref="status-modal" hide-footer :title="$t('changeStatus')">
      <div class="d-block">
        <b-form-group :label="$t('status')" class="text-6">
          <b-form-select
            class="form-control"
            type="text"
            v-model="statusArr.id"
            :options="statusArr"
            text-field="name"
            value-field="id"
            required
          ></b-form-select>
        </b-form-group>
        <div class="text-right">
          <b-button
            type="submit"
            tag="button"
            @click.prevent="changeOrdrStatus"
            class="btn btn-primary mt-4 mr-3"
            variant="primary mt-2"
          >
            {{ this.$t("changeStatus") }}
          </b-button>
          <b-button class="btn btn-primary mt-4" @click="hideStatusModal">
            {{ this.$t("cancel") }}
          </b-button>
        </div>
        <!-- <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3"></div>
        </div> -->
      </div>
    </b-modal>
  </div>
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
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Orders",
  },
  components: { VueTimepicker, DatePicker, html2pdf },
  data() {
    return {
      modalShow: false,
      from: 1,
      to: 15,

      totalRecords: 0,
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
        Accept: "application/json",
      },
      rows: [],
      orderDetails: [],
      items: [],
      current_order: {
        name: "",
        id: "",
      },
      statistics: [],
      currency: "",
      totalOrders: "",
      refused_reasons: {},
      rejected_order_id: "",
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
        { value: 1, text: "Pending" },
        { value: 2, text: "Accepted" },
        { value: 7, text: "Delivered" },
        { value: 8, text: "Complated" },
        { value: 9, text: "Rejected" },
      ],
      // date range
      dateFrom: null,
      dateTo: null,
      orderDateRange: "",
      restaurantSelect: [{ id: "", name: "All" }],
      restaurantID: "",
      rejectedReasonSelect: [],
      disabled: false,
      ordersFields: {
        "Order No": "order_number",
        "Customer Name": "user.name",
        "Restaurant Name": "restaurant.name",
        Status: "status",
        "Restaurant Address": "restaurant.address",
        "Castomer Address": "address.area",
        "Order Date": "date",
        Total: "total_amount",
      },
      paginationOption: true,
      printedColumns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("orderNo"),
          field: "order_number",
        },
        {
          label: this.$t("restaurant"),
          field: "restaurant.name",
        },
        {
          label: this.$t("customer"),
          field: "user.name",
        },
        {
          label: this.$t("address"),
          field: "address.area",
        },
        {
          label: this.$t("total"),
          field: "sub_total",
        },
        {
          label: this.$t("status"),
          field: "status",
          html: true,
        },
        {
          label: this.$t("orderDate"),
          field: "created_at",
          type: "date",
          dateInputFormat: "yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'",
          dateOutputFormat: "yyyy-MM-dd HH:mm",
        },
      ],
      driversSelect: [],
      drivers: {},
      orderID: window.location.pathname.split("/")[4],
      changStatus: [1, 2, 3, 4, 5, 6],
      statusArr: [],
    };
  },
  mounted() {
    // this.listData();
    this.orderDet();
    // this.listOrdersData();
    // this.listDataRes();
    // this.listRjectReasons();
    this.getDrivers();
    this.getStatus();
  },
  methods: {
    orderDet() {
      axios
        .get(`admin/orders/by-order/${this.orderID}`, { headers: this.headers })
        .then((response) => {
          this.orderDetails = response.data.order;
          console.log(this.orderDetails);
          this.items = this.orderDetails.order_item;
          console.log(this.items);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showDetModal(order) {
      this.current_order = order;
      this.orderDet(this.current_order.id);
      console.log(this.current_order);
      this.$refs["order-det-modal"].show();
    },
    showRejectModal(order) {
      this.refused_reasons = {};
      this.$refs["reject-modal"].show();
      this.rejected_order_id = this.orderID;
    },
    hideRejectModal() {
      this.current_order = {};
      this.$refs["reject-modal"].hide();
    },
    rejectOrder() {
      axios
        .post(
          `admin/orders/cancel/this/${this.rejected_order_id}`,
          {
            refuse_reason: this.refused_reasons.refuse_reason,
            refuse_comment: this.refused_reasons.refuse_comment,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", response.data.message);
          this.hideRejectModal();
          this.listOrdersData();
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
    },
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
          this.rows = response.data.orders.map((item) => ({
            ...item,
            status: `<span class="badge badge-success badge-${item.order_status.id}">${item.order_status.name}</span>`,
          }));
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showAssignModal() {
      this.drivers = {};
      this.$refs["assign-modal"].show();
      this.order_id = this.orderID;
    },
    hideAssignModal() {
      this.drivers = {};
      this.$refs["assign-modal"].hide();
    },
    assignDriver() {
      axios
        .post(
          `admin/orders/assign/driver/${this.order_id}`,
          {
            driver_id: this.drivers.user_id,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response);
          this.makeToast("success", response.data.message);
          this.hideAssignModal();
          this.listOrdersData();
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
          this.hideAssignModal();
        });
    },
    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
    getDrivers() {
      axios
        .get("admin/get-all/available/drivers", {
          headers: this.headers,
        })
        .then((response) => {
          this.driversSelect = response.data.drivers;
          console.log(this.driversSelect);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showStatusModal(order) {
      this.$refs["status-modal"].show();
      this.order_id = this.orderID;
    },
    hideStatusModal() {
      this.current_order = {};
      this.$refs["status-modal"].hide();
    },
    getStatus() {
      axios
        .get("admin-order-status", {
          headers: this.headers,
        })
        .then((response) => {
          this.statusArr = response.data.order_status;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    changeOrdrStatus() {
      axios
        .post(
          `admin/orders/change-status/of/${this.order_id}`,
          {
            status: this.statusArr.id,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response);
          this.makeToast("success", response.data.message);
          this.hideStatusModal();
          this.orderDet();
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
          this.hideStatusModal();
        });
    },
  },
};
</script>
<style>
.modal-xl {
  max-width: 900px;
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

.order-info .input-block {
  border-bottom: 1px solid #f1f2f3;
  padding: 5px;
  display: flex;
  align-items: center;
}

.order-info .input-block .label {
  color: #999999;
  text-align: left;
  font-weight: 500;
  padding: 0;
  font-size: 12px;
  width: 50%;
  float: left;
}

.input-block .value {
  width: 50%;
  float: right;
  text-align: right;
  font-size: 13px;
  font-weight: 500;
}

.order-items-table {
  margin-top: 25px;
}

.order-items-table .table th {
  border-top: 0;
}

.card-icon-bg .card-body .content {
  margin-right: 0;
  margin-left: 25px;
  max-width: 150px;
}
.form-control {
  width: 100%;
}

.mx-datepicker {
  width: 100%;
}
.disabled {
  cursor: default;
  opacity: 0.3;
}
.sm_font {
  font-size: 0.9em;
  color: #ef1f1f;
}
</style>
