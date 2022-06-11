<template>
  <div class="main-content">
    <breadcumb :page="$t('general_settings')" :folder="$t('forms')" />
    <b-form>
      <b-row>
        <b-col md="12">
          <b-card class="mb-30">
            <div
              class="form-group row"
              v-for="(item, index) in setting"
              :key="item.id"
            >
              <label
                v-if="
                  item.key != 'free_delivery_for_all' &&
                  item.key != 'driver_max_time_for_receive'
                "
                class="col-sm-12 col-form-label"
                >{{ item.name }}</label
              >
              <!-- checkbox -->

              <label
                class="col-sm-12 checkbox checkbox-success"
                v-if="item.key === 'free_delivery_for_all'"
              >
                <input type="checkbox" v-model="is_checked" />
                <span>{{ item.name }}</span>
                <span class="checkmark"></span>
              </label>

              <label
                class="col-sm-12 checkbox checkbox-success"
                v-else-if="item.key === 'login_by_social_media'"
              >
                <input type="checkbox" v-model="login_checked" />
                <span>{{ item.name }}</span>
                <span class="checkmark"></span>
              </label>

              <!-- file (logo) -->
              <div class="col-sm-12" v-else-if="item.key === 'app_logo'">
                <b-form-group class="text-6">
                  <b-form-file
                    class="form-control"
                    accept="image/jpeg, image/png"
                    :value="item.value"
                    @change="imgUpload($event)"
                  ></b-form-file>
                </b-form-group>
              </div>
              <div v-else-if="item.key === 'driver_max_time_for_receive'">
                <!--                  do nothing-->
              </div>
              <!-- textarea -->
              <div
                class="col-sm-12"
                v-else-if="item.key === 'short_description'"
              >
                <textarea
                  class="form-control"
                  rows="2"
                  id="shortName"
                  :value="item.value"
                  @input="bindInput($event, index)"
                ></textarea>
              </div>

              <!-- input text -->

              <div class="col-sm-12" v-else>
                <input
                  type="text"
                  class="form-control"
                  id="appName"
                  :value="item.value"
                  @input="bindInput($event, index)"
                />
              </div>
            </div>
          </b-card>
        </b-col>

        <b-col md="12">
          <b-card class="mb-30">
            <div class="form-group row">
              <div class="col-sm-12">
                <button
                  type="button"
                  class="btn btn-primary mt-4 btn-block"
                  @click="updateData()"
                  :disabled="counter >= 1"
                >
                  {{ $t("save") }}
                </button>
              </div>
            </div>
          </b-card>
        </b-col>
      </b-row>
    </b-form>
  </div>
</template>
<script>
import vue from "vue";
import { mapGetters, mapActions } from "vuex";
import axios from "axios";
export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "General Settings",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  data() {
    return {
      setting: [],
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
        
        Accept: "application/json",
      },
      imgUploaded: [],
      is_checked: false,
      counter: 0,
      login_checked: false,
    };
  },
  mounted() {
    this.listData();
  },
  methods: {
    bindInput(e, index) {
      this.setting[index].value = e.target.value;
    },
    imgUpload(e) {
      let form = new FormData();
      form.append("image", e.target.files[0]);
      axios
        .post("upload", form, {
          headers: this.headers,
        })
        .then((res) => {
          console.log(res);
          this.imgUploaded = res.data.image;
          console.log(this.imgUploaded);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    updateData() {
      axios
        .post(
          "general-settings",
          {
            settings: {
              app_name: this.setting[0].value,
              app_logo: this.imgUploaded,
              short_description: this.setting[2].value,
              contact_phone: this.setting[3].value,
              contact_email: this.setting[4].value,
              facebook: this.setting[5].value,
              twitter: this.setting[6].value,
              instagram: this.setting[7].value,
              no_action_taken_for_order: this.setting[8].value,
              cancel_the_no_action_order: this.setting[9].value,
              free_delivery_for_all: this.is_checked,
              technical_support_number: this.setting[12].value,
              time_for_send_scheduling_order_notfication:
                this.setting[13].value,
              max_radius_of_restaurant: this.setting[14].value,
              max_radius_of_driver_in_order: this.setting[15].value,
              time_for_send_no_driver_notification: this.setting[16].value,
              login_by_social_media: this.login_checked,
              customer_app_android_version: this.setting[18].value,
              customer_app_IOS_version: this.setting[19].value,
              driver_app_android_version: this.setting[20].value,
              driver_app_IOS_version: this.setting[21].value,
              restaurant_app_android_version: this.setting[22].value,
              restaurant_app_IOS_version: this.setting[23].value,
            },
          },

          {
            headers: this.headers,
          }
        )
        .then((res) => {
          this.counter++;
          this.makeToast("success", res.data.message);
          this.listData();
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
    listData() {
      axios
        .get("general-settings", {
          headers: this.headers,
        })
        .then((response) => {
          this.setting = response.data.settings;
          this.imgUploaded = response.data.settings.filter(function (elem) {
            if (elem.key === "app_logo") return elem.value;
          });
          this.is_checked = response.data.settings.filter(function (elem) {
            if (elem.key === "free_delivery_for_all") return elem.value;
          });

          this.login_checked = response.data.settings.filter(function (elem) {
            if (elem.key === "login_by_social_media") return elem.value;
          });

          this.imgUploaded = this.imgUploaded[0].value;
          this.is_checked =
            this.is_checked[0].value === "true" ||
            this.is_checked[0].value === "1";

          this.login_checked =
            this.login_checked[0].value === "true" ||
            this.login_checked[0].value === "1";
          // this.appName = response.data.settings[0].value;
          console.log(this.is_checked);
        })
        .catch((errors) => {
          console.log(errors);
        });
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
label.checkbox {
  margin-left: 1.1rem;
}
</style>
