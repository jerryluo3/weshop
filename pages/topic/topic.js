//index.js
//获取应用实例
const app = getApp()
const utils = app.globalData.utils
const domain = app.globalData.DOMAIN
// var router= app.globalData.router
Page({
  data: {
    topAd:'',
    middleAd:'',
      siteurl: app.siteInfo.siteroot,
      cat_goods_list: [],  //商品列表
      /*-------------------------顶部swiper配置-------------------------*/
      topSwiper:{
          use:false,
          item:[
              { img: '', link: '',key:0 },
          ],
          autoplay : false,
          // circular : true //loop
          indicatorDots:false
      },
      id:'',
      // footer:router.footerArray,
  },
    updateAD(){
        var scope = this
      var url = `${domain}qiyue/getGoodsSubject`
      var id = scope.data.id
        utils.post(url,{id},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
            console.log(res)
            var topSwiper = scope.data.topSwiper
            topSwiper.item[0].img = `${domain}${res.data.result.gs_picture}`
            var middleAd = `${domain}${res.data.result.gs_ads}`

            var cat_goods_list = res.data.goods_list
            cat_goods_list.forEach(( value, index )=>{
                let url = value['goods_picture']
                let s = url.split('.')
                cat_goods_list[ index ]['goods_thumb'] = s[0]+'_thumb.'+s[1]
            })
            var topAd = `${domain}${res.data.result.gs_picture}`
            scope.setData({
                middleAd,
                cat_goods_list,
                // topSwiper,
                topAd
            })
        })
    },
    //商品链接
    getGoodsInfo:function(e){
        var that = this
        var id = e.currentTarget.dataset.id
        wx.navigateTo({
            url: '/pages/goods/goods?id=' + id
        })
    },

  onLoad: function (options) {
    var scope = this
      scope.updateAD()

    var id = options.id
    if(id != ''){
      scope.setData({
        id
      })
    }
      // router.setActive(0)
      // scope.setData({footer:router.footerArray})
  },
    onPullDownRefresh: function () {
        this.updateAD()
        wx.stopPullDownRefresh();
    },

})
