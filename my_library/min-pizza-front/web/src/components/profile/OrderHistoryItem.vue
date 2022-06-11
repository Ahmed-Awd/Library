<template>
  <div v-if="order.length != 0">
    <table class="table">
      <tbody>
        <tr>
          <th scope="row">
            {{ $t("orderNumber") }}</th>
          <td class="text-end green-color">
            <router-link
                :to="{ name: 'Order', params: { id: order.id } }"
              >
               # {{ order.order_number }}
              </router-link>
          </td>
        </tr>
        <tr>
          <th scope="row">{{ $t("orderDate") }}</th>
          <td class="text-end">{{new Date(order.created_at).toLocaleDateString()}} | {{new Date(order.created_at).toLocaleTimeString()}}</td>
        </tr>
        <tr>
          <th scope="row">{{ $t("totalOrder") }}</th>
          <td class="text-end green-color">{{ order.total_amount }}</td>
        </tr>
        <tr>
          <th scope="row">{{ $t("resturant") }}</th>
          <td class="text-end" v-if="order.restaurant">
            {{ order.restaurant.name }}
          </td>
        </tr>
        <tr>
          <!-- TODO: Need to know the values of each status in both languages -->
          <th scope="row">{{ $t("orderStatus") }}</th>
          <td class="text-end">
            <span
              class="badge bg-secondary "
              :class="order.order_status_id == 9 ? 'red-badge' : 'green-badge'"
              v-if="order.order_status.name == order.order_status.name"
            >
              {{ order.order_status.name }}
            </span>
          </td>
        </tr>
        <tr>
          <th scope="row"></th>
          <td class="text-start">
            <button
              type="button" 
              class="btn custom-btn small-btn" 
              data-bs-toggle="modal"
              data-bs-target="#reorderModal"
              @click.prevent="getOrderData(order.id)"
              >{{ $t("reorder") }}
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: ["order"],
};
</script>
