/**
 * Created by liuhuizhi on 15/11/12.
 */

require.config({
    baseUrl: '../build/lib',
    path: {
        fastclick: "fastclick.js",
        zepto: "zepto.js"
    }
})



//requirejs([zepto, fastclick],function($,fastclick){
//    console.log($);
//})

define([zepto,fastclick],function($,fc){
    console.log($)
})