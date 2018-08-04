//app.js
var api = require('./utils/api.js').API
var utils = require('./utils/util.js')
var Router = require('./template/footer/footer.js')
var router = new Router()
var domain = "https://www.qiyue99.com/weshop/"
import Customer from './pages/shelf/customer.js'

App({
    
  onLaunch: function () {
      var scope = this
      wx.getSystemInfo({
          success:function(res){
              scope.globalData.deviceInfo = res
              //windowHeight
              //windowWidth
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
      deviceInfo:{},
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
    tapScan(callback=function(res){}){
      wx.scanCode({
        success: (res) => {
            console.log(res)
            var QR_CODE;
            var WX_CODE;
            var EAN_13;
            var url
            var shop_id = wx.getStorageSync('shop_id');//尝试获取shop_id

            //二维码
            QR_CODE = res.scanType == 'QR_CODE'
            //小程序码
            WX_CODE = res.scanType == 'WX_CODE'
            //条形码
            EAN_13 = res.scanType == 'EAN_13'
            if( EAN_13 == true ){
                //已经缓存过门店
                if( ( shop_id > 0 ) == true ){
                    //返回条形码
                    callback(res.result)
                    return
                }
                //没有缓存过门店
                else{
                    wx.showToast({
                        title: '请先扫描二维码或小程序码进入便利架',
                        icon: 'success',
                        duration: 2000
                    });
                    return
                }
            }


            //二维码，切换门店
            if( QR_CODE == true ){

                url = res.result
                shop_id = url.match(/\/\d+.html/g)[0].match(/\d+/g)[0]

            }
              //小程序码，切换门店
            if( WX_CODE == true){

                url = res.path
                shop_id = url.match(/\?shopid=\d+/g)[0].match(/\d+/g)[0]
            }

            var customer = new Customer()
            var buffer_customer = wx.getStorageSync('customer')
            if(buffer_customer){
                Object.assign(customer,buffer_customer)
                customer.clearCartList()
                wx.setStorageSync('customer',customer)
            }
            if( !!shop_id && shop_id.length!=0 ){
                wx.setStorageSync('shop_id',shop_id)
            }
            console.log(shop_id)
            router.setShelf()
            wx.navigateTo({
                url: '/pages/shelf/index'
            })

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
    createQudraticBezier(points,segements){
        segements = parseInt( segements )
        var result = []
        for( var i = 0; i < segements; i ++ ){
            var t = i / segements
            var x = Math.pow((1-t),2) * points[0].x + 2 * t * ( 1 - t ) * points[1].x + Math.pow(t,2) * points[2].x
            var y = Math.pow((1-t),2) * points[0].y + 2 * t * ( 1 - t ) * points[1].y + Math.pow(t,2) * points[2].y
            result.push({x,y})
        }
        return result
    }
})
