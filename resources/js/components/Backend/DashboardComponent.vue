<template>
	<div>
	
      <v-navigation-drawer app
        v-model="drawer"
        :color="color"
        :sr="bg"
        clipped
        danse
        
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

            <v-list-item-content>
              <v-list-item-title>Application</v-list-item-title>
              <v-list-item-subtitle>Subtext</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-divider></v-divider>
          <!-- Dashboard -->
          <v-list-item
            link
          >
            <v-list-item-icon>
              <v-icon>mdi-view-dashboard</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Dashboard</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- Dashboard end -->
          <!-- Users-->
          <v-list-item
            link
            to="/admin/profile"
            
          >
            <v-list-item-icon>
              <v-icon>mdi-account</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Users</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          
          <!-- Users end -->
        </v-list>


        <template v-slot:append>
        <div class="pa-2">
          <v-btn block color="error" @click="Logout">Logout</v-btn>
        </div>
      </template>

      </v-navigation-drawer>
    

  <!-- Vuetify app bar -->
  <v-app-bar app
      color="error"
      clipped-left
      danse
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title>Title</v-toolbar-title>
    </v-app-bar>

  <!-- Sizes your content based upon application components -->
  <v-content>
     
    <!-- Provides the application the proper gutter -->
    <v-container fluid>

       <!-- Snackbar start -->
      <v-snackbar
        v-model="snackbar"
      >
        You are loggedin successfully !
        <v-btn
          color="pink"
          text
          @click="snackbar = false"
        >
          Close
        </v-btn>
      </v-snackbar>
      <!-- Sanckbar end -->

      <!-- If using vue-router -->
      <router-view></router-view>
    </v-container>
  </v-content>

  <v-footer app>
    <!-- -->
  </v-footer>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        drawer: null,
        snackbar:false,
        // items: [
        //   { title: 'Dashboard', icon: '', action:'/admin/dashboard' },
        //   { title: 'Role', icon: 'mdi-image', action:'/admin/role' },
        //   { title: 'About', icon: 'mdi-help-box', action:'/admin/profile' },
        // ],
        color: 'primary',
        colors: [
          'primary',
          'blue',
          'success',
          'red',
          'teal',
        ],
        right: true,
        miniVariant: false,
        expandOnHover: false,
        background: false,
        
      }
    },
    computed: {
      bg () {
        this.$vuetify.theme.dark = true
      },
    },
    mounted(){
      this.snackbar = localStorage.getItem('logIn')?true:false;
      localStorage.removeItem('logIn');
    },
    methods:{
      Logout(){
        localStorage.removeItem('token');
        localStorage.removeItem('roleId');
        this.$router.push('/login');
      },
      
    }
  }
</script>