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
              <img 
              :src="`../storage/Users/${profile.profile_image}`"
              v-if="this.profile.profile_image != 'default.png'"
              >

              <img 
              :src="`../storage/${profile.profile_image}`"
              v-else
              >

            </v-list-item-avatar>

            <v-list-item-content>
              <v-list-item-title>{{userInfo.name}} ( {{userStatus.name}} ) </v-list-item-title>
              
              <v-list-item-subtitle>{{userInfo.email}}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

          <v-divider></v-divider>
          <!-- Dashboard -->
          <v-list-item
            link
            to="/admin"
          >
            <v-list-item-icon>
              <v-icon>mdi-view-dashboard</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Dashboard</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- Dashboard end -->

           <!-- Administration -->
          <v-list-item
            link
            to="/admin/administration"
          >
            <v-list-item-icon>
              <v-icon>mdi-shield-lock-outline</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Administration</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- Administration end -->

          <!-- Users-->
          <v-list-item
            link
            to="/admin/roles"
            v-show="isRole() === '1'?true:false"
          >
            <v-list-item-icon>
              <v-icon>mdi-account-edit</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Role</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          
          <!-- Users end -->

          <!-- Users-->
          <v-list-item
            link
            to="/admin/users"
            v-show="isRole() === '1'?true:false"
          >
            <v-list-item-icon>
              <v-icon>mdi-account-group</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Users</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          
          <!-- Users end -->

          <!-- Tag-->
          <v-list-item
            link
            to="/admin/tag"
            v-show="isRole() === '1'?true:false"
          >
            <v-list-item-icon>
              <v-icon>mdi-border-color</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Tag</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          
          <!-- Tag end -->

          <!-- Category-->
          <v-list-item
            link
            to="/admin/category"
            v-show="isRole() === '1'?true:false"
          >
            <v-list-item-icon>
              <v-icon>mdi-equal</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Category</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          
          <!-- Category end -->

          <!-- Brand-->
          <v-list-item
            link
            to="/admin/brand"
            v-show="isRole() === '1'?true:false"
          >
            <v-list-item-icon>
              <v-icon>mdi-label-variant-outline</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Brand</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          
          <!-- Brand end -->


          <!-- Brand-->
          <v-list-item
            link
            to="/admin/dailyproduct"
            v-show="isRole() === '1'?true:false"
          >
            <v-list-item-icon>
              <v-icon>mdi-archive</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>Dailyproduct</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          
          <!-- Brand end -->

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
       <AdminDashboard v-show="$route.path === '/admin'?true:false"></AdminDashboard>
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
  import AdminDashboard from './HomeComponent.vue';
  export default {
    components:{
      AdminDashboard
    },
    data () {
      return {
        drawer: null,
        snackbar:false,
        profile:{},
        userInfo:{},
        userStatus:{},
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
      this.getProfileImage();
      this.getUser();
      
    },
    methods:{
      Logout(){
        localStorage.removeItem('token');
        localStorage.removeItem('roleId');
        this.$router.push('/login');
      },
      isRole(){
        return localStorage.getItem('roleId');
      },
      getProfileImage(){
        this.$Progress.start();
        axios.get('/api/auth/user/profile')
        .then(response=>{
          this.profile = response.data
          this.$Progress.finish()
        })
        .catch(e=>{
          console.log(e)
        })
      },
      getUser(){
        axios.get('/api/auth/user')
        .then(response=>{
          
          this.userInfo = response.data.user
          this.userStatus = response.data.role
          
        })
        .catch(e=>{
          console.log(e)
        })
      },
    }
  }
</script>