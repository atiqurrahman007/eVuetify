<template>
	<div >
		<Navbar/>
		<v-container>
        <router-view></router-view>
	   </v-container>
	   <Footer/>
	</div>
</template>

<script>
	import Navbar from "./partials/NavbarComponent.vue"
	import Footer from "./partials/FooterComponent.vue"
	export default{
		components:{
             Navbar,
             Footer
		}
	}
</script>