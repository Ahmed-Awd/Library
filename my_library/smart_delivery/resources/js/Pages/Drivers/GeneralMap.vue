<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{__('Trace')}}
                </h2>
            </div>
        </template>

        <div class="py-12"  ref="scrollToMe">
            <div class="flex flex-col max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form class="w-1/2 flex items-center mobile-frm-flex" @submit.prevent="driverSearch">
                    <multiselect
                        mode="single"
                        searchable=true
                        :placeholder="__('Driver name')"
                        class="searched-select mr-2"
                        v-model="selectedDriver"
                        :options="drivers"
                        valueProp="id"
                        label="name"
                        trackBy="name"
                    >
                    </multiselect>
                    <jet-button class="ml-2">
                        <icon name="search" class="w-5 h-5"/>
                    </jet-button>
                </form>
                <div class="flex mt-3">
                    <div class="flex text-center bg-white p-4 rounded-lg shadow w-100 mr-1 mobile-m-10" >
                        
                        <GoogleMap
                            ref="mapRef"
                            id="map"
                            :api-key="googleKey"
                            :street-view-control="false"
                            :map-type-control="false"
                            style="width: 100%; height: 600px"
                            :center="location"
                            :zoom="13">
                            <!-- <Marker 
                                v-for="(avilableDriver, index) in avilableDrivers" :key="avilableDriver.id" 
                                :options="{
                                    position: {lat: parseFloat(avilableDriver.lat), lng: parseFloat(avilableDriver.lng)},
                                    title: avilableDriver.name,
                                    icon:  '../../../images/icon-ping.png',
                                }"  
                                @click="toggleInfoWindow(avilableDriver, index)"
                            >
                            </Marker>
                            
                            <Marker 
                                v-for="unavilableDriver in unavilableDrivers" :key="unavilableDriver.id" 
                                :options="{
                                    position: {lat: parseFloat(unavilableDriver.lat), lng: parseFloat(unavilableDriver.lng)},
                                    title: unavilableDriver.name,
                                    icon:  '../../../images/icon-pinr.png',
                                }"  
                            /> -->
                            <!-- <infowindow
                                :options="infoOptions"
                                :position="infoWindowPos"
                                :opened="infoWinOpen"
                                @closeclick="infoWinOpen=false"
                            >
                                <div v-html="infoContent"></div>
                            </infowindow> -->
                        </GoogleMap>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Paginator from "@/Components/Paginator";
    import Toggle from '@vueform/toggle'
    import JetButton from '@/Jetstream/Button'
    import QrCode from '@/Components/QrCode.vue';
    import {GoogleMap, Marker} from 'vue3-google-map'
    import Button from "../../Jetstream/Button";
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetLabel from '@/Jetstream/Label'
    import Multiselect from '@vueform/multiselect';
    import Dropdown from '@/Jetstream/Dropdown.vue';
    import DropdownLink from '@/Jetstream/DropdownLink.vue';
    import axios from 'axios';

    export default {
        components: {
            Button,
            AppLayout,
            Paginator,
            Toggle,
            JetButton,
            QrCode,
            GoogleMap,
            Marker,
            JetFormSection,
            JetInput,
            JetLabel,
            Multiselect,
            Dropdown,
            DropdownLink,
        },
        props: {
            current: Object,
        },
        mounted() {
            this.getDrivers();

            setTimeout(() => {
                this.scrollToElement();
            }, 500);

            setInterval(function(){
                this.getDrivers();
                this.setMarkers();
            }.bind(this), 20000);

            setTimeout(() => {
                this.initMap();
                this.getDrivers();
                this.setMarkers();
            }, 2500);
            console.log(this.$page.props.town)

        },
        data() {
            return {
                googleKey : this.$page.props.googleMapKey,
                townId : this.$page.props.town.id,
                townName : this.$page.props.town.name,
                avilableDrivers: [],
                unavilableDrivers: [],
                location: {lat: parseFloat(this.$page.props.town.center_lat), lng: parseFloat(this.$page.props.town.center_lng)},
                selectedDriver: '',
                drivers: [],

                map: null,
                infoContent: '',
                infoWindowPos: {
                    lat: 0,
                    lng: 0
                },
                infoWinOpen: false,
                currentMidx: null,
                infoOptions: {
                    pixelOffset: {
                        width: 0,
                        height: -35
                    }
                },  
            }
        },
        methods: {
            // initMap() {
            //     let address = this.townName;
            //     let geocoder = new google.maps.Geocoder();
            //     geocoder.geocode({
            //         'address': address
            //     }, (results, status) => {
            //         if (status === "OK") {
            //             this.location = {
            //                lat: results[0].geometry.location.lat(),
            //                lng: results[0].geometry.location.lng(),
            //             }
            //         } else {
            //             console.error("Geocoding request status: " + status);
            //             console.error(results);
            //         }
            //     });
            // },
            initMap() {
                this.map = new google.maps.Map(
                    document.getElementById("map"),
                    {
                        zoom: 13,
                        center: this.location,
                    }
                );

            },
            setMarkers(){
                console.log("new markers");
                for (let i = 0; i < this.avilableDrivers.length; ++i) {
                    // console.log(parseFloat(this.avilableDrivers[i].lat));
                    const marker = new google.maps.Marker({
                        position: {
                            lat: parseFloat(this.avilableDrivers[i].lat),
                            lng: parseFloat(this.avilableDrivers[i].lng),
                        },
                        map: this.map,
                        icon: '../../../images/icon-ping.png',
                    });
                    this.attachSecretMessage(marker, this.avilableDrivers[i]);
                }
                for (let i = 0; i < this.unavilableDrivers.length; ++i) {
                    const marker = new google.maps.Marker({
                        position: {
                            lat: parseFloat(this.unavilableDrivers[i].lat),
                            lng: parseFloat(this.unavilableDrivers[i].lng),
                        },
                        map: this.map,
                        icon: '../../../images/icon-pinr.png',
                    });
                    this.attachSecretMessage(marker, this.unavilableDrivers[i]);
                }
            },
            getDrivers(){
                axios.get(`/driver/all/on-map/${this.townId}`, {
                    headers: {"Accept" : "application/json"}
                })
                .then((response) => {
                    this.avilableDrivers = response.data.data.available_drivers;
                    this.unavilableDrivers = response.data.data.unavailable_drivers;
                    this.drivers = this.avilableDrivers.concat(this.unavilableDrivers);
                    // console.log(this.drivers);
                }).catch((errors) => {
                    console.log(errors.data);
                });
            },
            scrollToElement() {
                const el = this.$refs.scrollToMe;
                if (el) {
                // Use el.scrollIntoView() to instantly scroll to the element
                    el.scrollIntoView({behavior: 'smooth'});
                }
            },
            driverSearch() {
                let serchedDriver = this.selectedDriver;
                console.log("Selected Driver ID " + this.selectedDriver);
                let driver = this.drivers.filter((el) => {
                    if(el.id == serchedDriver) return el;
                });
                // console.log("tababababa " + driver[0].lat);
                this.location = {
                    lat: parseFloat(driver[0].lat),
                    lng: parseFloat(driver[0].lng),
                }

                const map = new google.maps.Map(
                    document.getElementById("map") ,
                    {
                        zoom: 16,
                        center: this.location,
                    }
                );

                const infoContent = this.getInfoWindowContent(driver[0])

                const infowindow = new google.maps.InfoWindow({
                    content: infoContent,
                });

                const marker = new google.maps.Marker({
                    position: this.location,
                    map,
                    icon: '../../../images/icon-ping.png',
                });

                infowindow.open({
                    anchor: marker,
                    map,
                    shouldFocus: false,
                });
            },
            toggleInfoWindow: function (marker, idx) {
                console.log(marker);
                this.infoWindowPos =  { lat: parseFloat(marker.lat), lng: parseFloat(marker.lng) };
                this.infoContent = this.getInfoWindowContent(marker);    
            },
            getInfoWindowContent: function (marker) {
                let orderDiv = '';
                let statusColor = 'green-color';
                if(marker.current_order.length > 0){
                    orderDiv+= `<div class="order-details">
                                <div class=""><span style="font-weight: bold;">Order: </span>
                                    # ${marker.current_order[0].order_number}
                                    <a href="${route('orders.show', marker.current_order[0].id)}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                    </a>
                                </div>
                                <div class=""><span style="font-weight: bold;">Delivery Price: </span>
                                     ${marker.current_order[0].delivery_price}
                                </div>
                                <div class=""><span style="font-weight: bold;">Total: </span>
                                     ${marker.current_order[0].total_price}
                                </div>
                                <div class=""><span style="font-weight: bold;">Address: </span>
                                     ${marker.current_order[0].customer_address}
                                </div>
                                </div>`;
                }
                if(marker.is_available == 0){
                    statusColor = 'red-color';
                }
                return (
                `<div class="infowindow">
                    <div class="driver-img"><img src="${marker.profile_photo_url}" /></div>
                    <div class="driver-info">
                        <div class="clearfix"><span style="font-weight: bold;">Name: </span>
                            ${marker.name}
                            <a class="driver-status ${statusColor}">
                                ${marker.is_available == 1 ? "available" : "unavailable"}
                            </a>
                        </div>
                        <div class=""><span style="font-weight: bold;">Phone: </span>
                            ${marker.phone}
                        </div>
                        <div class=""><span style="font-weight: bold;">Town: </span>
                            ${marker.town.name}
                        </div>
                        ${orderDiv}
                        
                    </div>
                </div>`);
            },
            attachSecretMessage(marker,secretMessage) {
                const infowindow = new google.maps.InfoWindow({
                    content: this.getInfoWindowContent(secretMessage),
                });

                marker.addListener("click", () => {
                    infowindow.open(marker.get("map"), marker);
                });
            }
        },
        
    }
</script>

<style src="@vueform/toggle/themes/default.css"></style>
<style src="@vueform/multiselect/themes/default.css"></style>
