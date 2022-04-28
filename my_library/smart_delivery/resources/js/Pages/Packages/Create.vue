<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ balancePackage? `${__("Edit package")} ${balancePackage.package_name}` : `${__("Create a new package")}` }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <jet-form-section @submitted="submit">
                    <template #form>
                        <div class="col-span-6">
                            <jet-label for="package_name" :value="__('Name')" />
                            <jet-input id="package_name" type="text" class="mt-1 block w-full" v-model="form.package_name"/>
                            <jet-input-error :message="form.errors.package_name" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="price" :value="__('Price')" />
                            <jet-input id="price" type="text" class="mt-1 block w-full" v-model="form.price"/>
                            <jet-input-error :message="form.errors.price" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="value" :value="__('Value')" />
                            <jet-input id="value" type="text" class="mt-1 block w-full" v-model="form.value"/>
                            <jet-input-error :message="form.errors.value" class="mt-2" />
                        </div>
                        <div class="col-span-6">
                            <jet-label for="type" :value="__('package type')"/>
                            <select id="type" v-model="form.type"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option selected value="owner">{{__('owner')}}</option>
                                <option value="driver">{{__('driver')}}</option>
                            </select>
                        </div>
                    </template>

                    <template #actions>
                        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                            {{__("Saved")}}.
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
    import AppLayout from '@/Layouts/AppLayout'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    export default {
        components: {
            AppLayout,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetActionMessage,
            JetSecondaryButton,
        },

        props: {
            balancePackage: Object,
        },

        data() {
            return {
                form: this.$inertia.form({
                    package_name: this.balancePackage? this.balancePackage.package_name : null,
                    price: this.balancePackage? this.balancePackage.price : null,
                    value: this.balancePackage? this.balancePackage.value : null,
                    type: this.balancePackage? this.balancePackage.type : "owner",
                })
            };
        },
         methods: {
            submit() {
                if (this.balancePackage) {
                    this.form.patch(this.route('packages.update', this.balancePackage.id));
                } else {
                    this.form.post(this.route('packages.store'));
                }
             },
        },
    }
</script>
