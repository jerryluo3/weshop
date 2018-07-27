//index.js
//获取应用实例
const app = getApp()
const api = app.globalData.api
let router = app.globalData.router
const utils = app.globalData.utils
import Customer from './customer.js'
const domain = app.globalData.DOMAIN
const outUrl = '/pages/outurl/outurl'
const USERINFO_BUFFER_TIME = 20000//用户信息
const AUTHORIZE_BUFFER_TIME = 20000//授权缓存时间

// import api from '/utils/api.js'




Page({
  data: {
      orderInfo:[],
      listScroll:{
          scrollY:true
      },
      paytype:1,//微信支付,
      totalPrize:0,
      oid:0,
  },
   changePay( e ){
     var paytype = e.currentTarget.dataset.paytype
       console.log(e)
       this.setData({
           paytype
       })
   },
    pay(){
        var scope = this
        var url

        var uid = wx.getStorageSync('uid')
        var oid = scope.data.oid
        var ordertype = 1//便利架订单类型
        var paytype = scope.data.paytype

        //微信支付
        if( paytype==1 ){
            url = app.util.url('qiyue/payfee')
            console.log('url',url)
          console.log('oid',oid)
          console.log('uid', uid)
          console.log('ordertype',ordertype)
            utils.post(url,{uid,oid,ordertype},{"Content-Type": "application/x-www-form-urlencoded"})
              .then((res)=>{
                console.log('payfee返回',res)
                return app.wxPay(res.data)
              })
              .then((res)=>{
                console.log("微信支付成功",res)
                wx.showToast({
                  title: '支付成功',
                  icon: 'success',
                  duration: 2000,
                  success(res) {
                      setTimeout(function () {
                          wx.navigateTo({
                              url: '/pages/member/member'
                          })
                      }, 2000) //延迟时间
                  }
              });
          })
        }
        //余额支付
        else{
            wx.showModal({
                title:'提示',
                content:'确认使用余额支付吗?',
                success:function(s){
                    if (s.confirm) {

                        url = app.util.url('qiyue/walletPay')
                        utils.post(url,{uid,oid,ordertype},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
                            console.log("余额支付成功",res)

                            if(res.data.status == 200){
                                wx.showToast({
                                    title: '支付成功',
                                    icon: 'success',
                                    duration: 2000,
                                    success(ress) {
                                        setTimeout(function () {
                                            wx.navigateTo({
                                                url: '/pages/member/member'
                                            })
                                        }, 2000) //延迟时间
                                    }
                                });
                            }
                            if (res.data.status == 1){
                                wx.showToast({
                                    title: '支付失败',
                                    icon: 'none',
                                    duration: 2000,
                                    success(ress) {
                                        setTimeout(function () {
                                            wx.navigateTo({
                                                url: '/pages/member/member'
                                            })
                                        }, 2000) //延迟时间
                                    }
                                });

                            }

                        })
                    }
                },
            })

        }

    },
  getBLZOrderInfo:function(oid){
    var scope = this
      var url = `${domain}qiyue/getBLZOrderInfo`
      utils.post(url,{oid},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
        console.log(res)
          var orderInfo = res.data.order_list
          var totalPrize = 0
          orderInfo.forEach((value,index)=>{
            totalPrize = ( totalPrize * 10000 + value['snum'] * value['price'] * 10000 ) / 10000
          })
          scope.setData({
              orderInfo,
              totalPrize
          })
      })
  },
  onLoad: function ( options ) {

    var that = this
    var oid = options.oid
      this.setData({
          oid
      })
      var totalPrize = options.totalPrize
      if(totalPrize){
        this.setData({totalPrize})
      }
    that.getBLZOrderInfo(oid)
  },

})
