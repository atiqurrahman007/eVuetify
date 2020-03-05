<template>
	<div >
		<Navbar/>
		<v-container>
        <router-view></router-view>
	   </v-container>
	</div>
</template>

<script>
	import Navbar from "./partials/NavbarComponent.vue"
	export default{
		components:{
             Navbar
		}
	}
</script>