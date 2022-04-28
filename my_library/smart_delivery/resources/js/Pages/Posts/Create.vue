<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ post? __('Edit post') + ` "${post.id}"` : __('Create a new post') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
                <jet-form-section @submit.prevent="submit">
                    <template #form>
                        <dropdown class="col-span-3">
                            <template #trigger>
                                <div class="flex items-center text-gray-800 capitalize border px-4 py-2 rounded-md border-gray-500 cursor-pointer hover:bg-gray-200">
                                    <icon name="translate" class="w-5 h-5 mr-2"/>
                                    {{__("Language")}}: {{languages[selectedLanguage]}}
                                    <icon name="dropdown" class="w-4 h-4 ml-2"/>
                                </div>
                            </template>
                            <template #content>
                                <dropdown-link as="button" button-type="button" v-for="(language, key) in languages" @click="selectLanguage(key)" :key="key">
                                    {{language}}
                                </dropdown-link>
                            </template>
                        </dropdown>
                      <div class="col-span-6">
                            <jet-label for="title" :value="__('Title')"/>
                            <jet-input id="title" type="text" class="mt-1 block w-full" v-model="form.translations[selectedLanguage].title"/>
                            <jet-input-error :message="form.errors.ar_title" class="mt-2" />
                      </div>  
                     <div class="col-span-6">
                            <jet-label for="content" :value="__('Content')"/>
                            <ckeditor id="content" :editor="editor" v-model="form.translations[selectedLanguage].content" :config="editorConfig"></ckeditor>
                            <jet-input-error :message="form.errors.ar_content" class="mt-2" />
                      </div>  
                    </template>

                    <template #actions>
                        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                            {{__("Saved")}}
                        </jet-action-message>

                        <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{__("Save")}}
                        </jet-button>
                    </template>
                </jet-form-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout';
    import Paginator from "@/Components/Paginator";
    import Toggle from '@vueform/toggle';
    import JetButton from '@/Jetstream/Button';
    import JetFormSection from '@/Jetstream/FormSection';
    import JetInput from '@/Jetstream/Input';
    import JetInputError from '@/Jetstream/InputError';
    import JetLabel from '@/Jetstream/Label';
    import JetActionMessage from '@/Jetstream/ActionMessage';
    import JetSecondaryButton from '@/Jetstream/SecondaryButton';
    import Dropdown from '@/Jetstream/Dropdown.vue';
    import DropdownLink from '@/Jetstream/DropdownLink.vue';
    // import CKEditor from '@ckeditor/ckeditor5-vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
    // import ClassicEditor from '@ckeditor/ckeditor5-build-classic/build/ckeditor';
    // import SimpleUploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter';
    class UploadAdapter
    {
        constructor (loader)
        {
            // The file loader instance to use during the upload.
            this.loader = loader;
        }

        // Starts the upload process.
        upload ()
        {
            return new Promise((resolve, reject) =>
            {
                const reader = new window.FileReader();

                reader.addEventListener('load', () =>
                {
                    resolve({'default': reader.result});
                });

                reader.addEventListener('error', err =>
                {
                    reject(err);
                });

                reader.addEventListener('abort', () =>
                {
                    reject();
                });

                this.loader.file.then(file =>
                {
                    reader.readAsDataURL(file);
                });
            });
        }
        // Aborts the upload process.
        abort ()
        {
            //
        }
    }
    export default {
        components: {
            AppLayout,
            Paginator,
            Toggle,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetActionMessage,
            JetSecondaryButton,
            Dropdown,
            DropdownLink,
        },

        props: {
            post: Object,
        },

        data() {
            return {
                value: '',
                selectedLanguage: 'ar',
                languages: {
                    ar: 'Arabic',
                    en: 'English',
                    tr: 'Turkish',
                },
                editor: ClassicEditor,
                form: this.$inertia.form({
                    translations: {ar: {title: null, content: '', language: 'ar'}},
                }),
                editorConfig: {
                    'extraPlugins': [this.uploader]
                },
            };
        },
        created() {
            if (this.post) {
                this.post.translations.forEach((translation) => {
                    this.form.translations[translation.language] = translation;
                });
            }
        },

        methods: {
            selectLanguage(key) {
                if (!this.form.translations[key]) {
                    this.form.translations[key] = {title: null, content: '', language: key};
                }
                this.selectedLanguage = key;
            },
            submit() {
                if (this.post) {
                    this.form.patch(this.route('posts.update', this.post.id))
                } else {
                    this.form.post(this.route('posts.store'));
                }
            },
            uploader(editor)
            {
                editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                    return new UploadAdapter( loader );
                };
            },
        }
    }
</script>

<style src="@vueform/toggle/themes/default.css"></style>
