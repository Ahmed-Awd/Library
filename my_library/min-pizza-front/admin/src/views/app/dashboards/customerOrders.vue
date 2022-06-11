<template>
  <div class="main-content">
    <breadcumb :page="'orders'" :folder="'Customer Orders'" />
    <!-- <div class="wrapper"> -->
    <vue-good-table
      :columns="columns"
      :line-numbers="false"
      :totalRows="totalRecords"
      :search-options="{
        enabled: true,
        placeholder: 'Search this table'
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
        selectionInfoClass: 'table-alert__box'
      }"
      :rows="rows"
    >
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'">
          <a href="" @click.prevent="showDetModal(props.row)">
            <i class="i-Eye text-22 text-warning mr-2"></i>
            {{ props.row.button }}</a
          >
        </span>
      </template>
    </vue-good-table>

    <b-modal ref="order-det-modal" hide-footer title="Order Details" size="xl">
        <div class="order-info">
          <div class="input-block clearfix">
            <div class="label">Order No:</div>
            <div class="value">{{ orderDetails.order_number }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Restaurant Name:</div>
            <div class="value">{{ orderDetails.restaurant ? orderDetails.restaurant.name : ' ' }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Customer Name:</div>
            <div class="value">{{ orderDetails.user ? orderDetails.user.name : ' ' }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Contact Number:</div>
            <div class="value">{{ orderDetails.user ? orderDetails.user.phone : ' '}}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Address:</div>
            <div class="value">{{ orderDetails.address ? orderDetails.address.area + " , "  + orderDetails.address.description : '' }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Order Date :</div>
            <div class="value">{{ orderDetails.created_at ? new Date(orderDetails.created_at).toLocaleDateString() : ' ' }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Driver Name :</div>
            <div class="value">{{ orderDetails.driver ? orderDetails.driver.name : ' ' }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Driver Phone :</div>
            <div class="value">{{ orderDetails.driver ? orderDetails.driver.phone : ' ' }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Delivery Time :</div>
            <div class="value">{{ orderDetails.delivery_time ? orderDetails.delivery_time : ' ' }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Delivered at :</div>
            <div class="value">{{ orderDetails.delivered_at ? orderDetails.delivered_at : ' ' }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Delivery Fee :</div>
            <div class="value">{{ orderDetails.delivery_fee ? orderDetails.delivery_fee +' ' + orderDetails.currency_code : ' ' }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Order Status :</div>
            <div class="value">{{ orderDetails.order_status ? orderDetails.order_status.name  : ' ' }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Preparation Time :</div>
            <div class="value">{{ orderDetails.preparation_time ? orderDetails.preparation_time  + ' min' : ' ' }}</div>
          </div>
          <div class="input-block clearfix">
            <div class="label">Service Type :</div>
            <div class="value">{{ orderDetails.service_info_type == 0 ? 'Teakaway' : 'Delivery' }}</div>
          </div>
          <div class="order-items-table">
            <div class="table-responsive">
              <table class="table order-det-table table-hover mb-4">
                <thead class="bg-gray-300">
                  <tr>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Note</th>
                    <th>Price</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in items" :key="item.id">
                    <td>{{ item.name }}</td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.note }} </td>
                    <td class="text-left">{{ item.total }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="invoice-summary">
              <p>Sub total: <span>{{ orderDetails.sub_total ? orderDetails.sub_total  + ' ' + orderDetails.currency_code : 0  + orderDetails.currency_code }}</span></p>
              <p>Discount: <span>{{ orderDetails.sub_total ? orderDetails.total_discount  + ' ' + orderDetails.currency_code : 0  + orderDetails.currency_code }}</span></p>
              <h5 class="font-weight-bold">Total: <span>{{ orderDetails.total_amount ? orderDetails.total_amount + ' ' + orderDetails.currency_code : 0  + orderDetails.currency_code }}</span></h5>
            </div>
          </div>
        </div>
    </b-modal>
  </div>
</template>

<script>
import axios from "axios";
import {mapGetters} from "vuex";
export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Orders"
  },
  data() {
    return {
      modalShow: false,
      from: 1,
      to: 15,
      serverParams: {
        page: 1,
        perPage: 15,
      },
      totalRecords: 0,
      headers: {
        "Authorization": "Bearer " + localStorage.getItem("token"),
        
        "Accept": "application/json",
      },
      columns: [
        {
          label: "ID",
          field: "id"
        },
        {
          label: "Order No",
          field: "order_number"
        },
        {
          label: "Resturant",
          field: "restaurant.name"
        },
        {
          label: "Customer",
          field: "user.name"
        },
        {
          label: "Address",
          field: "address.area"
        },
        {
          label: "Total",
          field: "sub_total"
        },
        {
          label: "Status",
          field: "status",
          html: true
        },
        {
          label: "Created on",
          field: "created_at",
          type: "date",
          dateInputFormat: "yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'",
          dateOutputFormat: "yyyy-MM-dd HH:mm",
        },
        {
          label: "",
          field: "button",
          html: true,
          tdClass: "text-right",
          thClass: "text-right"
        }
      ],
      rows: [],
      orderDetails: [],
      items: [],
      current_order: {
        name: "",
        id: "",
      },
      customer_id: window.location.pathname.split('/')[4],
    };
  },
  mounted() {
    this.listData();
    this.orderDet(1);
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({page: params.currentPage});
      this.listData();
    },
    listData() {
      axios.get(`orders/of/customer/${this.customer_id}`, {headers: this.headers, params: this.serverParams,})
        .then((response) => {
          this.totalRecords = response.data.orders.total;
          this.to = response.data.orders.to || 0;
          this.from = response.data.orders.from || 0;
          console.log(response.data.orders);
          this.rows = response.data.orders.data.map((item) => ({
              ...item,
              status: `<span class="badge badge-success badge-${item.order_status.id}">${item.order_status.name}</span>`,
            }));
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    orderDet(orderID){
      axios.get(`admin/orders/by-order/${orderID}`, {headers: this.headers,})
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
  },
};
</script>
<style >
.modal-xl{
  max-width: 900px;
}
.badge-1 {
  background-color: #ffc107;
}
.badge-6 {
  background-color: #52495a;
}
.badge-4{
  background-color: #003473;
}
.badge-5{
  background-color: #8b5cf6;
}
.badge-9{
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
.order-items-table{
  margin-top: 25px;
}
.order-items-table .table th{
  border-top: 0;
}
</style>
