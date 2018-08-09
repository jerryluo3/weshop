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

        },
        step1Completed:false,
        step2Completed:false,
        step3Completed:false,
        picturesInfoStep1:{
            //0:{path:string,size:number}
        },
        picturesUrlsStep1:[],

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
            let url = `${domain}/qiyue/mendianProductStoreSave`
            let uid = wx.getStorageSync('uid')
            let shop_id = wx.getStorageSync('shop_id')
            utils.post(url,{form:result,uid,shop_id},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
                console.log(res)
                wx.hideLoading()
                wx.showToast({
                    title: '已提交',
                    icon: 'success',
                    duration: 2000
                })
            })
        }else{
            wx.showToast({
                title: '请完整填写库存信息再提交',
                icon: 'none',
                duration: 2000
            })
        }


    },
    toStep2(){
        var scope = this
        wx.showModal({
            title: '提示',
            content: '是否上传照片',
            success: function(res) {
                if (res.confirm) {
                    scope.uploadPicturesStep1()
                    scope.setData({
                        step1Completed : true
                    })
                } else if (res.cancel) {
                    console.log('用户点击取消')
                }
            }
        })

    },
    addOnePictrueStep1(){
        let scope = this
        let picturesInfoStep1 = scope.data.picturesInfoStep1
        let picturesUrlsStep1 = scope.data.picturesUrlsStep1
        wx.chooseImage({
            count:2,
            success: function(res) {
                var tempFilePaths = res['tempFilePaths']//array[string]
                var tempFiles = res['tempFiles']//array[object]
                console.log(res)
                let len =Object.keys( picturesInfoStep1 ).length
                picturesInfoStep1[ len ] = tempFiles[ 0 ]
                picturesUrlsStep1.push( tempFilePaths[ 0 ] )
                wx.getImageInfo({
                    src: tempFilePaths[ 0 ],
                    success: function (res) {
                        picturesInfoStep1[ len ]['width'] = res.width
                        picturesInfoStep1[ len ]['height'] = res.height
                        scope.setData({
                            picturesInfoStep1,
                            picturesUrlsStep1
                        })
                    }
                })

                // wx.uploadFile({
                //     url: 'https://example.weixin.qq.com/upload', //仅为示例，非真实的接口地址
                //     filePath: tempFilePaths[0],
                //     name: 'file',
                //     formData:{
                //         'user': 'test'
                //     },
                //     success: function(res){
                //         var data = res.data
                //         //do something
                //         if( now == need ){
                //
                //         }else{
                //             need++
                //             loop()
                //         }
                //     }
                // })
            }
        })
    },
    previewAllPicturesStep1(){
        let scope = this
        let picturesInfoStep1 = scope.data.picturesInfoStep1
        let picturesUrlsStep1 = scope.data.picturesUrlsStep1

        let currentIndex = 0
        wx.previewImage({
            current: picturesUrlsStep1[ currentIndex ], // 当前显示图片的http链接
            urls: picturesUrlsStep1 // 需要预览的图片http链接列表
        })
    },
    previewAllPicturesStep2(){

    },
    uploadPicturesStep1(){
        for( let [ index, value ] of this.data.picturesUrlsStep1 ){

        }
    },
    uploadPicturesStep2(){

    },
    previewOnePictures( index = 0, pictrues = {} ){

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
