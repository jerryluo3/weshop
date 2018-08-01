const app = getApp()
const utils = app.globalData.utils
const domain = app.globalData.DOMAIN
// var router= app.globalData.router
Page({
    data: {
        moneyList: [],
        userInfo:{},
    },
    //获取会员信息
    getUserInfo:function(uid){
        var that = this
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
                    userInfo: res.data.user,
                })
                wx.setStorageSync("uid", res.data.user.mem_id);
                wx.setStorageSync("userInfo", res.data.user);
            }
        });
    },
    getAccountMoneyList: function () {
        var that = this
        var uid = wx.getStorageSync("uid");
        console.log(uid);
        var url = app.util.url('qiyue/getAccountMoneyList');
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
                    moneyList: res.data.result
                })
                console.log(res.data.result)
            }
        })
    },
    /*-----------------------生命周期-----------------------*/
    onLoad: function (options) {
        this.getAccountMoneyList();
        var uid = wx.getStorageSync("uid");
        this.getUserInfo(uid)
    },
    onShow: function () {

    },
    onPullDownRefresh: function () {
        var that = this
        that.getAccountMoneyList();
        wx.stopPullDownRefresh();
    },
    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {

    },

})
