/**
 * Created by Administrator on 2018/7/18 0018.
 */
var utils = require('../../utils/util.js')
var SHELF_CONFIG = {
  "pagePath": "/pages/shelf/index",
  "text": "便利站",
  "iconPath": "/assets/tarbar/tab_shop.png",
  "selectedIconPath": "/assets/tarbar/tab_shop_hover.png",
  "active": false,
  index: 2,
  navigator: true,
}

var SCAN_CONFIG = {
  "pagePath": "",
  "text": "扫一扫",
  "iconPath": "/assets/tarbar/scan.png",
  "selectedIconPath": "/assets/tarbar/scan.png",
  "active": false,
  index: 2,
  navigator: false,
}

class Router{
    constructor(){
        this.footerArray = [
            {
                "pagePath": "/pages/index/index",
                "text": "首页",
                "iconPath": "/assets/tarbar/tab_home.png",
                "selectedIconPath": "/assets/tarbar/tab_home_hover.png",
                "active":false,
                index:0,
                navigator:true,
            },
            {
                "pagePath": "/pages/article/article",
                "text": "发现",
                "iconPath": "/assets/tarbar/view.png",

                "selectedIconPath": "/assets/tarbar/viewselect.png",
                "active":false,
                index:1,
                navigator: true,
            },
            {
              "pagePath": "",
              "text": "",
                "iconPath": "",
              "selectedIconPath": "",
              "active": false,
              index: 2,
              navigator: false,
            },
            {
                "pagePath": "/pages/member/kefu/kefu",
                "text": "消息",
                "iconPath": "/assets/tarbar/tab_coin.png",
                "selectedIconPath": "/assets/tarbar/tab_coin_hover.png",
                "active":false,
                index:3,
                navigator: true,
            },
            {
                // "pagePath": "/pages/topic/topic",
                "pagePath": "/pages/member/member",
                "text": "我的",
                "iconPath": "/assets/tarbar/tab_member.png",
                "selectedIconPath": "/assets/tarbar/tab_member_hover.png",
                "active":false,
                index:4,
                navigator: true,
            }
        ]
        this.footerObject = undefined;
        this._DEFAULT_ACTIVE = 0;
        this._activeIndex = this._DEFAULT_ACTIVE;
        this._init()
    }
    _init(){
        this.updateFooterObject()
        this._setDefaultActive()
    }
    _setDefaultActive(){
        this.setActive( this._DEFAULT_ACTIVE )
    }
    setActive( index ){
        this._activeIndex = index
        for( let key in this.footerObject){
            this.footerObject[ key ].active = false
        }
        if(this.footerObject[ index ])this.footerObject[ index ].active = true
        this._updateFooterArray()
    }
    setShelf(){
      this.footerArray[2] = SHELF_CONFIG
      this.updateFooterObject()
    }
    setScan(){
      this.footerArray[2] = SCAN_CONFIG
      this.updateFooterObject()
    }
    _updateFooterArray(){
        this.footerArray = utils.ObjectToArray(this.footerObject)
    }
    updateFooterObject(){
      this.footerObject = utils.ArrayExtractToObjectWithKey(this.footerArray, 'index')
    }


}
Router.INDEX = 0
Router.DISCOVER = 1
Router.SHELF = 2
Router.MESSAGE = 3
Router.MEMBER = 4

module.exports = Router
