import Vue from "vue";
import VueRouter from "vue-router";
import builder from "@/views/builder.vue";
Vue.use(VueRouter);

const routes = [
	{
		path: "/",
		name: "Home",
		component: builder,
	},
];

const router = new VueRouter({
	routes,
});

export default router;
