


let cart = window.localStorage.getItem('cart');


export default{
    state:{
        snackText: 'Data update Success',
        user:[],
        categories:[],
        dailyproducts:[],
        // Cart Function
        carts:cart? JSON.parse(cart): [],
    },
    getters:{
      getSnackText(state){
          return state.snackText
      },
      getUser(state){
        return state.user
      },
      getCategories(state){
        return state.categories
      },
      getDailyproduct(state){
        return state.dailyproducts
      },
      // Cart Function
      getCarts(state){
        return state.carts
      },
      cartItemCount(state){
        return state.carts.length
      },
      subTotal(state){
        let sum = 0;
        state.carts.forEach(item=>{
          sum += parseFloat( item.proQuantity * item.proPrice);
        });
        return sum;
      },
      totalQuantity(state){
        let sum = 0;
        state.carts.forEach(item=>{
          sum += item.proQuantity
        });
        return sum;
      }
    },
    actions:{
      // Get Auth User
      authUser(context){
        axios.get('/api/auth/user')
        .then(response=>{
          context.commit('getAuthUser', response.data.user)
        })
        .catch(e=>{
          console.log(e)
        })
      },
      // Get All Category
      getAllCategory(context){
        axios.get('/api/category')
        .then(response=>{
          context.commit('allCategory', response.data.categories)
        })
        .catch(e=>{
          console.log(e)
        })
      },
      // Get All Dailyproduct
      getAllDailyProduct(context){
        axios.get('/api/dailyproduct')
        .then(response=>{
          context.commit('allDailyproduct', response.data.dailyproduct)
        })
        .catch(e=>{
          console.log(e)
        })
      },

      // Cart Function
      addProductToCart(context, {product, quantity}){
       
        context.commit('ADD_TO_CART', 
        {proId:product.id, 
        proPrice:product.selling_price,
        proImage:product.product_image,
        proQuantity:quantity}
        );
        this.commit('setData');
        
      }
    },
    mutations:{
     getAuthUser(state, payload){
         return state.user = payload
     },
     allCategory(state, payload){
      return state.categories = payload
     },
    //  Dailyproduct
    allDailyproduct(state, payload){
      return state.dailyproducts = payload
    },
    // Cart Function
    ADD_TO_CART(state, payload){
      let productInCart = state.carts.find(item =>{
        return item.proId === payload.proId;
      });
      if(productInCart){
        productInCart.proQuantity ++;
        return;
      }else{
        state.carts.push( payload)
      }
      
    },
    setData(state){
      window.localStorage.setItem('cart', JSON.stringify(state.carts))
    }


    },


}