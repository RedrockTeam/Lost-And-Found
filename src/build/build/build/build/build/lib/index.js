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



requirejs(['zepto', 'fastclick'],function(zepto,fc){
    console.log(zepto("body"));
    console.log(fc);
})