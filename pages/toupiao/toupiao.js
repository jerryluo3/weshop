// pages/toupiao/toupiao.js

var app = getApp()
var page = 1;

Page({

  /**
   * 页面的初始数据
   */
  data: {
    siteurl: app.siteInfo.siteroot,
    ads:[],
    keys:'',
    page:page,
    company_list:[],
    search_company_list:[],
    scrollTop: 0,
    floorstatus: false,
    pophide: 'hide',
    tid:0,
    popshare: 'hide',
    popsharehb: 'hide',
    popphone:'hide',
  },

  popphone: function (e) {
    var that = this
    that.setData({ popphone: ''});
  },

  closepopphone: function () {
    var that = this
    that.setData({ popphone: 'hide' });
  },

  popShare: function (e) {
    var that = this
    var tid = e.currentTarget.dataset.id
    that.setData({ popshare: '',tid:tid });
  },

  closeShare: function () {
    var that = this
    that.setData({ popshare: 'hide', tid:0 });
  },

  popShareHB: function () {
    var that = this
    that.setData({ popsharehb: '', popshare: 'hide' });
    that.getShareHB();
  },

  closeSharehb: function () {
    var that = this
    that.setData({ popsharehb: 'hide', tid: 0 });
  },

  getShareHB: function () {
    var that = this
    that.getToupiaoShareImg();
  },

  getToupiaoShareImg: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    var url = app.util.url('sharepic/getToupiaoShareImg/');
    wx.showToast({
      title: '加载数据中',
      icon: 'loading',
      duration: 1000
    })
    wx.request({
      url: url,
      data: {
        tid: that.data.tid,
        uid: uid,
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        //console.log(res);
        that.setData({ shareImg: res.data.result, popsharehb: '' })
      }

    });
  },

  save: function () {
    var that = this
    wx.showToast({
      title: '处理中',
      icon: 'loading',
      duration: 1000
    })
    wx.downloadFile({
      url: that.data.siteurl + that.data.shareImg,
      success: function (res) {
        var tempFilePath = res.tempFilePath
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
                  wx.showModal({
                    title: '保存成功',
                    content: '您可以分享到朋友圈啦',
                    showCancel: false,
                    success: function (res) {

                    }
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
      }
    })

  },

  dealFormIds: function (formId) {
    let formIds = app.globalData.gloabalFomIds;//获取全局数据中的推送码gloabalFomIds数组
    
    if (!formIds) formIds = [];
    let data = {
      formId: formId
      //expires: parseInt(new Date().getTime() / 1000) + 604800 //计算7天后的过期时间时间戳
    }
    formIds.push(data);//将data添加到数组的末尾
    console.log('+++' + JSON.stringify(formIds))
    app.globalData.gloabalFomIds = formIds; //保存推送码并赋值给全局变量
  },


  toupiao:function(e){
    var that = this
    console.log(e);
    var id = e.currentTarget.dataset.id
    var index = e.currentTarget.dataset.index
    var uid = wx.getStorageSync("uid");
    var userInfo = wx.getStorageSync("userInfo");
    if (uid == '') {
      that.popAuth();
      return false;
    }
    if(userInfo.mem_mobile == ''){      
      that.popphone();
      return false;
    }

    //let formId = e.detail.formId;
    //that.dealFormIds(formId); //处理保存推送码


    var url = app.util.url('qiyue/toupiao');
    wx.request({
      url: url,
      data: {
        id:id,
        uid:uid
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        console.log(res);
        if (res.data.status == 200){          
          wx.showToast({ title: '投票成功', icon: 'success', duration: 1000 })
          var keys = that.data.keys
          if(keys == ''){
            var company_list = that.data.company_list;
            company_list[index]['tc_nums'] = res.data.result;
            that.setData({ company_list: company_list });
          }else{
            var search_company_list = that.data.search_company_list;
            search_company_list[index]['tc_nums'] = res.data.result;
            that.setData({ search_company_list: search_company_list });
          }
          return false;
        }else if (res.data.status == 1) {
          wx.showToast({ title: '同一个公司每天最多只能投一票', icon: 'none', duration: 1000 })
        } else if (res.data.status == 2) {
          wx.showToast({ title: '投票活动还未开始', icon: 'none', duration: 1000 })
        } else if (res.data.status == 3) {
          wx.showToast({ title: '投票活动已结束', icon: 'none', duration: 1000 })
        } else if (res.data.status == 0) {
          wx.showToast({ title: '投票失败', icon: 'none', duration: 1000 })
        }
      }
    });
  },

  getPhoneNumber: function (e) {
    var that = this
    console.log(e.detail.errMsg)
    console.log(e.detail.iv)
    console.log(e.detail.encryptedData)

    if (e.detail.errMsg == 'getPhoneNumber:fail user deny') {
      wx.showModal({
        title: '提示',
        showCancel: false,
        content: '未授权',
        success: function (res) { }
      })
    } else {
      wx.login({
        success: function (res) {
          var code = res.code
          var url = app.util.url('qiyue/userAuthMobile');
          var uid = wx.getStorageSync("uid");
          wx.request({
            url: url,
            data: {
              uid: uid,
              code: code,
              iv: e.detail.iv,
              encryptedData: e.detail.encryptedData,
            },
            header: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            method: 'POST',
            success: function (res) {

              if (res.data.status == 200) {

                //console.log(res)
                wx.showToast({
                  title: '处理成功',
                  icon: 'success',
                  duration: 1000
                })
                that.getUserInfo(uid);
                that.closepopphone();
              } else {
                console.log(res)
                wx.showToast({
                  title: '处理失败',
                  icon: 'loading',
                  duration: 1000
                })
              }

            }
          })
        }
      });

    }
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
        wx.setStorageSync("uid", res.data.user.mem_id);
        wx.setStorageSync("userInfo", res.data.user);
      }
    });
  },

  //弹出需要授权层
  popAuth: function () {
    var that = this
    that.setData({ pophide: '' });
  },

  closeAuth: function () {
    var that = this
    that.setData({ pophide: 'hide' });
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
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 1000,
      success(ress) {
        setTimeout(function () {
          var uid = wx.getStorageSync("uid");
          console.log('---------' + uid)
          //that.getUserInfo(uid);
          //var url = '/pages/toupiao/toupiao';
          //wx.reLaunch({
          //  url: url
          //})
        }, 2000) //延迟时间
      }
    })


  },

  goTop: function (e) {
    this.setData({
      scrollTop: 0
    })
  },
  scroll: function (e) {
    if (e.detail.scrollTop > 500) {
      this.setData({
        floorstatus: true
      });
    } else {
      this.setData({
        floorstatus: false
      });
    }
  },

  getMore:function(){
    var that = this
    
    var keys = that.data.keys
    if(keys == ''){
      var page = that.data.page + 1;
      this.setData({
        page: page
      })
      that.getCompanyList();
    }
  },

  getToupiaoAds:function(){
    var that = this
    var url = app.util.url('qiyue/getToupiaoAds');
    wx.request({
      url: url,
      data: {},
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({ ads: res.data.ads })
      }
    });
  },

  getCompanyList:function(){
    var that = this
    wx.showToast({ title: '加载中',icon: 'loading',duration: 1000  })
    var url = app.util.url('qiyue/getCompanyList');
    wx.request({
      url: url,
      data: {
        page:that.data.page
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({ company_list: that.data.company_list.concat(res.data.company_list) })
      }
    });
  },

  delKeys:function(){
    var that = this
    that.setData({ keys: '' })
  },


  formSubmit: function (e) {
    //console.log('123');
    var that = this
    var keys = e.detail.value.keys

    if (keys == '') {
      wx.showToast({ title: '请输入编号或者公司名称', icon: 'none', duration: 1000 })
      return false
    }
    
    wx.showToast({
      title: '处理中',
      icon: 'loading',
      duration: 1000
    })
    var url = app.util.url('qiyue/searchToupiaoCompany');
    wx.request({
      url: url,
      data: {
        keys: keys
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        //console.log(res);
        that.setData({ search_company_list: res.data.company_list, keys: keys })
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    
    wx.setNavigationBarTitle({
      title: '最美茶水间投票'
    })
    var that = this

    //投票推荐用户
    var tpuid = options.tpuid
    var tid = options.tid
    if (tpuid > 0) {
      wx.setStorageSync("tpuid", tpuid);
    }

    var uid = wx.getStorageSync("uid");
    var userInfo = wx.getStorageSync("userInfo");

    if(uid == ''){
      that.popAuth();
    }


    that.getToupiaoAds();
    if(parseInt(tid) > 0){
      var keys = parseInt(tid)
      var url = app.util.url('qiyue/searchToupiaoCompany');
      wx.request({
        url: url,
        data: {
          keys: keys
        },
        header: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        method: 'POST',
        success: function (res) {
          //console.log(res);
          that.setData({ search_company_list: res.data.company_list, keys: keys })
        }
      })
    }else{
      that.getCompanyList();
    }
    
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
    var that = this
    var page = that.data.page+1;
    this.setData({
      page: page
    })
    that.getCompanyList();
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    var that = this
    var uid = wx.getStorageSync("uid");
    var title = '嘉兴首届最美茶水间评比大赛';
    return {
      title: title,
      path: '/pages/toupiao/toupiao?tpuid=' + uid,
      success: function (res) {
        console.log(res)
        // console.log        
      },
      fail: function (res) {
        // 分享失败
        console.log(res)
      }
    }
  }
})