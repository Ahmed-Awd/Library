import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import { createI18n } from "vue-i18n";
import en from "./lang/en.json";
import sv from "./lang/sv.json";
import axios from "axios";
import Toaster from "@meforma/vue-toaster";
import useVuelidate from "@vuelidate/core";
import $ from "jquery";
import VueGoogleMaps from "@fawmi/vue-google-maps";
import GAuth from "vue3-google-oauth2";

axios.defaults.baseURL = "https://testsmart.eastus.cloudapp.azure.com/api/";
axios.defaults.withCredentials = false;
axios.defaults.headers.common["Access-Control-Allow-Origin"] = "*";
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.common["Authorization"] =
  "Bearer " + (localStorage.getItem("customerToken") || "");
axios.defaults.headers.common["Accept-Language"] =
  localStorage.getItem("appLang") || "sv";

// initialization of translation
const messages = {
  en: en,
  sv: sv,
};

const DEFAULT_LANG = localStorage.getItem("appLang") || "sv";

const i18n = createI18n({
  // something vue-i18n options here ...
  locale: DEFAULT_LANG,
  messages,
});

const gAuthOptions = {
  clientId:
    "764952688072-55ck2hadgjshe5ol29mqi31qo41legma.apps.googleusercontent.com",
  scope: "heba.wessam@gmail.com",
  prompt: "consent",
  fetch_basic_profile: false,
};

const image_location = "https://testsmart.eastus.cloudapp.azure.com/";
const app = createApp(App)
  // something vue options here ...
  .use(VueGoogleMaps, {
    load: {
      key: "AIzaSyB_-R-N4JMQQfUveMj6YAZPrHgbldFkTSg",
      libraries: "places",
    },
  })
  .use(GAuth, gAuthOptions)
  .use(store)
  .use(router)
  .use(i18n)
  .use(Toaster)
  .use(useVuelidate)
  .provide("image_location", image_location)
  .mount("#app");
