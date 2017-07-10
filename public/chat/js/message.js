function message() {

  //发送文本消息
  $('#sendTextMessage').click(function () {
    var message = $('#content').val();
    if (!message.length) {
      $('#content').focus();
      return false;
    }
    conversation.then(function (conversation) {
      return conversation.send(new AV.TextMessage(message));
    }).then(function (message) {
      var html = handleMessage(message);
      $(html).appendTo('.chat-list');
      $('#content').val('');
      $('#content').focus();
      $('.chat-list').scrollTop($('.chat-list').get(0).scrollHeight);
    }).catch(console.error);
  })

  //发送图片消息
  $('#uploadImg').change(function () {
    var fileUploadControl = $('#uploadImg')[0];
    if (!new RegExp('image/').test(fileUploadControl.files[0].type)) {
      alert('请选择图片');
    }
    var file = new AV.File('user.jpg', fileUploadControl.files[0]);
    file.save().then(function () {
      var message = new AV.ImageMessage(file);
      return conversation.then(function (conversation) {
        return conversation.send(message);
      });
    }).then(function (message) {
      var html = handleMessage(message);
      $(html).appendTo('.chat-list');
      $('.chat-list').scrollTop($('.chat-list').get(0).scrollHeight);
    }).catch(console.error);
  });

  //接受消息
  IM.then(function (user) {
    user.on('message', function messageEventHandler(message, conversation) {
      var html = handleMessage(message);
      $(html).appendTo('.chat-list');
      $('.chat-list').scrollTop($('.chat-list').get(0).scrollHeight);
    })
  });

  //每次登录加载聊天记录
  getRecords();

  //上滑加载聊天记录
  $('.chat-list').scroll(function () {
    if ($('.chat-list').scrollTop() == 0) {
      $('.chat-list').prepend('<div id="load" style="text-align:center">loading....</div>')
      setTimeout(function () {
        $('#load').remove()
        getRecords()
      }, 3000);
    } else {
      $('#load').remove()
    }
  })

  //获取未读消息
  IM.then(function (client) {
    client.on('unreadmessages', function unreadMessagesEventHandler(payload, conversation) {
      conversation.markAsRead().then(function (conversation) {
      }).catch(console.error.bind(console));
    });
  });

  //监听键盘事件
  $(document).keydown(function (e) {
    if (e.shiftKey && e.which == 13) {
      $('#sendTextMessage').trigger("click");
    }
  });
}

//获取聊天记录并展示
function getRecords() {
  messageIterator.then(function (messageIterator) {
    messageIterator = messageIterator.next();
    messageIterator.then(function (result) {
      result.value.reverse();
      var self = $('.chat-list').find('div:first')
      $(result.value).each(function (index, value) {
        var html = handleMessage(value);
        $('.chat-list').prepend(html)
      });
      $('.chat-list').scrollTop(self.offset().top)
    });
  });
}

//处理消息
function handleMessage(message) {
  if(message._lcattrs) {
    if (message.from == CId && message._lcattrs.hello)
      return false;
  }
  var position = '';//消息展示在左边还是右边
  var content = '';//消息内容
  var name = '';//消息发送人昵称
  var avatar = '';//图像
  if (message.from == CId) {
    position = 'right';
    name = customer;
    avatar = CIcon;
  } else if(message.from == WId ) {
    position = 'left';
    name = waiter;
    avatar = WIcon;
  }
  //判断是何种类型的消息
  switch (message.type) {
    case AV.TextMessage.TYPE:
      content = message.getText();
      content = content.replace(/\n/g,'<br>');
      content = content.replace(/\s/g,'&nbsp;');
        $.each(alias,function(i,item){
        content = content.replace(item,'<img src="/chat/img/emojis/'+i+'.png">');
      });
      break;
    case AV.ImageMessage.TYPE:
      content = '<img src="'+message.getFile().url()+'">'
      break;
    default:
      console.warn('收到未知类型消息');
  }

  return '<div class="chat-item '+position+' "> <div class="user clearfix"> <div class="user-img"> <img src="' + avatar + '"alt=""> </div> <div class="user-name">' + name + '</div> </div> <div class="chat-content">' + content + '</div> </div>';
}
