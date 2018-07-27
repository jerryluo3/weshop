// pages/articleview/articleview.js
var app = getApp()
var WxParse = require('../../wxParse/wxParse.js');
let router= app.globalData.router

Page({

  /**
   * 页面的初始数据
   */
  data: {
    siteurl: app.siteInfo.siteroot,
    row:'',
    content:'',
    id:'',
    showad:'',

      footer:router.footerArray,

  },

  jumpUrl: function (e) {
    var that = this
    var url = e.currentTarget.dataset.url
    if (url == '') {
      return false
    } else {
      wx.navigateTo({
        url: url
      })
    }
  },

  close_ad:function(){
    var that = this
    that.setData({
      showad: 'hide',
    })
  },

  getContent:function(id){
    var that = this
    var url = app.util.url('qiyue/getContent/'+id);
    wx.request({
      url: url,
      data: {},
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({ 
          row: res.data.article,
        })
        WxParse.wxParse('content', 'html', res.data.article.content, that, 5)
        //console.log(res)
      }
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

      var scope = this
      router.setActive(5)
      scope.setData({footer:router.footerArray})

    wx.setNavigationBarTitle({
      title: '文章详情'
    })
    var that = this
    that.setData({
      id: options.id,
    })
    // app.util.footer(that);
    that.getContent(options.id);
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
    var id = that.data.id
    that.getContent(id);
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
    var uid = wx.getStorageSync("uid");
    return {
      title: '栖约·惠生活',
      path: '/pages/index/index?tjuid=' + uid,
      success: function (res) {

      },
      fail: function (res) {
        // 分享失败
        console.log(res)
      }
    }
  }
})