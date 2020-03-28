<template>
 <v-card>
   <v-snackbar
      v-model="sanckbar"
    >
      {{ snackText }}
      <v-btn
        color="pink"
        text
        @click="snackbar = false"
      >
        Close
      </v-btn>
    </v-snackbar>

  <v-data-table
    :headers="headers"
    :items="getDailyproduct"
    sort-by="calories"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat color="amber">
        <v-toolbar-title>Daily Product System</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="800px">
          <template v-slot:activator="{ on }">
            <v-btn color="teal" small fab dark class="mx-2" v-on="on">
              <v-icon dark>mdi-plus-circle-outline</v-icon>
            </v-btn>
           
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>
           <v-form
             ref="form"
             v-model="valid"
           >
            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="6" md="4">
                    <v-text-field 
                    v-model="editedItem.product_name" 
                    label="Daily product name"
                    :rules="[rules.required]"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                    <v-text-field 
                    v-model="editedItem.product_code" 
                    label="Daily product code"
                    :rules="[rules.required]"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                    <v-text-field 
                    v-model="editedItem.buying_price" 
                    label="Daily Product Buying Price"
                    :rules="[rules.required]"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                    <v-text-field 
                    v-model="editedItem.selling_price" 
                    label="Daily Product Selling price"
                    :rules="[rules.required]"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
                    <v-select
                      label="Select Category"
                      :items="getCategory"
                      v-model="editedItem.cat_id"
                      item-text="cat_name"
                      item-value="id"
                      :rules="[rules.required]"
                    >
                      
                    </v-select>
                  </v-col>
                  
                    <!-- Buying Date start -->
                   <v-col cols="12" sm="6" md="4">
                    <v-menu
                      ref="menu"
                      v-model="menu"
                      :close-on-content-click="false"
                      :return-value.sync="editedItem.buying_date"
                      transition="scale-transition"
                      offset-y
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          v-model="editedItem.buying_date"
                          label="Buying Date"
                          prepend-icon="mdi-calendar-range"
                          readonly
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker v-model="editedItem.buying_date"  scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                        <v-btn text color="primary" @click="$refs.menu.save(editedItem.buying_date)">OK</v-btn>
                      </v-date-picker>
                    </v-menu>
                  </v-col>
                 <!-- Buying Date end -->

                  <!-- Buyong Date start -->
                   <v-col cols="12" sm="6" md="4">
                    <v-dialog
                      ref="dialog"
                      v-model="modal"
                      :return-value.sync="editedItem.date_expired"
                      :close-on-content-click="false"
                      width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          v-model="editedItem.date_expired"
                          label="Date expire at"
                          prepend-icon="mdi-calendar-range"
                          readonly
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker 
                       v-model="editedItem.date_expired"
                       scrollable
                       
                       >
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="modal = false">Cancel</v-btn>
                        <v-btn text color="primary" @click="$refs.dialog.save(editedItem.date_expired)">OK</v-btn>
                      </v-date-picker>
                    </v-dialog>
                  </v-col>
                 <!-- Buying Date end -->


                  <v-col cols="12" sm="6" md="8">
                    <v-textarea
                    v-model="editedItem.product_short_desc"
                      prepend-inner-icon="mdi-comment"
                      class="mx-2"
                      label="Daily Product Description"
                      rows="1"
                      :rules="[rules.required, rules.minName, rules.maxName]"
                    ></v-textarea>
                  </v-col>


                  <v-col cols="12" sm="6" md="4">
                    <v-text-field 
                    type="number"
                    v-model="editedItem.product_quantity" 
                    label="Daily product Quantity"
                    :rules="[rules.required]"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6" md="4">
                    <v-text-field 
                    type="text"
                    v-model="editedItem.product_unit" 
                    label="Daily product Unit"
                    :rules="[rules.required]"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" sm="6" md="4">
                    <v-btn
                     color="error"
                     raised
                     dark
                     @click ="pickImage"
                    > 
                    Upload Image                   
                    </v-btn>
                    <input
                     type="file"
                     style="display:none"
                     ref="fileInput"
                     accept="image/jpg, image/jpeg, image/png, image/bmp, image/svg"
                     @change="selectImage"
                    >
                  </v-col>

                  <v-col cols="12" sm="6" md="4">
                    <v-checkbox v-model="editedItem.product_status" label="Status"></v-checkbox>
                  </v-col>

                  <v-col cols="12" sm="6" md="4">
                    <v-color-picker v-model="editedItem.color"></v-color-picker>
                  </v-col>

                  <v-col cols="12" sm="6" md="4">
                    <v-img
                    :src="editedItem.product_image"
                    >
                    </v-img>
                  </v-col>

                </v-row>
              </v-container>
            </v-card-text>
            </v-form>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="save" :disabled="!valid">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.product_image="{ item }">
      <v-list-item-avatar>
        <v-img
        :src="`../storage/Dailyproducts/${item.product_image}`"
        >

        </v-img>
      </v-list-item-avatar>
    </template>

    <template v-slot:item.product_status="{ item }">
      <v-chip color="success" dark v-if="item.product_status == 1">Active</v-chip>
      <v-chip color="red" dark v-else>Deactive</v-chip>
    </template>

    <template v-slot:item.product_short_desc="{ item }">
      <p class="text-justify" v-if="item.product_short_desc.length<10">{{item.product_short_desc}}</p>
      <p class="text-justify" v-else>{{item.product_short_desc.substring(0, 10)+"..."}}</p>
    </template>

    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
        @click="deleteItem(item)"
      >
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize">Reset</v-btn>
    </template>
  </v-data-table>
 </v-card>
</template>




<script>
  export default {
    data: () => ({
      valid:false,
      dialog: false,
      menu: false,
      modal: false,
      sanckbar:false,
      snackText:'',
      user:[],
      categories:[],
      rules:{
        required: v => !!v|| 'The Field is Required',
        minName: v => (v && v.length >= 3) || 'Minimum 3 char required',
        maxName: v => (v && v.length <= 100) || 'Maximum Length 100 Character.',
      },
      headers: [
        {
          text: 'Name',
          align: 'start',
          sortable: false,
          value: 'product_name',
        },
        { text: 'Product Code', value: 'product_code' },
        { text: 'Buying Price', value: 'buying_price' },
        { text: 'Product Image', value: 'product_image' },
        { text: 'Product Buying Price', value: 'product_short_desc' },
        { text: 'Product Buying Price', value: 'buying_price' },
        { text: 'Product Selling Price', value: 'selling_price' },
        { text: 'Product Quantity', value: 'product_quantity' },
        { text: 'Status', value: 'product_status' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      desserts: [],
      editedIndex: -1,
      editedItem: {
        id:'',
        cat_id:'',
        product_name: '',
        product_code: '',
        product_short_desc: '',
        buying_price: '',
        selling_price: '',
        buying_date:new Date().toISOString().substr(0, 10),
        date_expired:new Date().toISOString().substr(0, 10),
        product_image:'',
        product_quantity:'',
        product_status:'',
        product_unit:'',
        color:'',
      },
      defaultItem: {
        id:'',
        cat_id:'',
        product_name: '',
        product_code: '',
        product_short_desc: '',
        buying_price: '',
        selling_price: '',
        buying_date:'',
        date_expired:'',
        product_image:'',
        product_quantity:'',
        product_status:'',
        product_unit:'',
        color:'',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      },
      // Auth User
      getUser(){
        return this.$store.getters.getUser
      },
      // Get All Category
      getCategory(){
        return this.$store.getters.getCategories
      },
      // Get Dailyproduct
      getDailyproduct(){
        return this.$store.getters.getDailyproduct
      }
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },
    mounted(){
      this.$store.dispatch('authUser');
      this.$store.dispatch('getAllCategory');
      this.initialize();
    
      
    },

    created () {
      
    },

    methods: {
     
      initialize () {
        this.$store.dispatch('getAllDailyProduct');
      },

      editItem (item) {
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.desserts.indexOf(item)
        confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        }, 300)
      },

      save () {
        if (this.editedIndex > -1) {
          Object.assign(this.desserts[this.editedIndex], this.editedItem)
        } else {
          axios.post('/api/dailyproduct',
              {
              'user':this.getUser.id,
              'image':this.editedItem.product_image,
              'name':this.editedItem.product_name,
              'code':this.editedItem.product_code,
              'desc':this.editedItem.product_short_desc,
              'buy_price':this.editedItem.buying_price,
              'sell_price':this.editedItem.selling_price,
              'buy_date':this.editedItem.buying_date,
              'exp_date':this.editedItem.date_expired,
              'quantity':this.editedItem.product_quantity,
              'unit':this.editedItem.product_unit,
              'status':this.editedItem.product_status,
              'category':this.editedItem.cat_id,
              'color':this.editedItem.color,
              
              }
         )
          .then(response=>{
           this.sanckbar = true
           this.snackText = 'Data insert success !'
           this.initialize()
          })
          .catch(e=>{
            console.log(e)
          })
        }
        this.close()
      },
  // Image section
      pickImage(){
        this.$refs.fileInput.click()
      },
      selectImage(event){
        let file = event.target.files[0];
        if(file.size > 5000000){
           this.$snotify.warning('Please ! Upload less than 5 MB', {
            timeout: 5000,
            showProgressBar: false,
            closeOnClick: false,
            pauseOnHover: true,
            
              });
        }else{
           let reader = new FileReader();
           reader.onload = event=>{
             this.editedItem.product_image = event.target.result
           }
           reader.readAsDataURL(file);
        }
      },
      // End Image section
    },
  }
</script>

<style type="text/css" scoped></style>