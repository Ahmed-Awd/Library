<template>
  <div>
    <div class="d-block">
      <b-form-group :label="$t('restaurant')" class="text-6" >
        <b-form-select
          class="form-select"
          @change="onChange($event)"
          :options="restaurants"
          text-field="name"
          value-field="id"
        >
        </b-form-select>
      </b-form-group>
    </div>

    <vue-good-table
      :columns="columns"
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
      :rows="rows"
    >
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'">
          <router-link
            tag="a"
            :to="{ path: `/app/dashboards/itemOfferItems/${props.row.id}` }"
            class="btn btn-outline-primary text-black text-10 p-1 mr-2"
          >
            {{ $t("items") }}
          </router-link>
        </span>
      </template>
    </vue-good-table>
    
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import axios from "axios";
export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Categories",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  data() {
    return {
      offer: {
        id: "",
        new_price: "",
        rank: "",
        start_date: "",
        end_date: "",
      },
        headers: {
        "Authorization": "Bearer " + localStorage.getItem("token"),
        
        "Accept": "application/json",
      },
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("name"),
          field: "name",
        },
        {
          label: this.$t("description"),
          field: "description",
          width: "500px",
        },
      
        {
          label: "",
          field: "button",
          html: true,
          tdClass: "text-right",
          thClass: "text-right",
        },
      ],
      rows: [],
      totalRecords: 0,
      from: 1,
      to: 15,
      serverParams: {
        page: 1,
        perPage: 15,
      },
      restaurants: [],
      new_offer: {},
      current_row: {},
    };
  },
  mounted() {
    this.getRestaurants();
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.show();
    },

    onChange(e) {
      axios.get(`menu/restaurant/categories/${e}`, {
        headers: this.headers,
      })
          .then((response) => {
            console.log(response.data);
            this.rows = response.data.categories.map(item => ({
              ...item,
            }))
          })
          .catch((errors) => {
            console.log(errors.data);
          })
    },

    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },

    
    getRestaurants() {
      axios
        .get(`restaurants/all/lite`, { headers: this.headers })
        .then((response) => {
          this.restaurants = response.data.restaurants;
          console.log(this.restaurants);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
  },
};
</script>
<style></style>
