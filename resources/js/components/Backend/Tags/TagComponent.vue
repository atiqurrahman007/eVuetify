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
    :search="search"
    :headers="headers"
    :items="tags"
    :itemsPerPage=5
    sort-by="name"
    class="elevation-1"
    show-select
    @input="selectAll"
    :footer-props="{
    itemsPerPageOptions:[5,10,15],
      showFirstLastPage: true,
      firstIcon: 'mdi-arrow-collapse-left',
      lastIcon: 'mdi-arrow-collapse-right',
      prevIcon: 'mdi-minus',
      nextIcon: 'mdi-plus',
      'items-per-page-text':'Tags per page'
  }"

  > 
   <template v-slot:item.status="{ item }">
   <v-chip @click="Active(item)" color="red" dark v-if="item.status == 0">Dactive</v-chip>
   <v-chip @click="Active(item)" color="success" dark v-else="">Active</v-chip>
  </template>

    <template v-slot:top>
      <v-toolbar flat color="amber" >
        <v-toolbar-title>Tag Management System</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <!-- Search -->
        <v-text-field
        v-model="search"

        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
        color="white"
      ></v-text-field>
      <!-- Search end -->
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
                  <v-col cols="12" sm="12" >
                    <v-text-field v-model="editedItem.name" label="Tag name"></v-text-field>
                  </v-col>
                  <v-checkbox
                    v-model="editedItem.status"
                    label="Status"
                  ></v-checkbox>
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
      loading:true,
      snackbar: false,
      snackText: '',
      search:'',
      selected:{},
      headers: [
        
        { text: 'Name', value: 'name' },
        { text: 'Created at', value: 'created_at' },
        { text: 'Updated at', value: 'updated_at' },
        { text: 'Status', value: 'status' },
        { text: 'Actions', value: 'action', sortable: false },
      ],
      tags: [],
      editedIndex: -1,
      editedItem: {
        id:'',
        name: '',
        status:''
        
      },
      defaultItem: {
        name: '',
        
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Add Tag' : 'Edit Tag'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    mounted () {
      this.initialize();
     
    },

    methods: {
      selectAll(e){
       this.selected = [];
       if (e.length>0) {
        this.selected = e.map(val=>val.id)
       }
       console.dir(this.selected)
      },
      deleteAll(){
        axios.post('/api/tag/multidelete', {'tags':this.selected})
        .then(response=>{
          this.snackbar = true
          this.snackText = "Data's delete success !"
          this.initialize()
        })
        .catch(e=>{
          console.log(e)
        })
      },
      initialize () {
        axios.get('/api/tag')
        .then(response=>{
          this.tags = response.data.tag
          this.loading = false
          
        })
        .catch(e=>{
          console.log(e)
        })
      },

      editItem (item) {
        this.editedIndex = this.tags.indexOf(item)
        
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.tags.indexOf(item)
        axios.delete('/api/tag/'+item.id)
        .then(response=>{
          this.snackbar = true
          this.snackText = "Data delete success !"
          this.initialize()
        })
        .catch(e=>{
          console.log('error')
        })
        
        
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
          axios.put('/api/tag/'+ this.editedItem.id, this.editedItem)
          .then(response=>{
            Object.assign(this.tags[index], response.data.tag)
            this.snackbar = true
            this.snackText = "Data update success !"
           
          })
          .catch(e=>{
            console.log(e)
          })
          
        } else {
          
          axios.post('/api/tag',this.editedItem)
          .then(response=>{
            this.tags.push(response.data.tag)
           this.snackbar = true
            this.snackText = "Data save successfully !"
           
           
          })
          .catch(e=>{
            console.log('error')
          })
        }
        this.close()
      },
      Active(activeId){
        axios.get('/api/tag/status/'+activeId.id)
        .then(response=>{
          this.snackbar = true
          this.snackText = "Status update success !"
          this.initialize()
        })
        .catch(e=>{
          console.log(e)
        })
      }
    },
  }
</script>