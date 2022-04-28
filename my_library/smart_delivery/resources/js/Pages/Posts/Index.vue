<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{__("Posts")}}
                </h2>
                <div>
                    <inertia-link :href="route('posts.create')">
                        <jet-button>
                            {{__("New Post")}}
                        </jet-button>
                    </inertia-link>
                </div>
            </div>
        </template>

        <div class="py-12">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{__("Post number")}}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{__("title")}}
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">{{__("Edit or Delete")}}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(post, key) in posts.data" :key="post.post_id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{post.post_id}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{post.title}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <inertia-link :href="route('posts.edit', post.post_id)" class="text-indigo-600 hover:text-indigo-900 mr-5">{{__("Edit")}}</inertia-link>
                                        <a href="#" class="text-red-600 hover:text-red-900" @click="postToDeleteIndex = key">{{__("Delete")}}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <paginator :paginator="stores"/>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <dialog-modal :show="postToDeleteIndex !== null" @close="postToDeleteIndex = null">
            <template #title>
                {{__("Delete post")}} "{{this.posts.data[postToDeleteIndex].title}}"
            </template>
            <template #content>
                {{__("If you deleted the post, All related data will be deleted as well and you won't be able to restore it again.")}}
            </template>
            <template #footer>
                <secondary-button @click="postToDeleteIndex = null" class="mx-2">
                    {{__("Cancel")}}
                </secondary-button>
                <danger-button @click="deletePost(posts.data[postToDeleteIndex].post_id)">
                    {{__("DELETE!")}}
                </danger-button>
            </template>
        </dialog-modal>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Paginator from "@/Components/Paginator";
    import Toggle from '@vueform/toggle'
    import JetButton from '@/Jetstream/Button'
    import DialogModal from '@/Jetstream/DialogModal.vue';
    import DangerButton from '@/Jetstream/DangerButton.vue';
    import SecondaryButton from '@/Jetstream/SecondaryButton.vue';

    export default {
        components: {
            AppLayout,
            Paginator,
            Toggle,
            JetButton,
            DialogModal,
            DangerButton,
            SecondaryButton,
        },

        props: {
            posts: Array
        },
        
        data() {
            return {
                postToDeleteIndex: null,
                mounted: false,
            };
        },
        
        mounted() {
            this.mounted = true;
        },
        
        methods: {
            deletePost(postId) {
                this.$inertia.delete(this.route('posts.destroy', postId));
                this.postToDeleteIndex = null;
            }
        }
    }
</script>

<style src="@vueform/toggle/themes/default.css"></style>
