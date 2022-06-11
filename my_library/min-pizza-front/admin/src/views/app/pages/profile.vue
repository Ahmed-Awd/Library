<template>
  <div class="main-content">
    <breadcumb :page="'User Profile'" :folder="'Pages'"/>
    <div class="card user-profile o-hidden mb-30">
      <div class="header-cover"
           style="background-image: url(http://gull-html-laravel.ui-lib.com/assets/images/photo-wide-5.jpeg)"/>
      <div class="user-info">
        <img class="profile-picture avatar-lg mb-2" :src="user.pic"
             @error="$event.target.src='https://img.icons8.com/material/100/000000/user-male-circle--v1.png'" alt=""/>
        <p class="m-0 text-24">{{ this.user.name }}</p>
      </div>
      <div class="card-body">
        <div>
          <b-tabs content-class="mt-3" align="center">
            <b-tab title="About" active>
              <h4>Personal Information</h4>
              <hr>
              <div class="text-right">
                <b-button class="btn btn-primary mt-4" variant="primary" @click="showModal">Edit</b-button>
              </div>
              <div class="row">
                <div class="col-md-6 col-6">
                  <div class="mb-30">
                    <p class="text-primary mb-1"><i class="i-Email text-16 mr-1"/>Email</p>
                    <span>{{ user.email }}</span>
                  </div>
                  <div class="mb-30">
                    <p class="text-primary mb-1"><i class="i-Telephone text-16 mr-1"/>Telephone</p>
                    <span>{{ user.country_code }} - {{ user.phone }}</span>
                  </div>
                </div>
                <div class="col-md-6 col-6" v-if="user.city">
                  <div class="mb-30">
                    <p class="text-primary mb-1"><i class="i-Globe text-16 mr-1"/>Country</p>
                    <span>{{ this.user.city.country.name }}</span>
                  </div>
                  <div class="mb-30">
                    <p class="text-primary mb-1"><i class="i-Map2 text-16 mr-1"/>City</p>
                    <span>{{ this.user.city.name }}</span>
                  </div>
                </div>
                <div>
                  <b-modal ref="edit-modal" hide-footer title="Edit User">
                    <div class="d-block" v-if="edit_user">
                      <b-form-group label="Name" class="text-6">
                        <b-form-input class="form-control" type="text" v-model="edit_user.name" required/>
                      </b-form-group>
                      <div class="row">
                        <b-form-group label="country code" class="text-6 col-md-3">
                          <b-form-select :options="codes" class="form-control" type="text"
                                         v-model="edit_user.country_code" required/>
                        </b-form-group>
                        <b-form-group label="Phone Number" class="text-6 col-md-9">
                          <b-form-input class="form-control" type="text" v-model="edit_user.phone" required/>
                        </b-form-group>
                      </div>

                      <div class="row" v-if="edit_user.city">
                        <b-form-group label="Country" class="text-6 col-md-6">
                          <b-form-select class="form-control" v-model="edit_user.city.country.id" :options="countries"
                                         @change="getCities" text-field="name" value-field="id"/>
                        </b-form-group>
                        <b-form-group label="City" class="text-6 col-md-6">
                          <b-form-select class="form-control" v-model="edit_user.city.id" :options="cities"
                                         text-field="name" value-field="id"/>
                        </b-form-group>
                      </div>
                      <b-form-group label="Default Language" class="text-6">
                        <b-form-select class="form-control" :options="lang" v-model="edit_user.default_lang"
                                       text-field="name" value-field="id"/>
                      </b-form-group>
                      <b-button type="submit" tag="button" @click.prevent="editInfo" class="btn-rounded btn-block mt-4"
                                variant="primary mt-2" :disabled="loading">Update
                      </b-button>
                      <b-button class="btn-rounded btn-block mt-4" variant="outline-danger" block @click="hideModal">
                        Cancel
                      </b-button>
                      <div v-once class="typo__p" v-if="loading">
                        <div class="spinner sm spinner-primary mt-3"/>
                      </div>
                    </div>
                  </b-modal>
                </div>
              </div>
            </b-tab>
          </b-tabs>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import {mapGetters} from "vuex";

export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Profile"
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  data() {
    return {
      lang: [
        "en",
        "ar",
        "sv",
      ],
      codes: [
        "20",
        "966",
        "46",
        "90"
      ],
      countries: [],
      cities: [],
      user: {},
      edit_user: {
        name: "",
        country: "",
        city: "",
        phone: "",
      },
      headers: {
        "Authorization": "Bearer " + localStorage.getItem("token"),
        "Accept-Language": "en",
        "Accept": "application/json"
      },
    }
  },
  mounted() {
    this.show();
    this.getCountries();
  },
  methods: {
    show() {
      axios.get('my-info', {headers: this.headers,})
          .then((response) => {
            this.user = response.data.user;
            if (response.data.user.profile_photo_path != null) {
              this.user.pic = response.data.user.profile_photo_path;
            } else {this.user.pic = "";}
          })
          .catch((errors) => {
            console.log(errors.data);
          });
    },
    showModal() {
      this.edit_user = this.user;
      this.getCities(this.user.city.country.id);
      this.$refs["edit-modal"].show();
    },
    hideModal() {
      this.$refs["edit-modal"].hide();
    },
    editInfo: function () {
      axios.post('my-info', {
        name: this.edit_user.name,
        country_code: this.edit_user.country_code,
        city_id: this.edit_user.city.id,
        phone: this.edit_user.phone,
        default_lang: this.edit_user.default_lang,
      }, {headers: this.headers,})
          .then((response) => {
            console.log(response.data);
            this.makeToast("success", "update completed successfully");
            this.show();
            this.hideModal();
          })
          .catch((errors) => {
            console.log(errors.data);
            this.makeToast("warning", "update failed");
          });
    },
    makeToast(variant = null, msg) {
      this.$bvToast.toast(msg, {
        title: ` ${variant || "default"}`,
        variant: variant,
        solid: true,
      });
    },
    getCountries() {
      axios.get("countries", {headers: this.headers})
          .then((response) => {
            this.countries = response.data.countries;
            console.log(this.countries);
          })
          .catch((errors) => {
            console.log(errors);
          });
    },
    getCities(city) {
      axios.get(`cities/${city}`, {headers: this.headers})
          .then((response) => {
            this.cities = response.data.cities;
          })
          .catch((errors) => {
            console.log(errors);
          });
    },
  },
}
</script>
