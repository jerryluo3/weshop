/**
 * Created by Administrator on 2018/7/17 0017.
 */
var utils = require('../../utils/util')
 class Customer{
    constructor(){
        this.name = '',
        this.age = 0,
        this.uid = undefined,//用户唯一id,
        this.phoneNumber = undefined,
        this.productArray = [
            // { mp_title: '农夫水溶C100柠檬水',mp_stocks:5,mp_price:4.80,mp_picture:"/assets/upload/1529411148_thumb.jpg",icon:'',number:0,mp_id:0,mp_cid:0},
            // { mp_title: '康师傅茉莉蜜茶',mp_stocks:3,mp_price:2.90,mp_picture:"/assets/upload/1529574112_thumb.jpg",icon:'',number:0,mp_id:1,mp_cid:0},
            // { mp_title: '统一阿萨姆奶茶焙煎绿茶',mp_stocks:1,mp_price:4.50,mp_picture:"/assets/upload/1530085819_thumb.jpg",icon:'',number:0,mp_id:2,mp_cid:3},
            // { mp_title: '非你杯茶港式丝袜奶茶',mp_stocks:3,mp_price:5.50,mp_picture:"/assets/upload/1515117212_thumb.jpg",icon:'',number:0,mp_id:3,mp_cid:3},
            // { mp_title: '香飘飘Meco牛乳茶',mp_stocks:3,mp_price:2.90,mp_picture:"/assets/upload/1515131695_thumb.jpg",icon:'',number:0,mp_id:4,mp_cid:2},
            // { mp_title: '乐虎氨基酸功能饮料',mp_stocks:6,mp_price:5.50,mp_picture:"/assets/upload/1529574145_thumb.jpg",icon:'',number:0,mp_id:5,mp_cid:4},
            // { mp_title: '康师傅冰红茶柠檬味',mp_stocks:4,mp_price:2.90,mp_picture:"/assets/upload/1529574122_thumb.jpg",icon:'',number:0,mp_id:6,mp_cid:5},
            // { mp_title: '酷儿橙汁饮料',mp_stocks:5,mp_price:3.20,mp_picture:"/assets/upload/1529574112_thumb.jpg",icon:'',number:0,mp_id:7,mp_cid:6},
        ],
        this.productObject = {
            // 0:{ mp_title: '农夫水溶C100柠檬水',mp_stocks:5,mp_price:4.80,mp_picture:"/assets/upload/1529411148_thumb.jpg",icon:'',number:0,mp_id:0,mp_cid:0},
            // 1:{ mp_title: '康师傅茉莉蜜茶',mp_stocks:3,mp_price:2.90,mp_picture:"/assets/upload/1529574112_thumb.jpg",icon:'',number:0,mp_id:1,mp_cid:0},
            // 2:{ mp_title: '统一阿萨姆奶茶焙煎绿茶',mp_stocks:1,mp_price:4.50,mp_picture:"/assets/upload/1530085819_thumb.jpg",icon:'',number:0,mp_id:2,mp_cid:3},
            // 3:{ mp_title: '非你杯茶港式丝袜奶茶',mp_stocks:3,mp_price:5.50,mp_picture:"/assets/upload/1515117212_thumb.jpg",icon:'',number:0,mp_id:3,mp_cid:3},
            // 4:{ mp_title: '香飘飘Meco牛乳茶',mp_stocks:3,mp_price:2.90,mp_picture:"/assets/upload/1515131695_thumb.jpg",icon:'',number:0,mp_id:4,mp_cid:2},
            // 5:{ mp_title: '乐虎氨基酸功能饮料',mp_stocks:6,mp_price:5.50,mp_picture:"/assets/upload/1529574145_thumb.jpg",icon:'',number:0,mp_id:5,mp_cid:4},
            // 6:{ mp_title: '康师傅冰红茶柠檬味',mp_stocks:4,mp_price:2.90,mp_picture:"/assets/upload/1529574122_thumb.jpg",icon:'',number:0,mp_id:6,mp_cid:5},
            // 7:{ mp_title: '酷儿橙汁饮料',mp_stocks:5,mp_price:3.20,mp_picture:"/assets/upload/1529574112_thumb.jpg",icon:'',number:0,mp_id:7,mp_cid:6},
        },
        this.totalNumber = 0,
        this.totalPrize = 0,
        //购物车清单,发送到后台生成订单
        this.cart_list = {
            //0:1               //mp_id : number
        },
        this.upload_info_arr = []
    }

    init(){
        //数组转obj
        this.productObject = utils.ArrayExtractToObjectWithKey( this.productArray,'mp_id' );
        this.updateCartList();//更新要上传的购物车清单
    }
    //用户只能操作object,进而更新数组，刷新视图
    addOne( mp_id ){
        if( typeof this.productObject[ mp_id ].number !='number'){
            this.productObject[ mp_id ].number = 0;
        }
      
      if (this.productObject[mp_id].number == this.productObject[mp_id].mp_stocks ){
        return false
      }
        this.productObject[ mp_id ].number += 1

        //加到购物车列表
        this.cart_list[ mp_id ] = this.productObject[ mp_id ].number
        this.updateProductArray()
        this.totalNumber+=1;

        this.totalPrize = (this.totalPrize * 10000 + this.productObject[ mp_id ].mp_price * 10000 ) / 10000

        return true

    }
    subOne( mp_id ){

        this.productObject[ mp_id ].number -= 1
        //加到购物车列表
        this.cart_list[ mp_id ] = this.productObject[ mp_id ].number
        this.updateProductArray()
        this.totalNumber-=1;

        this.totalPrize = (this.totalPrize * 10000 - this.productObject[ mp_id ].mp_price * 10000 ) / 10000
        return true

    }
    updateProductArray(){
        this.productArray = utils.ObjectToArray( this.productObject )
    }
    updateProductObject(){
        this.productObject = utils.ArrayExtractToObjectWithKey( this.productArray,'mp_id' );
    }
    updateCartList(){
        this.productArray.forEach(( value,index )=>{
            if( value.number > 0 ){
                var mp_id = value.mp_id
                this.cart_list[ mp_id ] = value.number
            }
        })
    }
    clearCartList(){
        let list = this.cart_list
        for(var key in list){
            console.log(key)
            this.productObject[ key ].number = 0
        }
        this.cart_list = {}
        this.totalNumber = 0
        this.totalPrize = 0
        this.updateProductArray()
    }
    SyncProductListWithCart(){
        var cart_list = this.cart_list

        for( var key in cart_list ){
            if(this.productObject.hasOwnProperty(key)){
                this.productObject[ key ].number = cart_list[ key ]
            }
        }
        this.updateProductArray()
    }
}

class CustomerSearch extends Customer{
    constructor(){
        super()
    }
}

export default Customer
// export CustomerSearch