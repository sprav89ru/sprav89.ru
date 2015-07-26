helloWorld = 'Hello World! from scripts.js!!';

// var main = function () {
//   "use strict";
//   window.alert("hello, world!");
//   window.confirm(helloWorld);
// };
//   $(document).ready(main);

var main = function () {
"use strict";
$(".comment-input button").on("click", function (event) {
console.log("Hello, World!");
});
};
$(document).ready(main);

//alert (helloWorld);

var cssFix = function(){
  var u = navigator.userAgent.toLowerCase(),
  addClass = function(el,val){
    if(!el.className) {
      el.className = val;
    }
    else {
      var newCl = el.className;
      newCl+=(" "+val);
      el.className = newCl;
    }
  console.log('el.className = '+ el.className); 
  },

  is = function(t){return (u.indexOf(t)!=-1)};
  arra = [
    (!(/opera|webtv/i.test(u))&&/msie (\d)/.test(u))?('ie ie'+RegExp.$1)
      :is('firefox/2')?'gecko ff2'
      :is('firefox/3')?'gecko ff3'
      :is('gecko/')?'gecko'
      :is('opera/9')?'opera opera9':/opera (\d)/.test(u)?'opera opera'+RegExp.$1
      :is('konqueror')?'konqueror'
      :is('applewebkit/')?'webkit safari'
      :is('mozilla/')?'gecko':'',
    (is('x11')||is('linux'))?' linux'
      :is('mac')?' mac'
      :is('win')?' win':''
  ];
  arra.join(" ");

  //console.log('arra.join(" ")');
  addClass(document.getElementsByTagName('html')[0],arra.join(" "));
  //console.log(' addClass  = '+ addClass.); 
}();