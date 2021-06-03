var ui = {
    //加载提示模态框
    alert: function (obj) {
        var title=(obj==undefined||obj.title==undefined)?'系统消息':obj.title;
        var msg=(obj==undefined||obj.msg==undefined)?'':obj.msg;
        var icon=(obj==undefined||obj.icon==undefined)?'warning':obj.icon;
        var html = '<div class="modal fade" id="ui-alert-sm"  data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">\n' +
            '  <div class="modal-dialog modal-sm">\n' +
            '    <div class="modal-content">\n' +
            '      <div class="modal-header">\n' +
            '        <h5 class="modal-title" id="staticBackdropLabel">' + title + '</h5>\n' +
            '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
            '          <span aria-hidden="true">&times;</span>\n' +
            '        </button>\n' +
            '      </div>\n' +
            '      <div class="modal-body">\n' +
            '        <p><img src="assets/images/'+icon+'.png" style="width: 32px;height: 32px" class="mr-3">'+msg+'</p>\n' +
            '      </div>\n' +
            '      <div class="modal-footer">\n' +
            '        <button type="button" class="btn btn-secondary" data-dismiss="modal">确定</button>\n' +
            '      </div>\n' +
            '    </div>\n' +
            '  </div>\n' +
            '</div>';
        $('body').append(html);
        $('#ui-alert-sm').modal({backdrop: 'static'});
        $('#ui-alert-sm').modal('show');
        $('#ui-alert-sm').on('hidden.bs.modal', function (event) {
            $('#ui-alert-sm').remove();
        })
    },
    //加载模态框
    open:function (obj){
        var title=(obj==undefined||obj.title==undefined)?'':obj.title;
        var url=(obj==undefined||obj.url==undefined)?'':obj.url;
        var width=(obj==undefined||obj.width==undefined)?500:obj.width;
        var height=(obj==undefined||obj.height==undefined)?450:obj.height;
        var html='<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">\n' +
            '    <div class="modal-dialog">\n' +
            '        <div class="modal-content">\n' +
            '            <div class="modal-header">\n' +
            '                <h5 class="modal-title" id="exampleModalLabel">'+title+'</h5>\n' +
            '                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
            '                    <span aria-hidden="true">&times;</span>\n' +
            '                </button>\n' +
            '            </div>\n' +
            '            <div class="modal-body">\n' +
            '                 <iframe src="'+url+'" style="width: 100%;height: 100%;" frameborder="0"></iframe>\n'+
            '            </div>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div>';
        $('body').append(html);
        $('#loginModal .modal-dialog').css({'max-width':width});
        $('#loginModal .modal-body').css({'height':height});
        $('#loginModal').modal({backdrop: 'static'});
        $('#loginModal').modal('show');
        $('#loginModal').on('hidden.bs.modal', function (event) {
            $('#loginModal').remove();
        })
    }
};