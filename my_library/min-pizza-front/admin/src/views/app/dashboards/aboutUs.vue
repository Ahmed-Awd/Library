<template>
  <div>
    <h2 class="text-center"><strong>{{ $t("aboutUs" )}}</strong></h2>
    <hr>
    <div v-html="about.english_value"></div>
    <div class="text-right">
      <b-button tag="button" class="btn btn-primary mt-4 mr-3" variant="primary mt-2" @click="showAboutModal">{{ $t("edit") }}
      </b-button>
    </div>
    <hr>
    <h2 class="text-center"><strong>{{ $t("termsCond") }}</strong></h2>
    <hr>
    <div v-html="terms.english_value"></div>
    <div class="text-right">
      <b-button tag="button" class="btn btn-primary mt-4 mr-3" variant="primary mt-2" @click="showTermsModal">{{ $t("edit") }}
      </b-button>
    </div>
    <b-modal ref="about-modal" hide-footer :title="$t('editAboutus')">
      <div class="d-block text-center">
        <h4>English</h4>
        <ckeditor :editor="editor" v-model="about.english_value" :config="editorConfig"/>
        <h4>Swedish</h4>
        <ckeditor :editor="editor" v-model="about.swedish_value" :config="editorConfig"/>
      </div>
      <div class="text-right">
        <b-button type="submit" tag="button" @click.prevent="updateAboutUs" class="btn btn-primary mt-4 mr-3"
                  variant="primary mt-2" :disabled="loading">{{ $t("save") }}
        </b-button>
        <b-button class="btn btn-primary mt-4" variant="outline-danger" @click="hideAboutModal">{{ $t('cancel') }}</b-button>
      </div>
    </b-modal>
    <b-modal ref="terms-modal" hide-footer title="Edit Terms and Conditions">
      <div class="d-block text-center">
        <h4>English</h4>
        <ckeditor :editor="editor" v-model="terms.english_value" :config="editorConfig"></ckeditor>
        <h4>Swedish</h4>
        <ckeditor :editor="editor" v-model="terms.swedish_value" :config="editorConfig"></ckeditor>
      </div>
      <div class="text-right">
        <b-button type="submit" tag="button" @click.prevent="updateTerms" class="btn btn-primary mt-4 mr-3"
                  variant="primary mt-2" :disabled="loading">{{ $t('save') }}
        </b-button>
        <b-button class="btn btn-primary mt-4" variant="outline-danger" @click="hideTermsModal">{{ $t('cancel') }}</b-button>
      </div>
    </b-modal>
  </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import axios from 'axios';
import {mapGetters} from "vuex";


export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "aboutus",
  },
  computed: {
    ...mapGetters(["loggedInUser", "loading", "error"]),
  },
  data() {
    return {
      about: {
        english_value: '',
        swedish_value: '',
      },
      terms: {
        english_value: '',
        swedish_value: '',
      },
      editor: ClassicEditor,
      editorConfig: {
        language: {
          ui: 'en',
          content: 'en'
        },
        additionalLanguages: 'all',
        toolbar: {
          items: [
            'heading', '|',
            'bold', 'italic', 'link', '|',
            'bulletedList', 'numberedList', '|',
            'undo', 'redo'
          ],
        }
      },
      headers: {
        "Authorization": "Bearer " + localStorage.getItem("token"),
        
        "Accept": "application/json"
      },
    };
  },
  mounted() {
    this.showAboutUs();
    this.showTerms();
  },
  methods: {
    showAboutModal() {
      this.$refs['about-modal'].show()
    },
    hideAboutModal() {
      this.$refs['about-modal'].hide()
    },
    showTermsModal() {
      this.$refs["terms-modal"].show();
    },
    hideTermsModal() {
      this.$refs["terms-modal"].hide();
    },
    showAboutUs() {
      axios.get('about-us', {headers: this.headers,})
          .then((response) => {
            this.about.english_value = response.data.about_us.value.en;
            this.about.swedish_value = response.data.about_us.value.sv;
            console.log(response.data);

          }).catch((errors) => {
        console.log(errors.data);
      });
    },
    showTerms() {
      axios.get('terms-and-conditions', {headers: this.headers,})
          .then((response) => {
            this.terms.english_value = response.data.terms.value.en;
            this.terms.swedish_value = response.data.terms.value.sv;
            console.log(response.data);
          }).catch((errors) => {
        console.log(errors.data);
      });
    },
    updateAboutUs() {
      axios.post('about-us', {
        english_value: this.about.english_value,
        swedish_value: this.about.swedish_value,
      }, {
        headers: this.headers
      }).then((response) => {
        console.log(response.data);
        this.makeToast("success", "update completed successfully");
        this.hideAboutModal();
        this.showAboutUs();
      }).catch((errors) => {
        console.log(errors.data);
        this.makeToast("warning", "update failed");
      });
    },
    updateTerms() {
      axios.post('terms-and-conditions', {
        english_value: this.terms.english_value,
        swedish_value: this.terms.swedish_value,
      },{
        headers: this.headers
      }).then((response) => {
        console.log(response.data);
        this.makeToast("success", "update completed successfully");
        this.hideTermsModal();
        this.showTerms();
      }).catch((errors) => {
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
  },
}
</script>