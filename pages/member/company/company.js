const app = getApp()
const utils = app.globalData.utils
const domain = app.globalData.DOMAIN
// var router= app.globalData.router
Page({
    data: {
        top:[
            { avatar:'/assets/member/avart.jpg',qyb:1234},
            { avatar:'/assets/member/avart.jpg',qyb:2345},
            { avatar:'/assets/member/avart.jpg',qyb:6789},
        ],
    },
    /*-----------------------生命周期-----------------------*/
    onLoad: function (options) {

    },
    onShow: function () {

    },
    onPullDownRefresh: function () {
        wx.stopPullDownRefresh();
    },
    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {

    },

})
