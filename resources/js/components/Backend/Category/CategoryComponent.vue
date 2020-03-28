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
    :items="categories"
    sort-by="calories"
    class="elevation-1"
    show-select
    @input="slectAll"
  >
  <template v-slot:item.cat_image ="{ item }">
   <v-list-item-avatar>
     <v-img 
     :src="`../storage/Category/${item.cat_image}`"
     >

     </v-img>
   </v-list-item-avatar>
  </template>
  <template v-slot:item.cat_status ="{ item }">
    <v-chip @click.stop.prevent="statusUpdate(item)" color="success" dark v-if="item.cat_status == 1">Active</v-chip>
    <v-chip @click.stop.prevent="statusUpdate(item)" color="red" dark v-else="">Deactive</v-chip>
  </template>

    <template v-slot:top>
      <v-toolbar flat color="amber">
        <v-toolbar-title>Category Management Syatem</v-toolbar-title>
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
                    <v-text-field v-model="editedItem.cat_name"  prepend-icon="mdi-border-color" label="Category name">

                    </v-text-field>
                  </v-col>
                  <v-col cols="12" sm="12">
                    <v-text-field v-model="editedItem.cat_link" prepend-icon="mdi-vector-link" label="Category link">

                    </v-text-field>
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
                    :src="editedItem.cat_image"
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
                    <v-checkbox v-model="editedItem.cat_status" label="Status"></v-checkbox>
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
        { text: 'Category Name', value: 'cat_name' },
        { text: 'Category Image', value: 'cat_image' },
        { text: 'Category Link', value: 'cat_link' },
        { text: 'Created At', value: 'created_at' },
        { text: 'Status', value: 'cat_status' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      categories: [],
      editedIndex: -1,
      editedItem: {
        id: '',
        cat_name: '',
        cat_image:'',
        cat_link: '',
        cat_status: '',
        image:null,
        
      },
      defaultItem: {
        id: '',
        cat_name: '',
        cat_image:'',
        cat_link: '',
        cat_status: '',
        image:null,
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Category' : 'Edit Category'
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
                 this.editedItem.cat_image = event.target.result
                 console.log(this.defaultItem.cat_image)
               	
               };
               reader.readAsDataURL(file);
               this.editedItem.image = file
               console.log(this.editedItem.image)
              }
        
      },
      initialize () {
        axios.get('/api/category')
        .then(response=>{
         this.categories = response.data.categories
        })
        .catch(e=>{
         console.log(e)
        })
      },

      editItem (item) {
        this.editedIndex = this.categories.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.categories.indexOf(item)
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
                axios.delete('/api/category/'+ item.id)
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
          axios.put('/api/category/'+ this.editedItem.id, this.editedItem)
          .then(response=>{
            this.sanckbar = true
            this.snackText = "Data update success !"
            Object.assign(this.categories[index], response.data)
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
          if(this.editedItem.image!= null){
            let formData = new FormData();
            formData.append('image', this.editedItem.image, this.editedItem.image.name)
            formData.append('name', this.editedItem.cat_name)
            formData.append('link', this.editedItem.cat_link)
            formData.append('status', this.editedItem.cat_status)
          axios.post('/api/category', formData)
          .then(response=>{
            console.log(this.editedItem)
            this.initialize();
            this.editedItem.image = null
          })
          .catch(e=>{
            console.log(e)
          })
          }else{
              this.$snotify.warning('Please ! Upload an Image', {
              timeout: 5000,
              showProgressBar: false,
              closeOnClick: false,
              pauseOnHover: true,
              
                });
          }
        }
        this.close()
      },
      onPickFile(){
        this.$refs.fileInput.click()
      },
      showImage(){
        let image = this.editedItem.cat_image
				if (image.length >250) {
					return this.editedItem.cat_image
				}else{
					return `../storage/Category/${this.editedItem.cat_image}`;
				} 
      },
      statusUpdate(item){
        axios.put('/api/category/statusupdate/'+ item.id, item.cat_status)
        .then(response =>{
         this.initialize();
         this.sanckbar = true
         this.snackText = "Category Status Update successfully !"
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
       console.dir(this.slectall)
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
                axios.post('/api/category/deleteall', {'categories': this.slectall})
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