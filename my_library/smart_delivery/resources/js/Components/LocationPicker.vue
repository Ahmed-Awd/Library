<template>
    <GoogleMap
        ref="mapRef"
        :api-key="googleKey"
        :street-view-control="false"
        :map-type-control="false"
        style="width: 100%; height: 500px"
        :center="location || { lat: 40.689247, lng: -74.044502 }"
        :zoom="15"
        @click="chooseLocation">
        <custom-control position="TOP_LEFT">
            <form class="flex" @submit.prevent="searchAddress">
                <jet-input type="text" class="mt-2 ml-2 rounded-r-none" placeholder="Search.." v-model="query"/>
                <jet-button class="rounded-l-none mt-2 border-gray-100 border-l-0">
                    <icon name="search" class="w-4 h-4"/>
                </jet-button>
            </form>
        </custom-control>
        <Marker :options="{ position: location }"/>
    </GoogleMap>
</template>

<script>
    import {GoogleMap, Marker, CustomControl} from 'vue3-google-map'
    import JetInput from '@/Jetstream/Input';
    import JetButton from '@/Jetstream/Button';

    export default {
        components: {
            GoogleMap,
            Marker,
            CustomControl,
            JetInput,
            JetButton,
        },

        props: {
            defaultLocation: Object
        },

        data() {
            return {
                location: this.defaultLocation || null,
                address: null,
                query: null,
                googleKey : this.$page.props.googleMapKey
            };
        },

        methods: {
            chooseLocation($event) {
                this.location = $event.latLng;
                console.log($event.latLng.lat(), $event.latLng.lng())
                let geocoder = new google.maps.Geocoder();
                geocoder.geocode({location: this.location}, (results, status) => {
                    console.log(results);
                    if (status === 'OK' && results.length > 0) {
                        this.address = results[0].formatted_address;
                    } else {
                        console.error('Geocoding request status: ' + status);
                        console.error(results);
                    }
                });
            },
            searchAddress() {
                let service = new google.maps.places.PlacesService(this.$refs.mapRef.map)
                service.findPlaceFromQuery({query: this.query, fields: ['name', 'formatted_address', 'geometry']}, (results, status) => {
                    if (status === google.maps.places.PlacesServiceStatus.OK && results.length > 0) {
                        this.location = results[0].geometry.location;
                        this.address = results[0].formatted_address;
                    } else {
                        console.error('Places request status: ' + status);
                        console.error(results);
                    }
                    this.query = null;
                })
            }
        },
        watch: {
            location() {
                this.$emit('locationChange', this.location);
            },
            address() {
                this.$emit('addressChange', this.address);
            }
        }
    }
</script>
