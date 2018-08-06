// pages/vip/vip.js

var app = getApp()
let router = app.globalData.router

Page({

    /**
     * 页面的初始数据
     */
    data: {
        siteurl: app.siteInfo.siteroot,
        header_ads: [],
        tejia_list: [],
        tejiadataList: [],
        shareNums: 0,
        uid: '',
        userInfo: [],
        footer: router.footerArray,
        pre_goods_list: [],    //上新预告
    },

    //获取头部广告
    getHeaderAdsList: function () {
        var that = this
        var url = app.util.url('qiyue/getVipHeaderAds');
        wx.request({
            url: url,
            data: {},
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
                that.setData({header_ads: res.data.result})
                //console.log(res)
            }
        });
    },

    //获取按钮广告
    getButtonadsList: function () {
        var that = this
        var url = app.util.url('qiyue/getVipButtonAds');
        wx.request({
            url: url,
            data: {},
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
                that.setData({button_ads: res.data.result})
                //console.log(res)
            }
        });
    },

    jumpUrl: function (e) {
        var that = this
        var url = e.currentTarget.dataset.url
        //console.log(url);
        wx.navigateTo({
            url: url
        })
    },

    //商品链接
    getGoodsInfo: function (e) {
        var that = this
        var id = e.currentTarget.dataset.id
        wx.navigateTo({
            url: '/pages/goods/goods?id=' + id
        })
    },

    tejianowTime: function () {//时间函数
        var that = this;
        var dates = new Array()
        var len = that.data.tejia_list.length;//时间数据长度

        //console.log(len)
        for (var i = 0; i < len; i++) {
            var tarr = new Array();
            var endtime = that.data.tejia_list[i].goods_endtime;//获取数据中的时间戳
            var ntime = new Date();
            var ntime = (Date.parse(ntime) / 1000);//当前时间戳
            var intDiff = endtime - ntime;//时间差：
            //console.log(endtime + "----" + ntime)
            var day = 0, hour = 0, minute = 0, second = 0;
            if (intDiff > 0) {//转换时间
                day = Math.floor(intDiff / (60 * 60 * 24));
                hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
                if (hour <= 9) hour = '0' + hour;
                if (minute <= 9) minute = '0' + minute;
                //if (second <= 9) second = '0' + second;
                //var str = '距活动开始还有：' + day + '天' + hour + '时' + minute + '分' + second + '秒'
                tarr[0] = day
                tarr[1] = hour
                tarr[2] = minute
                tarr[3] = second
                tarr[4] = ''
                // console.log(str)
            } else {
                //var str = "已结束！";
                tarr[4] = '已结束！'
                //clearInterval(ntimer);
            }
            // console.log(str);
            dates[i] = tarr;//在数据中添加difftime参数名，把时间放进去
        }
        //console.log(dates)
        that.setData({
            tejiadataList: dates
        })

    },


    //获取特价商品列表
    getTejialist: function () {
        var that = this
        var url = app.util.url('qiyue/getTejiaList');
        wx.request({
            url: url,
            data: {},
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
                that.setData({tejia_list: res.data.tejia_list})
                //console.log(res.data.tejia_list)
            }
        });
    },

    //获取会员信息
    getUserInfo: function (uid) {
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
                    uid: res.data.user.mem_id,
                    userInfo: res.data.user,
                })
                wx.setStorageSync("uid", res.data.user.mem_id);
                wx.setStorageSync("userInfo", res.data.user);
            }
        });
    },

    getShareNums: function (uid) {
        var that = this
        var url = app.util.url('qiyue/getShareNums/');
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
                    shareNums: res.data.shareNums,
                })
                //console.log(shareNums)
            }
        });
    },
    //提醒我
    tixing: function (e) {
        var that = this
        var goodsid = e.currentTarget.dataset.id
        var index = e.currentTarget.dataset.index
        var formId = e.detail.formId;
        var uid = wx.getStorageSync("uid");
        if (uid == '' || uid == 'undefined') {
            that.popAuth();
            return false
        }
        var url = app.util.url('qiyue/preGoodsTixing');
        wx.request({
            url: url,
            data: {
                uid: uid,
                goodsid: goodsid,
                formId: formId,
            },
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            // header: {}, // 设置请求的 header
            success: function (res) {
                wx.showToast({title: '预约成功', icon: 'success', duration: 1000})
                var pre_goods_list = that.data.pre_goods_list
                pre_goods_list[index]['tixing'] = 1;
                that.setData({
                    pre_goods_list: pre_goods_list
                })
            },
            fail: function (err) {
                // fail
                console.log('失败' + err);
                wx.showToast({title: '失败' + err, icon: 'loading', duration: 1000})
            },
            complete: function () {
                // complete
            }
        })

    },
    //获取预告商品列表
    getPreGoodslist: function () {
        var that = this
        var url = app.util.url('qiyue/getPreGoodsList');
        var uid = wx.getStorageSync("uid");
        wx.request({
            url: url,
            data: {},
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
                var pre_goods_list = res.data.pre_goods_list
                var len = pre_goods_list.length

                for (var i = 0; i < len; i++) {
                    var tx_uid = pre_goods_list[i]['goods_tx_uid']
                    if (uid > 0 && tx_uid != null) {
                        if (tx_uid.indexOf(uid) != -1) {
                            pre_goods_list[i]['tixing'] = 1
                        } else {
                            pre_goods_list[i]['tixing'] = 0
                        }
                    }
                }
                that.setData({ pre_goods_list})
                //console.log(that.data.pre_goods_list)
            }
        });
    },
    updateAll(){
        var that = this
        //获取头部广告
        that.getHeaderAdsList();
        //获取按钮广告
        that.getButtonadsList();
        //获取特价列表
        that.getTejialist();

        //获取预告商品列表
        that.getPreGoodslist();
    },


    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        var scope = this
        router.setActive(5)
        scope.setData({footer: router.footerArray})

        var that = this
        // app.util.footer(that);
        var uid = wx.getStorageSync("uid");
        var userInfo = wx.getStorageSync("userInfo");
        if (userInfo.mem_type == 0) {
            that.getShareNums(uid);
        }
        that.getUserInfo(uid);

        that.updateAll()


        wx.showShareMenu({
            withShareTicket: true,
        })

        var tjtimer = setInterval(that.tejianowTime, 1000);
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
        that.getTejialist();
        //获取预告商品列表
        that.getPreGoodslist();
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
        var title = '加入栖约VIP，天天0元购';

        return {
            title: title,
            path: '/pages/vip/vip?tjuid=' + uid,
            success: function (res) {
                console.log(res.shareTickets[0] + '----------')
                // console.log
                /**wx.getShareInfo({
          shareTicket: res.shareTickets[0],
          success: function (res) { 
              console.log(res)
              var iv = res.iv
              var encryptedData = res.encryptedData
              
              wx.login({
                success: function (res) {
                  var code = res.code
                  var url = app.util.url('qiyue/shareInfo');
                  var uid = wx.getStorageSync("uid");
                  wx.request({
                    url: url,
                    data: {
                      uid: uid,
                      code: code,
                      iv: iv,
                      encryptedData: encryptedData,
                    },
                    header: {
                      "Content-Type": "application/x-www-form-urlencoded"
                    },
                    method: 'POST',
                    success: function (res) {
                      console.log(res)
                      if (res.data.status == 200) {

                        console.log(res)
                        wx.showToast({ title: '处理成功',icon: 'success',duration: 1000 })
                        that.getUserInfo(uid);
                        console.log(res.data.openGId)
                        that.setData({ shareNums: res.data.shareNums })
                        
                        if (shareNums >= 5){
                          wx.showToast({ title: '您已获得一天体验VIP的资格', icon: 'none', duration: 3000 })
                        }

                      } else {
                        
                        wx.showToast({ title: '处理失败',icon: 'none',duration: 1000 })
                      }

                    }
                  })
                }
              });

           },
          fail: function (res) { console.log(res) },
          complete: function (res) { 

          }
        }) **/
            },
            fail: function (res) {
                // 分享失败
                console.log(res)
            }
        }
    }
})