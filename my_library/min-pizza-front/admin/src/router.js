import Vue from "vue";
import store from "./store";
// import {isMobile} from "mobile-device-detect";
import Router from "vue-router";
import NProgress from "nprogress";
import authenticate from "./auth/authenticate";
import unauthenticated from "./auth/unauthenticated";

Vue.use(Router);

// create new router

const routes = [
    {
        path: "/",
        component: () => import("./views/app"), //webpackChunkName app
        beforeEnter: authenticate,
        redirect: "/app/dashboards/dashboard.v1",

        children: [
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"), //dashboard
                children: [
                    {
                        path: "dashboard.v1",
                        name: "dashboard.v1",
                        component: () => import("./views/app/dashboards/dashboard.v1"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "reviews",
                        name: "reviews",
                        component: () => import("./views/app/dashboards/reviews"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "users",
                        name: "users",
                        component: () => import("./views/app/dashboards/users"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "orders/:id",
                        name: "orders",
                        component: () => import("./views/app/dashboards/orders"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "restaurantMenu/:id",
                        name: "restaurantMenu",
                        component: () => import("./views/app/dashboards/restaurantMenu"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "orderStatus",
                        name: "orderStatus",
                        component: () => import("./views/app/dashboards/orderStatus"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "categories",
                        name: "categories",
                        component: () => import("./views/app/dashboards/categories"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "drivers",
                        name: "drivers",
                        component: () => import("./views/app/dashboards/drivers"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "addOrderStatus",
                        name: "addOrderStatus",
                        component: () => import("./views/app/dashboards/addOrderStatus"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "resturantsList",
                        name: "resturantsList",
                        component: () => import("./views/app/dashboards/resturantsList"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "restaurantOffers",
                        name: "restaurantOffers",
                        component: () => import("./views/app/dashboards/restaurantOffers"),
                    },
                ],
            },
            {
            path: "/app/dashboards",
            component: () => import("./views/app/dashboards"),
            children: [
                {
                    path: "itemsOffers",
                    name: "itemsOffers",
                    component: () => import("./views/app/dashboards/itemsOffers"),
                },
            ],
        },
        {
            path: "/app/dashboards",
            component: () => import("./views/app/dashboards"),
            children: [
                {
                    path: "itemOfferCategories",
                    name: "itemOfferCategories",
                    component: () => import("./views/app/dashboards/itemOfferCategories"),
                },
            ],
        },
        {
            path: "/app/dashboards",
            component: () => import("./views/app/dashboards"),
            children: [
                {
                    path: "itemOfferItems/:id",
                    name: "itemOfferItems",
                    component: () => import("./views/app/dashboards/itemOfferItems"),
                },
            ],
        },
            
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "generalSetting",
                        name: "generalSetting",
                        component: () => import("./views/app/dashboards/generalSetting"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "addResturant",
                        name: "addResturant",
                        component: () => import("./views/app/dashboards/addResturant"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "taxs",
                        name: "taxs",
                        component: () => import("./views/app/dashboards/taxs"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "addTax",
                        name: "addTax",
                        component: () => import("./views/app/dashboards/addTax"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "customers",
                        name: "customers",
                        component: () => import("./views/app/dashboards/customers"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "aboutUs",
                        name: "aboutUs",
                        component: () => import("./views/app/dashboards/aboutUs"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "fqa",
                        name: "fqa",
                        component: () => import("./views/app/dashboards/fqa"),
                    },
                ],
            },

            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "restaurant/ratings/:restaurant",
                        name: "ratings",
                        component: () => import("./views/app/dashboards/ratings"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "restaurant/menu/categories/:restaurant",
                        name: "menuCategories",
                        component: () => import("./views/app/dashboards/menuCategories"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "reasons",
                        name: "reasons",
                        component: () => import("./views/app/dashboards/reasons"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "discount-codes",
                        name: "discount-codes",
                        component: () => import("./views/app/dashboards/discountCodes"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "contact-us",
                        name: "contact-us",
                        component: () => import("./views/app/dashboards/contact_us"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "sliders",
                        name: "sliders",
                        component: () => import("./views/app/dashboards/sliders"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "canceledOrders",
                        name: "canceledOrders",
                        component: () => import("./views/app/dashboards/canceledOrders"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "option",
                        name: "option",
                        component: () => import("./views/app/dashboards/option"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "optionTemplates",
                        name: "optionTemplates",
                        component: () => import("./views/app/dashboards/optionTemplates"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "updateOption/:id",
                        name: "updateOption",
                        component: () => import("./views/app/dashboards/updateOption"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "addCategory",
                        name: "addCategory",
                        component: () => import("./views/app/dashboards/addCategory"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "updateOptionTemplate/:id",
                        name: "updateOptionTemplate",
                        component: () => import("./views/app/dashboards/updateOptionTemplate"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "addOptionTemplate",
                        name: "addOptionTemplate",
                        component: () => import("./views/app/dashboards/addOptionTemplate"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "workTimes/:id",
                        name: "workTimes",
                        component: () => import("./views/app/dashboards/workTimes"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "customerOrders/:id",
                        name: "customerOrders",
                        component: () => import("./views/app/dashboards/customerOrders"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "resturantSetting/:id",
                        name: "resturantSetting",
                        component: () => import("./views/app/dashboards/resturantSetting"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "notifications",
                        name: "notifications",
                        component: () => import("./views/app/dashboards/notifications"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "specialNotifications",
                        name: "specialNotifications",
                        component: () => import("./views/app/dashboards/specialNotifications"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "modules",
                        name: "modules",
                        component: () => import("./views/app/dashboards/modules"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "holiday/:id",
                        name: "holiday",
                        component: () => import("./views/app/dashboards/holiday"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "admin",
                        name: "admin",
                        component: () => import("./views/app/dashboards/admin"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "createAdmin",
                        name: "createAdmin",
                        component: () => import("./views/app/dashboards/createAdmin"),
                        
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "ordersList",
                        name: "ordersList",
                        component: () => import("./views/app/dashboards/ordersList"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "updateAdmin/:id",
                        name: "updateAdmin",
                        component: () => import("./views/app/dashboards/updateAdmin"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "orderDetails/:id",
                        name: "orderDetails",
                        component: () => import("./views/app/dashboards/orderDetails"),
                    },
                ],
            },
            {
                path: "/app/dashboards",
                component: () => import("./views/app/dashboards"),
                children: [
                    {
                        path: "addOrder",
                        name: "addOrder",
                        component: () => import("./views/app/dashboards/addOrder"),
                    },
                ],
            },
        ],
    },
    
    
    
    // sessions
    {
        path: "/app/sessions",
        component: () => import("./views/app/sessions"),
        redirect: "/app/sessions/signIn",
        beforeEnter: unauthenticated,
        children: [
            {
                path: "signIn",
                component: () => import("./views/app/sessions/signIn"),
            },
            {
                path: "signUp",
                component: () => import("./views/app/sessions/signUp"),
            },
            {
                path: "forgot",
                component: () => import("./views/app/sessions/forgot"),
            },
            {
                path: "reset",
                component: () => import("./views/app/sessions/reset"),
            },
        ],
    },
    {
        path: "/vertical-sidebar",
        component: () => import("./containers/layouts/verticalSidebar"),
    },
    //pages
    {
        path: "/app/pages",
        component: () => import('./views/app/pages'),
        children: [
            {
                path: "profile",
                component: () => import('./views/app/pages/profile'),
            },
            {
                path: "*",
                component: () => import("./views/app/pages/notFound"),
            },
        ],
    },
];

const router = new Router({
    mode: "history",
    linkActiveClass: "open",
    routes,
    scrollBehavior(to, from, savedPosition) {
        return { x: 0, y: 0 };
    },
});

router.beforeEach((to, from, next) => {
    // If this isn't an initial page load.
    if (to.path) {
        // Start the route progress bar.

        NProgress.start();
        NProgress.set(0.1);
    }
    next();
});

router.afterEach(() => {
    // Remove initial loading
    const gullPreLoading = document.getElementById("loading_wrap");
    if (gullPreLoading) {
        gullPreLoading.style.display = "none";
    }
    // Complete the animation of the route progress bar.
    setTimeout(() => NProgress.done(), 500);
    // NProgress.done();
    // if (isMobile) {
    if (window.innerWidth <= 1200) {
        // console.log("mobile");
        store.dispatch("changeSidebarProperties");
        if (store.getters.getSideBarToggleProperties.isSecondarySideNavOpen) {
            store.dispatch("changeSecondarySidebarProperties");
        }

        if (store.getters.getCompactSideBarToggleProperties.isSideNavOpen) {
            store.dispatch("changeCompactSidebarProperties");
        }
    } else {
        if (store.getters.getSideBarToggleProperties.isSecondarySideNavOpen) {
            store.dispatch("changeSecondarySidebarProperties");
        }

        // store.state.sidebarToggleProperties.isSecondarySideNavOpen = false;
    }
});

export default router;
