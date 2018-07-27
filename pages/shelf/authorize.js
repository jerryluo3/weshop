//app.js
App({
  

  onLaunch: function () {
    
    
    //调用API从本地缓存中获取数据
    //var uid = wx.getStorageSync("uid");
    
    //if( uid == ''){      
      //this.getUserDataToken();
    //}
    //this.getUserDataToken();
    
  },
  onShow: function () {
  },
  onHide: function () {
  },
  onError: function (msg) {
    console.log(msg)
  },
  util: require('asset/js/util.js'),
  tabBar: {
    "color": "#123",
    "selectedColor": "#1ba9ba",
    "borderStyle": "#ddd",
    "backgroundColor": "#fafafa",
    "list": [
      {
        "pagePath": "/pages/index/index",
        "iconPath": "/asset/icon/home.png",
        "selectedIconPath": "/asset/icon/homeselect.png",
        "text": "首页"
      },
      {
        "pagePath": "/pages/article/article",
        "iconPath": "/asset/icon/view.png",
        "selectedIconPath": "/asset/icon/viewselect.png",
        "text": "发现"
      },
      {
        "pagePath": "/pages/cart/cart",
        "iconPath": "/asset/icon/cart.png",
        "selectedIconPath": "/asset/icon/cartselect.png",
        "text": "购物车"
      },
      {
        "pagePath": "/pages/member/member",
        "iconPath": "/asset/icon/user.png",
        "selectedIconPath": "/asset/icon/userselect.png",
        "text": "我的"
      }
    ]
  },
  globalData: {
    userInfo: null,
    popcartnums:0,
    gloabalFomIds:[],
  },
  siteInfo: {
    'uniacid': '8', //公众号uniacid
    'acid': '8',
    'multiid': '68035',  //小程序版本id
    'version': '2.0.0',  //小程序版本
    'siteroot': 'https://weapp.qiyue99.com/',
  },

  checkUserLogin:function(e){
    var that = this;
    var uid = wx.getStorageSync("uid");
    if (uid == '') {
      wx.login({
        success: function (res) {
          
          var code = res.code;
          var rawData = e.rawData
          var encryptedData = e.encryptedData
          var iv = e.iv
          var url = that.siteInfo.siteroot + 'qiyue/userAuthLogin';
          var tjuid = wx.getStorageSync("tjuid");
          var tpuid = wx.getStorageSync("tpuid");
          wx.request({
            //用户登陆URL地址，请根据自已项目修改
            url: url,
            method: "POST",
            header: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
              uid: uid,
              tjuid: tjuid,
              tpuid: tpuid,
              code: code,
              rawData: rawData,
              encryptedData: encryptedData,
              iv: iv
            },
            fail: function (res) {

            },
            success: function (res) {
              var uid = res.data.uid;
              var userInfo = res.data.userInfo;
              console.log(uid);
              console.log(userInfo);

              //设置用户缓存
              console.log(userInfo);
              wx.setStorageSync("uid", uid);
              wx.setStorageSync("userInfo", userInfo);
              console.log('登录成功');
            }
          })    
        },
        fail: function (res) {
          console.log(res);
        }
      })
    }
  },

  getUserDataToken: function () {

    var that = this;
    //获取用户缓存token 此token是服务器作为用户唯一验证的标识，具体请看后端代码
    var uid = wx.getStorageSync("uid");
        
    if (uid == ''){
      wx.login({
        success: function (res) {
          var code = res.code; 
          console.log(res)
          return false;
          wx.getUserInfo({
            success: function (res) {
              var simpleUser = res.userInfo;
              console.log(res);
              //return false;
              var url = that.siteInfo.siteroot+'qiyue/userAuthLogin';
              var tjuid = wx.getStorageSync("tjuid");
              wx.request({
                //用户登陆URL地址，请根据自已项目修改
                url: url,
                method: "POST",
                header: {
                  "Content-Type": "application/x-www-form-urlencoded"
                },
                data: {
                  uid: uid,
                  tjuid: tjuid,
                  code: code,
                  rawData: res.rawData,
                  encryptedData: res.encryptedData,
                  iv: res.iv
                },
                fail: function (res) {

                },
                success: function (res) {
                  var uid = res.data.uid;
                  var userInfo = res.data.userInfo;
                  
                  //设置用户缓存
                  console.log(userInfo);
                  wx.setStorageSync("uid", uid);
                  //wx.setStorageSync("userInfo", simpleUser);
                  wx.setStorageSync("userInfo", userInfo);
                  console.log('登录成功');          
                }
              })
            },
            fail: function () {
              // 调用微信弹窗接口


              wx.showModal({
                title: '警告',
                content: '您点击了拒绝授权，将无法正常使用部分功能。请10分钟后再次点击授权，或者删除小程序重新进入。',
                success: function (res) {
                  if (res.confirm) {
                    console.log('用户点击确定');
                  }
                }
              })
            }
          })
        },
        fail: function (res) {
          console.log(res);
        }
      })
    }
  }

});

//多张图片上传
function uploadimg(data) {
  var that = this,
    i = data.i ? data.i : 0,
    success = data.success ? data.success : 0,
    fail = data.fail ? data.fail : 0;
  wx.uploadFile({
    url: data.url,
    filePath: data.path[i],
    name: 'fileData',//这里根据自己的实际情况改
    formData: null,
    success: (resp) => {
      success++;
      console.log(resp)
      console.log(i);
      //这里可能有BUG，失败也会执行这里,所以这里应该是后台返回过来的状态码为成功时，这里的success才+1
    },
    fail: (res) => {
      fail++;
      console.log('fail:' + i + "fail:" + fail);
    },
    complete: () => {
      console.log(i);
      i++;
      if (i == data.path.length) {   //当图片传完时，停止调用          
        console.log('执行完毕');
        console.log('成功：' + success + " 失败：" + fail);
      } else {//若图片还没有传完，则继续调用函数
        console.log(i);
        data.i = i;
        data.success = success;
        data.fail = fail;
        that.uploadimg(data);
      }

    }
  });
}