//index.js
//获取应用实例
var app = getApp()
var router = app.globalData.router

Page({
    data: {
        siteurl: app.siteInfo.siteroot,
        ads_list: [],
        midadds_list: [],
        popads: [],
        navs: [],
        pophide: 'hide',
        sanbaohide: 'hide',
        popadshide: 'hide',
        cartnums: 0,
        topfixed: '',
        midfixed: '',
        cat_goods_list: [],  //商品列表
        cid: 0,

        userInfo: "",
        currentTab: 0, //预设当前项的值
        scrollLeft: 0, //tab标题的滚动条位置
        showblock: 0,  //0:正在热售 1:上新预告
        tejia_list: [],
        goods_list: [],      //正在热售
        dataList: [],
        predataList: [],
        tejiadataList: [],
        lastX: 0,          //滑动开始x轴位置
        lastY: 0,          //滑动开始y轴位置
        text: "没有滑动",
        currentGesture: 0, //标识手势
        tag_all: 1,     //综合
        tag_addtime: 0,  //上新时间
        tag_price: 0,   //价格排序 0:默认 1:升序 2：降序

        footer: router.footerArray,
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

    getTime: function (e) {
        var id = e.currentTarget.dataset.id;
        var _this = this;
        var timeList = _this.data.timeList;
        var lastDate = timeList[id].lastDate;
        lastDate = Date.parse(lastDate);
        console.log(lastDate)
        app.util.interval(lastDate, _this, id);
    },

    //弹出需要授权层
    popAuth: function () {
        var that = this
        that.setData({pophide: ''});
    },

    closeAuth: function () {
        var that = this
        that.setData({pophide: 'hide'});
    },

    //弹出三包层
    popSanbao: function () {
        var that = this
        that.setData({sanbaohide: ''});
    },

    closeSanbao: function () {
        var that = this
        that.setData({sanbaohide: 'hide'});
    },

    closeAds: function () {
        var that = this
        that.setData({popadshide: 'hide'});
        var ntime = new Date();
        var ntime = (Date.parse(ntime) / 1000);//当前时间戳
        wx.setStorageSync("popadsCloseTime", ntime);
    },


    //商品链接
    getGoodsInfo: function (e) {
        var that = this
        var id = e.currentTarget.dataset.id
        wx.navigateTo({
            url: '/pages/goods/goods?id=' + id
        })
    },

    //获取热售商品列表
    getGoodslist: function () {
        var that = this
        var url = app.util.url('qiyue/getGoodsList');
        wx.request({
            url: url,
            data: {},
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
                that.setData({goods_list: res.data.goods_list})
            }
        });
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
                that.setData({pre_goods_list: pre_goods_list})
                //console.log(that.data.pre_goods_list)
            }
        });
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

    // 点击标题切换当前页时改变样式
    swichNav: function (e) {
        var that = this;
        var cur = e.target.dataset.current;
        var cid = e.target.dataset.cid;
        if (this.data.currentTaB == cur) {
            return false;
        }
        else {
            this.setData({
                currentTab: cur
            })
            that.setData({cid: cid})
            that.getCategoryGoods(cid)
        }
        //console.log(that.data.cat_goods_list);
        //console.log(that.data.goods_list);

    },

    //获取分类商品
    getCategoryGoods: function (cid) {
        var that = this
        var url = app.util.url('qiyue/getCategoryGoods');
        //console.log(that.data.tag_all)
        //console.log(that.data.tag_addtime)
        //console.log(that.data.tag_price)
        wx.request({
            url: url,
            data: {
                cid: cid,
                tag_all: that.data.tag_all,
                tag_addtime: that.data.tag_addtime,
                tag_price: that.data.tag_price
            },
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
                that.setData({cat_goods_list: res.data.goods_list})
                console.log(res.data.goods_list)
                console.log(res.data.sql)
            }
        });
    },

    //切换排序状态
    changeOrder: function (e) {
        var that = this
        var order = e.currentTarget.dataset.order
        if (order == 1) {
            if (that.data.tag_all == 1) {
                return false
            } else {
                that.setData({tag_all: 1, tag_addtime: 0, tag_price: 0,})
                that.getCategoryGoods(that.data.cid);
            }
        } else if (order == 2) {
            if (that.data.tag_addtime == 2) {
                return false
            } else {
                that.setData({tag_all: 0, tag_addtime: 1, tag_price: 0,})
                that.getCategoryGoods(that.data.cid);
            }
        } else if (order == 3) {
            if (that.data.tag_price == 1) {
                that.setData({tag_all: 0, tag_addtime: 0, tag_price: 2,})
            } else {
                that.setData({tag_all: 0, tag_addtime: 0, tag_price: 1,})
            }
            that.getCategoryGoods(that.data.cid);
        }
    },

    //获取分类
    getCates: function () {
        var that = this
        var url = app.util.url('qiyue/getCates');
        wx.request({
            url: url,
            data: {},
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
                that.setData({navs: res.data.navs})
                //console.log(res.data.navs)
            }
        });
    },

    //获取广告
    getAdsList: function () {
        var that = this
        var url = app.util.url('qiyue/getIndexAds');
        wx.request({
            url: url,
            data: {},
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
                that.setData({ads_list: res.data.ads_list})
                //console.log(res.data.ads_list)
            }
        });
    },

    //获取弹出广告
    getIndexPopAds: function () {
        var that = this
        //var popads = wx.getStorageSync("popads");
        var popadsCloseTime = wx.getStorageSync("popadsCloseTime");
        var ntime = new Date();
        var ntime = (Date.parse(ntime) / 1000);//当前时间戳
        var ltime = ntime - popadsCloseTime;

        if (popadsCloseTime > 0 && ltime < 86400) {//小于一天
            console.log(ltime)
            return false;
        }

        var url = app.util.url('qiyue/getIndexPopAds');
        wx.request({
            url: url,
            data: {},
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
                var popadshide = !!res.data.ads_list ? '' : 'hide';
                that.setData({popads: res.data.ads_list, popadshide: popadshide})

                //console.log(that.data.popadshide)
            }
        });
    },

    //获取中间广告
    getMidAdsList: function () {
        var that = this
        var url = app.util.url('qiyue/getIndexMidAds');
        wx.request({
            url: url,
            data: {},
            header: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {
                that.setData({midadds_list: res.data.midadds_list})
                //console.log(res.data.midadds_list)
            }
        });
    },

    //选择显示块， 0：正在热售 1：上新预告
    chooseblock: function (e) {
        var that = this
        var showblock = e.currentTarget.dataset.id
        that.setData({showblock: showblock})

        if (that.data.showblock == 0) {
            //that.nowTime();
            clearInterval(ntimer);
            var ntimer = setInterval(that.nowTime, 1000);
            clearInterval(pretimer);

        } else {

            //that.prenowTime();
            clearInterval(pretimer);
            var pretimer = setInterval(that.prenowTime, 1000);
            clearInterval(ntimer);
        }
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


    nowTime: function () {//时间函数
        var that = this;
        var dates = new Array()
        var len = that.data.goods_list.length;//时间数据长度

        //console.log(len)
        for (var i = 0; i < len; i++) {
            var tarr = new Array();
            var endtime = that.data.goods_list[i].goods_endtime;//获取数据中的时间戳
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
                if (second <= 9) second = '0' + second;
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
            dataList: dates
        })

    },

    //距活动开始倒计时
    prenowTime: function () {//时间函数
        var that = this;
        var dates = new Array()
        var len = that.data.pre_goods_list.length;//时间数据长度

        // console.log(a)
        for (var i = 0; i < len; i++) {
            var tarr = new Array();
            var starttime = that.data.pre_goods_list[i].goods_starttime;//获取数据中的时间戳
            var ntime = new Date();
            var ntime = (Date.parse(ntime) / 1000);//当前时间戳
            var intDiff = starttime - ntime;//时间差：
            //console.log(endtime + "----" + ntime)
            var day = 0, hour = 0, minute = 0, second = 0;
            if (intDiff > 0) {//转换时间
                day = Math.floor(intDiff / (60 * 60 * 24));
                hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
                if (hour <= 9) hour = '0' + hour;
                if (minute <= 9) minute = '0' + minute;
                if (second <= 9) second = '0' + second;
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
                clearInterval(pretimer);
            }
            // console.log(str);
            dates[i] = tarr;//在数据中添加difftime参数名，把时间放进去
        }
        //console.log(dates)
        that.setData({
            predataList: dates
        })

    },


    //更新推荐人员
    updateTjUser: function (tjuid) {
        var that = this
        var uid = wx.getStorageSync("uid");
        if (uid > 0) {
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
                    console.log(res)
                    console.log('推荐成功');
                }
            })
        }
        else {
            console.log('UID空的');
            // wx.getSetting({
            //   success(res) {
            //     console.log(res);
            //     if (!res.authSetting['scope.userInfo']) {
            //       console.log('------没有授权----')
            //       that.popAuth();
            //     }
            //   }
            // })
        }


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
                    mobile: app.util.hidePhoneNumber(res.data.user.mem_mobile)
                })
                wx.setStorageSync("uid", res.data.user.mem_id);
                wx.setStorageSync("userInfo", res.data.user);
            }
        });
    },


    //获取购物车中商品数量
    getCartNums: function () {
        var that = this
        var uid = wx.getStorageSync("uid");
        if (uid > 0) {
            var url = app.util.url('qiyue/getCartNums/');
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
                        cartnums: res.data.cartnums
                    })
                    app.globalData.popcartnums = res.data.cartnums;
                    console.log(app.globalData.popcartnums)
                }
            });
        } else {
            that.setData({
                cartnums: 0
            })
            app.globalData.popcartnums = 0;
        }
    },

    saveFormIds: function () {
        var that = this
        var uid = wx.getStorageSync("uid");
        var formIds = app.globalData.gloabalFomIds; // 获取gloabalFomIds
        //console.log('---' + app.globalData.gloabalFomIds)
        if (formIds.length) {//gloabalFomIds存在的情况下 将数组转换为JSON字符串
            formIds = JSON.stringify(formIds);
            console.log(formIds)
            app.globalData.gloabalFomIds = '';
            var url = app.util.url('qiyue/saveFormIds');
            wx.request({//通过网络请求发送openId和formIds到服务器
                url: url,
                method: 'POST',
                data: {
                    uid: uid,
                    formIds: formIds
                },
                header: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                success: function (res) {
                }
            });
        }

    },
    indexScan: app.tapScan,
    updateAll(){
        var that = this
        that.getIndexPopAds();
        //获取广告
        that.getAdsList();
        //获取分类
        that.getCates();
        //获取中间广告
        that.getMidAdsList();
        //获取特价列表
        that.getTejialist();
        //获取热售商品列表
        that.getGoodslist();


        that.getCartNums();
    },
    updateAll2(){
        var that = this
        that.getIndexPopAds();
        //获取广告
        // that.getAdsList();
        //获取分类
        that.getCates();
        //获取中间广告
        that.getMidAdsList();
        //获取特价列表
        that.getTejialist();
        //获取热售商品列表
        that.getGoodslist();
        //获取预告商品列表
        that.getPreGoodslist();

        that.getCartNums();
    },

    onLoad: function (options) {
        //小程序码扫进来的优先
        var shopid = options['shopid']
        var storage_shopid = wx.getStorageSync('shop_id')

        //如果是扫小程序码进的,直接设置导航栏的"扫一扫"
        if (!!shopid) {
            wx.setStorageSync('shop_id', shopid)
            wx.navigateTo({
                url:'/pages/shelf/index'
            })
        }

        //处理导航条
        let scope = this
        router.setActive(0)
        scope.setData({footer: router.footerArray})

        var that = this
        // app.util.footer(that);
        app.util.popkefu(that);

        //有推荐码
        var tjuid = options.tjuid
        var uid = wx.getStorageSync('uid')
        var userInfo = wx.getStorageSync("userInfo");
        if(userInfo == ''){
            if(uid>0){
                that.getUserInfo(uid)
            }
            else{
                console.log('未授权，缺少uid，无法获取userInfo')
            }
        }else{
            that.setData({userInfo})
        }


        if (tjuid > 0) {
            wx.setStorageSync("tjuid", tjuid);
            if (userInfo != '' && userInfo.mem_firstgrade == 0) {
                that.updateTjUser(tjuid);
            }
        }

        that.updateAll()
        // that.saveFormIds();


        //that.nowTime();
        // var ntimer = setInterval(that.nowTime, 1000);
        // var tjtimer = setInterval(that.tejianowTime, 1000);

    },
    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {

    },
    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {
        this.updateAll2()
        wx.stopPullDownRefresh();
    },
    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {

    },
    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        var uid = wx.getStorageSync("uid");
        return {
            title: '栖约·惠生活',
            path: '/pages/index/index?tjuid=' + uid,
            success: function (res) {

            },
            fail: function (res) {
                // 分享失败
                console.log(res)
            }
        }

    }

});
