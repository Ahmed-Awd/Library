<template>
  <vue-perfect-scrollbar
    class="sidebar-panel rtl-ps-none ps scroll"
    @mouseleave.native="
      sidebarCompact();
      returnSelectedParentMenu();
    "
    @mouseenter.native="removeSidebarCompact"
    :class="{
      'vertical-sidebar-compact': getVerticalCompact.isSidebarCompact,
      'sidebar-full-collapse': getVerticalSidebar.isMobileCompact,
    }"
    :settings="{ suppressScrollX: true, wheelPropagation: false }"
  >
    <div>
      <div
        class="
          gull-brand
          text-center
          d-flex
          align-items-center
          pl-2
          mb-2
          justify-content-between
        "
      >
        <div>
          <img class src="@/assets/images/logo.png" />
        </div>
        <div class="toggle-sidebar-compact">
          <label class="switch ul-switch switch-white ml-auto">
            <input @click="switchSidebar" type="checkbox" />
            <span class="ul-slider o-hidden"></span>
          </label>
        </div>
      </div>

      <div class="close-mobile-menu" @click="mobileSidebar">
        <i class="text-16 text-white i-Close-Window font-weight-bold"></i>
      </div>

      <div class="mt-4 main-menu">
        <ul class="ul-vertical-sidebar pl-4" id="menu">
          <p
            class="main-menu-title text-uppercase text-12 mt-4 mb-2"
            v-if="role === 'admin' || role==='super_admin' "
          >
            {{ $t("sidebar.menu") }}
          </p>
          <p
            class="main-menu-title text-uppercase text-12 mt-4 mb-2"
            style="color: white"
            v-else
          >
            {{ rest_name }}
          </p>

          <li class="Ul_li--hover" v-if="isSuperAdmin || isAdmin">
            <div v-b-toggle.collapse-1>
              <a
                class="has-arrow"
                href="#"
                :class="{ active: selectedParentMenu == 'dashboards' }"
              >
                <i class="i-Bar-Chart text-17 mr-3"></i>
                <router-link tag="a" class to="/app/dashboards/dashboard.v1">
                  <span class="text-14">{{ $t("dashboard") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('edit settings'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Gear-2 text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/generalSetting">
                  <span class="text-14">{{ $t("general_settings") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('control drivers'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Motorcycle text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/drivers">
                  <span class="text-14">{{ $t("drivers") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('control customers'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Add-User text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/customers">
                  <span class="text-14">{{ $t("sidebar.customers") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('control categories'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Library text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/categories">
                  <span class="text-14">{{ $t("sidebar.categories") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li class="Ul_li--hover" v-if="isSuperAdmin || isAdmin">
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Debian text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/resturantsList">
                  <span class="text-14">{{ $t("restaurant") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('control offers'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Debian text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/restaurantOffers">
                  <span class="text-14"
                    >{{ $t("restaurant") }} {{ $t("offers") }}</span
                  >
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('control offers'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Bag-Items text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/itemsOffers">
                  <span class="text-14"
                    >{{ $t("items") }} {{ $t("offers") }}</span
                  >
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('control taxes'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Dollar-Sign text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/taxs">
                  <span class="text-14">{{ $t("sidebar.taxes") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('edit questions'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Quotes text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/fqa">
                  <span class="text-14">{{ $t("sidebar.fqa") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('edit settings'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Library text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/aboutUs">
                  <span class="text-14">{{ $t("sidebar.about") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('control reasons'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Library text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/reasons">
                  <span class="text-14">{{ $t("sidebar.reasons") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('control sliders'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Library text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/sliders">
                  <span class="text-14">{{ $t("sidebar.sliders") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('control codes'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-ID-Card text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/discount-codes">
                  <span class="text-14">{{ $t("sidebar.discountCodes") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('view orders'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Library text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/ordersList">
                  <span class="text-14">{{ $t("sidebar.allOrders") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('view orders'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Library text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/canceledOrders">
                  <span class="text-14">{{ $t("canceledOrder") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('control feedbacks'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Telephone-2 text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/contact-us">
                  <span class="text-14">{{ $t("contact_us") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('send notification'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Bird-Delivering-Letter text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/notifications">
                  <span class="text-14"
                    >{{ $t("general") }} {{ $t("notifications") }}</span
                  >
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('send notification'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Bird-Delivering-Letter text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/specialNotifications">
                  <span class="text-14"
                    >{{ $t("special") }} {{ $t("notifications") }}</span
                  >
                </router-link>
              </a>
            </div>
          </li>

          <li
            class="Ul_li--hover"
            v-if="
              isSuperAdmin ||
              (isAdmin && adminPermissions.includes('edit settings'))
            "
          >
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Memory-Card text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/modules">
                  <span class="text-14">{{ $t("modules") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li class="Ul_li--hover" v-if="isSuperAdmin">
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Management text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/admin">
                  <span class="text-14">{{ $t("admin") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <!-- options that appear for an restaurant owner -->
          <li class="Ul_li--hover" v-if="isOwner">
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Library text-17 mr-3"></i>
                <router-link
                  tag="a"
                  :to="{
                    path: `/app/dashboards/restaurant/menu/categories/${rest_id}`,
                  }"
                >
                  <span class="text-14">{{ $t("sidebar.categories") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li class="Ul_li--hover" v-if="isOwner">
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Library text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/option">
                  <span class="text-14">{{ $t("option") }}</span>
                </router-link>
              </a>
            </div>
          </li>

          <li class="Ul_li--hover" v-if="isOwner">
            <div v-b-toggle.collapse-2>
              <a class="has-arrow" href="#">
                <i class="i-Library text-17 mr-3"></i>
                <router-link tag="a" to="/app/dashboards/optionTemplates">
                  <span class="text-14">{{ $t("optionTemp") }}</span>
                </router-link>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </vue-perfect-scrollbar>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import arrowIcon from "@/components/arrow/arrowIcon";
export default {
  components: {
    arrowIcon,
  },
  computed: {
    ...mapGetters(["getVerticalCompact", "getVerticalSidebar"]),
  },
  created() {

    var role_name = localStorage.getItem("role");
    this.role = role_name;
    this.adminPermissions = localStorage.getItem("adminPermissions");
    this.rest_id = localStorage.getItem("restaurantID");
    this.rest_name = localStorage.getItem("restaurantName") || "";

    console.log("Permissions:", this.adminPermissions);

    // check if super admin
    if (role_name == "super_admin") {
      this.isSuperAdmin = true;
    } else {
      this.isSuperAdmin = false;
    }

    // check if admin
    if (role_name == "admin") {
      this.isAdmin = true;
    } else {
      this.isAdmin = false;
    }
    // check if owner
    if (role_name == "owner") {
      this.isOwner = true;
    } else if (role_name == "owner") {
      this.isOwner = false;
    }
  },
  data() {
    return {
      selectedParentMenu: "",
      isSuperAdmin: false,
      isAdmin: false,
      isOwner: false,
      rest_name: "",
      role: "",
      rest_id: 0,
      adminPermissions: [],
    };
  },
  mounted() {
    this.toggleSelectedParentMenu();
    document.addEventListener("click", this.returnSelectedParentMenu);
  },
  beforeDestroy() {
    document.removeEventListener("click", this.returnSelectedParentMenu);
  },
  methods: {
    ...mapActions([
      "switchSidebar",
      "sidebarCompact",
      "removeSidebarCompact",
      "mobileSidebar",
    ]),

    toggleSelectedParentMenu() {
      const currentParentUrl = this.$route.path
        .split("/")
        .filter((x) => x !== "")[1];

      if (currentParentUrl !== undefined || currentParentUrl !== null) {
        this.selectedParentMenu = currentParentUrl.toLowerCase();
        // console.log(currentParentUrl);
      } else {
        this.selectedParentMenu = "dashboards";
      }
    },
    returnSelectedParentMenu() {
      this.toggleSelectedParentMenu();
    },
  },
};
</script>
<style></style>
