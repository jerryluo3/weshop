// pages/member/recharge/recharge.js
var app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    moneyList: [],
      userInfo:{},
  },
//获取会员信息
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
                    userInfo: res.data.user,
                })
                wx.setStorageSync("uid", res.data.user.mem_id);
                wx.setStorageSync("userInfo", res.data.user);
            }
        });
    },


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // this.getAccountMoneyList();
    var uid = wx.getStorageSync("uid");
    this.getUserInfo(uid)
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
    that.getAccountMoneyList();
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