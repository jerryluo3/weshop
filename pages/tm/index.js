//index.js
//获取应用实例
const app = getApp()
let router= app.globalData.router
Page({
  data: {

  },

  onLoad: function () {
      let scope = this
      router.setActive(1)
      scope.setData({footer:router.footerArray})
  },

})
