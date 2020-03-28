<template>
  <div pd="0">
    <!-- Main roe start -->
    <v-row
     
     
    >
    <!-- Main colum 8 start -->
      <v-col
       cols="12"
       md="7"
       sm="7"
       xs="6"
       
      >
   <!-- Main colum 8 Card start -->
        <v-card>
          <v-list-item style="background:red;">
            <v-list-item-avatar color="white">
              <v-btn icon color="pink">
                <v-icon>mdi-shopping-outline</v-icon>
              </v-btn>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title class="headline white--text">নিত্য প্রয়োজনীয় দ্রব্য</v-list-item-title>
              <v-list-item-subtitle>Product's</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-container
            
            id="scroll-target"
            style="max-height: 600px"
            class="overflow-y-auto"
          >
            <v-row dense
              v-scroll:#scroll-target="onScroll"
              align="center"
              justify="center"

            >
              

              <v-col
                v-for="(item, i) in getDailyproduct"
                :key="i"
                cols="12"
              >
                <v-card
                  :color="item.color"
                  dark
                >
                  <div class="d-flex flex-no-wrap justify-space-between">
                      
                     <v-avatar
                      class="ma-3"
                      size="125"
                      tile
                    >
                      <v-img :src="`../storage/Dailyproducts/${item.product_image}`"></v-img>
                    </v-avatar>

                    <div>
                      <v-card-title
                        class="headline"
                        v-text="item.product_name"
                      ></v-card-title>

                      <v-card-subtitle v-text="item.product_short_desc" v-if="item.product_short_desc.length<50"></v-card-subtitle>
                      <v-card-subtitle v-text="item.product_short_desc.substring(0, 50)+`..`" v-else></v-card-subtitle>
                      <v-card-text>
                        <v-chip-group
                         active-class="deep-purple accent-4 white--text"
                          column
                        >
                          

                          <v-chip>Per {{item.product_unit}}</v-chip>
                          <v-chip>{{item.selling_price}} Tk</v-chip>

                           <v-btn class="mx-2" fab dark small color="success">
                              <v-icon dark>mdi-thumb-up-outline</v-icon>
                            </v-btn>

                           <v-btn class="mx-2" fab dark small color="pink">
                              <v-icon dark>mdi-heart</v-icon>
                            </v-btn>

                           <v-btn class="mx-2" fab dark small color="white">
                              <v-icon dark color="pink" @click="addCart(item)">mdi-cart-outline</v-icon>
                            </v-btn>
                        </v-chip-group>
                      </v-card-text>
                    </div>

                   
                  </div>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
     <!-- Main colum 8 Card start -->
        
      </v-col>
   <!-- Main colum 8 end -->
   <!-- Main colum 4 start -->
      <v-col
       cols="12"
       md="5"
       sm="5"
       xs="6"
       
      >
      
        <v-card>
          <v-list-item style="background:red;" >
            <v-badge
            color="error"
            :content="carts.length? cartItemCount : '0'"
            bordered
            overlap
            >
              <v-list-item-avatar color="white">
              <v-btn icon color="pink">
                <v-icon>mdi-cart-outline</v-icon>
              </v-btn>
            </v-list-item-avatar>
            </v-badge>
            <v-list-item-content>
              <v-list-item-title class="headline white--text">আপনার অর্ডার </v-list-item-title>
              <v-list-item-subtitle>Your Cart</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-container 
          style="max-height: 500px"
          class="overflow-y-auto"
          >
           
           <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-left">Image</th>
                  <th class="text-left">Per Unit Price</th>
                  <th class="text-left">Quantity</th>
                  <th class="text-left">Price</th>
                  <th class="text-left">Action</th>
                  
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, i) in carts" :key="i">
                  <td>
                    <v-list-item-avatar color="grey">
                      <v-img
                      :src="`../storage/Dailyproducts/${item.proImage}`"
                      >

                      </v-img>
                    </v-list-item-avatar>
                  </td>
                  <td>{{ item.proPrice }}</td>
                  <td>
                   <v-text-field 
                   v-model="item.proQuantity"
                   color="pink"
                   type="text"
                   :append-outer-icon="appendIcon"
                   :prepend-icon ="prependIcon"
                   @click:append-outer="incrementCart"
                   @click:prepend="dcrementtCart"
                   >
                  </v-text-field>

                  </td>
                  <td>{{item.proQuantity * item.proPrice}}</td>
                  <td>
                    <v-btn icon color="pink" small xs-small fab dark class="mx-2">
                      <v-icon dark>mdi-close-circle-outline</v-icon>
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
          </v-container>

          <v-card-actions mt="4" >
           <v-row>
            <v-col cols="12">
              <!-- V-List -->
           <v-list two-line>
            <v-list-item-group>
              <!-- 1St Item List -->
              <v-list-item>
              <v-list-item-content>
                 <v-list-item-title>Total Quantities : </v-list-item-title>
                 
              </v-list-item-content>
              
              <v-list-item-action>
               <strong>{{totalQuantity}}</strong>
              </v-list-item-action>
            </v-list-item>
             <v-divider ></v-divider>
            <!-- End List -->


              <!-- 1St Item List -->
              <v-list-item>
              <v-list-item-content>
                 <v-list-item-title>Sub Total : </v-list-item-title>
                 
              </v-list-item-content>
              
              <v-list-item-action>
               <strong>{{subTotal}}</strong>
              </v-list-item-action>
            </v-list-item>
             <v-divider ></v-divider>
            <!-- End List -->

             <!-- 1St Item List -->
              <v-list-item>
              <v-list-item-content>
                 <v-list-item-title>Vat : </v-list-item-title>
                 
              </v-list-item-content>
              
              <v-list-item-action>
               <strong>100</strong>
              </v-list-item-action>
            </v-list-item>
             <v-divider ></v-divider>
            <!-- End List -->

            <!-- 1St Item List -->
              <v-list-item>
              <v-list-item-content>
                 <v-list-item-title>Total : </v-list-item-title>
                 
              </v-list-item-content>
              
              <v-list-item-action>
               <strong>100</strong>
              </v-list-item-action>
            </v-list-item>
             <v-divider ></v-divider>
            <!-- End List -->
           

            </v-list-item-group>

           </v-list>
            
           
          </v-col>
        <!-- V-List End -->

        <!-- Order Action Start -->

          <v-col cols="12" d-flex>
            
             <v-toolbar
                color="pink"
                dark
              >
               

                <v-toolbar-title>অর্ডার</v-toolbar-title>

                <v-spacer></v-spacer>

                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    
                    <v-btn icon>
                      <v-icon  v-on="on">mdi-close-outline</v-icon>
                    </v-btn>
                  </template>
                  <span>Cancle Order</span>
                </v-tooltip>

                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    
                    <v-btn icon>
                      <v-icon  v-on="on">mdi-heart-outline</v-icon>
                    </v-btn>
                  </template>
                  <span>Whitelist</span>
                </v-tooltip>

                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    
                    <v-btn icon>
                      <v-icon  v-on="on">mdi-thumb-up-outline</v-icon>
                    </v-btn>
                  </template>
                  <span>Order Confirm</span>
                </v-tooltip>
              </v-toolbar>
          </v-col>
        <!-- Order Action End -->

            </v-row> 
          </v-card-actions>

        </v-card>

        
      </v-col>
   <!-- Main colum 4 start -->
    </v-row>
    <!-- Main roe End -->
  </div>
</template>



<script>
  export default {
    data: () => ({
      prependIcon:'mdi-minus-circle-outline',
      appendIcon:'mdi-plus-circle-outline',
      items: [ 
      ],

       desserts: [
          {
            name: 'Frozen Yogurt',
            calories: 159,
          },
          
        ],
    }),
    computed:{
      getDailyproduct(){
        return this.$store.getters.getDailyproduct
      },
      carts(){
        return this.$store.getters.getCarts
      },
      cartItemCount(){
        return this.$store.getters.cartItemCount
      },
      subTotal(){
        return this.$store.getters.subTotal
      },
      totalQuantity(){
        return this.$store.getters.totalQuantity
      }
     
     
      // totalsubTotal(){
      //   let sum = 0;
      //   for(let i=0; i<this.carts.length; i++){
      //     sum +=(parseFloat(this.carts[i].pro_price) * parseFloat(this.carts[i].quantity) );
      //   }
      //   return sum;
      // }
      
    },
    mounted(){
      this.$store.dispatch('getAllDailyProduct');
     console.log(this.carts)
    },

    methods:{
      onScroll (e) {
        this.offsetTop = e.target.scrollTop
      },

      // Cart Functionility
      
      addCart(item){
         this.$store.dispatch('addProductToCart', {product: item, quantity:1})
      },
      incrementCart(){},
      dcrementtCart(){},
    }
  }
</script>

<style  scoped>
  
  .v-text-field > .v-input__control > 
  .v-input__slot > 
  .v-text-field__slot {
    width: 25px !important;
  }
  .v-text-field input {
    text-align: center !important;
  }

  .v-text-field > .v-input__control > .v-input__slot:after {
    bottom: -1px;
    content: none !important;
    left: 0;
    position: absolute;
    transition: 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
    width: 0px !important;
    }
  .v-list{
    width: 100% !important;
  }
</style>