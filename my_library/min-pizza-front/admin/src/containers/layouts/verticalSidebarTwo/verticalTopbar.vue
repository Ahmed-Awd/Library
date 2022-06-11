<template>
  <div class="mb-30 m-4">
    <header
      class="
        main-header
        vertical-header
        bg-white
        d-flex
        justify-content-between
        rounded-0
      "
    >
      <div class="menu-toggle vertical-toggle" @click="mobileSidebar">
        <div></div>
        <div></div>
        <div></div>
      </div>
      <!-- <div @click="compactSideBarToggle" class="menu-toggle">
        <div></div>
        <div></div>
        <div></div>
      </div>-->

      <!-- <div class="d-flex align-items-center">
        <div
          :class="{ show: isMegaMenuOpen }"
          class="dropdown mega-menu d-none d-md-block"
        >
          <a
            href="#"
            class="btn text-muted dropdown-toggle mr-3"
            id="dropdownMegaMenuButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            @click="toggleMegaMenu"
            >Mega Menu</a
          >
          <div
            class="dropdown-menu text-left"
            :class="{ show: isMegaMenuOpen }"
            aria-labelledby="dropdownMenuButton"
          >
            <div class="row m-0">
              <div class="col-md-4 p-4 text-left bg-img">
                <h2 class="title">Mega Menu<br />Sidebar</h2>
                <p>
                  Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                  Asperiores natus laboriosam fugit, consequatur.
                </p>
                <p class="mb-30">
                  Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                  Exercitationem odio amet eos dolore suscipit placeat.
                </p>
                <button class="btn btn-lg btn-rounded btn-outline-warning">
                  Learn More
                </button>
              </div>
              <div class="col-md-4 p-4 text-left">
                <p
                  class="
                    text-primary text--cap
                    border-bottom-primary
                    d-inline-block
                  "
                >
                  Features
                </p>
                <div class="menu-icon-grid w-auto p-0">
                  <a href="#"><i class="i-Shop-4" />Home</a>
                  <a href="#"><i class="i-Library" />UI Kits</a>
                  <a href="#"><i class="i-Drop" />Apps</a>
                  <a href="#"><i class="i-File-Clipboard-File--Text" />Forms</a>
                  <a href="#"><i class="i-Checked-User" />Sessions</a>
                  <a href="#"><i class="i-Ambulance" />Support</a>
                </div>
              </div>
              <div class="col-md-4 p-4 text-left">
                <p
                  class="
                    text-primary text--cap
                    border-bottom-primary
                    d-inline-block
                  "
                >
                  Components
                </p>
                <ul class="links">
                  <li><a href="accordion.html">Accordion</a></li>
                  <li><a href="alerts.html">Alerts</a></li>
                  <li><a href="buttons.html">Buttons</a></li>
                  <li><a href="badges.html">Badges</a></li>
                  <li><a href="carousel.html">Carousels</a></li>
                  <li><a href="lists.html">Lists</a></li>
                  <li><a href="popover.html">Popover</a></li>
                  <li><a href="tables.html">Tables</a></li>
                  <li><a href="datatables.html">Datatables</a></li>
                  <li><a href="modals.html">Modals</a></li>
                  <li><a href="nouislider.html">Sliders</a></li>
                  <li><a href="tabs.html">Tabs</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="search-bar" @click="toggleSearch">
          <input type="text" placeholder="Search" />
          <i class="search-icon text-muted i-Magnifi-Glass1" />
        </div>
      </div> -->

      <div style="margin: auto" />

      <div class="header-part-right">
        <div class="languages">
          <select
            class="select_lang"
            v-model="lang"
            @change="changeLang($event)"
          >
            <option value="en">
              English
              <!-- <country-flag country="us" size="small" /> -->
            </option>
            <option value="sv">
              Swedish
              <!-- <country-flag country="se" size="small" /> -->
            </option>
          </select>
        </div>
        <!-- Full screen toggle -->
        <i
          class="i-Full-Screen header-icon d-none d-sm-inline-block"
          @click="handleFullScreen"
        />
        <!-- User avatar dropdown -->
        <div class="dropdown">
          <b-dropdown
            id="dropdown-1"
            text="Dropdown Button"
            class="m-md-2 user col align-self-end"
            toggle-class="text-decoration-none"
            no-caret
            variant="link"
          >
            <template slot="button-content">
              <img
                :src="user.pic"
                @error="
                  $event.target.src =
                    'https://img.icons8.com/material/100/000000/user-male-circle--v1.png'
                "
                alt=""
                id="userDropdown"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              />
            </template>

            <div class="dropdown-menu-right" aria-labelledby="userDropdown">
              <div class="dropdown-header">
                <i class="i-Lock-User mr-1" /> {{ this.user.name }}
              </div>
              <router-link to="/app/pages/profile" class="dropdown-item"
                >Account settings</router-link
              >
              <a
                class="dropdown-item"
                v-if="logedAdmin == 1 && isOwner == 'owner'"
                @click="logoutOwner()"
                >{{ $t("backAdmin") }}</a
              >
              <a class="dropdown-item" href="#" @click.prevent="logoutUser"
                >Sign out</a
              >
            </div>
          </b-dropdown>
        </div>
      </div>
      <search-component
        :isSearchOpen.sync="isSearchOpen"
        @closeSearch="toggleSearch"
      />
    </header>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import Util from "@/utils";
import searchComponent from "../common/search";
import axios from "axios";
import CountryFlag from "vue-country-flag";

export default {
  components: {
    searchComponent,
    CountryFlag,
  },
  computed: {
    ...mapGetters([
      "getVerticalCompact",
      "getVerticalSidebar",
      "getSideBarToggleProperties",
    ]),
  },
  data() {
    const lang = localStorage.getItem("lang") || "en";

    return {
      logedAdmin: localStorage.getItem("logedAdmin"),
      isOwner: localStorage.getItem("role"),
      lang: lang,
      isMegaMenuOpen: false,
      isSearchOpen: false,
      user: {
        pic: "",
        name: "",
      },
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
        "Accept-Language": localStorage.getItem("lang"),
        Accept: "application/json",
      },
    };
  },
  mounted() {
    this.showUser();
  },
  methods: {
    changeLang(event) {
      console.log(event.target.value);
      localStorage.setItem("lang", event.target.value);
      window.location.reload();
      axios
        .post(
          `language/change`,
          {
            language: event.target.value,
          },
          { headers: this.headers }
        )
        .then((response) => {
          console.log(response.data);
        })
        .catch((errors) => {
          const Err = errors.response.data.errors;
          for (const el in Err) {
            Err[el].map((item) => {
              this.makeToast("warning", item);
            });
          }
        });
    },
    showUser() {
      axios
        .get("my-info", { headers: this.headers })
        .then((response) => {
          this.user.name = response.data.user.name;
          if (response.data.user.profile_photo_path != null) {
            this.user.pic = response.data.user.profile_photo_path;
          }
        })
        .catch((errors) => {
          console.log(errors.data);
        });
    },
    ...mapActions([
      "switchSidebar",
      "sidebarCompact",
      "removeSidebarCompact",
      "mobileSidebar",
    ]),
    logoutOwner() {
      localStorage.setItem("role", "super_admin");
      let token = localStorage.getItem("oldToken");
      localStorage.setItem("token", token);
      localStorage.removeItem("restaurantName");
      localStorage.removeItem("oldToken");
      window.location.href = "/app/dashboards/resturantsList";
    },
    logoutUser() {
      console.log(localStorage.getItem("token"));
      if (localStorage.getItem("token") === null) {
        this.$router.push("/app/sessions/signIn");
      } else {
        axios
          .post("logout", { headers: this.headers })
          .then((response) => {
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            localStorage.removeItem("restaurantName");
            this.$router.push("/app/sessions/signIn");
          })
          .catch((errors) => {
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            localStorage.removeItem("restaurantName");
            this.$router.push("/app/sessions/signIn");
          });
      }
    },
    handleFullScreen() {
      Util.toggleFullScreen();
    },
    compactSideBarToggle() {
      console.log("hello");
    },
    closeMegaMenu() {
      this.isMegaMenuOpen = false;
      // console.log(this.isMouseOnMegaMenu);
      // if (!this.isMouseOnMegaMenu) {
      //   this.isMegaMenuOpen = !this.isMegaMenuOpen;
      // }
    },
    toggleMegaMenu() {
      this.isMegaMenuOpen = !this.isMegaMenuOpen;
    },
    toggleSearch() {
      this.isSearchOpen = !this.isSearchOpen;
    },
  },
};
</script>
<style scoped>
.select_lang {
  border: none;
}
</style>
