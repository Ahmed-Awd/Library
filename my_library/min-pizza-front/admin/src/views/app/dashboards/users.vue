<template>
  <div class="main-content">
    <breadcumb :page="'users'" :folder="'Datatables'" />
    <!-- <div class="wrapper"> -->
    <vue-good-table
      :columns="columns"
      :line-numbers="false"
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
    <div slot="table-actions" class="mb-3">
        <b-button variant="primary" class="btn-rounded">
          Add New
        </b-button>
      </div>
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
          label: "ID",
          field: "id",
        },
        {
          label: "User Name",
          field: "username"
        },
        {
          label: "Name",
          field: "name"
        },
        {
          label: "Created On",
          field: "createdAt",
          type: "date",
          dateInputFormat: "yyyy-mm-dd",
          dateOutputFormat: "MMM Do yy"
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
          username:"admin",
          name:"User full Name",
          createdAt: "2021-10-29",
        },
        {
          id: 1,
          username:"admin",
          name:"User full Name",
          createdAt: "2021-10-29",
        },
        {
          id: 1,
          username:"admin",
          name:"User full Name",
          createdAt: "2021-10-29",
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
