<template>
<v-card>
  <v-snackbar
      v-model="snackbar"
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
    :items="administration"
    sort-by="calories"
    class="elevation-1"
  >

    <template v-slot:top>
      <v-toolbar flat color="deep-orange">
        <v-toolbar-title>Administration Management System</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ on }">
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
                    <v-text-field 
                    v-model="editedItem.name"
                    label="User name"
                    type="text"
                    prepend-icon="mdi-account"
                    :rules="[rules.required, rules.minName]"
                    >
                      
                    </v-text-field>
                  </v-col>
                 </v-row>

                 <v-row v-if="editedIndex == -1"> 
                  <v-col cols="12" sm="12" >
                    <v-text-field
                       v-model="editedItem.email"
                       label="User email"
                       type="email"
                       prepend-icon="mdi-email"
                       :rules="[rules.required,rules.emailValid]"
                       :success-messages="success"
                       :error-messages="error"
                       @blur="checkEmail"
                       >
                         
                       </v-text-field>
                  </v-col>
                  <v-col cols="12" sm="12" >
                    <v-text-field
                        v-model="editedItem.password"
                        label="Password"
                        name="password"
                        prepend-icon="mdi-lock"
                        type="password"
                        :rules="[rules.required,rules.minPass]"
                        
                       >
                         
                       </v-text-field>
                  </v-col>

                  <v-col cols="12" sm="12" >
                    <v-text-field
                        v-model="editedItem.repassword"
                        label="Password confirm"
                        name="password"
                        prepend-icon="mdi-lock"
                        type="password"
                        :rules="[rules.required,rules.minPass, passwordMatch]"
                       
                       >
                         
                       </v-text-field>
                  </v-col>
                 </v-row> 

                 <v-row> 
                  <v-col cols="12" sm="12">
                    <v-select
                    label="--Select Role (optional)--"
                    :items = "administration"
                      v-model="editedItem.selectRole"
                      item-text="role.name"
                      item-value="role.id"
                      :rules="[rules.required]"
                    >

                    </v-select>
                  </v-col>
                  <v-col cols="12" sm="12">
                    <v-btn 
                     color="error"
                     raised
                     dark
                     @click ="pickImage"
                    >
                    Upload An Image
                    </v-btn>
                    <input
                     type="file"
                     style="display:none"
                     ref="fileInput"
                     accept="image/jpg, image/jpeg, image/png, image/bmp, image/svg"
                     @change="selectImage"
                    >
                  </v-col>
                  <v-col cols="12" sm="12">
                    <!-- Add Item -->
                    <v-img 
                    :src="editedItem.image"
                    v-if="editedIndex == -1"
                    >
                    </v-img>
                    <!-- Edite Item -->
                   <v-img
                   :src="ShowImage()"
                   v-if="editedIndex > -1"
                   >
                  </v-img>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
              <v-btn color="blue darken-1" text type="submit" :disabled="!valid" @click.prevent="save">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <!-- Role template Start -->
    <template v-slot:item.role="{ item }">
    <v-chip :color="colorByRole(item.role.id)" dark>
        {{item.role.name}}
    </v-chip>
    </template>
    <!-- Role template End -->
    <!-- Image Template Start -->
    <template v-slot:item.profile="{ item }">
       <v-list-item-avatar>
         <img
          :src="`../storage/Users/${item.profile.profile_image}`"
          alt="Image"
          v-if="item.profile.profile_image != 'default.png'"
         >
         <img
          :src="`../storage/${item.profile.profile_image}`"
          alt="Image"
          v-else
         >
       </v-list-item-avatar>

    </template>
    <!-- Image Template end -->
    
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
      dialog: false,
      valid:true,
      snackbar:false,
      snackText:'',
      success:'',
      error:'',
      rules:{
        required: v => !!v || 'The field is required !',
        minName: v => (v && v.length >= 3) || 'Minimum 3 char required !',
        minPass: v => (v && v.length >= 8) || 'Minimum 8 char required !',
        emailValid: v => /.+@.+\..+/.test(v) || 'E-mail must be valid !',

      },
      headers: [
        {
          text: 'User Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Email', value: 'email' },
        { text: 'Satus', value: 'role' },
        { text: 'Image', value: 'profile' },
        { text: 'Joining Date', value: 'created_at' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      administration: [],
      editedIndex: -1,
      editedItem: {
        id:'',
        name: '',
        role: '',
        profile: '',
        image:'',
        selectRole:'',
        email:'',
        repassword:'',
      },
      defaultItem: {
        id:'',
        name: '',
        role: '',
        profile: '',
        image:'',
        selectRole:'',
         email:'',
        repassword:'',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      },

      passwordMatch(){
        return this.editedItem.password != this.editedItem.repassword ? 'Password Does Not Match' : true
      }
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    mounted () {
      this.initialize()
    },

    methods: {
      initialize () {
        this.$Progress.start();
        axios.get('/api/administration')
        .then(response=>{
            this.administration = response.data
            this.$Progress.finish();
        })
        .catch(e=>{
            console.log(e)
        })
      },

      editItem (item) {
        this.editedIndex = this.administration.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.administration.indexOf(item)
         this.$snotify.clear();
          this.$snotify.confirm('You are not able to recover your data!', 'Delete It !', {
            timeout: 5000,
            showProgressBar: false,
            closeOnClick: false,
            pauseOnHover: true,
            buttons: [
              {text: 'Yes', action: toast =>{
                this.$snotify.remove(toast.id);
                this.$Progress.start();
                axios.delete('/api/administration/'+ item.id)
                  .then(response => {
                      this.initialize();
                      this.$Progress.finish();
                      this.$snotify.success("Data delete success !", " Success");
                  })
                  .catch(e =>{
                      this.$Progress.fail();
                      this.$snotify.warning("Something Happend Worng !", " Failed !");
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
          const index = this.editedItem
          axios.put('/api/administration/'+ this.editedItem.id , this.editedItem)
          .then(response=>{
            this.initialize()
            this.snackbar = true
            this.snackText = " Data Update Success !"
          })
          .catch(e=>{
            console.log(e)
          })
          
        } else {
          axios.post('/api/administration', this.editedItem)
          .then(response=>{
            this.initialize()
            this.snackbar = true
            this.snackText = " Data Save Success !"
          })
          .catch(e=>{
            this.$snotify.warning('Failed ! Something happend worng !', {
            timeout: 5000,
            showProgressBar: false,
            closeOnClick: false,
            pauseOnHover: true,
            
              });
            console.log(e)
          })
          
        }
        this.close()
      },
      colorByRole(role){
        if(role == 1){
          return 'red'
        }else{
          return 'orange'
        }
      },
      // Image Functionality
      ShowImage(){
        let image = this.editedItem.profile
				if (image.length >250) {
					return this.editedItem.profile
        }else if(this.editedItem.profile.profile_image == 'default.png'){
          return `../storage/${this.editedItem.profile.profile_image}`
        }
        else{
					return `../storage/Users/${this.editedItem.profile.profile_image}`;
				} 
      },
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
          reader.onload = event =>{
            this.editedItem.profile = event.target.result
            this.editedItem.image = event.target.result
          } 
          reader.readAsDataURL(file);

        }
      },
      // Email Validation Check
      checkEmail(){
      if (/.+@.+\..+/.test(this.editedItem.email)) {
       axios.post('/api/email/verify', {'email':this.editedItem.email})
       .then(response=>{
        console.log(response.data)
        this.success = response.data.message
        this.error = '';
       })
       .catch(e=>{
        this.success = '';
        this.error = 'Email Already Exist'
       })
      }
    }
    },
  }
</script>