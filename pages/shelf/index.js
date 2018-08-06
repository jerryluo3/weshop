//index.js
//获取应用实例
const app = getApp()
const api = app.globalData.api
let router= app.globalData.router
const utils = app.globalData.utils
import Customer,{Search} from './customer.js'
import CustomerSearch from './customer.js'
const domain = app.globalData.DOMAIN
const outUrl = '/pages/outurl/outurl'
const USERINFO_BUFFER_TIME = 20000//用户信息
const AUTHORIZE_BUFFER_TIME = 20000//授权缓存时间

// import api from '/utils/api.js'

//后端拉取的原始数据,后期不再修改
var origin_productList = [
    { mp_title: '农夫水溶C100柠檬水',mp_stocks:5,mp_price:4.80,mp_picture:"/assets/upload/1529411148_thumb.jpg",icon:'',number:0,mp_id:0,mp_cid:0},
    { mp_title: '康师傅茉莉蜜茶',mp_stocks:3,mp_price:2.90,mp_picture:"/assets/upload/1529574112_thumb.jpg",icon:'',number:0,mp_id:1,mp_cid:0},
    { mp_title: '统一阿萨姆奶茶焙煎绿茶',mp_stocks:1,mp_price:4.50,mp_picture:"/assets/upload/1530085819_thumb.jpg",icon:'',number:0,mp_id:2,mp_cid:3},
    { mp_title: '非你杯茶港式丝袜奶茶',mp_stocks:3,mp_price:5.50,mp_picture:"/assets/upload/1515117212_thumb.jpg",icon:'',number:0,mp_id:3,mp_cid:3},
    { mp_title: '香飘飘Meco牛乳茶',mp_stocks:3,mp_price:2.90,mp_picture:"/assets/upload/1515131695_thumb.jpg",icon:'',number:0,mp_id:4,mp_cid:2},
    { mp_title: '乐虎氨基酸功能饮料',mp_stocks:6,mp_price:5.50,mp_picture:"/assets/upload/1529574145_thumb.jpg",icon:'',number:0,mp_id:5,mp_cid:4},
    { mp_title: '康师傅冰红茶柠檬味',mp_stocks:4,mp_price:2.90,mp_picture:"/assets/upload/1529574122_thumb.jpg",icon:'',number:0,mp_id:6,mp_cid:5},
    { mp_title: '酷儿橙汁饮料',mp_stocks:5,mp_price:3.20,mp_picture:"/assets/upload/1529574112_thumb.jpg",icon:'',number:0,mp_id:7,mp_cid:6},
]

//常量定义
const staticUrl = '/assets/index/image/'//静态图片路径
const uploadUrl = '/assets/upload/'//商品图片路径
const defaultType = 'all'//默认分类
const OPERATE_MAP = {
    addOne : 1,
    subOne : -1
}



Page({
    data: {

    /*-------------------------顶部swiper配置-------------------------*/
    topSwiper:{
      item:[
        { img:''/*`${staticUrl}1531127496.jpg`*/, link: '../outurl/outurl?url=http://www.baidu.com',key:0 },
        { img: '', link: '../outurl/outurl?url=http://www.baidu.com',key:1 },
      ],
      autoplay : true,
      interval : 3000, 
      circular : true //loop
    },

    domain : domain,
    /*-------------------------左侧数据-------------------------*/
    //当前分类
    sorter:defaultType,
    //滚动配置
    leftScroll:{
          scrollY :true
      },
    //分类tags
    sortTags:[
        { cat_name: '日用商品',cat_id:0,md_company:'嘉报栖约中心'},
        { cat_name: '乳品饮料',cat_id:1,md_company:'嘉报栖约中心'},
        { cat_name: '休闲零食',cat_id:2,md_company:'嘉报栖约中心'},
        { cat_name: '方便速食',cat_id:3,md_company:'嘉报栖约中心'},
        { cat_name: '补拍专栏',cat_id:4,md_company:'嘉报栖约中心'},
        { cat_name: '冷饮系列',cat_id:5,md_company:'嘉报栖约中心'},
        { cat_name: '面包饼干', cat_id:6,md_company:'嘉报栖约中心'},
        { cat_name: '全部', cat_id:defaultType,md_company:'嘉报栖约中心'},
    ],

    /*-------------------------右侧数据-------------------------*/
      //全部产品列表，基于原始数据
      //@name:商品名
      //@stock:库存
      //@prize:商品价格
      //@img:商品图片
      //@number:用户已经添加的数量
      //@uid:商品唯一代码
      //@type:商品所属分类
    productList:[

    ],
    //滚动配置
    rightScroll:{
        scrollY :true
    },
    //分类后的产品列表
    sortList : [
        //默认分类type为0
    ],

    customer:undefined,
    footer:router.footerArray,

    /*-------------------------底部结算-------------------------*/
    cart:{
      number:0,
      total:0.00,
      opened:false,
        info:{}
    },


    cartScroll:{
        scrollY :true
    },
    needAuthorize:false,

        searchList:[],
        showSearchList : false,
        input_value:"",

        //动画相关
        cart_vector:{x:0,y:0},
        touch_vector:{x:0,y:0},
        control_vector:{x:0,y:0},
        fly:false,//购物车动画点是否显示
        fly_position:{x:0,y:0},//动态更新
        animate_position:{x:0,y:0},
        QuadraticBezier:[],
        tasks:{},
        chosedImage:'',

        shop_id:0,
  },
    tapScan(){
        app.tapScan((code)=>{
            //功能上线后去掉return
            return
            if( typeof code == 'string' ){
                this._addProductToCartByEAN_13(code)
            }
        })
    },
    //根据扫描的条形码，将物品加入到购物车
    _addProductToCartByEAN_13(ean_13){
        let customer = this.data.customer
        let productList = customer.productArray

        let code
        let needUpdate
        for( let i = 0, il = productList.length; i < il; i ++ ){
            code = productList[ i ]["ean_13"]
            console.log(code)
            console.log(ean_13)
            if ( code == ean_13 ){
                let mp_id = productList[ i ]['mp_id']
                needUpdate = customer.addOne( mp_id )
                console.log(`总共${il}条数据，当前循环到第${i}条`)
                break;
            }
            if(i==(il-1)){
                wx.showToast({
                    title: '条码未录入该便利架，请手动搜索商品加入到购物车',
                    icon: 'none',
                    duration: 5000
                });
                return
            }
        }
        if(!needUpdate){
            wx.showToast({
                title: '库存不足',
                icon: 'none',
                duration: 2000
            });
            return

        }

        this.setData({
            customer
        })
        wx.setStorageSync("customer",customer)
    },
    toggleCart(){
      let cart = this.data.cart
      if( cart.opened ){
          cart.opened = false
      }else{
          cart.opened = true
      }
      this.setData({ cart })
    },
    closeCart(){
        let cart = this.data.cart
        cart.opened = false
        this.setData({
            cart
        })
    },
    //点击分类标签
    sort( e ){

        let scope = this;
        this.setData({
            sorter : e.target.dataset.cat_id,
            showSearchList:false
        })
      //重新进行分类列表
      // api.getProductionList( function( res ){
      //     scope.setData({
      //         productList : res.data.data
      //     })
      // } )

  },
    _fly(e,segements,duration,id){
        var scope = this
        var QuadraticBezier = this._createBezier(e,segements,id)
        var QL = QuadraticBezier.length - 1
        // this.setData({QuadraticBezier})

        var p = 0//进度
        var nowTime = 0//时间
        var index = 0
        var fps = 30
        var interval = 1000 / fps
        var animate_position
        var fly_position
        var handle = setInterval(function(){
            p = nowTime / duration
            index = parseInt( p * QL )
            animate_position = QuadraticBezier[index]
            // fly_position = QuadraticBezier[index]
            // console.log(animate_position)
            scope.setData({
                animate_position
                // fly_position
            })
            if((nowTime+interval)<duration){
                nowTime+=interval
            }
            else{
                scope._hideFly()
                clearInterval(handle)
            }

        },interval)
    },
    _createBezier(e,segements,id){
        //固定
        var cart_vector = this.data.cart_vector
        //实时产生
        var touch_vector = {
            x:e.changedTouches[0].clientX,
            y:e.changedTouches[0].clientY
        }
        // var control_vector = {
        //     x:cart_vector.x + parseInt((touch_vector.x - cart_vector.x)/2),
        //     y:touch_vector.y
        // }
        var startPos = {x:0,y:0}
        var endPos = {x:cart_vector.x-touch_vector.x,y:cart_vector.y-touch_vector.y}
        var control_vector = {
            x:endPos.x + parseInt((startPos.x - endPos.x)/2),
            y:0
        }
        this._setFlyPosition( touch_vector,id )
        // var control_vector = this.data.control_vector
        // this.setData({control_vector,touch_vector})

        return app.createQudraticBezier([startPos,control_vector,endPos],segements)
    },
    _setFlyPosition(position,id){
        var chosedImage = domain+this.data.customer.productObject[id].mp_picture

        this.setData({
            chosedImage,
            fly_position:position,
            fly:true
        })
    },
    _hideFly(){
        this.setData({fly:false,animate_position:{x:0,y:0}})
    },
    operate( e ){
        //操作只会更改视图列表 this.data.productList
      var target = e.target;
      var id = target.dataset['mp_id'];
      var op = target.dataset.op;

      var customer = this.data.customer

      //用户加/减 产品uid
      var needUpdate = customer[ op ]( id )
        if( !needUpdate ){
            wx.showToast({
                title: '库存不足',
                icon: 'none',
                duration: 2000
            });
            return
        }

        //如果操作的是搜索列表,更新searchList
        if( this.data.showSearchList == true ){

            var list = this.data.searchList
            for(var i = 0, il = list.length; i < il; i ++ ){
                //说明搜索列表里有这个物品
                if( list[ i ]['mp_id'] == id ){
                    //减一个
                    if(op=="subOne"){
                        list[ i ].number-=1
                    }
                    //加一个
                    else if(op=="addOne"){
                        if(typeof list[ i ].number !='number'){
                            list[ i ].number = 0
                        }
                        list[ i ].number+=1
                        this._fly(e,60,60,id)
                    }
                    console.log(`总共${il}条数据，当前循环到第${i}条，break`)
                    break;
                }
            }

            this.setData({
                searchList:list
            })

        }
        else{
            //如果操作的是customer
            if(op=='addOne'){
                this._fly(e,60,500,id)
            }

        }

      this.setData({ customer })
      wx.setStorage({
          key:"customer",
          data:customer
      })









  },

    //授权
    bindGetUserInfo(e){
        this.setData({
            needAuthorize:false
        })
        wx.showLoading({
            title:"数据加载中"    
        })
        var scope = this
        e.timeStamp = new Date().getTime()
        // console.log('授权回调e:',e)

    
        var code
        var rawData = e.detail.rawData
        var encryptedData = e.detail.encryptedData
        var iv = e.detail.iv
        utils.login()//登录 + 获取code
            .then(res=>{
                //console.log('login:')
                // console.log('login.res:',res)
                code = res.code

                console.log('code:',code)
                console.log('rawData:',rawData)
                console.log('encryptedData:',encryptedData)
                console.log('iv:',iv)
                console.log('url:',`${domain}qiyue/userAuthLogin`)

                //换取用户uid
                return utils.post(`${domain}qiyue/userAuthLogin`,{code,rawData,encryptedData,iv},{"Content-Type": "application/x-www-form-urlencoded"})
            })//拿到code + 获取uid
            .then(res=>{
                console.log('serverData:',res)
                let uid = res.data['uid']
                scope.data.customer.uid = uid
                wx.setStorage({
                    key:"uid",
                    data:uid
                })
                let url = `${domain}qiyue/addBLZOrder`
                let cart_list = JSON.stringify( scope.data.customer.cart_list )

                console.log('便利站提交订单:')
                console.log('uid:',uid)
                console.log('url:',url)
                console.log('cart_list:',cart_list)

                return utils.post(url,{uid,cart_list},{"Content-Type": "application/x-www-form-urlencoded"})
            })//拿到uid + 获取订单信息
            .then((res)=>{
                wx.hideLoading()
                console.log('提交购物车列表:')
                console.log('返回的订单信息',res)
                var totalPrize = scope.data.customer.totalPrize
                scope.data.customer.clearCartList()
                scope.setData({
                    customer:scope.data.customer
                })
                wx.setStorage({
                    key:"customer",
                    data:scope.data.customer
                })

                //跳转到购物车结算
                wx.navigateTo({
                    url: `/pages/shelf/orderinfo?oid=${res.data.oid}&totalPrize=${totalPrize}`
                })



            })//获取订单信息
    },
    pay(){

        var scope = this
        console.log('支付:')
        if( this.data.cart.opened ){
            console.log('购物车是否开着:',this.data.cart.opened)

            //总数
            let number = scope.data.customer.totalNumber;
            if( number == 0)return

            //是否有用户uid
            var uid = wx.getStorageSync('uid')
            if( uid ){
                console.log('用户uid:',uid)
                //总价
                let prize = scope.data.customer.totalPrize;

                let url = `${domain}qiyue/addBLZOrder`
                let cart_list = JSON.stringify( scope.data.customer.cart_list )

                console.log('便利站提交订单:')
                console.log('uid:',uid)
                console.log('url:',url)
                console.log('cart_list:',cart_list)

                wx.showLoading({
                    title:"数据加载中"    
                })
                utils.post(url,{uid,cart_list},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
                    wx.hideLoading()
                    console.log('提交购物车列表:')
                    console.log('返回的订单信息',res)
                    var totalPrize = scope.data.customer.totalPrize
                    console.log(totalPrize)

                    scope.data.customer.clearCartList()
                    scope.setData({
                        customer:scope.data.customer
                    })
                    wx.setStorage({
                        key:"customer",
                        data:scope.data.customer
                    })

                    wx.navigateTo({
                        url: `/pages/shelf/orderinfo?oid=${res.data.oid}&totalPrize=${totalPrize}`
                    })

                })
                //发送购物车列表，后台生成订单
            }
            //提示授权,然后从后台拿到uid
            else{
                console.log("需要授权")
                //提示授权
                scope.setData({
                    needAuthorize:true
                })
                // wx.showModal({
                //     title:'提示',
                //     content:'是否同意授权小程序',
                //     showCancel:true,
                //     cancelText:'不同意',
                //     cancelColor:'#000000',
                //     confirmText:'同意',
                //     confirmColor:'#000000',
                //     success: function(res) {
                //         if (res.confirm) {
                //             console.log('用户同意')
                //         } else if (res.cancel) {
                //             console.log('用户不同意')
                //         }
                //     }
                // })
            }

            //调用微信支付接口
            if( 0 ){
                wx.requestPayment({
                    'timeStamp': String(new Date().getTime()),
                    'nonceStr': '',
                    'package': '',
                    'signType': 'MD5',
                    'paySign': '',
                    'success': function (res) {
                        console.log( res )
                    },
                    'fail': function (res) {
                        console.log(res)
                    },
                    'complete': function (res) {
                        console.log(res)
                    }
                })
            }else{
                return
            }

        }
        //先打开购物车列表
        else{
            console.log('开启购物车')
            let cart = this.data.cart
            cart.opened = true
            this.setData({
                cart
            })
        }

    },

    //弹出需要授权层
    closeAuth: function () {
        var that = this
        that.setData({ pophide: 'hide',needAuthorize:false });
    },
    //把购物车的数据同步到搜索列表中
    _SyncCartListToSearchList(searchList){
        var scope = this
        var customer = scope.data.customer
        //说明购物车有添加过东西
        console.log(Object.keys(customer.cart_list))
        if(Object.keys(customer.cart_list).length>0){
            var cart_list = customer.cart_list
            searchList.forEach(function( item, index ){
                var key = item.mp_id
                if(cart_list.hasOwnProperty(key)){
                    item['number'] = cart_list[ key ]
                }
            })
        }
    },
    search(){
        var scope = this
        utils.post(`${domain}qiyue/getBLZSearchGoods`,{keys:scope.data.input_value,shop_id:wx.getStorageSync('shop_id')},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
            var searchList = res.data.goods_list
            // var customer = scope.data.customer

            //把购物车的操作同步到searchList中
            scope._SyncCartListToSearchList(searchList)
            scope.setData({showSearchList:true,searchList})

        })
    },
    oninput(e){
        var input_value = e.detail.value
        this.setData({input_value})
    },

    //拉取分类标签
    updateTagList(){
        //拉取分类标签
        utils.post(`${domain}qiyue/getBLZIndexCats`,{shop_id:wx.getStorageSync('shop_id')},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
            console.log(res)
            let a = res.data['cat_list']
            let md_company = a[0].md_company
            a.push({ cat_name: '全部', cat_id:defaultType,md_company:md_company},)
            this.setData({
                sortTags:a,
                md_company
            })
        })
    },
    //拉取商品列表
    updateProductList( callback = function(){} ){
        var scope = this;
        utils.post(`${domain}qiyue/getBLZGoodsList`,{shop_id:wx.getStorageSync('shop_id')},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{

            //原始数据备份
            let goods_list = res.data['goods_list']
            let goods_length = goods_list.length
            let sorters = {}
            let sortTags = scope.data.sortTags
            //有数据就用拉取的数据
            if( goods_length!=0 ){
                origin_productList = [...goods_list]
                origin_productList.forEach(( value, index )=>{
                    let url = value['mp_picture']
                    if(url != ""){
                        let s = url.split('.')
                        origin_productList[ index ]['mp_picture'] = s[0]+'_thumb.'+s[1]
                    }
                    else{
                        origin_productList[ index ]['mp_picture'] = '';
                    }

                    sorters[value['mp_cid']] = 1

                })
                for(let [index,value] of sortTags.entries()){
                    let haveSort = sorters.hasOwnProperty(value.cat_id)
                    if(!haveSort&&(value.cat_id!=defaultType)){
                        sortTags.splice(index,1)
                    }
                }
                wx.setStorage({
                    key:"origin_productList",
                    data:origin_productList
                })

                //用户数据更新
                var customer = new Customer()
                var buffer_customer = wx.getStorageSync('customer')
                if( buffer_customer ){
                    for(var key in buffer_customer){
                        customer[ key ] = buffer_customer[ key ]
                    }
                }

                //先加入10条
                customer.productArray = origin_productList.slice(0,goods_length>10?10:goods_length)
                customer.init()
                customer.SyncProductListWithCart()

                //视图更新
                scope.setData({
                    customer,
                    sortTags
                })

                var t = setTimeout(function(){
                    if(customer.productArray.length == goods_length){
                        clearTimeout(t)
                        return
                    }
                    customer.productArray = customer.productArray.concat(origin_productList.slice(10))
                    customer.productArray[20]['ean_13'] = '6970042901556'
                    customer.updateProductObject()
                    customer.SyncProductListWithCart()
                    scope.setData({
                        customer
                    })
                    wx.setStorage({
                        key:"customer",
                        data:customer,
                    })

                    callback(customer)

                },500)

                wx.setStorage({
                    key:"customer",
                    data:customer,
                })

            }else{
                console.log('获取不到数据')
            }


        })
    },

    _QueryCartNode(callback){

        var scope = this
        let selectorquery = wx.createSelectorQuery()

        selectorquery.select('#cart-icon').boundingClientRect(function(res){
            var cart = scope.data.cart
            cart.info = res
            scope.setData({cart})
            callback()
        }).selectViewport().boundingClientRect(function(res){
        }).exec()



    },
    onLoad: function () {
        var scope = this
        /*--------------------------视图处理--------------------------*/
        //处理导航条
        router.setActive(2)
        scope.setData({footer:router.footerArray})

        var shop_id = wx.getStorageSync('shop_id')
        scope.setData({shop_id})
        if(!(shop_id>0)){
            return
        }
/*--------------------------数据请求--------------------------*/
      /*拉取swiper*/
      utils.get(`${domain}qiyue/getBLZIndexAds`).then((res)=>{
console.log(res)
          let list = res.data.ads_list
          let list_len = list.length

          let swiperItem = new Array( list_len ).fill(undefined)

          swiperItem.forEach(( value,index )=>{
              let name = list[ index ]['ads_picture']
              var ads_url = list[ index ]['ads_url']
              var ads_type = list[ index ]['ads_type']
              var link
              if(/http/g.test(ads_url)){
                  link = `${outUrl}?url=ads_url`
              }else{
                  link = ads_url
              }
              let v = {
                  img: `${domain}${name}`,
                  link,
                  key:index,
                  ads_type
              }
              swiperItem[ index ] = v
          })
          let topSwiper = this.data.topSwiper
          topSwiper.item = swiperItem

          this.setData({
              topSwiper
          })
      })
      /*拉取商品列表*/


      scope.updateTagList()
      scope.updateProductList(function(customer){
          console.log(customer)
      })




/*--------------------------获取设备信息--------------------------*/
        this._QueryCartNode(function(){
            var cart = scope.data.cart
            var cart_vector = {
                x:cart.info.left + parseInt(cart.info.width / 2),
                y:cart.info.top + parseInt(cart.info.height / 2)
            }
            scope.setData({cart_vector})
        })




    },
    onPullDownRefresh: function () {
        this.updateProductList()
        wx.stopPullDownRefresh();
    },
})
