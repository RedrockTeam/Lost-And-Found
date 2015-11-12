/**
 * Created by liuhuizhi on 15/11/12.
 */

require.config({
    baseUrl: '../build/lib',
    path: {
        fastclick: "fastclick.js",
        zepto: "zepto.min.js"
    }
})



requirejs([zepto, fastclick],function($,fastclick){
    alert(1);
})