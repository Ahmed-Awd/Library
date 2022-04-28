require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Icon from '@/Components/Icon';
import CKEditor from '@ckeditor/ckeditor5-vue';
import { translations } from "./Mixins/translations";
import Toaster from "@meforma/vue-toaster";


const el = document.getElementById('app');

const app = createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
});


app.mixin({ methods: { route } })
    .use(InertiaPlugin)
    .use(CKEditor)
    .use(Toaster)
    .mixin(translations)
    .mount(el);


app.component('Icon', Icon);



InertiaProgress.init({ color: '#4B5563' });
