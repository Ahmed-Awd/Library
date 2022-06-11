<template>
  <div class="main-content">
    <breadcumb :page="'Sliders'" :folder="'Datatables'" />
    <!-- <div class="wrapper"> -->
    <vue-good-table
      :columns="columns"
      :line-numbers="false"
      :search-options="{
        enabled: true,
        placeholder: $t('searchTable'),
      }"
      :pagination-options="{
        enabled: true,
        perPage: 15,
        mode: 'records',
        perPageDropdownEnabled: false,
      }"
      styleClass="tableOne vgt-table"
      :selectOptions="{
        enabled: false,
        selectionInfoClass: 'table-alert__box',
      }"
      :rows="rows"
    >
      <div slot="table-actions" class="mb-3">
        <b-button
          variant="primary"
          class="btn-rounded"
          :disabled="loadingRequest"
          @click="showCreateModal"
        >
          {{ this.$t("addNew") }}
        </b-button>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'button'">
          <button
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            @click.prevent="showEditModal(props.row)"
          >
            <i class="i-Eraser-2 text-25 text-success mr-2"></i>
            {{ props.row.button }}
          </button>
          <button
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            @click.prevent="showActivateModal(props.row)"
          >
            <i class="i-Stopwatch text-25 text-warning mr-2"></i>
            {{ props.row.button }}
          </button>
          <button
            class="loading-btn btn btn-link"
            :disabled="loadingRequest"
            @click.prevent="showDeleteModal(props.row)"
          >
            <i class="i-Close-Window text-25 text-danger"></i>
            {{ props.row.button }}
          </button>
        </span>
      </template>
    </vue-good-table>
    <div>
      <b-modal
        ref="create-modal"
        hide-footer
        :title="this.$t('create') + ' ' + this.$t('slider')"
      >
        <div class="d-block">
          <b-form-group :label="this.$t('title')" class="text-6">
            <b-form-input
              class="form-control"
              type="text"
              @input="$v.new_slider.name.$touch"
              v-model="new_slider.name"
              required
            ></b-form-input>
          </b-form-group>
          <p class="errors" v-if="$v.new_slider.name.$dirty">
            <span class="form__alert" v-if="!$v.new_slider.name.required">{{
              $t("validation.required")
            }}</span>
            <span class="form__alert" v-if="!$v.new_slider.name.minLength">{{
              $t("validation.nameMinLen3")
            }}</span>
            <span class="form__alert" v-if="!$v.new_slider.name.maxLength">{{
              $t("validation.nameMaxLen")
            }}</span>
          </p>

          <b-form-group :label="this.$t('content')" class="text-6">
            <b-form-textarea
              rows="4"
              class="form-control"
              @input="$v.new_slider.content.$touch"
              v-model="new_slider.content"
              required
            ></b-form-textarea>
          </b-form-group>
          <p class="errors" v-if="$v.new_slider.content.$dirty">
            <span class="form__alert" v-if="!$v.new_slider.content.required">{{
              $t("validation.required")
            }}</span>
            <span class="form__alert" v-if="!$v.new_slider.content.minLength">{{
              $t("validation.nameMinLen3")
            }}</span>
            <span class="form__alert" v-if="!$v.new_slider.content.maxLength">{{
              $t("validation.textMaxLen")
            }}</span>
          </p>

          <b-form-group
            :label="this.$t('slider') + ' ' + this.$t('image')"
            class="text-6"
          >
            <b-form-file
              class="form-control"
              accept="image/jpeg, image/png"
              v-model="new_slider.slider_photo_path"
              required
              @change="imageFileChanged($event)"
            ></b-form-file>
            <p class="errors">
              <span v-if="imageFile > 3140200" class="form__alert">
                {{ this.$t("validation.imgSize") }}
              </span>
            </p>
          </b-form-group>

          <b-button
            type="submit"
            tag="button"
            @click.prevent="createSlider"
            class="btn-rounded btn-block mt-4"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.new_slider.$invalid"
          >
            {{ this.$t("create") }}
          </b-button>
          <b-button
            class="btn-rounded btn-block mt-4"
            block
            @click="hideCreateModal"
            >{{ this.$t("cancel") }}</b-button
          >
          <div v-once class="typo__p" v-if="loading">
            <div class="spinner sm spinner-primary mt-3"></div>
          </div>
        </div>
      </b-modal>
    </div>

    <div>
      <b-modal
        ref="edit-modal"
        hide-footer
        :title="this.$t('edit') + ' ' + this.$t('slider')"
      >
        <div class="d-block">
          <b-form-group :label="this.$t('title')" class="text-6">
            <b-form-input
              class="form-control"
              type="text"
              @input="$v.current_slider.title.$touch"
              v-model="current_slider.title"
              required
            ></b-form-input>
          </b-form-group>
          <p class="errors" v-if="$v.current_slider.title.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_slider.title.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_slider.title.minLength"
              >{{ $t("validation.nameMinLen3") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_slider.title.maxLength"
              >{{ $t("validation.nameMaxLen") }}</span
            >
          </p>

          <b-form-group :label="this.$t('content')" class="text-6">
            <b-form-textarea
              rows="4"
              class="form-control"
              @input="$v.current_slider.content.$touch"
              v-model="current_slider.content"
              required
            ></b-form-textarea>
          </b-form-group>
          <p class="errors" v-if="$v.current_slider.content.$dirty">
            <span
              class="form__alert"
              v-if="!$v.current_slider.content.required"
              >{{ $t("validation.required") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_slider.content.minLength"
              >{{ $t("validation.nameMinLen3") }}</span
            >
            <span
              class="form__alert"
              v-if="!$v.current_slider.content.maxLength"
              >{{ $t("validation.textMaxLen") }}</span
            >
          </p>

          <b-form-group
            :label="this.$t('slider') + ' ' + this.$t('image')"
            class="text-6"
          >
            <b-form-file
              class="form-control"
              accept="image/jpeg, image/png"
              v-model="current_slider.slider_photo_path"
              @change="imageFileChanged($event)"
              required
            ></b-form-file>
            <p class="errors">
              <span v-if="imageFile > 3140200" class="form__alert">
                {{ this.$t("validation.imgSize") }}
              </span>
            </p>
          </b-form-group>

          <b-button
            type="submit"
            tag="button"
            @click.prevent="updateSlider"
            class="btn-rounded btn-block mt-4"
            variant="primary mt-2"
            :disabled="loadingRequest || $v.current_slider.$invalid"
          >
            {{ this.$t("update") }}
          </b-button>
          <b-button
            class="btn-rounded btn-block mt-4"
            block
            @click="hideEditModal"
          >
            {{ this.$t("cancel") }}</b-button
          >
          <div v-once class="typo__p" v-if="loading">
            <div class="spinner sm spinner-primary mt-3"></div>
          </div>
        </div>
      </b-modal>
    </div>

    <div>
      <b-modal ref="my-modal" hide-footer :title="this.$t('deleteConfirm')">
        <div class="d-block text-center">
          <h3>
            {{ this.$t("deleteCat") }}
            <strong>{{ current_slider.title }}</strong>
            {{ this.$t("slider") }} ?
          </h3>
        </div>
        <b-button class="mt-3" block @click="hideDeleteModal">{{
          this.$t("cancel")
        }}</b-button>
        <b-button
          class="mt-3"
          variant="outline-danger"
          block
          @click="deleteSlider"
          >{{ this.$t("delete") }}
        </b-button>
      </b-modal>
    </div>

    <div>
      <b-modal ref="active-modal" hide-footer :title="this.$t('visibility')">
        <div>
          <div class="d-block text-center">
            <h3 v-if="current_slider.is_active == 0">
              {{ this.$t("activateSlider") }}
            </h3>
            <h3 v-else>{{ this.$t("disableSlider") }}</h3>
          </div>
          <b-button class="mt-3" block @click="hideActivateModal">{{
            this.$t("cancel")
          }}</b-button>
          <b-button
            v-if="current_slider.is_active == 0"
            class="mt-3"
            variant="outline-danger"
            block
            @click="changeStatus"
          >
            {{ this.$t("enable") }}
          </b-button>
          <b-button
            v-else
            class="mt-3 btn-primary"
            variant="confirm"
            block
            @click="changeStatus"
          >
            {{ this.$t("disable") }}
          </b-button>
        </div>
      </b-modal>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Sliders",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  inject: ["file_url"],
  data() {
    return {
      slider: {
        name: "",
      },
      columns: [
        {
          label: this.$t("id"),
          field: "id",
        },
        {
          label: this.$t("title"),
          field: "title",
        },
        {
          label: this.$t("content"),
          field: "short_content",
        },
        {
          label: this.$t("image"),
          field: "photo",
          html: true,
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
      image: "",
      current_slider: {
        user: {
          is_active: 0,
        },
      },
      new_slider: {},
      imageFile: "",

      loadingRequest: false,
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),

        Accept: "application/json",
      },
    };
  },
  validations: {
    new_slider: {
      name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(50),
      },
      content: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(300),
      },
    },

    current_slider: {
      title: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(50),
      },
      content: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(300),
      },
    },
  },
  mounted() {
    this.show();
  },
  methods: {
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.show();
    },
    imageFileChanged(event) {
      var files = event.target.files || event.dataTransfer.files;

      if (!files.length) {
        return;
      }
      this.imageFile = files[0].size;
      console.log(this.imageFile);
    },
    show() {
      axios
        .get("admin-sliders", {
          headers: this.headers,
          params: this.serverParams,
        })
        .then((response) => {
          this.rows = response.data.sliders.map((item) => ({
            ...item,
            photo: `<image width="80" height="80" src="${this.file_url}${item.image}"/>`,
            short_content: item.content.substring(0, 90) + "....",
          }));
          //   this.rows = response.data.sliders;
          console.log(this.rows);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    showDeleteModal(slider) {
      this.current_slider = slider;
      this.$refs["my-modal"].show();
    },
    hideDeleteModal() {
      this.current_slider = {};
      this.$refs["my-modal"].hide();
    },
    showActivateModal(slider) {
      this.current_slider = slider;
      this.$refs["active-modal"].show();
    },
    hideActivateModal() {
      this.current_slider = {};
      this.$refs["active-modal"].hide();
    },
    showEditModal(slider) {
      this.current_slider = slider;
      this.current_slider.slider_photo_path = null;
      this.$refs["edit-modal"].show();
    },
    hideEditModal() {
      this.current_slider = {};
      this.imageFile = ""
      this.$refs["edit-modal"].hide();
      this.show();
    },
    showCreateModal() {
      this.new_slider = {};
      this.$refs["create-modal"].show();
    },
    hideCreateModal() {
      this.new_slider = {
        slider_photo_path:null
      };
      this.imageFile = "";
      this.$refs["create-modal"].hide();
      this.show();
    },

    updateSlider() {
      this.loadingRequest = true; // set this before running anything
      this.$v.$touch(); //it will validate all fields
      console.log(this.current_slider);
      let efd;
      efd = new FormData();
      efd.append("title", this.current_slider.title);
      efd.append("content", this.current_slider.content);
      if (this.current_slider.slider_photo_path != null) {
        efd.append("image", this.current_slider.slider_photo_path);
      }

      efd.append("_method", "patch");
      axios
        .post(`sliders/${this.current_slider.id}`, efd, {
          headers: this.headers,
        })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "update completed successfully");
          this.show();
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
      this.hideEditModal();
    },
    async deleteSlider() {
      this.loadingRequest = true; // set this before running anything

      await axios
        .delete(`sliders/${this.current_slider.id}`, { headers: this.headers })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "delete completed successfully");
          this.hideDeleteModal();
          this.show();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          console.log(errors.data);
          this.makeToast("warning", "delete failed");
          this.hideDeleteModal();
          this.loadingRequest = false;
        });
    },
    changeStatus() {
      this.loadingRequest = true; // set this before running anything

      axios
        .post(
          `sliders/switch/${this.current_slider.id}`,
          {},
          { headers: this.headers }
        )
        .then((response) => {
          this.makeToast("success", "status switched");
          this.hideActivateModal();
          this.show();
          this.loadingRequest = false;
        })
        .catch((errors) => {
          this.makeToast("warning", errors.response.data);
          this.loadingRequest = false;
        });
    },
    createSlider() {
      this.$v.$touch(); //it will validate all fields

      this.loadingRequest = true; // set this before running anything

      let fd;
      fd = new FormData();
      fd.append("title", this.new_slider.name);
      fd.append("content", this.new_slider.content);
      if (this.new_slider.slider_photo_path != null) {
        fd.append("image", this.new_slider.slider_photo_path);
      }

      //   console.log(fd);
      axios
        .post("/sliders", fd, { headers: this.headers })
        .then((response) => {
          console.log(response.data);
          this.makeToast("success", "create completed successfully");
          this.hideCreateModal();
          this.show();
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
  },
};
</script>
<style>
.loading-btn {
  text-decoration: none !important;
  margin: 1px !important;
}
</style>
