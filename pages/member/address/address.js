// pages/member/address/address.js
var app  = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    comefrom:'',
    useraddr:'',
    addressList: [],
    cartnums: 0,         //购物车中的商品数量
  },


  //获取购物车中商品数量
  getCartNums: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    if (uid > 0) {
      var url = app.util.url('qiyue/getCartNums/');
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
            cartnums: res.data.cartnums
          })
          //console.log(res.data.cartnums)
        }
      });
    } else {
      that.setData({
        cartnums: 0
      })
    }
  },


  setDefault: function (e) {
    var that = this
    var a_id = e.currentTarget.dataset.aid
    var uid = wx.getStorageSync("uid");
    console.log(uid);
    wx.showToast({
      title: '处理中',
      icon: 'loading',
      duration: 1000
    })
    var url = app.util.url('qiyue/setAddressDefault')
    wx.request({
      url: url, 
      data: {
        a_id: a_id,
        a_uid: uid
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        console.log(res.data.result)
        that.getAddressList();
      }
    })
  },

  delbtn: function (e) {
    var that = this
    var id = e.currentTarget.dataset.id
    wx.showModal({
      title: '提示',
      content: '确定要删除吗？',
      success: function (res) {
        if (res.confirm) {
          that.deladdress(id);
        }
      }
    })
  },
  deladdress: function (id) {
    var that = this
    var uid = wx.getStorageSync("uid");
    if (uid > 0) {
      wx.showToast({
        title: '处理中',
        icon: 'loading',
        duration: 1000
      })
      var url = app.util.url('qiyue/deleteMemberAddress')
      wx.request({
        url: url, 
        data: {
          id: id,
          uid: uid
        },
        header: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        method: 'POST',
        success: function (res) {

          that.getAddressList();
        }
      })
    }
  },

  editAddress: function (event) {
    wx.navigateTo({
      url: event.currentTarget.dataset.url
    })
  },

  getAddressList: function () {
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 1000
    })
    var that = this
    var uid = wx.getStorageSync("uid");
    var useaddr = wx.getStorageSync("useaddr");
    
    var url = app.util.url('qiyue/getMemberAddressList')
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
        that.setData({
          addressList: res.data.result,
          useaddr: useaddr
        })
        console.log(useaddr)
      }
    })
  },

  jumpUrl:function(e){
    var url = e.currentTarget.dataset.url
    wx.navigateTo({
      url: url
    })
  },

  //购物车跳转选择地址
  useAddress:function(e){
    var that = this
    var aid = e.currentTarget.dataset.aid
    that.setData({
      useaddr: aid
    })
    wx.setStorageSync("useaddr",aid);
    var url = '/pages/cart/cart';
    wx.reLaunch({
      url: url
    })
  },


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.setNavigationBarTitle({
      title: '收货地址'
    })
    var that = this
    var comefrom = options.comefrom
    that.setData({
      comefrom: comefrom
    })
    var userInfo = wx.getStorageSync("userInfo");
    console.log(userInfo);

    that.getAddressList();

    that.getCartNums();
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
    that.getAddressList();
    that.getCartNums();
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