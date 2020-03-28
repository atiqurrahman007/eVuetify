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

		<template>
  <v-data-table
    :headers="headers"
    :items="brands"
    sort-by="calories"
    class="elevation-1"
    show-select
    @input="slectAll"
  >
  <template v-slot:item.brand_image="{ item }">
   <v-list-item-avatar>
     <v-img 
     :src="`../storage/Brand/${item.brand_image}`"
     alt="Image"
     v-if="item.brand_image != 'default.png'"
     >
     </v-img>
     <v-img 
     :src="`../storage/${item.brand_image}`"
     alt="Image"
     v-else
     >

     </v-img>
   </v-list-item-avatar>
  </template>
  <template v-slot:item.brand_status ="{ item }">
    <v-chip @click.stop.prevent="statusUpdate(item)" color="success" dark v-if="item.brand_status == 1">Active</v-chip>
    <v-chip @click.stop.prevent="statusUpdate(item)" color="red" dark v-else="">Deactive</v-chip>
  </template>

    <template v-slot:top>
      <v-toolbar flat color="amber">
        <v-toolbar-title>Brand Management Syatem</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ on }">
            <v-btn class="mx-2" mr-2 fab dark small color="pink"  @click="deleteAll">
              <v-icon dark>mdi-delete</v-icon>
            </v-btn>
            <v-btn color="teal" small fab dark class="mx-2" v-on="on">
              <v-icon dark>mdi-plus-circle-outline</v-icon>
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="12">
                    <v-text-field v-model="editedItem.brand_name"  prepend-icon="mdi-border-color" label="Brand name">

                    </v-text-field>
                  </v-col>
                  <v-col cols="12" sm="12">
                    <v-textarea v-model="editedItem.brnad_description" prepend-icon="mdi-vector-link" label="Brand Description">

                    </v-textarea>
                  </v-col>
                  <!-- Image upload -->
                  <v-col cols="12" sm="12">
                    <v-btn 
                    raised
                    class="error"
                    @click="onPickFile"
                    >
                    Upload Image
                    </v-btn>
                    <input 
                    type="file" 
                    style="display: none"
                    accept="image/jpg, image/jpeg, image/png, image/bmp, image/svg"
                    ref="fileInput"
                    @change="selectImg"
                    >
                  </v-col>
                  <!-- Image Upload End -->
                  <!-- Image Showing -->
                  <v-col cols="12" sm="12">
                    <v-img 
                    :src="editedItem.brand_image"
                    v-if="editedIndex == -1"
                    >
                    </v-img>
                    <v-img 
                    :src="showImage()"
                    v-else
                    >
                    </v-img>

                   
                  </v-col>
                  <!-- Image Showing end -->
                  <v-col cols="12" sm="12">
                    <v-checkbox v-model="editedItem.brand_status" label="Status"></v-checkbox>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="save">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
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
</template>
	</v-card>
</template>


<script>
  export default {
    data: () => ({
      dialog: false,
      sanckbar:false,
      snackText:'',
      slectall:[],
      headers: [
        { text: 'Brand Name', value: 'brand_name' },
        { text: 'Brand Description', value: 'brnad_description' },
        { text: 'Brand Image', value: 'brand_image' },
        { text: 'Status', value: 'brand_status' },
        { text: 'Created At', value: 'created_at' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      brands: [],
      editedIndex: -1,
      editedItem: {
        id: '',
        brand_name: '',
        brand_image:'',
        brnad_description: '',
        brand_status: '',
       
        
      },
      defaultItem: {
        id: '',
        brand_name: '',
        brand_image:'',
        brnad_description: '',
        brand_status: '',
       
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Brand' : 'Edit Brand'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    mounted(){
      this.initialize();
    },

    methods: {
      selectImg(event){
        
        let file = event.target.files[0];
				if (file.size > 5000000) {
                  this.$snotify.warning('Please ! Upload less than 5 MB', {
                  timeout: 5000,
                  showProgressBar: false,
                  closeOnClick: false,
                  pauseOnHover: true,
                  
                    });
                }else{
               
               let reader = new FileReader();
               reader.onload = event =>{
                 this.editedItem.brand_image = event.target.result
                 console.log(this.defaultItem.brand_image)
               	
               };
               reader.readAsDataURL(file);
               
              }
        
      },
      initialize () {
        axios.get('/api/brand')
        .then(response=>{
         this.brands = response.data.brands
        })
        .catch(e=>{
         console.log(e)
        })
      },

      editItem (item) {
        this.editedIndex = this.brands.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.brands.indexOf(item)
         this.$snotify.clear();
          this.$snotify.confirm('You are not able to recover your data!', 'Delete', {
            timeout: 5000,
            showProgressBar: false,
            closeOnClick: false,
            pauseOnHover: true,
            buttons: [
              {text: 'Yes', action: toast =>{
                this.$snotify.remove(toast.id);
                this.$Progress.start();
                axios.delete('/api/brand/'+ item.id)
                  .then(response => {
                      this.initialize();
                      this.$Progress.finish();
                      this.$snotify.success("Data delete success !", " Success");
                  })
                  .catch(e =>{
                      this.$Progress.fail();
                      this.$snotify.warning("Something Happend Worng !", " Failed");
                      console.log(e);
                  })

              },bold: true},
              
              {text: 'No', action: toast => {this.$snotify.remove(toast.id); }, bold: true},
                ]
              });
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
          const index = this.editedIndex
          this.$Progress.start()
          axios.put('/api/brand/'+ this.editedItem.id, this.editedItem)
          .then(response=>{
            this.$Progress.finish()
            this.sanckbar = true
            this.snackText = "Data update success !"
            Object.assign(this.brands[index], response.data)
            console.log(response.data)
          })
          .catch(e=>{
            this.$snotify.warning('Failed ! Data Update Failed ', {
                  timeout: 5000,
                  showProgressBar: false,
                  closeOnClick: false,
                  pauseOnHover: true,
                  
                    });
          })
        } else {
          
          axios.post('/api/brand', this.editedItem)
          .then(response=>{
            
            this.initialize();
            this.sanckbar = true
            this.snackText = "Brand Save successfully !"
          })
          .catch(e=>{
            console.log(e)
          })
          
        }
        this.close()
      },
      onPickFile(){
        this.$refs.fileInput.click()
      },
      showImage(){
        let image = this.editedItem.brand_image
				if (image.length >250) {
					return this.editedItem.brand_image
				}else if(this.editedItem.brand_image == 'default.png'){
          return `../storage/${this.editedItem.brand_image}`
        }
        else{
					return `../storage/Brand/${this.editedItem.brand_image}`;
				} 
      },
      statusUpdate(item){
        axios.put('/api/brand/statusupdate/'+ item.id, item.brand_status)
        .then(response =>{
         this.initialize();
         this.sanckbar = true
         this.snackText = "Brand Status Update successfully !"
        })
        .catch(e=>{
         console.log(e)
        })
      },
      slectAll(e){
       this.slectall = []
       if(e.length > 0){
         this.slectall = e.map(val=>val.id)
       }
      },
      deleteAll(){
             
            this.$snotify.confirm('You are not able to recover your data!', 'Delete All ?', {
            timeout: 5000,
            showProgressBar: false,
            closeOnClick: false,
            pauseOnHover: true,
            buttons: [
              {text: 'Yes', action: toast =>{
                this.$snotify.remove(toast.id);
                this.$Progress.start();
                axios.post('/api/brand/deleteall', {'brands': this.slectall})
                .then(response=>{
                  this.initialize();
                  this.sanckbar = true
                  this.snackText = "Caterories Delete success !"
                })
                .catch(e=>{
                  console.log(e)
                })

              },bold: true},
              
              {text: 'No', action: toast => {this.$snotify.remove(toast.id); }, bold: true},
                ]
              });


        
      }
    },
  }
</script>