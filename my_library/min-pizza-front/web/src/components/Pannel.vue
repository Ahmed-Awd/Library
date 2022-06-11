<template>
  <div class="nav flex-column nav-pills tab-panel">
    <router-link to="/Profile" active-class="active" class="nav-link">{{
      $t("profile")
    }}</router-link>
    <router-link to="/ChangePass" active-class="active" class="nav-link">{{
      $t("changePass")
    }}</router-link>
    <router-link to="/Location" active-class="active" class="nav-link">{{
      $t("addressess")
    }}</router-link>
    <router-link to="/Favourite" active-class="active" class="nav-link">{{
      $t("fav")
    }}</router-link>
    <router-link to="/Following" active-class="active" class="nav-link">{{
      $t("following")
    }}</router-link>
    <router-link to="/OrderHistory" active-class="active" class="nav-link">{{
      $t("orderHistory")
    }}</router-link>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "Pannel",
  props: {
    msg: String,
  },
});
</script>
