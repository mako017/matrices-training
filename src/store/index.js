import Vue from "vue";
import Vuex from "vuex";
import builder from "@/store/modules/builder";
import database from "@/store/modules/database";
import editor from "@/store/modules/editor";

Vue.use(Vuex);

export default new Vuex.Store({
	modules: {
		builder,
		database,
		editor,
	},
});
