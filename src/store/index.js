import Vue from "vue";
import Vuex from "vuex";
import builder from "@/store/modules/builder";

Vue.use(Vuex);

export default new Vuex.Store({
	modules: {
		builder,
	},
});
