<template>
  <v-app id="inspire">
    <v-content>
      <v-container
        class="fill-height"
        fluid
      >
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
              <v-toolbar
                color="amber"
                dark
                flat
              >
                <v-toolbar-title>Sign-Up</v-toolbar-title>
                <v-spacer />
                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <v-btn
                      to="/login"
                      icon
                      large
                      v-on="on"
                    >
                      <v-icon>mdi-lock</v-icon>
                    </v-btn>
                  </template>
                  <span>Signin</span>
                </v-tooltip>
                
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    label="Enter your name"
                    name="name"
                    prepend-icon="mdi-account"
                    type="text"
                  />
                  <v-text-field
                    label="Enter your email"
                    prepend-icon="mdi-email"
                    

                  />
                  <v-text-field
                    label="Enter your phone"
                    name="phone"
                    prepend-icon="mdi-phone"
                    type="text"
                  />
                  <v-text-field
                    id="password"
                    label="Password"
                    name="password"
                    prepend-icon="mdi-lock"
                    type="password"
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer />
                <v-btn color="amber white--text">Sign-Up</v-btn>
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
    props: {
      source: String,
    },
    data(){
      return{

      }
    },
     mounted(){
      if(localStorage.getItem('token')){
        this.$router.push('admin');
      }
    },

    
  }
</script>