// import "babel-polyfill";
import Vue from "vue";
import vSelect from "vue-select";
import App from "./App.vue";
import router from "./router";
import CKEditor from '@ckeditor/ckeditor5-vue2';
// import VueRouter from "vue-router";
import GullKit from "./plugins/gull.kit";
// import "babel-polyfill";
// import es6Promise from "es6-promise";
// es6Promise.polyfill();
import store from "./store";
import Breadcumb from "./components/breadcumb";
import * as firebase from "firebase/app";

import "firebase/auth";
import { firebaseSettings } from "@/data/config";
import i18n from "./lang/lang";
import CountryFlag from 'vue-country-flag';
import DateRangePicker from "vue2-daterange-picker";
//you need to import the CSS manually (in case you want to override it)
import "vue2-daterange-picker/dist/vue2-daterange-picker.css";
//import material-icon scss
import "font-awesome/css/font-awesome.min.css";
import axios from "axios";
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)
Vue.component("v-select", vSelect);

import VueGeolocation from "vue-browser-geolocation";
Vue.use(VueGeolocation);

import * as VueGoogleMaps from "vue2-google-maps";

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyB_-R-N4JMQQfUveMj6YAZPrHgbldFkTSg',
    libraries: ["places"], // This is required if you use the Autocomplete plugin
  },
})

// import {GoogleMap, Marker} from 'vue3-google-map';


// oneSignal notification
import OneSignalVue from 'onesignal-vue'

Vue.use(OneSignalVue);


import JsonExcel from "vue-json-excel";
Vue.component("downloadExcel", JsonExcel);

// import VueHtml2pdf from 'vue-html2pdf'
// Vue.use(VueHtml2pdf);




// import 'vue2-timepicker/dist/VueTimepicker.css';
import '@/assets/styles/css/myStyle.css';
axios.defaults.baseURL = "https://minpizza.smartlife.ws/api/";
//axios.defaults.baseURL = "http://localhost:8000/api/";

axios.defaults.withCredentials = false;
axios.defaults.headers.common["Access-Control-Allow-Origin"] = "*";
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.common["Accept-Language"] = localStorage.getItem("lang");


//defined as global component
Vue.component(
    "VueFontawesome",
    require("vue-fontawesome-icon/VueFontawesome.vue").default
);

Vue.component('country-flag', CountryFlag)


Vue.component("breadcumb", Breadcumb);
import InstantSearch from "vue-instantsearch";
// Vue.use(VueRouter);

Vue.use(InstantSearch);
Vue.use(GullKit);
Vue.use(CKEditor);
var truncate = function(text, length, clamp){
    clamp = clamp || '...';
    var node = document.createElement('div');
    node.innerHTML = text;
    var content = node.textContent;
    return content.length > length ? content.slice(0, length) + clamp : content;
};
Vue.filter('truncate', truncate);

firebase.initializeApp(firebaseSettings);

Vue.config.productionTip = false;

// use beforeEach route guard to set the language
// router.beforeEach((to, from, next) => {

//     // use the language from the routing param or default language
//     let language = to.params.lang;
//     if (!language) {
//       language = 'en'
//     }

//     // set the current language for i18n.
//     i18n.locale = language
//     // console.log(language);
//     next()
//   })

new Vue({
    store,
    router,
    i18n,
    render: (h) => h(App),
    provide: {
        file_url: "https://minpizza.smartlife.ws/storage/",
    },
    beforeMount() {
      this.$OneSignal.init({ appId: '8c2ce721-0dcc-46ae-960e-dea288dd3459' }).then(() => {
        console.log('one signal is connected')
      });

    },
    created(){
      // this.changeLang();
    },
    methods: {
      // changeLang() {
      //   let lang =localStorage.getItem("lang");
      //   console.log(lang);
      //   axios
      //     .post(
      //       `language/change`,
      //       {
      //         language:lang,
      //       },
      //       { headers: this.headers }
      //     )
      //     .then((response) => {
      //       console.log(response.data);
      //     })
      //     .catch((errors) => {
      //       const Err = errors.response.data.errors;
      //       for (const el in Err) {
      //         Err[el].map((item) => {
      //           this.makeToast("warning", item);
      //         });
      //       }
      //     });
      // },
    }
}).$mount("#app");