<template>
  <div class="mt-5">
       <v-container>
       	<!-- New product header -->
       	<v-row
       	  align="center"
          justify="center">
       		<v-toolbar color="white" flat >
       		<v-toolbar-title class="grey--text text--darken-4 font-weight-bold">Albums</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-text-field
            class="mt-8 mr-2 amber--text"
            label="Search.."
            single-line
            solo
            append-icon="mdi-magnify"
            color="#ffc107"
            >
          	
           </v-text-field>

            <v-btn color="error" rounded>
              VIEW ALL
            </v-btn>
       	</v-toolbar>
       	</v-row>

    <!-- New product card -->
        <v-row >
          <v-col v-for="n in 8" :key="n" cols="12" sm="6" md="3">
            <v-card
              :loading="loading"
              class="mx-auto my-6"
              max-width="374"
            >
              <v-img
                height="250"
                src="https://cdn.vuetifyjs.com/images/cards/cooking.png"
              ></v-img>

              <v-card-title>Cafe Badilico</v-card-title>

              <v-card-text>
                <v-row
                  align="center"
                  class="mx-0"
                >
                  <v-rating
                    :value="4.5"
                    color="amber"
                    dense
                    half-increments
                    readonly
                    size="14"
                  ></v-rating>

                  <div class="grey--text ml-4">4.5 (413)</div>
                </v-row>

                <div class="my-4 subtitle-1">
                  $ • Italian, Cafe
                </div>

                <div>Small plates, salads & sandwiches - an intimate setting with 12 indoor seats plus patio seating.</div>
              </v-card-text>

              <v-divider class="mx-4"></v-divider>

              <v-card-title>Tonight's availability</v-card-title>

              <v-card-text>
                <v-chip-group
                  v-model="selection"
                  active-class="deep-purple accent-4 white--text"
                  column
                >
                  <v-chip>5:30PM</v-chip>

                  <v-chip>7:30PM</v-chip>

                  <v-chip>8:00PM</v-chip>

                  <v-chip>9:00PM</v-chip>
                </v-chip-group>
              </v-card-text>

              <v-card-actions>
                <v-btn
                  color="deep-purple lighten-2"
                  text
                  @click="reserve"
                >
                  Reserve
                </v-btn>
              </v-card-actions>
            </v-card>
            
          </v-col>
          
        </v-row>
       </v-container>
  </div>
</template>


<script>
  export default {
    data () {
      return {
        loading: false,
        selection: 1,
        
      }
    },
    methods: {
      reserve () {
        this.loading = true

        setTimeout(() => (this.loading = false), 2000)
      },
    },
  }
</script>

<style type="text/css" scoped="">

</style>