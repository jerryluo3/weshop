// pages/member/kefu/kefu.js
var app = getApp()
var WxParse = require('../../../wxParse/wxParse.js');
let router = app.globalData.router


Page({

  /**
   * 页面的初始数据
   */
  data: {
    title: '',
    content: '',
    tcontent: '',
    id: '',
      footer:router.footerArray
  },

  getKefuContent: function () {
    var that = this
    var url = app.util.url('qiyue/getKefuContent');
    wx.request({
      url: url,
      data: {},
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {

          WxParse.wxParse('content', 'html', res.data.cnxh, that, 5)
          WxParse.wxParse('tcontent', 'html', res.data.thhlc, that, 5)

      }
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    that.getKefuContent();

    /*footer*/
      let footer = this.data.footer
      router.setActive(3)
      this.setData({footer:router.footerArray})
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
    that.getKefuContent();
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