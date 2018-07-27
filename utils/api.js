class API{
    static ajax( { url = "", data = {}, header = {}, method = "", success = function(){}, fail = function(){}, complete = function(){} } = {} ){
        wx.request({
            url,
            data,
            header,
            method,
            success,
            fail,
            complete
        })
    }

    static getSortList( callback = function(){} ){
        this.ajax({
            url : "http://192.172.1.112/qiyue/pages/index/sorttag.json",
            success : function( res ){
                callback( res )
            }
        })
    }

    static getProductionList( callback = function(){} ){
        this.ajax({
            url : "http://192.172.1.112/qiyue/pages/index/production.json",
            success : function( res ){
                callback( res )
            }
        })
    }



}




module.exports = {
    API : API
}