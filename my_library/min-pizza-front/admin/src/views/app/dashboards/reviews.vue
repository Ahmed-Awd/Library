<template>
  <div class="main-content">
    <breadcumb :page="'Reviews'" :folder="'Datatables'" />
    <!-- <div class="wrapper"> -->
    <vue-good-table
      :columns="columns"
      :line-numbers="true"
      :search-options="{
        enabled: true,
        placeholder: 'Search this table'
      }"
      :pagination-options="{
        enabled: true,
        perPage: 15,
        mode: 'records'
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
          <a href="">
            <i class="i-Eraser-2 text-25 text-success mr-2"></i>
            {{ props.row.button }}</a
          >
          <a href="">
            <i class="i-Close-Window text-25 text-danger"></i>
            {{ props.row.button }}</a
          >
        </span>
      </template>
    </vue-good-table>
  </div>
</template>

<script>
export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "reviews"
  },
  data() {
    return {
      columns: [
        {
          label: "Resturant Name",
          field: "resturant_name",
          html: true
        },
        {
          label: "Customer",
          field: "customer"
        },
        {
          label: "Review",
          field: "review"
        },
        {
          label: "Rating",
          field: "rating"
        },
        {
          label: "Created On",
          field: "createdAt",
          type: "date",
          dateInputFormat: "yyyy-mm-dd",
          dateOutputFormat: "MMM Do yy"
        },
        {
          label: "Status",
          field: "status",
          html: true
        },
        {
          label: "",
          field: "button",
          html: true,
          tdClass: "text-right",
          thClass: "text-right"
        }
      ],
      rows: [
        {
          id: 1,
          resturant_name:"Resturant Name",
          customer:"Customer Name",
          review: 'Lorem Ipsum is simply dummy text of the printing and',
          rating:'4.0',
          createdAt: "2019-10-29",
          status: '<span class="badge badge-success">publish</span>'
        },
        {
          id: 1,
          resturant_name:"Resturant Name",
          customer:"Customer Name",
          review: 'Lorem Ipsum is simply dummy text of the printing and',
          rating:'4.0',
          createdAt: "2019-10-29",
          status: '<span class="badge badge-warning">waiting</span>'
        },
        {
          id: 1,
          resturant_name:"Resturant Name",
          customer:"Customer Name",
          review: 'Lorem Ipsum is simply dummy text of the printing and',
          rating:'4.0',
          createdAt: "2019-10-29",
          status: '<span class="badge badge-danger">refuced</span>'
        },
        {
          id: 1,
          resturant_name:"Resturant Name",
          customer:"Customer Name",
          review: 'Lorem Ipsum is simply dummy text of the printing and',
          rating:'4.0',
          createdAt: "2019-10-29",
          status: '<span class="badge badge-success">publish</span>'
        },
        {
          id: 1,
          resturant_name:"Resturant Name",
          customer:"Customer Name",
          review: 'Lorem Ipsum is simply dummy text of the printing and',
          rating:'4.0',
          createdAt: "2019-10-29",
          status: '<span class="badge badge-warning">waiting</span>'
        },
        {
          id: 1,
          resturant_name:"Resturant Name",
          customer:"Customer Name",
          review: 'Lorem Ipsum is simply dummy text of the printing and',
          rating:'4.0',
          createdAt: "2019-10-29",
          status: '<span class="badge badge-danger">refuced</span>'
        },
        {
          id: 1,
          resturant_name:"Resturant Name",
          customer:"Customer Name",
          review: 'Lorem Ipsum is simply dummy text of the printing and',
          rating:'4.0',
          createdAt: "2019-10-29",
          status: '<span class="badge badge-success">publish</span>'
        },
        {
          id: 1,
          resturant_name:"Resturant Name",
          customer:"Customer Name",
          review: 'Lorem Ipsum is simply dummy text of the printing and',
          rating:'4.0',
          createdAt: "2019-10-29",
          status: '<span class="badge badge-warning">waiting</span>'
        },
        {
          id: 1,
          resturant_name:"Resturant Name",
          customer:"Customer Name",
          review: 'Lorem Ipsum is simply dummy text of the printing and',
          rating:'4.0',
          createdAt: "2019-10-29",
          status: '<span class="badge badge-danger">refuced</span>'
        }
      ]
    };
  },
  methods: {
    addFile() {
      console.log("hello");
    }
  }
};
</script>
<style >

</style>
