<template>
  <div>
    <!-- <keep-alive> -->
    <component :is="getThemeMode.layout"></component>
    <!-- <component :is="compact-sidebar"></component> -->
    <!-- </keep-alive> -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  components: {},
  data() {
    return {};
  },
  computed: {
    ...mapGetters(["getThemeMode"]),
  },
  beforeCreate() {
    var userID = localStorage.getItem("userID");
    this.$OneSignal.showSlidedownPrompt();
    this.$OneSignal.setExternalUserId(userID.toString());
    this.$OneSignal.on('notificationDisplay',function(event) {
      console.log('OneSignal notification displayed:', event);
    });
    console.log("userID", userID.toString());
  },

  methods: {},
};
</script>
