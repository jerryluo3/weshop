const app = getApp()
const utils = app.globalData.utils
const domain = app.globalData.DOMAIN

// var router= app.globalData.router
Page({
    data: {
        domain,
        /*--------------------盘货必要字段--------------------*/
        uid:0,
        shop_id:0,
        mpd_id:0,
        // before_pics:[],
        // after_pics:[],
        // formData:{},
        formHiddenData:{},//应该有的结果
        /*--------------------盘货必要字段--------------------*/
        productList:[

        ],
        result:{

        },//盘货师傅填写结果

        uploadTask:{
            step1:{
                picturesInfo:{
                    //0:{path:string,size:number}
                },
                picturesUrls:[],//临时url
                completed:false,
                index:0,//当前index,当前传完会++
                total:0,//总数，比index多1
                percent:0,//百分比
                list:[],//服务器图片地址
            },
            step2:{
                completed:false,
                formData:{},//盘货师傅填写结果
            },
            step3:{
                picturesInfo:{
                    //0:{path:string,size:number}
                },
                picturesUrls:[],
                completed:false,
                index:0,//当前index,当前传完会++
                total:0,//总数，比index多1
                percent:0,//百分比
            },

            step:'step1',//实际操作

            completed:false,
        },//首次进页面的初始盘货数据
        view:'step1',//视图
        //题库
        questList:[
            {
                answer:1,
                options:[
                    {uid:1,text:'内容1'},
                    {uid:2,text:'内容2'},
                    {uid:3,text:'内容3'}
                ],
                userChose:0,
            },//第一题
            {
                answer:1,
                options:[
                    {uid:1,text:'内容4'},
                    {uid:2,text:'内容5'},
                    {uid:3,text:'内容6'}
                ],
                userChose:0,
            },//第二题
        ],
        nowIndex:0,//当前第0题

        //tabs
        tabs: ["上传盘货前图片", "上传盘货信息","上传盘货后图片"],
        activeIndex:0,//当前激活的tab
        sliderOffset: 0,//translateX
        sliderLeft: 0,//left
        sliderWidth:192,//rpx
        segmentWidth:0,//段宽
    },
    tabClick: function (e) {
        let activeIndex = e.currentTarget.id
        this._slideTo( activeIndex )
    },
    _slideTo( index ){

        let activeIndex = this.data.activeIndex
        let sliderLeft = this.data.sliderLeft
        let sliderOffset = this.data.sliderOffset
        let segmentWidth = this.data.segmentWidth
        let sliderWidth = this.data.sliderWidth

        activeIndex = parseInt( index )
        // sliderOffset = ( segmentWidth - sliderWidth ) / 2
        sliderOffset = segmentWidth * activeIndex
        sliderLeft = segmentWidth * activeIndex
        let view = 'step' + ( activeIndex + 1 )

        this.setData({
            activeIndex,
            sliderOffset,
            // sliderLeft,
            view
        });
    },
    next(){
        let nowIndex = this.data.nowIndex
        let questList = this.data.questList
        if( questList[ nowIndex ].userChose == 0 ){
            console.log('做完这题再做下一题')
            return
        }
        nowIndex++
        this.setData({
            nowIndex
        })
    },
    chose(e){
        let target = e.currentTarget
        let uid = target.dataset['uid']//用户选的index

        let questList = this.data.questList
        let nowIndex = this.data.nowIndex//题目的index
if(questList[ nowIndex ].userChose != 0){
            console.log('人生没有后悔药，请坚持你的选择')
            return
}
        questList[ nowIndex ].userChose = uid

        this.setData({
            questList
        })



    },
    oninput(e){
        let target = e.currentTarget
        let pid = target.dataset['pid']

        let uploadTask = this.data.uploadTask

        let formData = uploadTask['step2'].formData
        formData[ pid ] = e.detail.value
        this.setData({
            uploadTask
        })
        wx.setStorageSync( 'uploadTask', uploadTask )
    },

    //提交按钮   确认提交
    buttonSubmitStep1(){
        var scope = this
        let uploadTask = scope.data.uploadTask
        if( uploadTask['step1'].picturesUrls.length == 0 ){
            wx.showToast({
                title: '请至少上传一张图片',
                icon: 'none',
                duration: 2000
            })
            return
        }
        wx.showModal({
            title: '提示',
            content: '是否上传照片',
            success: function(res) {
                if (res.confirm) {
                    scope.confirmStep1()
                } else if (res.cancel) {
                    console.log('用户点击取消')
                }
            }
        })
    },
    confirmStep1(){
        let scope = this
        wx.showLoading({
            title: '正在上传图片',
        })
        //上传图片，完成后去第二步
        scope._uploadPictures(function(){

            let uploadTask = scope.data.uploadTask
            let step = uploadTask['step']
            wx.setStorageSync('uploadTask',uploadTask)
            scope.setData({
                uploadTask
            })

            let list = uploadTask[ step ].list
            let number = parseInt( step.charAt(4) )
            let url = `${domain}qiyue/xxx`
            let shop_id = wx.getStorageSync('shop_id')
            let uid = wx.getStorageSync('uid')

            //假请求,完成后
            // setTimeout(function () {
            //     wx.hideLoading()
            //     wx.showToast({
            //         title: '图片上传成功',
            //         icon: 'success',
            //         duration: 1000
            //     })
            //     uploadTask[ step ].completed = true
            //     uploadTask['step'] = 'step2'
            //     wx.setStorageSync('uploadTask',uploadTask)
            //     scope.setData({
            //         uploadTask,
            //     })
            //     setTimeout(()=>{
            //         scope._slideTo(1)
            //     },1000)//视图切换到第二步
            // },1000)
            //真请求
            scope._savePanhuoInfo(function(){
                wx.hideLoading()
                wx.showToast({
                    title: '图片上传成功',
                    icon: 'success',
                    duration: 1000
                })
                uploadTask[ step ].completed = true
                uploadTask['step'] = 'step2'
                wx.setStorageSync('uploadTask',uploadTask)
                scope.setData({
                    uploadTask,
                })
                setTimeout(()=>{
                    scope._slideTo(1)
                },1000)//视图切换到第二步
            })

            //
            // utils.post(url,{shop_id,step:number,uid,list},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
            //     console.log(res)
            //     wx.hideLoading()
            //     wx.showToast({
            //         title: '图片上传成功',
            //         icon: 'success',
            //         duration: 1000
            //     })
            //     setTimeout(()=>{
            //         uploadTask[ step ].completed = true
            //         uploadTask['step'] = 'step2'
            //         wx.setStorageSync('uploadTask',uploadTask)
            //         scope.setData({
            //             uploadTask,
            //             view:'step2'
            //         })
            //     },1000)
            // })



        })
    },
    buttonSubmitStep2(){
        var scope = this
        wx.showModal({
            title: '提示',
            content: '是否提交盘货信息',
            success: function(res) {
                if (res.confirm) {
                    scope.confirmStep2()
                } else if (res.cancel) {
                    console.log('用户点击取消')
                }
            }
        })
    },
    confirmStep2(){
        let scope = this
        wx.showLoading({
            title: '正在上传数据',
        })
        scope._uploadList(function(){
            //更新视图，去第三步
            wx.hideLoading()
            wx.showToast({
                title: '盘货信息已上传',
                icon: 'success',
                duration: 1000
            })
            //导航去第三步
            let uploadTask = scope.data.uploadTask
            let step = uploadTask['step']
            uploadTask[ step ].completed = true
            uploadTask['step'] = 'step3'
            scope.setData({
                uploadTask,
            })
            wx.setStorageSync('uploadTask',uploadTask)

            setTimeout(()=>{
                scope._slideTo(2)
            },1000)
        })
    },
    buttonSubmitStep3(){
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
    confirmStep3(){},

    //上传数据
    _uploadPictures( callback ){
        let scope = this
        let uploadTask = scope.data.uploadTask
        let step = uploadTask.step

        let entries = scope.data.uploadTask['step1'].picturesUrls.entries()
        let uid = wx.getStorageSync('uid');
        let url = `${domain}qiyue/uploadFile`
        let loop = function( item ){
            if( !item.done ){
                wx.uploadFile({
                    url: url, //仅为示例，非真实的接口地址
                    filePath: item.value[1],
                    name: 'fileData',
                    formData:{
                    },
                    success: function(res){
console.log(res)
                        if ( res.statusCode == 200 ){
                            uploadTask[ step ].index++
                            uploadTask[ step ].list.push(JSON.parse( res.data)['up_file'])
                            wx.setStorageSync('uploadTask',uploadTask)
                            scope.setData({
                                uploadTask
                            })
                        } //图片上传成功


                        console.log(`上传完第${item.value[0]}张图`)

                        let next = entries.next()
                        loop( next )
                    }
                })
            }else{
                callback()
            }
        }

        let item = entries.next()
        loop(item)

    },
    _uploadList( callback ){

        this._savePanhuoInfo(function( res ){
            //盘货信息表保存成功
            callback()
        })

        //假上传
        // setTimeout(function(){
        //     //上传成功
        //     callback()
        // },1000)


//真上传
        // utils.post(url,{formData,formHiddenData,uid,shop_id},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
        //     console.log(res)
        //     wx.hideLoading()
        //     wx.showToast({
        //         title: '已提交',
        //         icon: 'success',
        //         duration: 2000
        //     })
        // })


    },


    addOnePictrue(){
        let scope = this
        // let picturesInfoStep1 = scope.data.picturesInfoStep1
        // let picturesUrlsStep1 = scope.data.picturesUrlsStep1
        let uploadTask = scope.data.uploadTask
        let step = uploadTask.step

        let picturesInfo = uploadTask[ step ].picturesInfo
        let picturesUrls = uploadTask[ step ].picturesUrls

        wx.chooseImage({
            count:1,
            success: function(res) {
                var tempFilePaths = res['tempFilePaths']//array[string]
                var tempFiles = res['tempFiles']//array[object]

                let key =Object.keys( picturesInfo ).length
                picturesInfo[ key ] = tempFiles[ 0 ]
                picturesUrls.push( tempFilePaths[ 0 ] )
                uploadTask[ step ].total++
                wx.getImageInfo({
                    src: tempFilePaths[ 0 ],
                    success: function (res) {
                        picturesInfo[ key ]['width'] = res.width
                        picturesInfo[ key ]['height'] = res.height
                        scope.setData({
                            uploadTask
                        })
                        wx.setStorageSync('uploadTask',uploadTask)
                    }
                })
            }
        })
    },
    previewAllPictures(){
        let scope = this
        let uploadTask = scope.data.uploadTask
        let step =  scope.data.view
        let picturesInfo = uploadTask[ step ].picturesInfo
        let picturesUrls = uploadTask[ step ].picturesUrls

        if( picturesUrls.length == 0 ){
            wx.showToast({
                title: '请至少上传一张图片',
                icon: 'none',
                duration: 1500
            })
            return
        }

        let currentIndex = 0
        wx.previewImage({
            current: picturesUrls[ currentIndex ], // 当前显示图片的http链接
            urls: picturesUrls // 需要预览的图片http链接列表
        })
    },
    previewAllPicturesStep3(){

    },
    uploadPicturesStep3(){

    },
    previewOnePictures( index = 0, pictrues = {} ){

    },

    _getLocalStorage(){
        let uid = wx.getStorageSync('uid')
        let shop_id = wx.getStorageSync('shop_id')
        this.setData({
            uid,
            shop_id
        })
    },
    //拉取商品列表
    _updateProductList(callback = function(){} ){
        var scope = this;
        utils.post(`${domain}qiyue/getShopProducts `,{ shop_id:wx.getStorageSync('shop_id')},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
            console.log(res)
            let productList = scope.data.productList
            //原始数据备份
            let goods_list = res.data['product_list']
            let goods_length = goods_list.length
            //有数据就用拉取的数据
            if( goods_length!=0 ){
                productList = [ ...goods_list ]
                let uploadTask = scope.data.uploadTask
                let formHiddenData = scope.data.formHiddenData
                let formData = uploadTask['step2'].formData
                productList.forEach(( value, index )=>{
                    var disabled = ''
                    let pid = value[ 'mp_pid' ]
                    formHiddenData[ pid ] = value['mp_stocks']

                    if( pid == 393 || pid == 394 || pid == 395 ){
                        disabled = 'true'
                        formData[ pid ] = value['mp_stocks']
                    }
                    else{
                        formData[ pid ] = 0
                    }
                    let url = value['mp_picture']
                    //处理缩略图
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
                    formHiddenData,
                    uploadTask
                })
            }else{
                console.log('获取不到数据')
            }


        })
    },
    //获取当天盘货信息
    _getPanhuoInfo( callback ){
        let uid = wx.getStorageSync('uid')
        let shop_id = wx.getStorageSync('shop_id')
        utils.post(`${domain}qiyue/getPanhuoInfo`,{uid,shop_id},{"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
            console.log(res)//res.data.result == null
            callback( res )
        })
    },
    //保存盘货信息,可能会执行多次
    _savePanhuoInfo( callback ){
        let uploadTask = this.data.uploadTask
        let step = uploadTask['step']
        let step_number = parseInt( step.charAt(4) )

        let url = `${domain}qiyue/savePanhuoInfo`

        let uid = this.data.uid
        let shop_id = this.data.shop_id

        let mpd_id = this.data.mpd_id
        let before_pics = uploadTask['step1']['list']
        let formData = uploadTask['step2']['formData']
        let after_pics = uploadTask['step3']['list']
        let formHiddenData = this.data.formHiddenData
console.log(mpd_id)
        let data = {
            uid,
            shop_id
        }
        if( step_number == 1 ){
            Object.assign( data, {
                before_pics,
                step:step_number
            } )
        }
        else if( step_number == 2 ){
            Object.assign( data, {
                mpd_id,
                formData,
                formHiddenData,
                step:step_number
            } )
        }
        else if( step_number == 3 ){
            Object.assign( data, {
                mpd_id,
                after_pics,
                step:step_number
            } )
        }
console.log(step)
console.log(step_number)
console.log(data)
        utils.post( url, data, {"Content-Type": "application/x-www-form-urlencoded"}).then((res)=>{
            console.log(res)//res.data.result == null
            callback( res )
        })
    },
    /*-----------------------生命周期-----------------------*/
    onLoad: function (options) {
        let scope = this

        wx.setNavigationBarTitle({
            title: '嘉兴日报自提门店 - 实时库存'
        })//设置title
        this._getLocalStorage()//更新shop_id和uid

        //缓存的盘货操作
        let uploadTask = wx.getStorageSync('uploadTask')
        if(!uploadTask){
            uploadTask = scope.data.uploadTask
        }

        this._getPanhuoInfo(function( res ){
            console.log(res)
            let mpd_id = scope.data['mpd_id']
            if( res.data.result == null ){
                //当天还没进行过任何提交,也许有缓存操作
                uploadTask['step'] == 'step1'
            }//如果没提交过盘货信息
            else{
                mpd_id = res.data.result['mpd_id']
                if(res.data.result.after_pics!=null){
                    uploadTask['step'] = 'completed'
                }
                else if(res.data.result.mpd_real_stocks!=''&&res.data.result.mpd_stocks!=''){
                    uploadTask['step'] = 'step3'
                }else if(res.data.result.mpd_before_pics!=null){
                    uploadTask['step'] = 'step2'
                }
            }
            scope.setData({
                mpd_id,
                uploadTask,
            })
            wx.setStorageSync('uploadTask',uploadTask)

        })//先拉取盘货信息，更新本地的uploadTask

        this._updateProductList()//拉取货物信息

        var that = this;
         // 需要设置slider的宽度，用于计算中间位置,rpx
        wx.getSystemInfo({
            success: function(res) {
                let sliderWidth = that.data.sliderWidth;
                let ratio = res.windowWidth / 750
                sliderWidth *= ratio
                let segmentWidth = res.windowWidth / that.data.tabs.length

                that.setData({
                    sliderLeft: ( segmentWidth - sliderWidth ) / 2,
                    sliderOffset: segmentWidth * that.data.activeIndex,
                    sliderWidth,
                    segmentWidth
                });
            }
        });
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
