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
    :loading="loading"
    :headers="headers"
    :items="users"
    sort-by="name"
    class="elevation-1"
    show-select
    @input="selectAll"
  >
  <!-- Role Section Start-->
   <template v-slot:item.role="{ item }">
      
      <v-edit-dialog
       large
       block
       persistent
       @save="updateRole(item)"
      >
        <v-chip :color="getColor(item.role.id)" dark>{{ item.role.name }}</v-chip>
        <template v-slot:input>
          <h2>Change Role</h2>
        </template>
        <template v-slot:input>
          <v-select
            label="---Select Role---"          
            :items='roles' 
            v-model="editedItem.role_id"
            item-text="name"
            item-value="id"
            :rules="[rules.required]"
            > 
          </v-select>
        </template>
      </v-edit-dialog>
    </template>
    <!-- Role Section End -->
    <!-- Image Section Start-->
    <template v-slot:item.profile="{ item }">
      <v-edit-dialog>
           <v-list-item-avatar >
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
            <template v-slot:input>
              <v-file-input
               label="Select File"
               v-model="editedItem.photo"
               placeholder="Upload image"
               accept="image/png, image/jpeg, image/jpg, image/svg, image/bmp"
               
               @change="uploadImage(item)"
              />
            </template>
      </v-edit-dialog>
    </template>
 <!-- Image Section End-->
    <template v-slot:top>
      <v-toolbar flat color="amber">
        <v-toolbar-title>User Management System</v-toolbar-title>
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
           <v-form 
           v-model="valid"
           method="post"
           v-on:submit.stop.prevent="save"


           >
            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12" sm="12" >
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
                  <v-col cols="12" sm="12" >
                    <v-select
                      
                      :items='roles' 
                      v-model="editedItem.role_id"
                      item-text="name"
                      item-value="id"
                      :rules="[rules.required]"
                      >
                    	
                    </v-select>
                  </v-col>
                  
                </v-row>
              </v-container>
            </v-card-text>
            </v-form>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
              <v-btn color="blue darken-1" type="submit" :disabled="!valid" @click.prevent="save">Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.action="{ item }">
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
      snackbar:false,
      snackText:'',
      loading:true,
      valid:true,
      success:'',
      error:'',
      rules:{
        required: v => !!v || 'The field is required',
        minName: v => (v && v.length >= 3) || 'Minimum 3 char required',
        minPass: v => (v && v.length >= 8) || 'Minimum 8 char required',
        emailValid: v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      },
      selected:{},
      headers: [
        
        {
          text: 'User Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Email', value: 'email' },
        { text: 'Status', value: 'role' },
        { text: 'Image', value: 'profile' },
        { text: 'Created_at', value: 'created_at' },
        { text: 'Updated_at', value: 'updated_at' },
        { text: 'Actions', value: 'action', sortable: false },
      ],
      users: [],
      roles:[],
      roleSelect:'',

      editedIndex: -1,
      editedItem: {
        id:'',
        name: '',
        email: '',
        role_id: '',
        password:'',
        repassword:'',
        profile:'',
        photo:[],

      },
      defaultItem: {
        name: '',
        email: '',
        role: '',
        profile:'',
        photo:''
        
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Add New User' : 'Edit User'
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
      this.initialize();
      this.getRole();

    },

    methods: {
      uploadImage(item){
        if (this.editedItem.photo != null) {
        console.log(this.editedItem.photo)
        let formData = new FormData();
        formData.append('photo', this.editedItem.photo, this.editedItem.photo.name)
        formData.append('id', item.id)
        formData.append('user', item.name)
        axios.post('/api/users/profile', formData)
        .then(response=>{
           this.initialize()
            this.snackbar = true
            this.snackText = "Image save successfully !"
            this.editedItem.photo = null
        })
        .catch(e=>{
          console.log(e)
        })
        
        }
      },
      updateRole(item){
        axios.put('/api/users/role/'+ item.id, {'role':this.editedItem.role_id })
        .then(response=>{
          this.snackbar = true
          this.snackText = "Update role success !"
          this.initialize()
        })
        .catch(e=>{
          console.log(e)
        })
      },
      selectAll(e){
        this.selected = [];
        if (e.length>0) {
          this.selected = e.map(val=> val.id)
        }
        console.dir(this.selected)
      },

      initialize () {
        axios.get('/api/users')
        .then(response=>{
        	
        	this.users = response.data
          this.loading = false
        	console.log(this.users)
        })
        .catch(e=>{
        	console.log(e)
        })
      },

      editItem (item) {
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        console.log(this.editedItem)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.users.indexOf(item)
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
                axios.delete('/api/users/'+item.id)
                  .then(response => {
                      this.initialize();
                      this.$Progress.finish();
                      this.snackbar = true
                      this.snackText = "Data delete success !"
                      this.$snotify.success("Data delete success !", " Success");
                  })
                  .catch(e =>{
                      this.$Progress.fail();
                      this.$snotify.warning("Something Happend Worng !", "Delete Failed !");
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
          axios.put('/api/users/'+ this.editedItem.id, this.editedItem)
          .then(response=>{
            this.initialize()
            Object.assign(this.users[index], response.data)
            this.snackbar = true
            this.snackText = "Data update successfully !"
          })
          .catch(e=>{
            console.log(e)
          })
          
        } else {
          axios.post('/api/users' , this.editedItem)
          .then(response=>{
            this.initialize()
            this.snackbar = true
            this.snackText = "Data save successfully !"
          })
          .catch(e=>{
            console.log(e)
          })
          
        }
        this.close()
      },
      getRole(){
      	axios.get('/api/role')
      	.then(response=>{
      		this.roles = response.data
      		console.log(this.roles)
      		

      	})
      	.catch(e=>{
      		console.log(e)
      	})
      },
      getColor (calories) {
        if (calories == 1) return 'red'
        else if (calories == 2) return 'orange'
        else return 'green'
      },
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