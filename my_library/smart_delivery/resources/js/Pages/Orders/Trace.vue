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
                <div class="flex mt-3">
                    <div class="flex flex-col text-center bg-white p-4 rounded-lg shadow w-100 mr-1 mobile-m-10">
                        <GoogleMap
                            ref="mapRef"
                            id="map"
                            :api-key="googleKey"
                            :street-view-control="false"
                            :map-type-control="false"
                            style="width: 100%; height: 600px"
                            :center="location"
                            :zoom="14">
                            <Marker :options="driverMarkerOptions"  />
                            <Marker :options="customerMarkerOptions"/>
                            <Marker :options="storeMarkerOptions"/>
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
    import {GoogleMap, Marker, Polyline} from 'vue3-google-map'
    import Pusher from 'pusher-js'
    import Button from "../../Jetstream/Button";

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
            Polyline
        },
        props: {
            current: Object,
        },
        mounted() {
            this.subscribe();
            setTimeout(() => {
                this.scrollToElement();
                // this.initMap();
                this.drowRout();
            }, 500);
            
        },
        data() {
            return {
                location: {lat: parseFloat(this.current.store_lat), lng: parseFloat(this.current.store_lng)},
                driverMarkerOptions: {
                    position: {lat: parseFloat(this.current.driver.lat), lng: parseFloat(this.current.driver.lng)},
                    title: 'Driver',
                    icon: "../../images/icon-delivery.png",
                },
                storeMarkerOptions: {
                    position: {lat: parseFloat(this.current.store_lat), lng: parseFloat(this.current.store_lng)},
                    title: 'Store',
                    icon: "../../images/icon-shop.png",
                },
                customerMarkerOptions:{
                    position: {lat: parseFloat(this.current.customer_lat), lng: parseFloat(this.current.customer_lng)},
                    title: 'Customer',
                    icon: "../../images/icon-customer.png",
                },
                googleKey : this.$page.props.googleMapKey,
                flightPath: {
                    path :[
                            {lat: parseFloat(this.current.store_lat), lng: parseFloat(this.current.store_lng)},
                            {lat: parseFloat(this.current.customer_lat), lng: parseFloat(this.current.customer_lng)},
                    ],
                    geodesic: true,
                    strokeColor: '#fdc705',
                    strokeOpacity: 1.0,
                    strokeWeight: 3,
                },
                pusher :  new Pusher("935b5728f6da9399a3d9", {
                    cluster: "eu",
                }),
                customer_lat: parseFloat(this.current.customer_lat),
                customer_lng: parseFloat(this.current.customer_lng),
                store_lat: parseFloat(this.current.store_lat),
                store_lng: parseFloat(this.current.store_lng),
                map: null
            }
        },

        methods: {
            drowRout(){
                let map = new google.maps.Map(
                    document.getElementById("map"),
                    {
                        zoom: 14,
                        center: this.location,
                    }
                );

                const driverMarker = new google.maps.Marker({
                    position: {lat: parseFloat(this.current.driver.lat), lng: parseFloat(this.current.driver.lng)},
                    title: 'Driver',
                    map: map,
                    icon: "../../images/icon-delivery.png",
                });

                const storeMarker = new google.maps.Marker({
                    position: {lat: parseFloat(this.current.store_lat), lng: parseFloat(this.current.store_lng)},
                    title: 'Store',
                    icon: "../../images/icon-shop.png",
                    map: map,
                });

                const customerMarker = new google.maps.Marker({
                    position: {lat: parseFloat(this.current.customer_lat), lng: parseFloat(this.current.customer_lng)},
                    title: 'Customer',
                    icon: "../../images/icon-customer.png",
                    map: map,
                }); 
                // let map = this.$refs.mapRef;
                let directionsDisplay = new google.maps.DirectionsRenderer(),
                    directionsService = new google.maps.DirectionsService();
                    directionsDisplay.setMap(map);


                var request = {
                    origin: `${this.store_lat},${this.store_lng}`,
                    destination: `${this.customer_lat},${this.customer_lng}`,
                    travelMode: 'DRIVING',
                    provideRouteAlternatives: true,
                };
                directionsService.route(request, function (result, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                    var distance = null;
                    var routeIndex = 0;
                    // Loop through the routes to find the shortest one
                    for (var i = 0; i < result['routes'].length; i++) {
                        var routeDistance = result['routes'][i].legs[0].distance.value;
                        if (distance === null) {
                        distance = routeDistance;
                        routeIndex = i;
                        }
                        if (routeDistance < distance) {
                        distance = routeDistance;
                        routeIndex = i;
                        }
                    }
                    directionsDisplay.setDirections(result);
                    // Set route index
                    directionsDisplay.setOptions({
                        routeIndex: routeIndex,
                        suppressMarkers: true
                    });
                    }
                });
            },
            subscribe(){
                console.log( this.current);
                let channel = this.pusher.subscribe(`orders.${this.current.id}`);
                channel.bind("new-location", (data) => {
                    console.log("new-location");
                    if(this.current.id == data.order_id){
                        console.log(data);
                        this.driverMarkerOptions = {
                            position: {lat: parseFloat(data.driver_lat), lng: parseFloat(data.driver_lng)},
                            title: 'Driver',
                            icon: "../../images/icon-delivery.png",
                        };

                    }
                });
            },
            unSubscribe(){
                this.pusher.unsubscribe(`orders.${this.current.id}`);
                console.log("destroyed");
            },
            scrollToElement() {
                const el = this.$refs.scrollToMe;

                if (el) {
                // Use el.scrollIntoView() to instantly scroll to the element
                    el.scrollIntoView({behavior: 'smooth'});
                }
                // const top = el.offsetTop;
                // window.scrollTo(0, top);
            }
        },
        destroyed() {
            this.pusher.unsubscribe(`orders.${this.current.id}`);
            console.log("destroyed");
        }
    }
</script>

<style src="@vueform/toggle/themes/default.css"></style>
