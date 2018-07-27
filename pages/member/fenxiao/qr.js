// pages/member/fenxiao/qr.js
const app = getApp()


Page({

  /**
   * 页面的初始数据
   */
  data: {
    siteurl: app.siteInfo.siteroot,
    shareQR: '',
    shareHB: '',
  },

  previewImage: function (e) {
    var img = e.currentTarget.dataset.src
    var urls = [img];
    console.log(img);
    wx.previewImage({
      current: img, // 当前显示图片的http链接   
      urls: urls // 需要预览的图片http链接列表   
    })
  }, 

  shareQR: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 1000
    })
    var url = app.util.url('qiyue/getUserShareQR')
    wx.request({
      url: url,
      data: {
        uid: uid
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        var shareHB = res.data.shareHB
        that.setData({
          shareQR: res.data.result,
          shareHB: res.data.shareHB,
          isshow: 'bg_show'
        })
        if (shareHB != ''){
          that.showShareHB();
        }else{          
          setTimeout(function () {
            that.buildImg();
          }, 1000) //延迟时间
        }
        
      }
    })
  },

  showShareHB:function(){
    var that = this
    var shareHB = that.data.siteurl + that.data.shareHB
    console.log(shareHB)
    var ctx = wx.createCanvasContext('myCanvas');
    ctx.setFillStyle('white');
    ctx.fillRect(0, 0, 375, 700);
    wx.downloadFile({
      url: shareHB,
      success: function (res) {
        ctx.drawImage(res.tempFilePath, 0, 0, 375, 700);
      }
    })
    wx.showToast({
      title: '正在生成',icon: 'loading',duration: 1000,
      success(ress) {
        setTimeout(function () {
          ctx.draw();
        }, 1000) //延迟时间
      }
    })
    console.log('绘制完成')
  },




  buildImg: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    var userInfo = wx.getStorageSync("userInfo");
    var goods = that.data.goods

    var words = '我是' + userInfo.mem_nickname+',邀请您加入VIP栖约会员';
    var len = words.length;
    var qrCodePath = that.data.siteurl + 'data/ewm/' + uid + '.png'
    var bg = 'https://weapp.qiyue99.com/data/asset/bg/sharebg.jpg'

    
    console.log('开始绘图')
    const ctx = wx.createCanvasContext('myCanvas');
    //绘制背景
    ctx.setFillStyle('white');
    ctx.fillRect(0, 0, 375, 700);
    //绘制BG图片

    wx.downloadFile({
      url: bg,
      success: function (res) {
        ctx.drawImage(res.tempFilePath, 0, 0, 375, 700);

        //绘制分享的文字1
        ctx.setFontSize(14);
        ctx.setFillStyle('#ffffff');
        ctx.setTextAlign('center');
        ctx.fillText(words, 190, 120);

        //绘制二维码图片    
        wx.downloadFile({
          url: qrCodePath,
          success: function (res) {
            ctx.drawImage(res.tempFilePath, 110, 310, 150, 150);
          }
        })
      }
    })

    

    wx.showToast({
      title: '正在生成',
      icon: 'loading',
      duration: 1000,
      success(ress) {
        setTimeout(function () {
          ctx.draw();
        }, 1000) //延迟时间
      }
    })

    console.log('绘制完成')
  },

  save: function () {
    wx.canvasToTempFilePath({
      x: 0,
      y: 0,
      width: 375,
      height: 700,
      destWidth: 375,
      destHeight: 700,
      canvasId: 'myCanvas',
      success: function (res) {
        var tempFilePath = res.tempFilePath
        console.log(tempFilePath);
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
                  wx.showToast({
                    title: '保存成功',
                    icon: 'success',
                    duration: 1000
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
        //wx.saveImageToPhotosAlbum({
        //filePath: res.tempFilePath,
        //})

        //console.log(res.tempFilePath)
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.shareQR();
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
    that.shareQR();
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