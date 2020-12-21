<template>
	<div id="app">
		<router-view />
	</div>
</template>

<script>
require("@/assets/css/normal.css");
</script>

<style scoped>
#app {
	font-family: Roboto, sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	min-height: 100vh;
	background-color: hsl(0, 0%, 92%);
}

#nav {
	padding: 30px;
	font-size: 1.4rem;
	background-color: #212121;
}

#nav a {
	font-weight: bold;
	color: #fff;
}

#nav a.router-link-exact-active {
	color: #42b983;
}
</style>
