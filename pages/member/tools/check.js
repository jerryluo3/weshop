const app = getApp()
const utils = app.globalData.utils
const domain = app.globalData.DOMAIN
// var router= app.globalData.router
Page({
    data: {
        domain,
        productList:[

        ],
        result:{

        }
    },
    //拉取商品列表
    updateProductList( callback = function(){} ){
        var scope = this;
        utils.post(`${domain}qiyue/getBLZGoodsList`,{shop_id:wx.getStorageSync('shop_id')},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
            let productList = scope.data.productList
            //原始数据备份
            let goods_list = res.data['goods_list']
            let goods_length = goods_list.length
            //有数据就用拉取的数据
            if( goods_length!=0 ){
                productList = [...goods_list]
                let result = scope.data.result
                productList.forEach(( value, index )=>{
                    var disabled = ''
                    let pid = value.mp_pid
                    if( pid == 393 || pid == 394 || pid == 395 ){
                        disabled = 'true'
                        result[pid] = value['mp_stocks']
                    }
                    let url = value['mp_picture']
                    if(url != ""){
                        let s = url.split('.')
                        productList[ index ]['mp_picture'] = s[0]+'_thumb.'+s[1]
                    }
                    else{
                        productList[ index ]['mp_picture'] = '';
                    }
                    productList[ index ]['disabled'] = disabled
                })

                //视图更新
                scope.setData({
                    productList,
                    result
                })
            }else{
                console.log('获取不到数据')
            }


        })
    },
    oninput(e){
        console.log(e)
        let target = e.currentTarget
        let pid = target.dataset['pid']
        let result = this.data.result
        result[pid] = e.detail.value
        this.setData({
            result
        })
    },
    submit(){
        var scope = this
        wx.showModal({
            title: '提示',
            content: '是否提交库存信息',
            success: function(res) {
                if (res.confirm) {
                    scope.uploadData()
                } else if (res.cancel) {
                    console.log('用户点击取消')
                }
            }
        })
    },
    uploadData(){

        let completed = false

        let result = this.data.result
        let productList = this.data.productList

        let fill_len = Object.keys(result).length
        let total_len = productList.length

        completed = fill_len == total_len
        if(completed){
            wx.showLoading({
                title: '正在上传数据',
            })
            setTimeout(function(){
                wx.hideLoading()
                wx.showToast({
                    title: '已提交',
                    icon: 'success',
                    duration: 2000
                })
            },3000)

        }else{
            wx.showToast({
                title: '请完整填写库存信息再提交',
                icon: 'none',
                duration: 2000
            })
        }


    },
    /*-----------------------生命周期-----------------------*/
    onLoad: function (options) {
        wx.setNavigationBarTitle({
            title: '嘉兴日报自提门店 - 实时库存'
        })
        this.updateProductList()
    },
    onUnload(){
        wx.setNavigationBarTitle({
            title: '栖约惠生活'
        })
    },
    onShow: function () {

    },
    onPullDownRefresh: function () {
        wx.stopPullDownRefresh();
    },
    onReady: function () {

    },

})
