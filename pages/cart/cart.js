// pages/cart/cart.js
var app = getApp()
let router= app.globalData.router

Page({

  /**
   * 页面的初始数据
   */
  data: {
    'siteurl': app.siteInfo.siteroot,
    'cartlist':[],
    'address': [],
    'aid':'',
    'postcost':0,
    'postcost_manjian':0,
    'posttype':'0',
    'needcost':0,
    'paytype':0,
    'pophide': 'hide',
    'allamount':0,
    'needcost_amount':0,
    'postcost_cha':0,
    'all_fee':0,
    'userAccount':0.00,
    'ids':[],
    'popPhonehide':'hide',

      footer:router.footerArray,
      fromTM:1
  },

  //配送方式
  changePostType:function(e){
    var that = this
    var ptype = e.currentTarget.dataset.ptype;
    that.setData({
      posttype: ptype
    })

  },

  //购物车数量加
  cartNumsADD: function (e) {
    var that = this
    var index = e.currentTarget.dataset.id
    var cart = that.data.cartlist[index]
    var nums = parseInt(cart.cart_goodsnum)
    var limitnums = parseInt(cart.goods_maxnums)
    var stocks = parseInt(cart.goods_stocks)
    var cart_id = cart.cart_id
    //console.log("stocks:" + stocks);
    //console.log("nums:" + nums);
    //console.log("limitnums：" + limitnums);
    


    if (limitnums > 0) {
      //有限购
      if (nums < limitnums) {
        if (nums < stocks) {
          console.log('----------')
          //有库存
          nums++
          //console.log(cart_id);
          that.updateCartListNums(cart_id,nums)
        } else {
          //库存存不足
          wx.showToast({ title: '库存不足', icon: 'none', duration: 1000 })
          return false
        }
      } else {
        //超过限购数量
        wx.showToast({ title: '超过限购数量', icon: 'none', duration: 1000 })
        return false
      }
    } else {
      if (nums < stocks) {
        //有库存
        nums++
        //console.log(nums);
        that.updateCartListNums(cart_id, nums)
      } else {
        //库存存不足
        wx.showToast({ title: '库存不足', icon: 'loading', duration: 1000 })
        return false
      }

    }

  },

  //更新购物车中的数量
  updateCartListNums:function(cart_id,nums){
    
    if (cart_id <= 0 || nums <= 0) {
      return false
    }
    var that = this
    var url = app.util.url('qiyue/updateCartNums')
    wx.request({
      url: url, //仅为示例，并非真实的接口地址
      data: {
        cart_id: cart_id,
        nums: nums
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        //console.log('----------------')
        that.getCartList();
        //console.log(res.data.result.a_id)
      }
    })
  },

  //购物车数量减
  cartNumsJIAN: function (e) {
    var that = this
    var index = e.currentTarget.dataset.id
    var cart = that.data.cartlist[index]
    var nums = cart.cart_goodsnum
    var limitnums = cart.goods_maxnums
    var stocks = cart.goods_stocks
    var cart_id = cart.cart_id

    if (nums > 1) {
      nums--
      that.updateCartListNums(cart_id, nums)
    } else {
      return false;
    }

  },

  //手动更新数量
  updateCartNums: function (e) {
    var that = this
    var v = parseInt(e.detail.value);
    if (v > 0) {

    } else {
      v = 1;
    }
    that.setData({ nums: v })
  },

  //切换支付方式
  changePayType:function(e){
    var that = this
    var ptype = e.currentTarget.dataset.type
    var allamount = that.data.allamount
    var userAccount = that.data.userAccount

    var userInfo = wx.getStorageSync("userInfo");

    if(userInfo.mem_type == 0 && userInfo.mem_usewalletmoney == 0){
      wx.showToast({
        title: 'VIP用户才能使用余额',
        icon: 'none',
        duration: 1000
      })
      return false
    }   

    if(ptype == 1){
      if(allamount > userAccount){
        //余额不足
        wx.showToast({
          title: '余额不足',
          icon: 'none',
          duration: 1000
        })
        return false
      }else{
        that.setData({
          paytype: ptype
        })
      }
    }else{
      that.setData({
        paytype: ptype
      })
    }
    //console.log(that.data.paytype)
  },

  chooseItem:function(e){
    var that = this
    
    var goodsid = parseInt(e.currentTarget.dataset.goodsid)
    var ids = that.data.ids
    var tindex = ids.indexOf(goodsid)
    if (tindex > -1 ){
      ids.splice(tindex, 1);
    }else{
      ids.push(goodsid)
    }
    that.setData({
      ids: ids
    })

    var index = parseInt(e.currentTarget.dataset.index);
    var selected = that.data.cartlist[index].selected;
    var carts = that.data.cartlist;
    //var price = that.data.carts[index].price; 


    carts[index].selected = !selected;
    var allamount = 0;
    var needcost_amount = 0;
    var needcost = 0;
    for (var i = 0; i < carts.length; i++) {
      if (carts[i].selected) {
        allamount += parseFloat(carts[i].cart_amount)
        if (carts[i].cart_needpostcost > 0){
          needcost_amount += parseFloat(carts[i].cart_amount)
        }
        needcost += parseFloat(carts[i].cart_needpostcost)
      }
    }
    var all_fee = 0;
   
    if (allamount < that.data.postcost_manjian && needcost > 0){
      all_fee = allamount + parseFloat(that.data.postcost)      
    }else{
      all_fee = allamount
    }
    var postcost_cha = that.data.postcost_manjian - needcost_amount
    postcost_cha = parseFloat(postcost_cha.toFixed(2))
    console.log(all_fee)

    that.setData({
      cartlist: carts,
      allamount: allamount.toFixed(2),
      all_fee: all_fee,
      needcost_amount: needcost_amount,
      needcost: needcost,
      postcost_cha: postcost_cha
    });
    
    
  },

  goaddress: function (e) {
    var that = this
    var comefrom = e.currentTarget.dataset.comefrom
    wx.navigateTo({
      url: '/pages/member/address/address?comefrom=' + comefrom,
    })
  },

  getUseAddress: function (useaddr) {    
    var that = this
    var uid = wx.getStorageSync("uid");
    var url = app.util.url('qiyue/getUseAddress')
    wx.request({
      url: url, //仅为示例，并非真实的接口地址
      data: {
        uid: uid,
        useaddr: useaddr
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        console.log(res)
        that.setData({
          address: res.data.result,
          aid: res.data.a_id
        })
        //console.log(res.data.result.a_id)
      }
    })
  },

  //获取会员信息
  getUserInfo: function (uid) {
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
          user: res.data.user,
          mobile: app.util.hidePhoneNumber(res.data.user.mem_mobile)
        })
        wx.setStorageSync("uid", res.data.user.mem_id);
        wx.setStorageSync("userInfo", res.data.user);
      }
    });
  },

  getPhoneNumber: function (e) {
    var that = this
    that.closePhoneAuth();

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


  //弹出需要授权层
  popPhoneAuth: function () {
    var that = this
    that.setData({ popPhonehide: '' });
  },

  closePhoneAuth: function () {
    var that = this
    that.setData({ popPhonehide: 'hide' });
  },


  orderOrder: function (e) {
    var that = this;
    var uid = wx.getStorageSync("uid");
    var aid = that.data.aid;
    var paytype = that.data.paytype
    var postcost = that.data.postcost
    var postcost_manjian = that.data.postcost_manjian
    var allamount = parseFloat(that.data.allamount)
    var needcost_amount = parseFloat(that.data.needcost_amount)
    var userInfo = wx.getStorageSync("userInfo");
    if(userInfo.mem_mobile == ''){
      that.popPhoneAuth();
      return false;
    }

    
    if (allamount > 0){
      allamount = allamount.toFixed(2);
    }
    var ids = that.data.ids
    var posttype = that.data.posttype;
   
    if ( (aid == '' || typeof (aid) == 'undefined') && posttype == 0 ) {
      wx.showToast({
        title: '请选择地址',
        icon: 'none',
        duration: 1000
      })
      return false
    }


    if (posttype == 0 && needcost_amount < postcost_manjian){
      var needcost = that.data.needcost;
      if (needcost > 0){
        var post_fee = postcost
      }else{
        var post_fee = 0
      }      
    }else{
      var post_fee = 0
    }

    if (ids == ''){
      wx.showToast({
        title: '请选择商品',
        icon: 'none',
        duration: 1000
      })
      return false
    }

    if (uid > 0) {
      wx.showToast({
        title: '加载中',
        icon: 'loading',
        duration: 1000
      })
      var url = app.util.url('qiyue/cartToOrder');
      wx.request({
        url: url,
        data: {
          uid: uid,
          aid: aid,
          posttype: posttype,
          ids: ids.toString(),
          paytype: paytype,
          post_fee: post_fee
        },
        header: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        method: 'POST',
        success: function (res) {
          //console.log(res)
          if (res.data.status == 200) {
            wx.showToast({
              title: '下单成功',
              icon: 'success',
              duration: 1000
            })
            if (paytype == 0){
              //调用微信支付
              that.weixinPay(res.data.oid, uid);
            }
            if (paytype == 1) {
              //调用余额支付
              that.walletPay(res.data.oid, uid);
            }


          }
          if (res.data.status == 2) {
            wx.showModal({
              title: '提示',
              content: '您有未评论的特价商品订单',
              showCancel: false,
              success: function (res) {

              }
            })
            //wx.showToast({
              //title: '您有未评论的特价商品订单',
              //icon: 'none',
              //duration: 2000
            //})
          }

        }
      })

    } else {
      app.getUserDataToken();
    }
  },

  weixinPay: function (oid, uid) {
    var that = this;
    var url = app.util.url('qiyue/payfee')
    wx.request({
      url: url,
      data: {
        oid: oid,
          ordertype:0,
          uid
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      method: 'POST',
      success: function (res) {
        //console.log(res.data);
        console.log('调起支付',res);
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
                    url: '/pages/member/member'
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

  //余额支付
  walletPay: function (oid, uid) {
    var that = this;
    var url = app.util.url('qiyue/walletPay')
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
        if(res.data.status == 200){
          wx.showToast({
            title: '支付成功',
            icon: 'success',
            duration: 2000,
            success(ress) {
              that.getUserInfo(uid);
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
              that.getUserInfo(uid);
              setTimeout(function () {
                wx.navigateTo({
                  url: '/pages/member/member'
                })
              }, 2000) //延迟时间
            }         
          });
          
        }
      },
      fail: function (res) {
        console.log(res.data)
      }
    });
  },

  setAddr: function (e) {
    var that = this
    var aid = e.currentTarget.dataset.aid
    that.setData({
      showaid: aid
    })
    //console.log(that.data.showaid)
  },

  

  //弹出需要授权层
  popAuth: function () {
    var that = this
    that.setData({ pophide: '' });
  },

  closeAuth: function () {
    var that = this
    that.setData({ pophide: 'hide' });
  },

  userInfoHandler: function (e) {
    
    wx.getSetting({
      success(res) {
        console.log(res);        
        if (!res.authSetting['scope.userInfo']) {
          console.log('------没有授权----')          
        }else{
          app.checkUserLogin(e.detail);
        }
      }
    })
    var that = this
    that.closeAuth();
    console.log(wx.getStorageSync("uid"))
  },
  
  //获取购物车内容
  getCartList:function(){
    var that = this
    var uid = wx.getStorageSync("uid");
    //console.log(uid)
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 1000,
    })
    if(uid > 0){
      var url = app.util.url('qiyue/getCartList')
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
            cartlist: res.data.cartlist
          })
          //console.log(res.data.cartlist)
          var allamount = 0;
          var needcost_amount = 0;
          var needcost = 0;
          var cartlist = that.data.cartlist
          var ids = [];
          var cartnums = 0;
          

          for (var i = 0; i < cartlist.length; i++) {            
            ids.push(parseInt(cartlist[i].cart_goodsid))
            needcost += parseInt(cartlist[i].cart_needpostcost)
            allamount += parseFloat(cartlist[i].cart_amount)
            if (cartlist[i].cart_needpostcost > 0) {
              needcost_amount += parseFloat(cartlist[i].cart_amount)
            }
            cartnums += parseInt(cartlist[i].cart_goodsnum)
          }
          var postcost_manjian = that.data.postcost_manjian;          
          var postcost_cha = parseFloat(that.data.postcost_manjian) - needcost_amount;

          that.setData({
            allamount: allamount.toFixed(2),
            needcost: needcost,
            needcost_amount: needcost_amount.toFixed(2),
            postcost_cha: postcost_cha.toFixed(2),
            ids: ids
          })
          app.globalData.popcartnums = cartnums;
        }
      })
    }else{
      app.getUserDataToken();
    }
  },

  //删除购物车商品
  delcart: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    if (id > 0) {
      wx.showModal({
        title: '提示',
        content: '确定要删除吗？',
        success: function (res) {
          if (res.confirm) {
            var url = app.util.url('qiyue/delCart')
            wx.request({
              url: url,
              data: {
                id: id
              },
              header: {
                "Content-Type": "application/x-www-form-urlencoded"
              },
              method: 'POST',
              success: function (res) {

                that.getCartList();

              }
            })
          }
        }
      })
      
    }
  },

  //跳转页面
  jumpUrl: function (e) {
    var that = this
    var url = e.currentTarget.dataset.url
    console.log(url);
    wx.navigateTo({
      url: url
    })
  },

  //邮费
  getPostcost:function(){
    var that = this;
    var uid = wx.getStorageSync("uid");
    if (uid > 0) {
      var url = app.util.url('qiyue/getPostcost')
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
          var postcost = parseFloat(res.data.postcost);
          var allamount = parseFloat(that.data.allamount);
          var needcost_amount = parseFloat(that.data.needcost_amount);
          var all_fee = parseFloat(postcost) + parseFloat(allamount)
          var postcost_cha = res.data.postcost_manjian - needcost_amount 
          that.setData({
            postcost: parseFloat(res.data.postcost),
            postcost_manjian: parseFloat(res.data.postcost_manjian),
            all_fee: all_fee,
            postcost_cha: postcost_cha.toFixed(2)
          })  
         // console.log(res)        
        }
      })
    }
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
      var scope = this
      router.setActive(5)
      scope.setData({footer:router.footerArray})

      var fromTM = options.fromTM
      var that = this
      var uid = wx.getStorageSync("uid");
      var userInfo = wx.getStorageSync("userInfo");
      if (uid > 0) {
        that.setData({
          userAccount: parseFloat(userInfo.mem_account),
            fromTM
        })
      }
      else {
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
    
      var useaddr = wx.getStorageSync("useaddr");
    
      // app.util.footer(that);
      that.getCartList();
      that.getUseAddress(useaddr);
      setTimeout(function () {
        that.getPostcost();
      }, 500) //延迟时间
    
    
    

    //console.log('-------'); 
    //var arr = [11,23,24]
    //console.log(typeof that.data.userAccount);
    //console.log(typeof that.data.allamount); 
    //console.log(that.data.ids.indexOf(2)); 
    //console.log(arr.indexOf(23)); 
    
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
    var useaddr = wx.getStorageSync("useaddr");
    that.getCartList();
    that.getUseAddress(useaddr);
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