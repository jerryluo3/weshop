// pages/toupiao/paihang.js

var app = getApp()
var page = 1;

Page({

  /**
   * 页面的初始数据
   */
  data: {
    siteurl: app.siteInfo.siteroot,
    ads: [],
    page: page,
    bang_list: [],
    scrollTop: 0,
    floorstatus: false
  },

  goTop: function (e) {
    this.setData({
      scrollTop: 0
    })
  },
  scroll: function (e) {
    if (e.detail.scrollTop > 500) {
      this.setData({
        floorstatus: true
      });
    } else {
      this.setData({
        floorstatus: false
      });
    }
  },

  getMore: function () {
    var that = this
    var page = that.data.page + 1;
    this.setData({
      page: page
    })
    that.getToupiaoBangList();
  },

  getToupiaoAds: function () {
    var that = this
    var url = app.util.url('qiyue/getToupiaoAds');
    wx.request({
      url: url,
      data: {},
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({ ads: res.data.ads })
      }
    });
  },

  getToupiaoBangList: function () {
    var that = this
    wx.showToast({ title: '加载中', icon: 'loading', duration: 1000 })
    var url = app.util.url('qiyue/getToupiaoBangList');
    wx.request({
      url: url,
      data: {
        page: that.data.page
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({ bang_list: that.data.bang_list.concat(res.data.bang_list) })
      }
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.setNavigationBarTitle({
      title: '最美茶水间投票-排行榜'
    })
    var that = this
    that.getToupiaoAds();
    that.getToupiaoBangList();
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