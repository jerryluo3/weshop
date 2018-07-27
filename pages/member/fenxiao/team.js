// pages/member/fenxiao/team.js

const app = getApp()


Page({

  /**
   * 页面的初始数据
   */
  data: {
    selected:1,
    firstnums:0,
    secondnums: 0,
    teamList:[]
  },

  getTeamList:function(grade){
    var that = this;
    var uid = wx.getStorageSync("uid");
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 1000
    })
    var url = app.util.url('qiyue/getTeamList')
    wx.request({
      url: url,
      data: {
        uid: uid,
        grade:grade
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({
          teamList: res.data.result,
          firstnums: res.data.firstnums,
          secondnums: res.data.secondnums
        })
        console.log(res.data)
      }
    })
  },

  chooseGrade:function(e){
    var that = this
    var types = e.currentTarget.dataset.type
    that.getTeamList(types);
    that.setData({
      selected:types
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.getTeamList(1);
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
    that.getTeamList(1);
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