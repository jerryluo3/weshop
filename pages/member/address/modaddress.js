// pages/member/tools/address/modaddress.js

var app = getApp()


Page({
  data: {
    aid:'',
    contacter: '',
    mobile: '',
    address: [],    
    address:'',
    value: [0, 0, 0],
    values: [0, 0, 0],
    condition: false
  },

  chooseLocation: function () {
    var that = this
    wx.chooseLocation({
      success: function (res) {
        that.setData({
          address: res
        });
        console.log(res)
      }

    });
  },

  

  formSubmit: function (e) {
    //console.log('123');
    var that = this
    var a_contacter = e.detail.value.a_contacter
    var a_mobile = e.detail.value.a_mobile
    var a_address = e.detail.value.a_address   
    var a_aname = that.data.address.name
    var a_latitude = that.data.address.latitude
    var a_longitude = that.data.address.longitude 
    var uid = wx.getStorageSync("uid");
    
    if (a_contacter == '') {
      wx.showToast({ title: '请输入收货人', icon: 'none', duration: 1000 })
      return false
    }
    if (a_mobile == '') {
      wx.showToast({ title: '请输入联系电话', icon: 'none', duration: 1000 })
      return false
    }
    if (a_address == '') {
      wx.showToast({ title: '请输入地址', icon: 'none', duration: 1000 })
      return false
    }    
    wx.showToast({
      title: '处理中',
      icon: 'loading',
      duration: 2000
    })
    var url = app.util.url('qiyue/saveAddress');
    wx.request({
      url: url,
      data: {
        a_contacter: a_contacter,
        a_mobile: a_mobile,
        a_address: a_address, 
        a_aname: a_aname, 
        a_latitude: a_latitude, 
        a_longitude: a_longitude,        
        uid: uid,
        aid:that.data.aid
      },
      header: {
        "Content-Type": "application/json"
      },
      method: 'GET',
      success: function (res) {
        console.log(res);
        if (res.data.status == 200) {
          wx.showToast({
            title: '操作成功',
            icon: 'success',
            duration: 2000,
            success(ress) {
              setTimeout(function () {
                wx.navigateTo({
                  url: '/pages/member/address/address'
                })
              }, 2000) //延迟时间
            }
          })
        }
      }
    })
  },

  bindChange: function (e) {
    //console.log(e);
    var val = e.detail.value
    var t = this.data.values;
    var cityData = this.data.cityData;

    if (val[0] != t[0]) {
      console.log('province no ');
      const citys = [];
      const countys = [];

      for (let i = 0; i < cityData[val[0]].sub.length; i++) {
        citys.push(cityData[val[0]].sub[i].name)
      }
      for (let i = 0; i < cityData[val[0]].sub[0].sub.length; i++) {
        countys.push(cityData[val[0]].sub[0].sub[i].name)
      }

      this.setData({
        province: this.data.provinces[val[0]],
        city: cityData[val[0]].sub[0].name,
        citys: citys,
        county: cityData[val[0]].sub[0].sub[0].name,
        countys: countys,
        values: val,
        value: [val[0], 0, 0]
      })

      return;
    }
    if (val[1] != t[1]) {
      console.log('city no');
      const countys = [];

      for (let i = 0; i < cityData[val[0]].sub[val[1]].sub.length; i++) {
        countys.push(cityData[val[0]].sub[val[1]].sub[i].name)
      }

      this.setData({
        city: this.data.citys[val[1]],
        county: cityData[val[0]].sub[val[1]].sub[0].name,
        countys: countys,
        values: val,
        value: [val[0], val[1], 0]
      })
      return;
    }
    if (val[2] != t[2]) {
      console.log('county no');
      this.setData({
        county: this.data.countys[val[2]],
        values: val
      })
      return;
    }


  },
  open: function () {
    this.setData({
      condition: !this.data.condition
    })
    //document.activeElement.blur();
  },
  getAddress:function(id){
    var that = this
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 1000
    })
    var url = app.util.url('qiyue/getMemberAddress');
    wx.request({
      url: url, 
      data: {
        id: id
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: 'POST',
      success: function (res) {
        that.setData({
          contacter: res.data.result.a_contacter,
          mobile: res.data.result.a_mobile,
          province: res.data.result.a_province,
          city: res.data.result.a_city,
          county: res.data.result.a_county,
          address: res.data.result.a_address,
          aid: res.data.result.a_id
        })
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    wx.setNavigationBarTitle({
      title: '编辑收货地址'
    })

    if (options.id > 0) {
      //修改地址
      that.getAddress(options.id);
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