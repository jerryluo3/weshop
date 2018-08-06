// pages/member/member.js
var app = getApp()
let router = app.globalData.router
const utils = app.globalData.utils
const domain = app.globalData.DOMAIN
import Customer from '../shelf/customer.js'
var sliderWidth = 96; // 需要设置slider的宽度，用于计算中间位置

Page({

  /**
   * 页面的初始数据
   */
  data: {
    pophide: 'hide',
    vippophide: 'hide',
    year_price:0,
    year_vipprice:0,
    mobile:'',
    unpay_nums:0,
    unsend_nums: 0,
    uncollect_nums: 0,
    unfinish_nums: 0,
    blz_unpay_nums:0,
    blz_finish_nums: 0,
    user:{
        mem_avatar:"",
        mem_nickname:"",
        mem_gender:""
    },
    uid:'',

    /*切换标签*/
    tabs: ["商城订单", "便利站订单"],
    activeIndex: 0,
    sliderOffset: 0,
    sliderLeft: 0,
      customer:undefined,

      binder:{
        tel:"",//手机号
          password:'',//密码
          code:"",//验证码
          needShowBindedTip:false,//控制提示框
          normalText:"获取验证码",//常规文字
          code_canBeUsed:"",//控制显示倒计时或者常规文字
      },
      code_clock:undefined,
      countdown:''//倒计时
  },

  tabClick: function (e) {
    this.setData({
      sliderOffset: e.currentTarget.offsetLeft,
      activeIndex: e.currentTarget.id
    });
  },
  getPhoneNumber: function (e) {
    console.log(e)
    var that = this
    console.log(e.detail.errMsg)
    console.log(e.detail.iv)
    console.log(e.detail.encryptedData)

    if (e.detail.errMsg == 'getPhoneNumber:fail user deny') {
      wx.showModal({
        title: '提示',
        showCancel: false,
        content: '未授权',
        success: function (res) { }
      })
    } else {
      wx.showModal({
        title: '提示',
        showCancel: false,
        content: '同意授权',
        success: function (res) {
          wx.login({
            success: function (res) {
              var code = res.code
              var url = app.util.url('qiyue/userAuthMobile');
              var uid = wx.getStorageSync("uid");
              wx.request({
                url: url,
                data: {
                  uid: uid,
                  code: code,
                  iv: e.detail.iv,
                  encryptedData: e.detail.encryptedData,
                },
                header: {
                  "Content-Type": "application/x-www-form-urlencoded"
                },
                method: 'POST',
                success: function (res) {

                  if (res.data.status == 200) {

                    //console.log(res)
                    wx.showToast({
                      title: '处理成功',
                      icon: 'success',
                      duration: 1000
                    })
                    that.getUserInfo(uid);

                  } else {
                    console.log(res)
                    wx.showToast({
                      title: '处理失败',
                      icon: 'loading',
                      duration: 1000
                    })
                  }

                }
              })
            }
          });
        }
      })

    }
  },

  vipOrder: function (e) {
    var that = this;
    var uid = wx.getStorageSync("uid");

    if (uid > 0) {
      wx.showToast({
        title: '加载中',
        icon: 'loading',
        duration: 1000
      })
      var url = app.util.url('qiyue/vipOrder');
      wx.request({
        url: url,
        data: {
          uid: uid
        },
        header: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        method: 'POST',
        success: function (res) {
          console.log(res)
          if (res.data.status == 200) {

            //调用支付
            that.weixinPay(res.data.oid, uid);


          } else {
            wx.showToast({
              title: '下单失败',
              icon: 'fail',
              duration: 1000
            })
          }

        }
      })

    } else {
      app.getUserDataToken();
    }
  },

  weixinPay: function (oid, uid) {
    var that = this;
    var url = app.util.url('qiyue/vippayfee')
    wx.request({
      url: url,
      data: {
        uid: uid,
        oid: oid,
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      method: 'POST',
      success: function (res) {
        //console.log(res.data);
        console.log('调起支付');
        wx.requestPayment({
          'timeStamp': res.data.timeStamp,
          'nonceStr': res.data.nonceStr,
          'package': res.data.package,
          'signType': 'MD5',
          'paySign': res.data.paySign,
          'success': function (res) {
            console.log('success');
            wx.showToast({
              title: '支付成功',
              icon: 'success',
              duration: 2000,
              success(ress) {
                setTimeout(function () {
                  wx.navigateTo({
                    url: '/pages/member/member?refresh=1'
                  })
                }, 2000) //延迟时间
              }
            });
            //that.getCartList();
          },
          'fail': function (res) {
            console.log('fail');
          },
          'complete': function (res) {
            console.log('complete');
          }
        });
      },
      fail: function (res) {
        console.log(res.data)
      }
    });
  },

  //成为VIP弹出框
  vipPOP:function(){
    var that = this
    var userInfo = wx.getStorageSync("userInfo");
    if(userInfo == ''){
      that.popAuth();
    }else{
      that.setData({ vippophide: '' });
    }
  },

  closevipPOP: function () {
    var that = this
    that.setData({ vippophide: 'hide' });
  },

  //获取状态订单数量信息
  getOrderStatusNums: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    if(!uid)return
    //特卖小红字
    var url = app.util.url('qiyue/getOrderStatusNums/');
    var url2 = app.util.url('qiyue/getBLZOrderStatusNums/');
    wx.request({
      url: url,
      data: {
        uid: uid
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({
          unpay_nums: res.data.unpay_nums,
          unsend_nums: res.data.unsend_nums,
          uncollect_nums: res.data.uncollect_nums,
          unfinish_nums: res.data.unfinish_nums,
        })
      }
    });
      wx.request({
          url: url2,
          data: {
              uid: uid
          },
          header: {
              "Content-Type": "application/x-www-form-urlencoded"
          },
          method: 'POST',
          success: function (res) {
            console.log(res)
              that.setData({
                  blz_unpay_nums: res.data.unpay_nums,
                  blz_finish_nums: res.data.finish_nums,
              })

          }
      });
  },

  userInfoHandler: function (e) {

    wx.getSetting({
      success(res) {
        console.log(res);
        if (!res.authSetting['scope.userInfo']) {
          console.log('------没有授权----')
        }else{
          app.checkUserLogin(e.detail);
          //console.log(e.type.detail)
        }
      }
    })

    var scope = this
      scope.closeAuth();

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
            var mobile;
              console.log('拿到uid:',res)
              if(res.data.userInfo.mem_mobile){
                mobile = res.data.userInfo.mem_mobile
              }
              let uid = res.data['uid']
              wx.setStorageSync('uid',uid)
              scope.data.customer.uid = uid
              scope.setData({
                  user:res.data.userInfo,
                  uid,
                  mobile
              })
              wx.setStorage({
                  key:"customer",
                  data:scope.data.customer
              })
              wx.setStorage({
                  key:"userInfo",
                  data:res.data.userInfo
              })

              scope.getOrderStatusNums()
          })//拿到uid


      // wx.showToast({
      //   title: '加载中',
      //   icon: 'loading',
      //   duration: 1000,
      //   success(ress) {
      //     setTimeout(function () {
      //       var uid = wx.getStorageSync("uid");
      //       console.log('---------' + uid)
      //       //that.getUserInfo(uid);
      //       var url = '/pages/member/member';
      //       wx.reLaunch({
      //         url: url
      //       })
      //     }, 2000) //延迟时间
      //   }
      // })
  },

  getYearVipInfo:function(){
    var that = this
    var url = app.util.url('qiyue/getYearVipInfo/');
    wx.request({
      url: url,
      data: { },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
          console.log(res);
        that.setData({
          year_price: res.data.year_price,
          year_vipprice: res.data.year_vipprice,
        })

      }
    });
  },

  /*-----------------------授权功能-----------------------*/
    popAuth: function () {
        var that = this
        that.setData({ pophide: '' });
    },
    closeAuth: function () {
        var that = this
        that.setData({ pophide: 'hide' });
    },
    bindGetUserInfo:function(){
        wx.getSetting({
            success(res) {
                console.log(res);
                if (!res.authSetting['scope.userInfo']) {
                    console.log('------没有授权----')
                } else {
                    app.checkUserLogin(e.detail);
                    //console.log(e.type.detail)
                }
            }
        })
    },
    getUserInfo:function(uid){
        var that = this
        var url = app.util.url('qiyue/getUserInfo/' + uid);
        wx.request({
            url: url,
            data: {},
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
                that.setData({
                    uid: res.data.user.mem_id,
                    user: res.data.user,
                    mobile: app.util.hidePhoneNumber(res.data.user.mem_mobile)
                })
                wx.setStorageSync("uid", res.data.user.mem_id);
                wx.setStorageSync("userInfo", res.data.user);
            }
        });
    },

  getUserInfoF: function () {

    var that = this;
    wx.getSetting({

      success: (res) => {
        wx.getUserInfo({
          success: res => {
            this.globalData.userInfo = res.userInfo
            console.log("一开始同意授权" + res.userInfo.nickName);
          },
          fail(err) {
            console.info(err.errMsg);
            wx.showModal({
              title: '警告',
              cancelText: '不授权',
              confirmText: '授权',
              confirmColor: '#37C31A',
              content: '若不授权微信登录，则无法使用栖约惠生活；点击重新获取授权，则可重新使用；' +
              '若点击不授权，将无法使用便捷服务。',

              success: function (res) {
                if (res.confirm) {
                  console.log('用户点击确定')
                  wx.openSetting({
                    success: (res) => {
                      if (res.authSetting['scope.userInfo']) {
                        wx.getUserInfo({
                          success: res => {
                            that.globalData.userInfo = res.userInfo
                            console.log("再次同意授权" + res.userInfo.nickName);
                          }
                        })
                      } else {
                        console.info("再次不允许");
                        wx.redirectTo({
                          url: 'home',
                        })
                      }
                    }
                  });
                } else if (res.cancel) {
                  console.log('弹出框用户点击取消')
                  wx.redirectTo({
                    url: 'home',
                  })

                }
              }
            })

          }

        })

      }
    })
  },

  /*-----------------------导航栏扫一扫-----------------------*/
    indexScan: app.tapScan,

  /*-----------------------vip、钱包-----------------------*/
    jumpUrl:function(e){
        var that = this
        var userInfo = wx.getStorageSync("userInfo");
        if (userInfo == '') {
            that.popAuth();
            return false;
        }
        var url = e.currentTarget.dataset.url
        wx.navigateTo({
            url: url
        })
    },
    /*-----------------------绑定功能-----------------------*/
    accountBindTip(){
        var binder = this.data.binder
        binder.needShowBindedTip = true
        this.setData({
            binder
        })
    },
    accountBindTipClose(){
        var binder = this.data.binder
        binder.needShowBindedTip = false
        binder.tel = ''
        binder.code = ''
        this.setData({
            binder
        })
        this.clearCodeInterval()
        this.allowButtonCode()
    },
    onTelInput(e){
        var binder = this.data.binder
        binder.tel = e.detail.value
        this.setData({binder})
    },
    onIdInput(e){
        var binder = this.data.binder
        binder.code = e.detail.value
        this.setData({binder})
    },
    onPasswordInput(e){
        var binder = this.data.binder
        binder.password = e.detail.value
        this.setData({binder})
    },
    //禁用"获取验证码"按钮
    banButtonCode(){
      var scope = this
        var binder = scope.data.binder
        binder.code_canBeUsed = 'false'
        scope.setData({
            binder,
        })
    },
    //恢复"获取验证码"按钮
    allowButtonCode(){
        var scope = this
        var binder = scope.data.binder
        binder.code_canBeUsed = ''
        scope.setData({
            binder
        })
    },
    //开启验证码定时器
    startCodeInterval(){
      var count = 60
        var countdown ='(60s)'
        var scope = this

      var code_clock = setInterval(function(){
        if(count>0){
          count--
            countdown = `(${count}s)`
            scope.setData({
                countdown
            })
        }

      },1000)
        scope.setData({
            code_clock,
            countdown
        })
    },
    //清理验证码定时器
    clearCodeInterval(){
      var h = this.data.code_clock
        if(h){
            clearInterval(h)
        }
    },
    buttonGetCode(){
      var phone = this.data.binder.tel
      var password = this.data.binder.password
        var reg=/^[1][3,4,5,7,8][0-9]{9}$/;
      var isRight = reg.test(phone)

        if(!isRight){
          wx.showToast({
              title: '请输入正确的手机号',
              icon: 'none',
              duration: 2000
          });
            return
        }

        var scope = this
        scope.banButtonCode()
        scope.startCodeInterval()
        wx.showToast({
            title: '注意查收验证码',
            icon: 'success',//none
            duration: 3000
        });
        var url = `${domain}qiyue/getSafeCode`

        utils.post(url,{phone},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
          console.log('上传手机号',res)
            if(res.status == 200){
              //成功
            }
            else if(res.status == 1){
              //原来没有手机号，不需要绑定
            }
        })

    },
    buttonBind(){

        var scope = this
        var safecode = this.data.binder.code
        var phone = this.data.binder.tel
        var uid = wx.getStorageSync('uid')
        if(uid>0){

        }else{
          scope.popAuth()
            return
        }
        //正则判断手机号
        var reg=/^[1][3,4,5,7,8][0-9]{9}$/;
        var isRight = reg.test(phone)
        if(!isRight){
            wx.showToast({
                title: '请输入正确的手机号',
                icon: 'none',
                duration: 2000
            });
            return false
        }
        // var password = scope.data.binder.password
        // if( password =="" ){
        //     wx.showToast({
        //         title: '请输入密码',
        //         icon: 'none',
        //         duration: 2000
        //     });
        //     return false
        // }
        var url = `${domain}qiyue/checkHaveAccountByPhone`
        wx.showLoading({
            title: '请稍后',
        });
        utils.post(url,{uid,phone,safecode},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
            console.log('上传验证码',res)
            //成功
            wx.hideLoading()
            if(res.data.result == 200){
                scope.accountBindTipClose()
                wx.showToast({
                    title: '绑定成功',
                    icon: 'success',
                    duration: 3000
                });
                scope.getUserInfo(uid)
            }
            //手机号错误
            else if(res.data.result == -1){
                wx.showToast({
                    title: '手机号错误',
                    icon: 'none',
                    duration: 3000
                });
            }
            //验证码位数不对
            else if(res.data.result == -2){
                wx.showToast({
                    title: '验证码错误',
                    icon: 'none',
                    duration: 3000
                });
            }
            //账号不存在
            else if(res.data.result == -3){
                wx.showToast({
                    title: '账号不存在',
                    icon:"none",
                    duration: 3000
                });
            }
            //验证码错误
            else if(res.data.result == -4){
                wx.showToast({
                    title: '验证码错误',
                    icon: 'none',
                    duration: 3000
                });
            }

        })

    },

  /*-----------------------基本设置-----------------------*/
    basicSettings(){

    },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

      let fromBLZ = options.fromBLZ
      if(fromBLZ == 1){
          this.setData({
              activeIndex:1
          })
      }

    /*footer*/
    let footer = this.data.footer
    router.setActive(4)
    this.setData({footer:router.footerArray})

    /*title*/
    wx.setNavigationBarTitle({
      title: '我的'
    })

    /*标签*/
    // app.util.footer(that);
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          sliderLeft: (res.windowWidth / that.data.tabs.length - sliderWidth) / 2,
          sliderOffset: res.windowWidth / that.data.tabs.length * that.data.activeIndex
        });
      }
    });

    //检查是否授权过
    // wx.getSetting({
    //     success:function( res ){
    //       console.log("检测授权情况",res)
    //         if ( res['authSetting']['scope.userInfo'] ){
    //           //授权过
    //             console.log('结果:已经授权')
    //             wx.getUserInfo({
    //                 success:function( res ){
    //                   console.log("wx.getUserInfo:",res)
    //
    //                 },
    //             })
    //         }else{
    //           console.log("未授权")
    //             that.popAuth();
    //         }
    //     }
    // })

    var refresh = options.refresh

    var uid = wx.getStorageSync('uid')
    var userInfo = wx.getStorageSync('userInfo')

      that.data.customer = new Customer()

      //检测uid
    if( uid ){
      that.setData({
          uid
      })
      //获取用户信息
        if( userInfo ){
            that.setData({
                user:userInfo
            })
        }
        else{
          let url = `${domain}qiyue/getUserInfo/${uid}`
            utils.post(url,{},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
                that.setData({
                    user:res.data.user
                })
                wx.setStorageSync('userInfo',res.data.user)
            })
        }

    }
    //提示授权,去生成uid
    else{
        that.popAuth();
    }

    if (refresh == 1){
      that.getUserInfo(uid)
    }
    else{
        if(uid > 0){
          that.setData({
            uid: uid,
            user: userInfo,
            mobile: app.util.hidePhoneNumber(userInfo.mem_mobile)
          })

        }else{
          wx.getSetting({
            success(res) {
              console.log(res);
              if (!res.authSetting['scope.userInfo']) {
                console.log('------没有授权----')
                that.popAuth();
              }
            }
          })
        }
    }

    var openvip = options.openvip;
    if (openvip == 1 && userInfo.mem_type == 0){
      that.vipPOP();
    }

    that.getOrderStatusNums();
    that.getYearVipInfo();

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    if (uid > 0) {
      that.getUserInfo(uid);
    }
    else{
      that.popAuth()
    }
    that.getOrderStatusNums();
    wx.stopPullDownRefresh();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})
