var appid = 'RmjXO3oaJYHIBOdAl93nXeeN-gzGzoHsz';
var appkey = '37e3XsQl7C1HQIpK1uUzARLX';
var string = 'http://id-card-no.oss-cn-shanghai.aliyuncs.com/img/PROTOSS_IMG78301489745886829.png?Expires=1491573274&amp;OSSAccessKeyId=LTAIhGkmaEKmWfWY&amp;Signature=DU9c0%2BROJBf0KCQBTTu7GIoGdQE%3D';
AV.init({
  appId: appid,
  appKey: appkey,
});

var Realtime = AV.Realtime;
var realtime = new Realtime({
  appId: appid,
  plugins: [AV.TypedMessagesPlugin],
});
var customer = '匿名用户';
var waiter = '客服';
var CId = '';
var WId = '';
var ConId = '';
var CIcon = 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1490682813797&di=cb4a751d1067752f3f75cc305cbb2b91&imgtype=0&src=http%3A%2F%2Fk1.jsqq.net%2Fuploads%2Fallimg%2F1612%2F140F5A32-6.jpg';
var WIcon = 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1490682813797&di=cb4a751d1067752f3f75cc305cbb2b91&imgtype=0&src=http%3A%2F%2Fk1.jsqq.net%2Fuploads%2Fallimg%2F1612%2F140F5A32-6.jpg';

//初始化
function init(){

  // 创建IMClient实例
  window.IM = realtime.createIMClient(CId);
  window.conversation = IM.then(function (user) {
    return user.getConversation(ConId);
  });
  //创建消息记录迭代器
  window.messageIterator = conversation.then(function (conversation) {
    return conversation.createMessagesIterator({limit: 10});
  });
}

function lookForService(){
  $.ajax({
    type:'POST',
    url:'/api/service/login/chatter',
    cache:false,
    dataType:'json',
    data:{"user_id":id},
    headers:{"X-Client":device_id},
    success:function(data){
      if(!data.service) {
        setTimeout('lookForService()',60*1000);
      }else{
        $('.input-chat').css('display','block');
        $('#message').remove();
        if(data.nick)
          customer = data.nick;
        if(data.icon_url)
        {
          CIcon = data.icon_url.split('?',1)[0];
        }
        if(data.service.nick)
          waiter = data.service.nick;
        if(data.service.icon_url)
        {
          WIcon = data.service.icon_url.split('?',1)[0];
        }
        CId = data.im_id;
        WId = data.service.im_id;
        ConId = data.con_id;
        //初始化IM
        init();
        //消息处理
        message();
      }
    }
  });

}
lookForService();






