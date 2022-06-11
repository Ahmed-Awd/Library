<template>
    <Header />  
    
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                    <h1>{{ $t("contact") }}</h1>
                    <h4>Mini Pizza {{ $t("online") }}</h4>
                </div>
            </div>
        </div>
        <div class="login-content white-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-12 align-self-center">
                       <img src="images/app-login.png">
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-12 col-12 align-self-center">
                        <form class="login-form">
                            <div class="mb-3">
                                <label  class="form-label">{{ $t("form.name")}}</label>
                                <input type="text" class="form-control" v-model="state.name">
                                <span class="error" v-if="v$.name.$error">
                                  {{ v$.name.$errors[0].$message }}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">{{ $t("form.email")}}</label>
                                        <input type="email" class="form-control" v-model="state.email">
                                        <span class="error" v-if="v$.email.$error">
                                          {{ v$.email.$errors[0].$message }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="mb-3">
                                        <label  class="form-label">{{ $t("form.phone")}}</label>
                                        <input type="text" class="form-control" v-model="state.phone">
                                        <span class="error" v-if="v$.phone.$error">
                                          {{ v$.phone.$errors[0].$message }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ $t("form.message")}}</label>
                                <textarea class="form-control" placeholder="Message" rows="3" v-model="state.message"></textarea>
                                <span class="error" v-if="v$.message.$error">
                                  {{ v$.message.$errors[0].$message }}
                                </span>
                            </div>
                            <div class="text-center justify-content-center">
                                <button type="submit" class="btn btn-primary blue-btn" @click.prevent="sendFeadback()">{{ $t("send")}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</template>

<script>
import { defineComponent } from 'vue';
import Header from '@/components/Header.vue'; // @ is an alias to /src
import axios from "axios";
import useVuelidate from "@vuelidate/core";
import {
  required,
  minLength,
  maxLength,
  email,
  sameAs,
  numeric,
  helpers,
} from "@vuelidate/validators";
import { reactive, computed } from "vue";

export default defineComponent({
  components: {
      Header
  },

  setup() {
    const state = reactive({
      name: "",
      email: "",
      phone: "",
      message: "",
    });
    const phoneLen = (value) => value.length == 9;
    const rules = computed(() => {
      return {
        name: { 
          required: helpers.withMessage( "This input is required", required ),
          minLength: minLength(2) ,
        },
        email: { required, email },
        phone: {
          required,
          numeric,
          phoneLen: helpers.withMessage(
            "Phone number must be 9 numbers",
            phoneLen
          ),
        },
        message: {
          required,
          minLength: minLength(5) ,
        }
      };
    });

    const v$ = useVuelidate(rules, state);

    return {
      state,
      v$,
    };
  },

  methods: {
   
    // sign up fn
    sendFeadback() {
      const result = this.v$.$validate();
      const data = {
        name: this.state.name,
        email: this.state.email,
        phone: this.state.phone,
        message: this.state.message,
      };
      if (!this.v$.$error) {
        axios
          .post("feedback/send", data, { headers: this.headers })
          .then((response) => {
            this.$toast.success(response.data.message, {
              position: "top-right",
            });
            this.$router.push("/Login");
          })
          .catch((errors) => {
            const Err = errors.response.data.errors;
            for (const el in Err) {
              Err[el].map((item) => {
                this.$toast.error(item, {
                  position: "top-right",
                });
              });
            }
          });
      }
    },
  },

});
</script>
