// pages/vip/vipdetail.js

const app = getApp()
var WxParse = require('../../wxParse/wxParse.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    siteurl: app.siteInfo.siteroot,
    ctype:0,
    content:'',
  },

  changeVip:function(e){
    var that = this
    var ctype = e.currentTarget.dataset.ctype;    
    that.getVipContent(ctype);
    that.setData({ ctype: ctype });
  },

  getVipContent: function (ctype){
    var that = this
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 1000
    })
    var url = app.util.url('qiyue/getVipContent');
    wx.request({
      url: url,
      data: {
        ctype: ctype
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        
        that.setData({
          content: WxParse.wxParse('content', 'html', res.data.result, that, 5),
        })
      }
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    that.getVipContent();
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
    that.getVipContent(that.data.ctype);
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