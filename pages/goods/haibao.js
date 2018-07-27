// pages/goods/haibao.js

var app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    siteurl: app.siteInfo.siteroot,
    goods: [],
    uid:'',
    userInfo: [],
    shareTempFilePath:''
  },

  /**
 * 绘制分享的图片
 * @param goodsPicPath 商品图片的本地链接
 * @param qrCodePath 二维码的本地链接
 */
  drawSharePic: function () {
    var that = this
    var goodsPicPath = 'https://weapp.qiyue99.com/data/uploads/1528887932.jpg'
    var qrCodePath = 'https://weapp.qiyue99.com/data/ewm/1.png'
    wx.showLoading({
      title: '正在生成图片...',
      mask: true,
    });
    //y方向的偏移量，因为是从上往下绘制的，所以y一直向下偏移，不断增大。
    let yOffset = 20;
    const goodsTitle = this.data.goods.goods_hb_title;
    
    const price = this.data.goods.goods_vipprice;
    const marketPrice = this.data.goods.goods_price;
    const title1 = '您的好友邀请您一起分享精品好货';
    const title2 = '立即打开看看吧';
    const codeText = '长按识别小程序码查看详情';
    const imgWidth = 780;
    const imgHeight = 1600;

    const canvasCtx = wx.createCanvasContext('shareCanvas');
    //绘制背景
    canvasCtx.setFillStyle('white');
    canvasCtx.fillRect(0, 0, 390, 800);
    //绘制分享的标题文字
    canvasCtx.setFontSize(24);
    canvasCtx.setFillStyle('#333333');
    canvasCtx.setTextAlign('center');
    canvasCtx.fillText(title1, 195, 40);
    //绘制分享的第二行标题文字
    canvasCtx.fillText(title2, 195, 70);
    //绘制商品图片
    canvasCtx.drawImage(goodsPicPath, 0, 90, 390, 390);
    //绘制商品标题
    yOffset = 490;
    
    //绘制价格
    yOffset += 8;
    canvasCtx.setFontSize(20);
    canvasCtx.setFillStyle('#f9555c');
    canvasCtx.setTextAlign('left');
    canvasCtx.fillText('￥', 20, yOffset);
    canvasCtx.setFontSize(30);
    canvasCtx.setFillStyle('#f9555c');
    canvasCtx.setTextAlign('left');
    canvasCtx.fillText(price, 40, yOffset);
    //绘制原价
    const xOffset = 2 * 24 + 50;
    canvasCtx.setFontSize(20);
    canvasCtx.setFillStyle('#999999');
    canvasCtx.setTextAlign('left');
    canvasCtx.fillText('原价:¥' + marketPrice, xOffset, yOffset);
    //绘制原价的删除线
    canvasCtx.setLineWidth(1);
    canvasCtx.moveTo(xOffset, yOffset - 6);
    canvasCtx.lineTo(xOffset + (3 + 2 / 2) * 20, yOffset - 6);
    canvasCtx.setStrokeStyle('#999999');
    canvasCtx.stroke();
    //绘制最底部文字
    canvasCtx.setFontSize(18);
    canvasCtx.setFillStyle('#333333');
    canvasCtx.setTextAlign('center');
    canvasCtx.fillText(codeText, 195, 780);
    //绘制二维码
    canvasCtx.drawImage(qrCodePath, 95, 550, 200, 200);
    canvasCtx.draw();
    //绘制之后加一个延时去生成图片，如果直接生成可能没有绘制完成，导出图片会有问题。
    setTimeout(function () {
      wx.canvasToTempFilePath({
        x: 0,
        y: 0,
        width: 390,
        height: 800,
        destWidth: 390,
        destHeight: 800,
        canvasId: 'shareCanvas',
        success: function (res) {
          that.setData({
            shareImage: res.tempFilePath,
            showSharePic: true
          })
          wx.hideLoading();
        },
        fail: function (res) {
          console.log(res)
          wx.hideLoading();
        }
      })
    }, 2000);
  },
  

  getGoodsInfo: function (id) {
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
          goods: res.data.goods
        })
        //console.log(res.data.goods)
      }

    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    var id = options.id
    that.getGoodsInfo(id);
    var uid = wx.getStorageSync("uid");
    var userInfo = wx.getStorageSync("userInfo");
    
    that.setData({
      uid: uid,
      userInfo: userInfo,
    })
    
    console.log(that.data.goods)
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