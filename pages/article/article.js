// pages/article/article.js
var app = getApp()
let router= app.globalData.router

Page({

  /**
   * 页面的初始数据
   */
  data: {
    siteurl: app.siteInfo.siteroot,
    ads_list:[],
    article_list: [],
      footer:router.footerArray
  },
    indexScan: app.tapScan,
  //获取广告
  getAdsList: function () {
    var that = this
    var url = app.util.url('qiyue/getArticleAds');
    wx.request({
      url: url,
      data: {},
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({ ads_list: res.data.ads_list })
        console.log(res.data.ads_list)
      }
    });
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

  //文章详细
  getarticleview: function (e){
    var that = this
    var id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/articleview/articleview?id=' + id
    })
  },

  //获取文章列表
  getArticleList:function(){
    var that = this
    var url = app.util.url('qiyue/getArticleList');
    wx.request({
      url: url,
      data: {},
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({ article_list: res.data.article_list })
        console.log(res.data.article_list)
      }
    });
  },

  //点赞
  acticleZan:function(e){
    var that = this
    var id = e.currentTarget.dataset.id
    
    var index = e.currentTarget.dataset.key
    var zan = that.data.article_list[index].zan    
    zan++;
    var _article_list = that.data.article_list
    _article_list[index].zan = zan
    var url = app.util.url('qiyue/acticleZan');
    wx.request({
      url: url,
      data: { id: id },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        
        that.setData({ article_list: _article_list })
        wx.showToast({
          title: '点赞成功',
          icon: 'none',
          duration: 1000
        })
        console.log(res)
      }
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var scope = this
      router.setActive(1)
      scope.setData({footer:router.footerArray})
    wx.setNavigationBarTitle({
      title: '发现'
    })
    var that = this
    // app.util.footer(that);
    //获取文章列表
    that.getArticleList();

    //获取广告列表
    that.getAdsList();
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
    //获取文章列表
    that.getArticleList();

    //获取广告列表
    that.getAdsList();
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