// pages/goods/goods.js
var app = getApp()
var WxParse = require('../../wxParse/wxParse.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    'siteurl': app.siteInfo.siteroot,
    'goods':[],
    'pophide': 'hide',
    'popshare': 'hide',
    'popsharehb': 'hide',
    'popgoodsdesc':'hide',
    'showinfo':0,
    'showattr':0,
    'userInfo':[],
    'nowtime':'',
    'comment_list':[],    //评论列表
    'cartnums':0,         //购物车中的商品数量
    'stocks':0,           //库存
    'hideitem':1,
    'guige':[],
    'guige_name':'',       //选择的规格名称
    'guige_key': -1,     //选中的规格键值
    'guige_price':0,         //选中规格的价格
    'guige_fencheng':0,       //分成
    'limitnums':0,             //商品限购，大于0表示限购
    'nums':1            //购买数量
  },

  popGoodsDesc() {
    var that = this
    that.setData({ popgoodsdesc: '' });
  },

  closeGoodsDesc: function () {
    var that = this
    that.setData({ popgoodsdesc: 'hide' });
  },

  popShare: function () {
    var that = this    
    that.setData({ popshare: '' });
  },

  closeShare: function () {
    var that = this
    that.setData({ popshare: 'hide' });
  },

  popShareHB:function(){
    var that = this
    that.setData({ popsharehb: '', popshare: 'hide' });
    that.getShareHB();
  },

  closeSharehb: function () {
    var that = this
    that.setData({ popsharehb: 'hide' });
  },

  getShareHB:function(){
    var that = this
    that.getGoodsQR();
  },

  getGoodsQR: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    var url = app.util.url('sharepic/getGoodsShareImg/');
    wx.showToast({
      title: '加载数据中',
      icon: 'loading',
      duration: 1000
    })
    wx.request({
      url: url,
      data: {
        goodsid: that.data.goods.goods_id,
        uid: uid,
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        //console.log(res);
        that.setData({ shareImg: res.data.result, popsharehb: '' })     
      }

    });
  },

  save: function () {
    var that = this
    wx.showToast({
      title: '处理中',
      icon: 'loading',
      duration: 1000
    })
    wx.downloadFile({
      url: that.data.siteurl+that.data.shareImg,
      success: function (res) {
        var tempFilePath = res.tempFilePath
        wx.getSetting({
          success(res) {
            if (!res.authSetting['scope.writePhotosAlbum']) {
              wx.authorize({
                scope: 'scope.writePhotosAlbum',
                success() {
                  console.log('授权成功');
                  wx.saveImageToPhotosAlbum({
                    filePath: tempFilePath,
                  })
                },
                fail() {
                  console.log('授权失败');
                }
              })
            } else {
              //console.log('保存图片')
              //console.log(tempFilePath);
              wx.saveImageToPhotosAlbum({
                filePath: tempFilePath,
                success(res) {
                  wx.showModal({
                    title: '保存成功',
                    content: '您可以分享到朋友圈啦',
                    showCancel:false,
                    success: function (res) {
                      
                    }
                  })
                },
                fail(res) {
                  console.log(res)
                },
                complete(res) {
                  console.log(res)
                }
              })
            }

          }
        })
      }
    })
    
  },


  previewImage: function (e) {
    var img = e.currentTarget.dataset.src
    var urls = [img];
    //console.log(img);
    wx.previewImage({
      current: img, // 当前显示图片的http链接   
      urls: urls // 需要预览的图片http链接列表   
    })
  }, 

  

  showattr:function(e){
    var that = this
    var attr = e.currentTarget.dataset.attr
    if(attr == -1){
      that.setData({ showinfo: 0, showattr:0 })
    }else{
      that.setData({ showinfo: 1, showattr: attr})
      if(attr == 1){
        that.getCommentList();
      }
    }
  },

  getCommentList: function (goodsid){
    var that = this
    //var goodsid = that.data.goods.goods_id
    var uid = wx.getStorageSync("uid");
    var url = app.util.url('qiyue/getMemberCommentList');
    wx.request({
      url: url,
      data: {
        uid: uid,
        goodsid: goodsid
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({ comment_list: res.data.comment_list })
        //console.log(res.data.comment_list)
      }
    })
  },

  //加入购物车
  addToCart:function(){
    var that = this
    //that.checkAttr();
    if (that.data.guige_key == -1) {
      wx.showToast({
        title: '请选择规格',
        icon: 'loading',
        duration: 1000
      })
      return false
    }

    var uid = wx.getStorageSync("uid");
    var goodsid = that.data.goods.goods_id;
    var goodstitle = that.data.goods.goods_title;
    var goodsnum = that.data.nums;
    var goodsguige = that.data.guige_name;
    var goodsprice = that.data.guige_price;
    var goodsfencheng = that.data.guige_fencheng;

    var stocks = that.data.goods.goods_stocks
    if(stocks < goodsnum){
      wx.showToast({ title: '库存不足', icon: 'none', duration: 1000 })
      return false
    }

    if (uid > 0) {      
      
      wx.showToast({
        title: '加载中',
        icon: 'loading',
        duration: 1000
      })
      var url = app.util.url('qiyue/addToCart');
      wx.request({
        url: url,
        data: {
          uid: uid,          
          goodsid: goodsid, 
          goodstitle: goodstitle, 
          goodsnum: goodsnum,
          goodsguige: goodsguige,
          goodsprice: goodsprice,
          goodsfencheng: goodsfencheng,
        },
        header: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        method: 'POST',
        success: function (res) {
          console.log(uid + ',' + goodsnum + ',' + goodsid + ',' + goodsguige);
          if(res.data.status == 1){
            //wx.showToast({
              //title: '购物车中已有特价商品，一次只能购买一个特价商品',
              //icon: 'none',
              //duration: 2000
            //})
            wx.showModal({
              title: '提示',
              content: '购物车中已有特价商品，一次只能购买一个特价商品',
              showCancel: false,
              success: function (res) {

              }
            })
            return false;
          }
          if(res.data.status == 200){
            wx.showToast({
              title: '已加入购物车',
              icon: 'success',
              duration: 1000
            })
            that.closePopCart();
            //that.getCartNums();
            wx.navigateTo({
              url: '/pages/cart/cart'
            })
          }
        }
      })

    } else {
      that.popAuth();
    }
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
        } else {
          app.checkUserLogin(e.detail);
          //console.log(e.type.detail)
        }
      }
    })

    var that = this

    that.closeAuth();
    


  },


  //参数验证
  checkAttr:function(){
    var that = this

    if(that.data.guige_name == ''){
      wx.showToast({
        title: '请选择规格',
        icon: 'loading',
        duration: 1000
      })
      return false
    }else{
      return true
    }
    
  
  },

  //购物车数量加
  cartNumsADD:function(){
    var that = this
    var nums = that.data.nums
    var limitnums = that.data.limitnums
    var stocks = that.data.stocks
    
    if(limitnums > 0){
      //有限购
      if(nums < limitnums){
        if(nums < stocks){
          //有库存
          nums++
          that.setData({ nums: nums })
        }else{
          //库存存不足
          wx.showToast({ title: '库存不足',icon: 'loading',duration: 1000 })
          return false
        }        
      }else{
        //超过限购数量
        wx.showToast({ title: '超过限购数量', icon: 'loading', duration: 1000 })
        return false
      }
    }else{
      if (nums < stocks) {
        //有库存
        nums++
        that.setData({ nums: nums })
      } else {
        //库存存不足
        wx.showToast({ title: '请选择规格', icon: 'loading', duration: 1000 })
        return false
      }
      
    }
    console.log(that.data.nums)
  },

  //购物车数量减
  cartNumsJIAN: function () {
    var that = this
    var nums = that.data.nums
    var limitnums = that.data.limitnums
    var stocks = that.data.stocks

    if(nums > 1){
      nums--
      that.setData({ nums: nums })
    }else{
      return false;
    }
    console.log(that.data.nums)
  },

  //手动更新数量
  updateCartNums:function(e){
    var that = this
    var v = parseInt(e.detail.value);
    if(v  > 0){
      
    }else{
      v = 1;
    }
    that.setData({ nums:v })
  },


  //选择规格
  chooseGuige:function(e){
    var that = this
    var key = e.currentTarget.dataset.key
    var guige = e.currentTarget.dataset.guige
    var price = e.currentTarget.dataset.price
    var vipprice = e.currentTarget.dataset.vipprice
    var fencheng = e.currentTarget.dataset.fencheng
    var userInfo = wx.getStorageSync("userInfo");
    if (userInfo != '' && userInfo.mem_type == 1){
      var tprice = vipprice
    }else{
      var tprice = price
    }
    if (key == that.data.guige_key){
      that.setData({
        guige_name: '',
        guige_key: -1,
        guige_price: 0,
        guige_fencheng: 0
      })
    }else{
      that.setData({
        guige_name: guige,
        guige_key: key,
        guige_price: tprice,
        guige_fencheng: fencheng
      })
    }
    
  },

  //返回首页
  gohome:function(){
    console.log('回到首页')
    wx.navigateTo({
      url: '/pages/index/index'
    })
  },

  //跳转购物车
  gotocart:function(){
    wx.navigateTo({
      url: '/pages/cart/cart'
    })
  },

  //关闭弹出层
  closePopCart:function(){
    var that = this
    that.setData({ hideitem: 1 })
    console.log(that.data.hideitem);
  },

  //弹出
  popCart:function(){
    var that = this
    var userInfo = wx.getStorageSync("userInfo");
    var goods = that.data.goods
   
    if ((parseInt(userInfo.mem_type) <= 0 || typeof userInfo.mem_type == 'undefined') && goods.goods_iscommend == 1 && goods.goods_commend_type == 1) {
      wx.showToast({
        title: 'VIP用户才能购买此商品',
        icon: 'none',
        duration: 2000
      })
      return false;
    }
    that.setData({ hideitem: 0 })
    console.log(that.data.hideitem);
  },

  getGoodsInfo:function(id){
    var that = this
    var url = app.util.url('qiyue/getGoodsInfo/' + id);
    wx.request({
      url: url,
      data: {},
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
          that.setData({
            goods: res.data.goods,
            stocks: res.data.goods.goods_stocks,
            guige:res.data.guige,
            limitnums: res.data.goods.goods_maxnums,
          })
          WxParse.wxParse('content', 'html', res.data.goods.goods_content, that, 5)
        //console.log(res.data.goods)
      }
      
    });
  },

  //获取购物车中商品数量
  getCartNums:function(){
    var that = this
    var uid = wx.getStorageSync("uid");
    if(uid > 0){
      var url = app.util.url('qiyue/getCartNums/');
      wx.request({
        url: url,
        data: {
          uid:uid
        },
        header: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        method: 'POST',
        success: function (res) {
          that.setData({
            cartnums: res.data.cartnums
          })
          app.globalData.popcartnums = res.data.cartnums;
          console.log(app.globalData.popcartnums)
        }
      });
    }else{
      that.setData({
        cartnums: 0
      })
      app.globalData.popcartnums = 0;
    }
  },

  //更新推荐人员
  updateTjUser: function (tjuid) {
    var that = this
    var uid = wx.getStorageSync("uid");
    if (uid == '') {
      console.log('UID空的');
      wx.getSetting({
        success(res) {
          console.log(res);
          if (!res.authSetting['scope.userInfo']) {
            console.log('------没有授权----')
            that.popAuth();
          }
        }
      })

    } else {
      var url = app.util.url('qiyue/updateTjUser');
      wx.request({
        url: url,
        data: {
          uid: uid,
          tjuid: tjuid
        },
        header: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        method: 'POST',
        success: function (res) {

          console.log('推荐成功');
        }
      })
    }



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

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this

    //有推荐码
    var tjuid = options.tjuid
    var userInfo = wx.getStorageSync("userInfo");

    var ntime = new Date();
    var ntime = (Date.parse(ntime) / 1000);

    that.setData({
      userInfo: userInfo,
      nowtime: ntime
    })
    if (tjuid > 0) {
      wx.setStorageSync("tjuid", tjuid);
      if (userInfo != '' && userInfo.mem_firstgrade == 0) {
        console.log('更新上级');
        that.updateTjUser(tjuid);
      }
    }

    that.getGoodsInfo(options.id);
    that.getCommentList(options.id)
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
    that.getGoodsInfo(that.data.goods.goods_id);
    that.getCommentList(that.data.goods.goods_id)
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
    var that = this
    var uid = wx.getStorageSync("uid");
    var title = that.data.goods.goods_title;
    var id = that.data.goods.goods_id
    return {
      title: title,
      path: '/pages/goods/goods?id='+id+'&tjuid=' + uid,
      success: function (res) {
        console.log(res)
        // console.log        
      },
      fail: function (res) {
        // 分享失败
        console.log(res)
      }
    }
  }
})