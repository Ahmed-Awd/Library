<template>
  <div class="main-content">
    <breadcumb
      :page="this.$t('restaurant') + ' ' + this.$t('ratings')"
      :folder="this.$t('datatables')"
    />
    <vue-good-table
      :columns="columns"
      :line-numbers="false"
      :totalRows="total"
      :search-options="{ enabled: true, placeholder: this.$t('searchTable') }"
      @on-page-change="onPageChange"
      :pagination-options="{
        enabled: true,
        perPageDropdownEnabled: false,
        infoFn: () => `${from} - ${to} of ${total}`,
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
          <a @click.prevent="showRatingModal(props.row)">
            <i class="i-Information text-25 text-primary mr-2" />
            {{ props.row.button }}</a
          >
          <a
            @click.prevent="showDeleteModal(props.row)"
            v-if="
              roleName == 'super_ademin' ||
              (roleName == 'admin' &&
                adminPermissionsList.includes('delete restaurant rating'))
            "
          >
            <i class="i-Close-Window text-25 text-danger" />
            {{ props.row.button }}</a
          >
        </span>
      </template>
    </vue-good-table>
    <b-modal ref="delete-modal" hide-footer :title="this.$t('deleteConfirm')">
      <div class="d-block">
        <h3>{{ this.$t("deleteCat") }} {{ this.$t("rating") }}?</h3>
      </div>
      <div class="text-right">
        <b-button class="btn btn-primary mt-4 mr-3" @click="hideDeleteModal">{{
          this.$t("cancel")
        }}</b-button>
        <b-button
          class="btn btn-primary mt-4"
          variant="outline-danger"
          @click="deleteRating"
          >{{ this.$t("delete") }}</b-button
        >
      </div>
    </b-modal>
    <b-modal ref="rating-modal" hide-footer :title="this.$t('rating')">
      <div class="d-block">
        <b-card no-body class="overflow-hidden" v-if="current_rating.user">
          <div class="text-left">
            <b-card-body>
              <b-img
                left
                rounded
                class="m1"
                :src="current_rating.user.profile_photo_path"
                @error="
                  $event.target.src =
                    'https://img.icons8.com/material/100/000000/user-male-circle--v1.png'
                "
              />
              <b-card-text>
                <div class="text-left">
                  <h5>
                    <strong>{{ current_rating.user.name }}</strong>
                  </h5>
                  <h5>
                    {{ this.$t("rate") }}:
                    <star-rating
                      :star-size="20"
                      :max-rating="6"
                      :show-rating="false"
                      :read-only="true"
                      :rating="current_rating.rate"
                    />
                  </h5>
                </div>
              </b-card-text>
            </b-card-body>
          </div>
          <div class="text-justify">
            <b-card-body :title="this.$t('comment')">
              {{ this.current_rating.comment }}
            </b-card-body>
          </div>
        </b-card>
      </div>
      <div class="text-right">
        <b-button
          class="btn btn-primary mt-4 mr-3"
          variant="outline-danger"
          @click="hideRatingModal"
          >Close</b-button
        >
      </div>
    </b-modal>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
import StarRating from "vue-star-rating";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "ratings",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  components: {
    StarRating,
  },
  data() {
    return {
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("name"),
          field: "user.name",
        },
        {
          label: this.$t("comment"),
          field: "comment",
          width: "400px",
        },
        {
          label: this.$t("rate"),
          field: "rate",
        },
        {
          label: this.$t("createdOn"),
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
          thClass: "text-right",
        },
      ],
      rows: [],
      to: 0,
      from: 0,
      total: 0,
      restaurant_id: this.$route.params.restaurant,
      rating: {},
      current_rating: {},
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),

        Accept: "application/json",
      },
    };
  },
  mounted() {
    this.showAll();
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.showAll();
    },
    showAll() {
      axios
        .get(`restaurants/rating/${this.restaurant_id}`, {
          headers: this.headers,
          params: this.serverParams,
        })
        .then((response) => {
          console.log(response.data);
          this.to = response.data.ratings.to || 0;
          this.from = response.data.ratings.from || 0;
          this.total = response.data.ratings.total;
          this.rows = response.data.ratings.data.map((item) => ({
            ...item,
          }));
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
    showRating() {
      if (this.current_rating.user.profile_photo_path == null) {
        this.current_rating.user.profile_photo_path = "";
      }
    },
    async deleteRating() {
      await axios
        .delete(`restaurants/rating/${this.current_rating.id}`, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "delete completed successfully");
          this.hideDeleteModal();
          this.showAll();
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", "delete failed");
          this.hideDeleteModal();
        });
    },
    showDeleteModal(rating) {
      this.current_rating = rating;
      this.$refs["delete-modal"].show();
    },
    hideDeleteModal() {
      this.current_rating = {};
      this.$refs["delete-modal"].hide();
    },
    showRatingModal(rating) {
      this.current_rating = rating;
      this.showRating();
      this.$refs["rating-modal"].show();
    },
    hideRatingModal() {
      this.current_rating = {};
      this.$refs["rating-modal"].hide();
    },
    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
  },
};
</script>

<style scoped>
.m1 {
  width: 100px;
  height: 100px;
  margin-right: 15px;
}
</style>
