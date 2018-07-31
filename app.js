//app.js
var api = require('./utils/api.js').API
var utils = require('./utils/util.js')
var Router = require('./template/footer/footer.js')
var router = new Router()
var domain = "https://www.qiyue99.com/weshop/"
App({
    
  onLaunch: function () {
    // var haveShopid = wx.getStorageSync('shop_id')
    // if (haveShopid){
    //   router.setShelf()
    // }else{
    //   router.setScan()
    // }

    // 登录
    wx.login({
      success: res => {
        // 发送 res.code 到后台换取 openId, sessionKey, unionId
      }
    })
    // 获取用户信息
    wx.getSetting({
      success: res => {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
          wx.getUserInfo({
            success: res => {
              // 可以将 res 发送给后台解码出 unionId
              this.globalData.userInfo = res.userInfo

              // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
              // 所以此处加入 callback 以防止这种情况
              if (this.userInfoReadyCallback) {
                this.userInfoReadyCallback(res)
              }
            }
          })
        }
      }
    })
  },
  globalData: {
    userInfo: null,
    api,
    utils,
    router:router,
    Router : Router,
    DOMAIN:domain,
    localData:false,
  },
    siteInfo: {
        'uniacid': '8', //公众号uniacid
        'acid': '8',
        'multiid': '68035',  //小程序版本id
        'version': '2.0.0',  //小程序版本
      'siteroot': 'https://www.qiyue99.com/weshop/',
    },
    checkUserLogin:function(e){
        var that = this;
        var uid = wx.getStorageSync("uid");
        if (uid == '') {
            wx.login({
                success: function (res) {

                    var code = res.code;
                    var rawData = e.rawData
                    var encryptedData = e.encryptedData
                    var iv = e.iv
                    var url = domain + 'qiyue/userAuthLogin';
                    var tjuid = wx.getStorageSync("tjuid");
                    var tpuid = wx.getStorageSync("tpuid");
                    utils.post(`${domain}qiyue/userAuthLogin`,{code,rawData,encryptedData,iv},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
                      console.log(res)
                        var uid = res.data.uid;
                        var userInfo = res.data.userInfo;
                        console.log(uid);
                        console.log(userInfo);

                        //设置用户缓存
                        wx.setStorageSync("uid", uid);
                        wx.setStorageSync("userInfo", userInfo);
                        console.log('登录成功');
                    })
                    // wx.request({
                    //     //用户登陆URL地址，请根据自已项目修改
                    //     url: url,
                    //     method: "POST",
                    //     header: {
                    //         "Content-Type": "application/x-www-form-urlencoded"
                    //     },
                    //     data: {
                    //         uid: uid,
                    //         tjuid: tjuid,
                    //         tpuid: tpuid,
                    //         code: code,
                    //         rawData: rawData,
                    //         encryptedData: encryptedData,
                    //         iv: iv
                    //     },
                    //     fail: function (res) {
                    //
                    //     },
                    //     success: function (res) {
                    //         var uid = res.data.uid;
                    //         var userInfo = res.data.userInfo;
                    //         console.log(uid);
                    //         console.log(userInfo);
                    //
                    //         //设置用户缓存
                    //         console.log(userInfo);
                    //         wx.setStorageSync("uid", uid);
                    //         wx.setStorageSync("userInfo", userInfo);
                    //         console.log('登录成功');
                    //     }
                    // })
                },
                fail: function (res) {
                    console.log(res);
                }
            })
        }
    },
    util: require('asset/js/util.js'),
    getUserDataToken: function () {

        var that = this;
        //获取用户缓存token 此token是服务器作为用户唯一验证的标识，具体请看后端代码
        var uid = wx.getStorageSync("uid");

        if (uid == ''){
            wx.login({
                success: function (res) {
                    var code = res.code;
                    console.log(res)
                    return false;
                    wx.getUserInfo({
                        success: function (res) {
                            var simpleUser = res.userInfo;
                            console.log(res);
                            //return false;
                            var url = that.siteInfo.siteroot+'qiyue/userAuthLogin';
                            var tjuid = wx.getStorageSync("tjuid");
                            wx.request({
                                //用户登陆URL地址，请根据自已项目修改
                                url: url,
                                method: "POST",
                                header: {
                                    "Content-Type": "application/x-www-form-urlencoded"
                                },
                                data: {
                                    uid: uid,
                                    tjuid: tjuid,
                                    code: code,
                                    rawData: res.rawData,
                                    encryptedData: res.encryptedData,
                                    iv: res.iv
                                },
                                fail: function (res) {

                                },
                                success: function (res) {
                                    var uid = res.data.uid;
                                    var userInfo = res.data.userInfo;

                                    //设置用户缓存
                                    console.log(userInfo);
                                    wx.setStorageSync("uid", uid);
                                    //wx.setStorageSync("userInfo", simpleUser);
                                    wx.setStorageSync("userInfo", userInfo);
                                    console.log('登录成功');
                                }
                            })
                        },
                        fail: function () {
                            // 调用微信弹窗接口


                            wx.showModal({
                                title: '警告',
                                content: '您点击了拒绝授权，将无法正常使用部分功能。请10分钟后再次点击授权，或者删除小程序重新进入。',
                                success: function (res) {
                                    if (res.confirm) {
                                        console.log('用户点击确定');
                                    }
                                }
                            })
                        }
                    })
                },
                fail: function (res) {
                    console.log(res);
                }
            })
        }
    },

    tapScan( e ){
      wx.scanCode({
        success: (res) => {
            console.log(res)
            var isQRCode;
            var isMiniCode;
            var url

            //二维码
            isQRCode = res.hasOwnProperty('result')
            //小程序码
            isMiniCode = res.hasOwnProperty('path')

            var shop_id = undefined;
            //二维码
            if( isQRCode == true ){

                url = res.result
                shop_id = url.match(/\/\d+.html/g)[0].match(/\d+/g)[0]

            }
              //小程序码
            if( isMiniCode == true){

                url = res.path
                shop_id = url.match(/\?shopid=\d+/g)[0].match(/\d+/g)[0]
            }

            if( !!shop_id && shop_id.length!=0 ){
                wx.setStorageSync('shop_id',shop_id)
            }
            console.log(shop_id)
            wx.navigateTo({
                url: '/pages/shelf/index'
            })
            router.setShelf()
        }
      })
    },
    wxPay(data){
        return new Promise((resolve,reject)=>{
            wx.requestPayment({
                'timeStamp': data.timeStamp,
                'nonceStr': data.nonceStr,
                'package': data.package,
                'signType': 'MD5',
                'paySign': data.paySign,
                'success': function (res) {
                    resolve(res)
                 },
                'fail': function (res) {
                    reject(res)
                 },
            });
        });
    },
    //余额支付
    walletPay: function (oid, ordertype) {
        var that = this;
        var url = app.util.url('qiyue/walletPay')
        return new Promise((resolve,reject)=>{
            wx.request({
                url: url,
                data: {
                    oid,
                    ordertype
                },
                header: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                method: 'POST',
                success: function (res) {
                    resolve(res)
                },
                fail: function (res) {
                    console.log('余额支付失败',res)
                    reject(res)
                }
            });
        });

    },
})