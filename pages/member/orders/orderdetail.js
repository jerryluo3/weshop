// pages/member/orders/orderdetail.js

var app = getApp()
var domain = app.globalData.DOMAIN
var backURL ='/pages/member/orders/orders?otype=99&fromTM=1'
Page({

  /**
   * 页面的初始数据
   */
  data: {
    siteurl: app.siteInfo.siteroot,
    isshow: '',
    orderqrshow: '',
    goodsid: '',
    //host: app.siteInfo.siteroot,
    pics: [],
    toView: 0,
    scrollLop: 0,
    order: [],
    orderqr: '',
    peisong: []
  },

  hiddenPeisong: function () {
    var that = this
    that.setData({
      isshow: ''
    })
  },

  hiddenComment: function () {
    var that = this
    that.setData({
      commentshow: '',
      goodsid: ''
    })
  },

  delOrder: function (e) {
    var that = this
    var oid = e.currentTarget.dataset.oid;
    wx.showModal({
      title: '警告',
      content: '确定要删除订单吗？',
      success: function (res) {
        if (res.confirm) {
          var url = app.util.url('qiyue/delMemberOrder')
          wx.request({
            url: url,
            data: {
              oid: oid
            },
            header: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
              wx.showToast({
                title: '操作成功',
                icon: 'none',
                duration: 1000,
                success(ress) {
                  setTimeout(function () {
                      wx.navigateTo({
                          url: backURL
                      })
                  }, 1000) //延迟时间
                }
              })
            }
          })

        }
      }
    })
  },

  shouhuo: function (e) {
    var that = this
    var oid = e.currentTarget.dataset.oid;
    wx.showToast({
      title: '处理中',
      icon: 'loading',
      duration: 1000
    })
    var url = app.util.url('qiyue/getMemberOrderShouhuo')
    wx.request({
      url: url,
      data: {
        oid: oid
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        wx.showToast({
          title: '操作成功',
          icon: 'none',
          duration: 1000
        })
          setTimeout(function(){
              wx.navigateTo({
                  url: backURL
              })
          },1000)
      }
    })
  },

  showComment: function (e) {
    var that = this
    var goodsid = e.currentTarget.dataset.goodsid;
    that.setData({
      commentshow: 'isshow',
      goodsid: goodsid
    })
  },

  peisong: function (e) {
    var that = this
    var oid = e.currentTarget.dataset.oid;
    wx.showToast({
      title: '处理中',
      icon: 'loading',
      duration: 500
    })
    var url = app.util.url('qiyue/getMemberOrderKuaidi')
    wx.request({
      url: url,
      data: {
        oid: oid
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({
          isshow: 'isshow',
          peisong: res.data.result
        })
      }
    })
  },

  cancelOrder: function (e) {
    var that = this
    var id = e.currentTarget.dataset.oid;
    wx.showModal({
      title: '警告',
      content: '确定要删除订单吗？',
      success: function (res) {
        if (res.confirm) {
          var url = app.util.url('qiyue/getMemberOrderCancel')
          wx.request({
            url: url,
            data: {
              oid: oid
            },
            header: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
              wx.showToast({
                title: '操作成功',
                icon: 'none',
                duration: 1000,
                success(ress) {
                  setTimeout(function () {
                    that.getOrderDetail(id);
                  }, 2000) //延迟时间
                }
              })
            }
          })

        }
      }
    })

  },

  wepay: function (e) {
    var that = this
    var oid = e.currentTarget.dataset.oid;
    var uid = wx.getStorageSync("uid");
    //调用支付
    that.weixinPay(oid, uid);
  },

  weixinPay: function (oid, uid) {
    var that = this;
    var url = app.util.url('qiyue/payfee')
    wx.request({
      url: url,
      data: {
        uid: uid,
        oid: oid,
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      method: 'POST',
      success: function (res) {
        console.log('调起支付');
        console.log(res);
        wx.requestPayment({
          'timeStamp': res.data.timeStamp,
          'nonceStr': res.data.nonceStr,
          'package': res.data.package,
          'signType': 'MD5',
          'paySign': res.data.paySign,
          'success': function (res) {
            wx.showToast({
              title: '支付成功',
              icon: 'success',
              duration: 1500
            });
            setTimeout(function(){
                wx.navigateTo({
                    url: backURL
                })
            },1500)

          },
          'fail': function (res) {
            console.log('fail');
          },
          'complete': function (res) {
            console.log('complete');
          }
        });
      },
      fail: function (res) {
        console.log(res.data)
      }
    });
  },


  formSubmit: function (e) {
    //console.log('form发生了submit事件，携带数据为：', e.detail.value)
    var that = this
    var comment = e.detail.value.comment
    var pics = ''
    var uid = wx.getStorageSync("uid");

    console.log(uid);
    console.log(that.data.goodsid);
    console.log(comment);
    //return false;

    if (comment == '') {
      wx.showToast({ title: '请输入评论内容', icon: 'none', duration: 1000 })
      return false
    }

    wx.showToast({
      title: '处理中',
      icon: 'loading',
      duration: 1000
    })
    var url = app.util.url('qiyue/addComment');
    wx.request({
      url: url,
      data: {
        comment: comment,
        pics: that.data.pics,
        gid: that.data.goodsid,
        uid: uid
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      method: 'POST',
      success: function (res) {
        if (res.data.status == 200) {
          wx.showToast({
            title: '评论成功',
            icon: 'success',
            duration: 1000,
            success(ress) {
              that.hiddenComment();
            }
          })
        }
      }
    })
  },


  choose: function () {//这里是选取图片的方法
    var that = this,
      pics = this.data.pics;

    wx.chooseImage({
      count: 9 - pics.length, // 最多可以选择的图片张数，默认9      
      sizeType: ['original', 'compressed'], // original 原图，compressed 压缩图，默认二者都有
      sourceType: ['album', 'camera'], // album 从相册选图，camera 使用相机，默认二者都有
      success: function (res) {
        //var imgsrc = res.tempFilePaths;
        //pics = pics.concat(imgsrc);
        //that.setData({
        //pics: pics
        //});
        //console.log(that.data.pics);

        var successUp = 0; //成功个数  
        var failUp = 0; //失败个数  
        var length = res.tempFilePaths.length; //总共个数  
        var i = 0; //第几个  
        that.uploadDIY(res.tempFilePaths, successUp, failUp, i, length);
      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })

  },



  uploadimg: function () {//这里触发图片上传的方法
    var pics = this.data.pics;
    var url = app.util.url('qiyue/uploadFile');
    app.uploadimg({
      url: url,//这里是你图片上传的接口
      path: pics//这里是选取的图片的地址数组
    });
  },

  delchoose: function (e) {
    var key = e.currentTarget.dataset.ikey;
    var that = this
    var arr = that.data.pics

    arr.splice(key, 1);
    that.setData({
      pics: arr
    });
  },


  /* 函数描述：作为上传文件时递归上传的函数体体；
   * 参数描述： 
   * filePaths是文件路径数组
   * successUp是成功上传的个数
   * failUp是上传失败的个数
   * i是文件路径数组的指标
   * length是文件路径数组的长度
   */
  uploadDIY(filePaths, successUp, failUp, i, length) {
    var that = this
    var url = app.util.url('qiyue/uploadFile');
    wx.uploadFile({
      url: url,
      filePath: filePaths[i],
      name: 'fileData',
      formData: {},
      header: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      success: (resp) => {
        successUp++;
        var imgsrc = resp.data;
        var jsonDataStr = JSON.parse(resp.data);
        //console.log(resp.data);
        var pics = that.data.pics
        pics = pics.concat(jsonDataStr.up_file);
        that.setData({
          pics: pics
        });
      },
      fail: (res) => {
        failUp++;
      },
      complete: () => {
        i++;
        if (i == length) {
          wx.showToast('总共' + successUp + '张上传成功,' + failUp + '张上传失败！');
        }
        else {  //递归调用uploadDIY函数
          that.uploadDIY(filePaths, successUp, failUp, i, length);
        }
      },
    });
  },

  //获取商品详情
  getOrderDetail:function(id){
    var that = this
    var uid = wx.getStorageSync("uid");
    var url = app.util.url('qiyue/getMemberOrderDetail');
    wx.request({
      url: url,
      data: {
        id: id,
        uid: uid
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      method: 'POST',
      success: function (res) {       
        that.setData({
          order: res.data.result
        })
        console.log(res)
      }
    })

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    var id = options.id
    var oid = options.oid
    var uid = wx.getStorageSync("uid");
    that.getOrderDetail(id);
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
    that.getOrderDetail(that.data.order.id);
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