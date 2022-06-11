import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import Home from "../views/Home.vue";
import authenticate from "@/auth/authenticated";
import unauthenticated from "@/auth/unauthenticated";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    name: "Home",
    component: Home,
  },
  {
    path: "/about",
    name: "About",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/About.vue"),
  },
  {
    path: "/terms",
    name: "Terms",
    component: () => import("../views/Terms.vue"),
  },
  {
    path: "/fqa",
    name: "Fqa",
    component: () => import("../views/Fqa.vue"),
  },
  {
    path: "/contact",
    name: "Contact",
    component: () => import("../views/Contact.vue"),
  },
  {
    path: "/Cart",
    name: "Cart",
    beforeEnter: authenticate,
    component: () => import("../views/Cart.vue"),
  },
  {
    path: "/Checkout",
    name: "Checkout",
    beforeEnter: authenticate,
    component: () => import("../views/Checkout.vue"),
  },
  {
    path: "/error",
    name: "Error",
    component: () => import("../views/Error.vue"),
  },
  {
    path: "/Search",
    name: "Search",
    component: () => import("../views/Search.vue"),
  },
  {
    path: "/Profile",
    name: "Profile",
    beforeEnter: authenticate,
    component: () => import("../views/Profile.vue"),
  },
  {
    path: "/ChangePass",
    name: "ChangePass",
    beforeEnter: authenticate,
    component: () => import("../views/ChangePass.vue"),
  },
  {
    path: "/Location",
    name: "Location",
    beforeEnter: authenticate,
    component: () => import("../views/Location.vue"),
  },
  {
    path: "/EditLocation/:id",
    name: "EditLocation",
    beforeEnter: authenticate,
    component: () => import("../views/EditAddress.vue"),
  },
  {
    path: "/Favourite",
    name: "Favourite",
    beforeEnter: authenticate,
    component: () => import("../views/Favourite.vue"),
  },
  {
    path: "/Following",
    name: "Following",
    beforeEnter: authenticate,
    component: () => import("../views/Following.vue"),
  },
  {
    path: "/OrderHistory",
    name: "OrderHistory",
    beforeEnter: authenticate,
    component: () => import("../views/OrderHistory.vue"),
  },
  {
    path: "/Order/:id",
    name: "Order",
    beforeEnter: authenticate,
    component: () => import("../views/Order.vue"),
  },
  {
    path: "/ForgetPass",
    name: "ForgetPass",
    beforeEnter: unauthenticated,
    component: () => import("../views/sessions/ForgetPass.vue"),
  },
  {
    path: "/login",
    name: "Login",
    beforeEnter: unauthenticated,
    component: () => import("../views/sessions/Login.vue"),
  },
  {
    path: "/signup",
    name: "Signup",
    beforeEnter: unauthenticated,
    component: () => import("../views/sessions/Signup.vue"),
  },
  {
    path: "/resetPass",
    name: "resetPass",
    beforeEnter: unauthenticated,
    component: () => import("../views/sessions/resetPass.vue"),
  },
  {
    path: "/addAddress",
    name: "addAddress",
    beforeEnter: authenticate,
    component: () => import("../views/addAddress.vue"),
  },
  {
    path: "/restaurantMenu/:id",
    name: "restaurantMenu",
    component: () => import("../views/restaurantMenu.vue"),
  },
  {
    path: "/restaurantRate/:id",
    name: "restaurantRate",
    component: () => import("../views/restaurantRate.vue"),
  },
  {
    path: "/restaurantDetails/:id",
    name: "restaurantDetails",
    component: () => import("../views/restaurantDetails.vue"),
  },
  {
    path: "/404",
    name: "Not Found",
    component: () => import("../views/Error.vue"),
  },
  {
    path: "/:catchAll(.*)", // Unrecognized path automatically matches 404
    redirect: '/404',
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});
const token = localStorage.getItem("customerToken") || "";
console.log(token);

export default router;
