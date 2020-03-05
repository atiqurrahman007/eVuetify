<template>
  <div class="mt-5">
    <v-tabs
      v-model="tab"
      background-color="deep-purple accent-4"
      justify-space-around
      d-flex
      dark
      icons-and-text
    >
      <v-tabs-slider></v-tabs-slider>

      <v-tab href="#tab-1" class="ml-2 mr-2">
        Recents
        <v-icon>mdi-phone</v-icon>
      </v-tab>
      <v-spacer></v-spacer>
      <v-tab href="#tab-2" class="ml-2 mr-2">
        Favorites
        <v-icon>mdi-heart</v-icon>
      </v-tab>
      <v-spacer></v-spacer>
      <v-tab href="#tab-3" class="ml-2 mr-2">
        Nearby
        <v-icon>mdi-account-box</v-icon>
      </v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <v-tab-item
        v-for="i in 3"
        :key="i"
        :value="'tab-' + i"
      >
        <v-row >
          <v-col v-for="n in 8" :key="n" cols="12" xsm="6" sm="4" md="2">
            <v-hover
              v-slot:default="{ hover }"
              close-delay="200"
            >
            <v-card
              :loading="loading"
              class="mx-auto my-4"
              max-width="374"
              :elevation="hover ? 16 : 2"
            >
              <v-img
                height="100"
                src="https://cdn.vuetifyjs.com/images/cards/cooking.png"
              ></v-img>

              <v-card-title class="amber--text">Cafe Badilico</v-card-title>

              
            </v-card>
            </v-hover>
          </v-col>
          
        </v-row>
      </v-tab-item>
    </v-tabs-items>
  </div>
</template>


<script>
  export default {
    data () {
      return {
        loading: false,
        selection: 1,
        tab: null,
        text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
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