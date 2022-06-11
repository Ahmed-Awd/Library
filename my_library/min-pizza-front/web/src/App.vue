<template>
  <router-view />
  <Footer />
</template>

<script>
import axios from "axios";
import Footer from "@/components/Footer.vue"; // @ is an alias to /src


export default {
  name: "App",
  data() {
    return {
      orders: [],
    };
  },
   components: {
    Footer,
  },
  mounted() {
    this.getUserData();
    this.webURL();
  },

  methods: {
    // get user data to use it in all pages
    getUserData() {
      axios
        .get("my-info")
        .then((response) => {
          const userData = response.data.user;
          // set customer data in local storage to use it
          localStorage.setItem("CustomerData", userData);
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    webURL() {
      var url = "https://minpizza.smartlife.ws/storage/";
      localStorage.setItem("imgURL", url);
    },
  },
};
</script>

<style></style>
