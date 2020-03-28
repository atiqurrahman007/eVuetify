
import Login from './components/Auth/LoginComponent.vue';
import Register from './components/Auth/RegisterComponent.vue';

// Frontend
import Home from './components/Frontend/HomeComponent.vue';
import ProductDetails from './components/Frontend/ProductDetails.vue';

// DailyBazar(Frontend)
import DailyBazarHome from './components/Frontend/DailyBazar/DailybazarHomeComponent.vue';

// Backend
import AdminHome from './components/Backend/DashboardComponent.vue';
import Administration from './components/Backend/AdministrationComponent.vue';
import Role from './components/Backend/RoleComponent.vue';
import AdminUsers from './components/Backend/UsersComponent.vue';
import Tags from './components/Backend/Tags/TagComponent.vue';
import Category from './components/Backend/Category/CategoryComponent.vue';
import Brand from './components/Backend/Brand/BrandComponent.vue';


// DailyProduct
import DailyProduct from './components/Backend/Dailyproduct/DailyproductComponent.vue';






export const routes = [


   {path:'/login', component:Login, name:'login'},
   {path:'/register', component:Register, name:'register'},

    {
    	path:'/',
    	component:Home,
    	name:'home',

    	children: [
        {
           path:'dailybazar',
           component:DailyBazarHome,
           name:'dailybazar',
        },
        {
          path: 'product-details',
          component: ProductDetails,
          name:'product-details',
          beforeEnter: (to, from, next) => {
	    	 let token =  localStorage.getItem('token');
	    	 if (token) {
	    	 	next()
	    	 }else{
	    	 	next('/login')
	    	 }
	        
	        },
        },
        
      ]
    },


    {
    	path:'/admin',
    	component:AdminHome,
    	name:'admin',
    	beforeEnter: (to, from, next) => {
    	 let token =  localStorage.getItem('token');
       let roleId = localStorage.getItem('roleId');
    	 if (token ) {
        if (roleId == 1 || roleId == 2) {
          next()
        }else{
          next('/')
        }
    	 	
    	 }else{
    	 	next('/login')
    	 }
        
        },

    	children: [
        {
          path: 'users',
          component: AdminUsers,
          name:'users'
          
        },

        {path:'administration', component:Administration, name:'administration'},
        {path:'roles', component:Role, name:'roles'},
        {path:'tag', component:Tags, name:'tag'},
        {path:'category', component:Category, name:'category'},
        {path:'brand', component:Brand, name:'brand'},

        // Dailyproduct
        {path:'dailyproduct', component:DailyProduct, name:'dailyproduct'},
        
      ]
    }



]

