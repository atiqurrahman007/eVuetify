<template>
  <v-app id="inspire">
    <v-content>
      <v-container
        class="fill-height"
        fluid

      >
      <!-- Snackbar start -->
      <v-snackbar
        v-model="snackbar"
      >
        {{ text }}
        <v-btn
          color="pink"
          text
          @click="snackbar = false"
        >
          Close
        </v-btn>
      </v-snackbar>
      <!-- Sanckbar end -->
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
            <v-card class="elevation-12">
              <v-progress-linear
                :active="loading"
                :indeterminate="loading"
                absolute
                top
                :color="progressColor"
              ></v-progress-linear>
              <v-toolbar
                color="amber"
                dark
                flat
              >
                <v-toolbar-title>Sign-In</v-toolbar-title>
                <v-spacer />
                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <v-btn
                      
                      icon
                      large
                      to="/register"
                      v-on="on"
                    >
                      <v-icon>mdi-account-question</v-icon>
                    </v-btn>
                  </template>
                  <span>Create an account !</span>
                </v-tooltip>
                
              </v-toolbar>
              <v-card-text>
                <v-form
                  ref="form"
                  v-model="valid"
                  :lazy-validation="lazy"
                  >
                  <v-text-field
                    v-model="email"
                    label="Email"
                    :rules="emailRules"
                    required
                    prepend-icon="mdi-account"
                    color="amber"
                  />

                  <v-text-field
                    v-model="password"
                    label="Password"
                    :rules="passwordRules"
                    :counter="8"
                    required
                    prepend-icon="mdi-lock"
                    type="password"
                    color="amber"
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-switch
                  v-model="remember_me"
                  label="Remember me"
                  color="amber"
                  class="ml-1"
                ></v-switch>
                <v-spacer />
                <v-btn color="amber white--text" @click="Login" :disabled="!valid">Login</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  export default {
    data(){
      return{
        email:'',
        emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],

        password:'',
        passwordRules: [
        v => !!v || 'Password is required',
        v => (v && v.length >= 8) || 'password must be 8 or more .',
      ],
        remember_me: false,
        valid: true,
        lazy: false,
        snackbar: false,
        text:'',
        loading:false,
        progressColor:'deep-purple accent-4',
        role:''
      }
    },
    mounted(){
      if(localStorage.getItem('token')){
        this.$router.push('admin');
      }
    },
    methods:{
      Login(){
        // Intercepter
        // Add a request interceptor
          axios.interceptors.request.use((config)=> {
              // Do something before request is sent
              this.loading = true;
              return config;
            },  (error)=> {
              // Do something with request error
              return Promise.reject(error);
            });

          // Add a response interceptor
          axios.interceptors.response.use( (response)=> {
              this.loading = false;
              return response;
            },  (error)=> {
              this.loading = false;
              this.progressColor = 'red'
              return Promise.reject(error);
            });
        // Intercepter end
        
        axios.post('/api/auth/login', {'email': this.email, 'password': this.password, 'remember_me': this.remember_me})
        .then(response=>{
          console.log(response)
          localStorage.setItem('token',response.data.access_token);
          localStorage.setItem('roleId',response.data.role);
          localStorage.setItem('logIn', true);
          this.role = response.data.role
          if (this.role == 1 || this.role == 2) {
            this.$router.push('/admin');
          }else{
            this.$router.push('/');
          }
          

        })
        .catch(e=>{
          console.dir(e)
          this.snackbar = true;
            this.text = e.response.statusText;
        })
        
      }
    }
  }
</script>