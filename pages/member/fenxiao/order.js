// pages/member/fenxiao/order.js

const app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    toView: 99,
    scrollLop: 0,
    orderList: []
  },

  orderList: function (e) {
    var that = this
    var otype = e.currentTarget.dataset.type;
    that.setData({
      toView: otype
    })
    that.getFenxiaoOrderList(otype);
  },

  getFenxiaoOrderList: function (otype) {
    var that = this;
    var uid = wx.getStorageSync("uid");
    console.log(otype);
    if (uid > 0) {
      wx.showToast({
        title: '加载中',
        icon: 'loading',
        duration: 1000
      })
      var url = app.util.url('qiyue/getFenxiaoOrderList');
      wx.request({
        url: url,
        data: {
          uid: uid,
          otype: otype
        },
        header: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        method: 'POST',
        success: function (res) {
          that.setData({
            orderList: res.data.result
          })
          console.log(res.data.result)
        }
      })

    } else {
      app.getUserDataToken();
    }
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    var otype = 99;
    that.getFenxiaoOrderList(otype);
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
    var otype = 99;
    that.getFenxiaoOrderList(otype);
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