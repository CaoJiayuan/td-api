/**
 * Created by d on 17-3-24.
 */
window.onload = function () {
  var ua = navigator.userAgent.indexOf("jsjapp");
  var openBtn = document.getElementById('oppenApp');
  if (ua !== -1) {
    // openBtn.style.display="none";
  } else {
    openBtn.style.display = "block";
    var ads = $('.ads');
    var type = ads.data('type');
    if (type != 3 && type != 2) {
      ads.attr('href', '#')
    }
  }
  var adsList = document.getElementsByClassName('ads');
  for (var i = 0; i < adsList.length; i++) {
    var item = adsList[i].getElementsByTagName('img')[0]
    var imgUrl = item.getAttribute('data-src')
    item.setAttribute('src', imgUrl)
  }
  var imgList = document.getElementById('center').getElementsByTagName('img');
  for (var i = 0; i < imgList.length; i++) {
    var imgUrl = imgList[i].getAttribute('data-src');
    imgList[i].setAttribute('src', imgUrl);
  }

};