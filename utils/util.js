const formatTime = date => {
  const year = date.getFullYear()
  const month = date.getMonth() + 1
  const day = date.getDate()
  const hour = date.getHours()
  const minute = date.getMinutes()
  const second = date.getSeconds()

  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}

const formatNumber = n => {
  n = n.toString()
  return n[1] ? n : '0' + n
}

let test = ()=>{
  let p;
  p = new Promise(( resolve, reject )=>{
    setTimeout(function(){
      wx.showToast({
          title:'Promise-Test'
      })
        resolve()
    },3000)
  })

    return p
}

function requstGet(url,data){
    return requst(url,'GET',data)
}
function requstPost(url,data,header = {}){
    return requst(url,'POST',data,header)
}
function requst(url,method,data = {},header = {}){
    wx.showNavigationBarLoading()
    data.method = method
    return new Promise((resove,reject) => {
        wx.request({
            url: url,
            data: data,
            method: method.toUpperCase(), // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
            header,
            success: function(res){
                wx.hideNavigationBarLoading()
                resove(res)
            },
            fail: function(msg) {
                console.log('reqest error',msg)
                wx.hideNavigationBarLoading()
                reject('fail')
            }
        })
    })
}
function getBuffer( key ){

    let res = wx.getStorageInfoSync()
    if( res.keys.includes( key ) ){
        return new Promise(( resolve, reject )=>{
            wx.getStorage({
                key : key,
                success : resolve,
                fail : reject
            })
        })
    }else{
        return false
    }

}

const ArrayExtractToObjectWithKey = ( array, key )=>{

  let result = new Object()

  for( let i =0,len = array.length; i < len; i ++ ){

    let newkey = array[ i ][ key ]
    result[ newkey ] = { ...array[ i ] }

  }

  return result

}

const ObjectToArray = ( object ) => {
    var res = new Array()

    for( var key in object){

      res.push( object[ key ] )

    }
    return res
}

//登录
function login(){
    return new Promise((resolve,reject) => wx.login({
        success:resolve,
        fail:reject
    }))
}



module.exports = {
  formatTime: formatTime,
  ArrayExtractToObjectWithKey,
  ObjectToArray,
    get:requstGet,
    post:requstPost,
    test,
    getBuffer,
    login
}
