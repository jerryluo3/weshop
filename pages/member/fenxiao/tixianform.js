// pages/member/fenxiao/tixianform.js

var app = getApp()


Page({

  /**
   * 页面的初始数据
   */
  data: {
    allmoney:'0.00',
    inputmoney:'',
    userInfo:[],
    ctype:0,
  },

  changeType:function(e){
    var that = this
    var ctype = e.currentTarget.dataset.ctype
    console.log(ctype)
    that.setData({
      ctype: ctype
    })
  },

  

  bindFormSubmit: function (e) {
    var that = this
    var txmoney = e.detail.value.txmoney;
    var txpaytype = that.data.ctype;
    var txname = e.detail.value.txname;
    var txaccount = e.detail.value.txaccount;
    var uid = wx.getStorageSync("uid");

    if (txmoney == '') {
      wx.showToast({
        title: '请输入提现金额',
        icon: 'none',
        duration: 1000
      })
      return false
    }
    var usermoney = parseFloat(that.data.userInfo.mem_usermoney) 
    if (txmoney > usermoney ){
      wx.showToast({ title: '您输入的金额大于实际可提现金额',icon: 'none',duration: 2000 })
      return false
    }

    if (txpaytype > 0 && txmoney < 100) {
      wx.showToast({
        title: '可提金额小于100元不能提现在微信支付宝',
        icon: 'none',
        duration: 2000
      })
      return false
    }

    if (txpaytype > 0 && txname == '') {
      wx.showToast({
        title: '请输入姓名',
        icon: 'none',
        duration: 1000
      })
      return false
    }

    if (txpaytype > 0 && txaccount == '') {
      wx.showToast({
        title: '请输入账号',
        icon: 'none',
        duration: 1000
      })
      return false
    }

    if (uid > 0) {
      wx.showToast({
        title: '处理中',
        icon: 'loading',
        duration: 1000
      })
      var url = app.util.url('qiyue/sendTixian')
      wx.request({
        url: url, 
        data: { txmoney: txmoney, uid: uid, txpaytype: txpaytype, txname: txname, txaccount: txaccount },
        header: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        method: 'POST',
        success: function (res) {  
          console.log(res.data.uid)        
          if (res.data.result == 200) {
            wx.showToast({
              title: '申请成功，等待处理',
              icon: 'success',
              duration: 2000,
              success: function () {
                setTimeout(function () {
                  wx.navigateTo({                   
                    url: '/pages/member/member',
                  })
                }, 2000);
              }
            })
          }
          if (res.data.result == 1) {
            wx.showLoading({
              title: '操作失败',
              fail: 'fail',
              duration: 1000
            })
          }
        },
        fail: function () {

        }
      })
    } else {
      app.getUserDataToken();
    }
  },

  allmoney: function () {
    var that = this
    that.setData({
      inputmoney: that.data.userInfo.mem_usermoney
    })
  },

  clearallmoney: function () {
    var that = this
    that.setData({
      inputmoney: ''
    })
  },

  getMemberInfo: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 1000
    })
    var url = app.util.url('qiyue/getUserBasicInfo')
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
        that.setData({
          userInfo: res.data.result,
        })
        console.log(that.data)
      }
    })
  },


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.getMemberInfo();
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
    that.getMemberInfo();
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