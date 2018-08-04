// pages/member/fenxiao.js
const app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    siteurl: app.siteInfo.siteroot,
    isshow:'',
    mobile:'',
    popshare: 'hide',
    popsharehb: 'hide',
    userInfo: {
      'mem_nickName': '用户名',
      'mem_avatar': '/asset/member/avatar.png',
      'mem_usermoney':'0.00',
      'mem_txmoney': '0.00',
    },
    'all_money': '0.00',
    'all_orders': '0',
    'all_teamers': '0',
    'tjr':[],
    ads_list:[],
      year_price:0,
      year_vipprice:0,
      vippophide: 'hide',
  },

  popShare: function () {
    var that = this
    that.setData({ popshare: '' });
  },

  closeShare: function () {
    var that = this
    that.setData({ popshare: 'hide' });
  },
  closevipPOP: function () {
      var that = this
      that.setData({ vippophide: 'hide' });
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
                    uid
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

                },
                fail:function(res){console.log(res)}
            })

        } else {
            app.getUserDataToken();
        }
    },
  popShareHB: function () {
    var that = this
    that.setData({ popshare: 'hide' });
    that.getShareHB();
  },

  closeSharehb: function () {
    var that = this
    that.setData({ popsharehb: 'hide' });
  },

  getShareHB: function () {
    var that = this
    that.getShareQR();
  },

  getShareQR: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    var url = app.util.url('sharepic/getShareImg/');
    wx.showToast({
      title: '加载数据中',
      icon: 'loading',
      duration: 1000
    })
    wx.request({
      url: url,
      data: {
        uid: uid,
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        console.log(res);
        that.setData({ shareImg: res.data.result, popsharehb: '' })
      }

    });
  },

  save: function () {
    var that = this
    wx.showToast({
      title: '处理中',
      icon: 'loading',
      duration: 1000
    })
    wx.downloadFile({
      url: that.data.siteurl + that.data.shareImg,
      success: function (res) {
        var tempFilePath = res.tempFilePath
        wx.getSetting({
          success(res) {
            if (!res.authSetting['scope.writePhotosAlbum']) {
              wx.authorize({
                scope: 'scope.writePhotosAlbum',
                success() {
                  console.log('授权成功');
                  wx.saveImageToPhotosAlbum({
                    filePath: tempFilePath,
                  })
                },
                fail() {
                  console.log('授权失败');
                }
              })
            } else {
              //console.log('保存图片')
              //console.log(tempFilePath);
              wx.saveImageToPhotosAlbum({
                filePath: tempFilePath,
                success(res) {
                  wx.showModal({
                    title: '保存成功',
                    content: '您可以分享到朋友圈啦',
                    showCancel: false,
                    success: function (res) {

                    }
                  })
                },
                fail(res) {
                  console.log(res)
                },
                complete(res) {
                  console.log(res)
                }
              })
            }

          }
        })
      }
    })

  },

  

  getUserInfo:function(uid){
    var that = this
    var url = app.util.url('qiyue/getUserFenxiaoInfo');
    wx.request({
      url: url,
      data: {
        uid:uid
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method:'POST',
      success: function (res) {
        console.log(res)
        that.setData({
          userInfo: res.data.userinfo,
          mobile: app.util.hidePhoneNumber(res.data.userinfo.mem_mobile),
          all_money: res.data.all_money,
          all_orders: res.data.all_orders,
          all_teamers: res.data.all_teamers,
          tjr: res.data.tjr,
        })
        console.log(that.data)
      }
    })
  },

  fenxiaoMoney:function(e){
      var userInfo = this.data.userInfo
      if( userInfo.mem_type==0 ){
          return
      }
    wx.navigateTo({
      url: '/pages/member/fenxiao/money'
    })
  },

  tixianMoney: function (e) {
      var userInfo = this.data.userInfo
      if( userInfo.mem_type==0 ){
          return
      }
    wx.navigateTo({
      url: '/pages/member/fenxiao/tixian'
    })
  },

  fenxiaoOrder: function (e) {
    wx.navigateTo({
      url: '/pages/member/fenxiao/order'
    })
  },

  fenxiaoTeam: function (e) {
    wx.navigateTo({
      url: '/pages/member/fenxiao/team'
    })
  },

  tixian:function(){
    wx.navigateTo({
      url: '/pages/member/fenxiao/tixianform'
    })
  },

  shareQR:function(){
    wx.navigateTo({
      url: '/pages/member/fenxiao/qr'
    })
  },

  
  hiddenShareQR:function(){
    var that = this
    that.setData({
      isshow: ''
    })
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
                that.setData({
                    year_price: res.data.year_price,
                    year_vipprice: res.data.year_vipprice,
                })

            }
        });
    },
//成为VIP弹出框
    vipPOP:function(){
        var that = this
        var userInfo = wx.getStorageSync("userInfo");
        if(!userInfo){
            that.popAuth();
        }else{
            that.setData({ vippophide: '' });
        }
    },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    var uid = wx.getStorageSync("uid");
    if(uid > 0){
      that.getUserInfo(uid);
    }

    that.getAdsList();
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
  onPullDownRefresh: function (options) {
    var that = this
    var uid = wx.getStorageSync("uid");
    if (uid > 0) {
      that.getUserInfo(uid);
    }
    wx.stopPullDownRefresh();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  //获取首页广告
  getAdsList: function () {
    var that = this
    var url = app.util.url('qiyue/getIndexAds');
    wx.request({
      url: url,
      data: {},
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({ ads_list: res.data.ads_list })
      }
    });
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    var ads_list = that.data.ads_list
    var imageUrl = ads_list[0]['ads_picture']
    return {
      title: '栖约·惠生活',
      path: '/pages/index/index?tjuid=' + uid,
      imageUrl: that.data.siteurl + imageUrl,
      success: function (res) {

      },
      fail: function (res) {
        // 分享失败
        console.log(res)
      }
    }
  }
})