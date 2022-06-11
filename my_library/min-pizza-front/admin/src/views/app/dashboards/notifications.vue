<template>
  <div>
    <breadcumb
        :page="$t('general') + ' ' + $t('notifications')"
        :folder="$t('datatables')"
    />
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

        @on-sort-change="onSortChange"
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
          <a href="" @click.prevent="showDetModal(props.row)">
            <i class="i-Eye text-22 text-warning mr-2"></i>
            {{ props.row.button }}</a
          >
        </span>
      </template>
      <div slot="table-actions" class="mb-3">
        <b-button
            v-if="
            roleName == 'super_admin' ||
            (roleName == 'admin' &&
              adminPermissionsList.includes('send notification'))
          "
            variant="primary"
            class="btn-rounded"
            @click="showCreateModal()"
            :disabled="loadingRequest"
        >
          {{ $t("addNew") }}
        </b-button>
      </div>
    </vue-good-table>
    <b-modal
        ref="notifications-det-modal"
        hide-footer
        :title="this.$t('notifications') + ' ' + this.$t('details')"
        size="xl"
    >
      <div class="d-flex justify-content-between mt-3 clearfix">
        <label class="label h5 text-muted"> {{ this.$t("creator") }}:</label>
        <div class="value h5">{{ creatorName }}</div>
      </div>
      <div class="d-flex justify-content-between mt-3 clearfix">
        <label class="label h5 text-muted"> {{ this.$t("createdAt") }}:</label>
        <div class="value h5">{{ formatTime }}</div>
      </div>
      <div class="d-flex justify-content-between mt-3 clearfix">
        <label class="label h5 text-muted"> {{ this.$t("delivered") }}:</label>
        <ul v-for="(role, index) of notificationDetails.roles" :key="index">
          <li class="value h5">{{ role.name }}</li>
        </ul>
      </div>
    </b-modal>
    <b-modal ref="create-modal" hide-footer :title="$t('create')">
      <div class="d-block">
        <b-form-group :label="$t('subject')" class="text-6">
          <b-form-input
              class="form-control"
              type="text"
              @input="$v.new_notifications.subject.$touch"
              v-model="new_notifications.subject"
              required
          ></b-form-input>
          <p class="errors" v-if="$v.new_notifications.subject.$dirty">
            <span
                class="form__alert"
                v-if="!$v.new_notifications.subject.required"
            >{{ $t("validation.required") }}</span
            >
            <span
                class="form__alert"
                v-if="!$v.new_notifications.subject.minLength"
            >{{ $t("validation.nameMinLen3") }}</span
            >
            <span
                class="form__alert"
                v-if="!$v.new_notifications.subject.maxLength"
            >{{ $t("validation.nameMaxLen") }}</span
            >
          </p>
        </b-form-group>
        <b-form-group :label="$t('body')" class="text-6">
          <b-textarea
              class="form-control"
              type="text"
              @input="$v.new_notifications.body.$touch"
              v-model="new_notifications.body"
              required
          ></b-textarea>
          <p class="errors" v-if="$v.new_notifications.body.$dirty">
            <span
                class="form__alert"
                v-if="!$v.new_notifications.body.required"
            >{{ $t("validation.required") }}</span
            >
            <span
                class="form__alert"
                v-if="!$v.new_notifications.body.minLength"
            >{{ $t("validation.questionMinLen") }}</span
            >
            <span
                class="form__alert"
                v-if="!$v.new_notifications.body.maxLength"
            >{{ $t("validation.bodyMaxLen") }}</span
            >
          </p>
        </b-form-group>
        <div class="mt-3">
          <h5>{{ $t("select") }}</h5>
          <div class="row ml-1">
            <div v-for="role in allRoles" :key="role[0]">
              <input
                  :value="role.id"
                  class="mr-3"
                  type="radio"
                  v-model="new_notifications.role"
              />
              <label class="mr-1" :for="new_notifications.roles">
                {{
                  role.name === "customer"
                      ? $t("customer")
                      : role.name === "driver"
                      ? $t("driver")
                      : $t("owner")
                }}
              </label>


            </div>
            <p class="errors" v-if="$v.new_notifications.role.$dirty">
                <span
                    class="form__alert"
                    v-if="!$v.new_notifications.role.required"
                >{{ $t("validation.rolemsg") }}</span
                >
            </p>
          </div>
        </div>

        <div class="text-right">
          <b-button
              type="submit"
              tag="button"
              class="btn btn-primary mt-4 mr-3"
              variant="primary mt-2"
              :disabled="loadingRequest || $v.new_notifications.$invalid"
              @click.prevent="createNew"
          >
            {{ $t("save") }}
          </b-button>
          <b-button class="btn btn-primary mt-4" @click="hideCreateModal">{{
              $t("cancel")
            }}
          </b-button>
        </div>
        <div v-once class="typo__p" v-if="loading">
          <div class="spinner sm spinner-primary mt-3"></div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import axios from "axios";
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
    title: "Categories",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
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
      searchtext: "",
      notification: {
        subject: "",
        body: "",
        role: "",
      },
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),

        Accept: "application/json",
      },
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("subject"),
          field: "subject",
        },
        {
          label: this.$t("body"),
          field: "body",
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
      allRoles: [],
      formatTime: "",
      creatorName: "",
      notificationDetails: [],
      new_notifications: {},
      current_row: {},
      loadingRequest: false,
    };
  },
  validations: {
    new_notifications: {
      subject: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(50),
      },
      body: {
        required,
        minLength: minLength(5),
        maxLength: maxLength(250),
      },
      role: {
        required,
      }
    },
  },
  // beforeCreate() {
  //   this.$OneSignal.showSlidedownPrompt();
  // },
  // beforeMount() {
  //   this.$OneSignal.init({ appId: "8c2ce721-0dcc-46ae-960e-dea288dd3459" });
  // },

  created() {
    // mount for define role name and permissions if admin login
    var role_name = localStorage.getItem("role");
    var adminPermissions = localStorage.getItem("adminPermissions");
    this.roleName = role_name;
    this.adminPermissionsList = adminPermissions;
  },
  // watcher for search term value
  watch: {
    searchtext: function (newval) {
      if (newval.length > 1) {
        this.getNotification(newval);
      } else {
        this.getNotification();
      }
    },
  },
  mounted() {
    this.getRoles();
    this.getNotification();
    this.getNotificationData();
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({page: params.currentPage});
      this.getNotification();
    },
    async onSortChange(params) {
      console.log(params);
      await this.updateParams({sort_by: params[0].field});
      await this.updateParams({sort_type: params[0].type});
      this.getNotification();
    },
    getNotification(searchTerm) {
      let url;
      if (searchTerm) {
        url = `get/notification/general?searchTerm=${searchTerm}`;
      } else {
        url = `get/notification/general`;
      }
      axios
          .get(url, {
            headers: this.headers,
            params: this.serverParams,
          })
          .then((response) => {
            this.rows = response.data.notifications.data;
            this.totalRecords = response.data.notifications.total;
            this.to = response.data.notifications.to || 0;
            this.from = response.data.notifications.from || 0;
            console.log(this.rows);
          })
          .catch((errors) => {
            console.log(errors);
          });
    },
    getNotificationData(id) {
      axios
          .get(`show/notification/${id}`, {
            headers: this.headers,
          })
          .then((response) => {
            this.notificationDetails = response.data.data;
            let time = this.notificationDetails.created_at;
            this.creatorName = this.notificationDetails.creator.name;
            this.formatTime = time.slice(0, 10);

            console.log(this.notificationDetails);
          })
          .catch((errors) => {
            console.log(errors);
          });
    },

    getRoles() {
      axios
          .get(`/roles/all`, {
            headers: this.headers,
          })
          .then((response) => {
            let Roles = response.data.roles;
            this.allRoles = Roles.slice(1, 4);
            console.log(this.allRoles);
          })
          .catch((errors) => {
            console.log(errors);
          });
    },

    createNew() {
      this.$v.$touch(); //it will validate all fields
      this.loadingRequest = true; // set this before running anything

      axios
          .post(
              "send/notification/general",
              {
                subject: this.new_notifications.subject,
                body: this.new_notifications.body,
                role: this.new_notifications.role,
              },
              {headers: this.headers}
          )
          .then((response) => {
            console.log(response.data);
            this.makeToast("success", "create completed successfully");
            this.getNotification();
            this.hideCreateModal();
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
            }

            this.loadingRequest = false;
          });
    },

    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
    showDetModal(notification) {
      this.current_row = notification;
      this.getNotificationData(this.current_row.id);
      console.log(this.current_row);
      this.$refs["notifications-det-modal"].show();
    },
    showCreateModal() {
      this.new_notifications = this.notification;
      console.log(this.new_notifications);
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_notifications.subject = "";
      this.new_notifications.body = "";
      this.new_notifications.role = "";
      this.$refs["create-modal"].hide();
    },
  },
};
</script>
<style></style>
