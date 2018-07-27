// pages/category/category.js
var app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    siteurl: app.siteInfo.siteroot,
    ads_list: [],
    goods_list:[],  //商品列表
    navs: [],
    cid:0,
    currentTab: 0, //预设当前项的值
    scrollLeft: 0, //tab标题的滚动条位置
    tag_all:1,     //综合
    tag_addtime:0,  //上新时间
    tag_price:0   //价格排序 0:默认 1:升序 2：降序
  },

  // 点击标题切换当前页时改变样式
  swichNav: function (e) {
    var that = this;
    var cur = e.target.dataset.current;
    var cid = e.target.dataset.cid;
    if (this.data.currentTaB == cur) { return false; }
    else {
      this.setData({
        currentTab: cur
      })
      that.getCategoryGoods(cid)
    }
    
    console.log(cid)
    if (cid == 0) {
      wx.navigateTo({
        url: '/pages/index/index'
      })
    }
  },

  //切换排序状态
  changeOrder:function(e){
    var that = this
    var order = e.currentTarget.dataset.order
    if(order == 1){
      if(that.data.tag_all == 1){
        return false
      }else{
        that.setData({ tag_all: 1, tag_addtime: 0, tag_price: 0, })
        that.getCategoryGoods(that.data.cid);
      }
    }else if(order == 2){
      if (that.data.tag_addtime == 2) {
        return false
      } else {
        that.setData({ tag_all: 0, tag_addtime: 1, tag_price: 0, })
        that.getCategoryGoods(that.data.cid);
      }
    }else if(order == 3){
      if (that.data.tag_price == 1) {
        that.setData({ tag_all: 0, tag_addtime: 0, tag_price: 2, })
      } else {
        that.setData({ tag_all: 0, tag_addtime: 0, tag_price: 1, })
      }
      that.getCategoryGoods(that.data.cid);
    }
  },

  //商品链接
  getGoodsInfo: function (e) {
    var that = this
    var id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/goods/goods?id=' + id
    })
  },

  //获取分类商品
  getCategoryGoods:function(cid){
    var that = this
    var url = app.util.url('qiyue/getCategoryGoods');
    //console.log(that.data.tag_all)
    //console.log(that.data.tag_addtime)
    //console.log(that.data.tag_price)    
    wx.request({
      url: url,
      data: {
        cid:cid,
        tag_all:that.data.tag_all,
        tag_addtime:that.data.tag_addtime,
        tag_price:that.data.tag_price
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({ goods_list: res.data.goods_list })
        //console.log(res.data.sql)
      }
    });
  },

  //获取分类
  getCates: function () {
    var that = this
    var url = app.util.url('qiyue/getCates');
    wx.request({
      url: url,
      data: {},
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({ navs: res.data.navs })
        //console.log(res.data.navs)
      }
    });
  },

  //获取广告
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
        //console.log(res.data.ads_list)
      }
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    app.util.footer(that);
    var cid = options.cid
    var cur = options.cur
    that.setData({ currentTab: cur,cid:cid })
    //获取广告    
    that.getAdsList();
    //获取分类
    that.getCates();
    //获取商品列表
    that.getCategoryGoods(cid);

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