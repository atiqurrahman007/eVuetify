
import Login from './components/Auth/LoginComponent.vue';
import Register from './components/Auth/RegisterComponent.vue';

// Frontend
import Home from './components/Frontend/HomeComponent.vue';
import ProductDetails from './components/Frontend/ProductDetails.vue';

// Backend
import AdminHome from './components/Backend/DashboardComponent.vue';
import AdminProfile from './components/Backend/AdminProfile.vue';






export const routes = [


   {path:'/login', component:Login, name:'login'},
   {path:'/register', component:Register, name:'register'},

    {
    	path:'/',
    	component:Home,
    	name:'home',

    	children: [
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
          path: 'profile',
          component: AdminProfile,
          name:'Profile'
          
        },
        
      ]
    }



]