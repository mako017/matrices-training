<template>
	<div class="builder">
		<div class="help-menu" v-bind:class="{ active: settings.activeView == 'help' }">
			<h2 class="side-title">Hilfe</h2>
			<collapse v-for="item in allHelp" :key="item.title" :item="item" />
			<button id="reset" class="final-button" @click="resetItem">Reset item</button>
		</div>
		<div class="main" v-bind:class="{ active: settings.activeView == 'construct' }">
			<div class="main-title">
				<svg @click="switchItem(-1)" viewBox="0 0 50 80" xml:space="preserve">
					<polyline stroke-linecap="round" stroke-linejoin="round" points="45.63,75.8 0.375,38.087 45.63,0.375 " />
				</svg>
				<h2>{{ itemCounter }}</h2>
				<svg @click="switchItem(1)" viewBox="0 0 50 80" xml:space="preserve">
					<polyline stroke-linecap="round" stroke-linejoin="round" points="0.375,0.375 45.63,38.087 0.375,75.8 " />
				</svg>
			</div>
			<con :item="item" />
			<drawer :items="items" />
		</div>
		<div class="control-menu" v-bind:class="{ active: settings.activeView == 'settings' }">
			<h2 class="side-title">Control Panel</h2>
			<div class="side-container">
				<h3>Used Rules</h3>
				<table class="rules-table" style="width:80%;">
					<thead>
						<th>Rule</th>
						<th>Item</th>
						<th>Test</th>
					</thead>
					<tbody>
						<tr>
							<td>ADD</td>
							<td>{{ item.rules.add.length }}</td>
							<td>{{ testRules.add }}</td>
						</tr>
						<tr>
							<td>SUB</td>
							<td>{{ item.rules.sub.length }}</td>
							<td>{{ testRules.sub }}</td>
						</tr>
						<tr>
							<td>DU</td>
							<td>{{ item.rules.eka.length }}</td>
							<td>{{ testRules.eka }}</td>
						</tr>
						<tr>
							<td>INT</td>
							<td>{{ item.rules.sm.length }}</td>
							<td>{{ testRules.sm }}</td>
						</tr>
						<tr>
							<td>ROT</td>
							<td>{{ item.rules.rot.length }}</td>
							<td>{{ testRules.rot }}</td>
						</tr>
						<tr>
							<td>COM</td>
							<td>{{ item.rules.voll.length }}</td>
							<td>{{ testRules.voll }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- <div class="side-container">
				<p>{{ currentItem }}</p>
			</div>-->
			<button id="save-item" class="final-button" @click="saveItem">Save item</button>
		</div>
	</div>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import { mapGetters } from "vuex";
import collapse from "@/components/builder/collapsible.vue";
import con from "@/components/builder/construction.vue";
import drawer from "@/components/builder/item-drawer.vue";
import { MdButton, MdProgress, MdSwitch } from "vue-material/dist/components";
import rCon from "@/assets/js/id-rule.js";
import "vue-material/dist/vue-material.min.css";
import "vue-material/dist/theme/default.css";
Vue.use(MdButton);
Vue.use(MdProgress);
Vue.use(MdSwitch);
export default {
	name: "builder",
	components: {
		collapse,
		con,
		drawer,
	},
	data: function() {
		return {
			items: [],
			item: {
				id: 0,
				code:
					"00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000",
				svg: ["0", "0", "0", "0", "0", "0", "0", "0", "0"],
				rules: {
					add: [],
					sub: [],
					eka: [],
					sm: [],
					rot: [],
					voll: [],
				},
			},
			participant: {
				code: "",
				session: Math.random()
					.toString(36)
					.substring(2),
			},
			settings: {
				svg: true,
				pdf: false,
				activeView: "construct",
			},
		};
	},
	methods: {
		resetItem() {
			this.$set(
				this.item,
				"code",
				"00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000",
			);
			this.item = Object.assign({}, this.item);
			// this.resetItemCode();
		}, //erledigt
		saveItem() {
			if (
				rCon.testDuplicate(
					this.item.code,
					this.items.map(item => item.code),
				)
			) {
				alert("Dieses Item existiert schon");
				return 1;
			}
			if (rCon.solvable(this.item.code, this.item.rules)) {
				if (!this.items[this.item.id]) {
					this.items.push({ ...this.item });
					this.$set(this.item, "id", this.item.id + 1);
					this.$set(
						this.item,
						"code",
						"00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000",
					);
					this.$set(this.item, "svg", ["0", "0", "0", "0", "0", "0", "0", "0", "0"]);
					this.$set(this.item, "rules", {
						add: [],
						sub: [],
						eka: [],
						sm: [],
						rot: [],
						voll: [],
					});
					this.item = Object.assign({}, this.item);
				} else {
					this.$set(this.items, this.item.id, { ...this.item });
				}
			}
			axios.post(
				"/php/saveData.php",
				JSON.stringify({
					call: "insertData",
					participant: this.participant,
					items: this.items,
				}),
			);
			// this.pushItem();
		}, //erledigt
		switchItem(val) {
			if (+val === 1 && this.item.id === this.items.length - 1) {
				this.$set(this.item, "id", this.item.id + 1);
				this.$set(
					this.item,
					"code",
					"00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000,00000000000000000000",
				);
				this.item = Object.assign({}, this.item);
			}
			if ((+val === -1 && this.item.id > 0) | (+val === 1 && this.item.id < this.items.length - 1)) {
				this.item = Object.assign({}, this.items[this.item.id + +val]);
			}
		},
	},
	computed: {
		...mapGetters(["allHelp"]),
		itemCounter: function() {
			return "Item " + (this.item.id + 1) + " von " + (this.items.length + 1);
		},
		itemDiff: function() {
			let rules = this.item.rules.add.length + this.item.rules.sub.length + this.item.rules.eka.length + this.item.rules.sm.length + this.item.rules.rot.length;
			if (this.item.rules.voll.length > 0) rules += 1;
			// console.log(rules/6);

			return (rules / 6) * 100;
		},
		testRules: function() {
			let rules = {
				add: 0,
				sub: 0,
				eka: 0,
				sm: 0,
				rot: 0,
				voll: 0,
				est: 0,
			};
			this.items.forEach(element => {
				rules.add += element.rules.add.length;
				rules.sub += element.rules.sub.length;
				rules.eka += element.rules.eka.length;
				rules.sm += element.rules.sm.length;
				rules.rot += element.rules.rot.length;
				if (element.rules.voll.length > 0) rules.voll += 1;
			});
			rules.est = ((rules.add + rules.sub + rules.eka + rules.sm + rules.rot + rules.voll) / (6 * this.items.length)) * 100;
			return rules;
		},
	},
};
</script>

<style scoped>
th {
	font-weight: bold;
}
h2 {
	font-weight: 800;
	text-align: center;
}
h3 {
	font-weight: 600;
}
textarea {
	resize: none;
	height: 4.2rem;
}
.builder {
	width: 100%;
	height: 100vh;
	background-color: hsl(0, 0%, 92%);
	display: grid;
	grid-template-columns: 25% 50% 25%;
}

.help-menu {
	border-right: 1px solid black;
	display: flex;
	flex-direction: column;
	overflow-y: auto;
}
.main {
	display: flex;
	flex-direction: column;
	padding: 1rem 0 0 0;
	overflow: hidden;
}
.main-title {
	align-self: center;
	display: flex;
	flex-direction: row;
	font-size: 1.3rem;
}
.main-title svg {
	height: 1.3rem;
	stroke-width: 5px;
	stroke: #000;
	fill: none;
	margin: 0 1.5rem;
}
.control-menu {
	border-left: 1px solid black;
	display: flex;
	flex-direction: column;
}

.side-title {
	font-size: 2rem;
	text-align: center;
	padding: 0.2rem;
	border-bottom: 1px solid black;
}
.side-container {
	padding: 0.5rem 0;
	margin: 0 1rem;
	display: flex;
	flex-direction: column;
	border-bottom: 1px solid black;
}
.export-settings {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-content: center;
	width: 30%;
	margin: 0 10%;
}
.side-buttons {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	justify-content: center;
}
.side-buttons > * {
	width: 40%;
	margin: 0.5rem auto;
}

.side-container:last-of-type {
	border: none;
}

.diff-grid {
	display: grid;
	grid-template-columns: 1fr 1fr;
	justify-items: center;
}

.diff-grid > * {
	width: 80%;
	text-align: center;
	margin: 0.3rem 1rem;
}

.final-button {
	margin: auto 0 0 0;
	height: 3rem;
	font-size: 2rem;
}

.rules-table {
	width: 80%;
	align-self: center;
}

.navbar {
	display: none;
}

@media (max-width: 850px) {
	.control-menu,
	.help-menu,
	.main {
		display: none;
	}
	.control-menu.active,
	.help-menu.active,
	.main.active {
		display: flex;
	}
	.builder {
		grid-template-columns: 1fr;
	}
	.navbar {
		height: 2rem;
		width: 100%;
		background-color: #212121;
		position: fixed;
		bottom: 0;
		color: #fff;
		font-size: 1.5rem;
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		align-items: center;
	}
	.navbar:hover {
		cursor: pointer;
	}
	.navbar span.active {
		color: #42b983;
	}
	.final-button {
		margin-bottom: 2rem;
	}
}
</style>
