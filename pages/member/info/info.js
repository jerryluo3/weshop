// pages/member/info/info.js

var app = getApp()


Page({

  /**
   * 页面的初始数据
   */
  data: {
    siteurl: app.siteInfo.siteroot,
    uid:'',
    user:[],
    avatar:'',
  },

  //获取会员信息
  getUserInfo: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    var url = app.util.url('qiyue/getUserInfo/' + uid);
    wx.request({
      url: url,
      data: {},
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({
          uid: res.data.user.mem_id,
          user: res.data.user,
          avatar: res.data.user.mem_avatar
        })
      }
    });
  },

  chooseAvatar: function () {//这里是选取图片的方法
    var that = this

    wx.chooseImage({
      count: 1, // 最多可以选择的图片张数，默认9      
      sizeType: ['original', 'compressed'], // original 原图，compressed 压缩图，默认二者都有
      sourceType: ['album', 'camera'], // album 从相册选图，camera 使用相机，默认二者都有
      success: function (res) {
        wx.showToast({
          title: '处理中',
          icon: 'loading',
          duration: 1000
        })        
        var url = app.util.url('qiyue/uploadFile')

        wx.uploadFile({
          url: url,
          filePath: res.tempFilePaths[0],
          name: 'fileData',
          formData: {},
          header: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          success: (resp) => {
            console.log(resp)
            var jsonDataStr = JSON.parse(resp.data);
            that.setData({
              avatar: that.data.siteurl+jsonDataStr.up_file
            });
            
          }
        });


      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })

  },

  formSubmit: function (e) {
    //console.log('123');
    var that = this
    var avatar = that.data.avatar
    var mem_nickname = e.detail.value.mem_nickname
    var mem_mobile = e.detail.value.mem_mobile
    
    var uid = wx.getStorageSync("uid");

    if (avatar == '') {
      wx.showToast({ title: '请上传头像', icon: 'none', duration: 1000 })
      return false
    }
    if (mem_nickname == '') {
      wx.showToast({ title: '请输入昵称', icon: 'none', duration: 1000 })
      return false
    }
    if (mem_mobile == '') {
      wx.showToast({ title: '请输入手机号码', icon: 'none', duration: 1000 })
      return false
    }
    wx.showToast({
      title: '处理中',
      icon: 'loading',
      duration: 2000
    })
    var url = app.util.url('qiyue/saveMemberInfo');
    wx.request({
      url: url,
      data: {
        mem_avatar: avatar,
        mem_nickname: mem_nickname,
        mem_mobile: mem_mobile,
        uid: uid,
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {        
        if (res.data.status == 200) {
          wx.showToast({
            title: '修改成功',
            icon: 'success',
            duration: 1500,
            success(ress) {
              setTimeout(function () {
                wx.navigateTo({
                  url: '/pages/member/member?refresh=1'
                })
              }, 1500) //延迟时间
            }
          })
        }
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this

    that.getUserInfo();
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