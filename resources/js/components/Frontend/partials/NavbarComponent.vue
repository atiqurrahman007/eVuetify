<template>
  <div>
    <v-app-bar
      app
     
      :color="color"
      danse

    >
      <v-container>
     	<v-row align="center"
          justify="center">
     	 
	      <v-app-bar-nav-icon class="white--text" @click="drawer = !drawer" />
	      <v-toolbar-title class=" ml-2 mr-5">Dhammaka</v-toolbar-title>
	      <!-- <span class="title ml-3 mr-5"><span class="font-weight-light">Keep</span></span> -->
	      <!-- <v-spacer></v-spacer> -->
	      <v-text-field
	        flat
	        solo-inverted
	        hide-details
	        label="Search"
	        prepend-inner-icon="mdi-magnify"
	        class="hidden-sm-and-down"
	        color="#ffc107"
	        
	      />

	      
	      <!-- <v-spacer></v-spacer> -->

	      <v-btn icon >
	        <v-icon class="white--text">mdi-bell</v-icon>
	      </v-btn>
          
	      <v-btn icon >
	        <v-icon class="white--text">mdi-cart</v-icon>
	      </v-btn>

	      <v-btn icon to="/login">
	        <v-icon class="white--text">mdi-account</v-icon>
	      </v-btn>
	    
     	</v-row>
     </v-container>
    </v-app-bar>
<!-- Vapp bar end -->

<!-- Vapp drawers -->
  <v-navigation-drawer 
        app
        v-model="drawer"
        :color="color"
        
        temporary
      >
        <v-list
          dense
          nav
          class="py-0"
        >
          <v-list-item two-line :class="miniVariant && 'px-0'">
            <v-list-item-avatar>
              <img src="https://randomuser.me/api/portraits/men/81.jpg">
            </v-list-item-avatar>
             <v-spacer></v-spacer>
            <v-btn icon @click="drawer=false">
	          <v-icon class="white--text">mdi-close</v-icon>
	        </v-btn>
          </v-list-item>

          <v-divider></v-divider>

          <v-list-item
            v-for="item in sidebars"
            :key="item.title"
            link
          >
            <v-list-item-icon>
              <v-icon class="white--text">{{ item.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title class="white--text">{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>


          <v-list-item
            link
            to="dailybazar"
          >
            <v-list-item-icon>
              <v-icon class="white--text font-weight-bold" >mdi-shopping-outline</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title class="red--text font-weight-bold">Daily Bazar</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

        </v-list>
      </v-navigation-drawer>
<!-- Vapp drawers end -->
   

    <v-content>
      <v-container
      >
        <v-row
          justify="center"
          align="center"
          v-show="$route.path === '/'?true:false"
        >
          
            <Carousel/>
            <Category/>
            <Newproduct/>
        
        </v-row>
      </v-container>
    </v-content>
  </div>
</template>

<script>
	import Carousel from './CarouselComponent.vue'
	import Category from './CategoryComponent.vue'
	import Newproduct from './NewproductComponent.vue'
  export default {
    props: {
      source: String,
    },
    components:{
    	Carousel,
    	Category,
    	Newproduct
    },
    data: () => ({
      drawer: null,
      items: [
        { icon: 'lightbulb_outline', text: 'Notes' },
        { icon: 'touch_app', text: 'Reminders' },
        { divider: true },
        { heading: 'Labels' },
        { icon: 'add', text: 'Create new label' },
        { divider: true },
        { icon: 'archive', text: 'Archive' },
        { icon: 'delete', text: 'Trash' },
        { divider: true },
        { icon: 'settings', text: 'Settings' },
        { icon: 'chat_bubble', text: 'Trash' },
        { icon: 'help', text: 'Help' },
        { icon: 'phonelink', text: 'App downloads' },
        { icon: 'keyboard', text: 'Keyboard shortcuts' },
        
      ],
      sidebars:[
        { title: 'Dashboard', icon: 'mdi-view-dashboard' },
        { title: 'Photos', icon: 'mdi-image' },
        { title: 'About', icon: 'mdi-help-box' },
      ],
      color: 'amber',
      colors: [
          'primary',
          'blue',
          'success',
          'red',
          'teal',
        ],
        background: false,
        miniVariant: false,
    }),
    computed: {
      
    },
  }
</script>

<style>


/*@media only screen and (max-width: 768px) { .v-app-bar { padding-top: 80px !important; } }*/

</style>