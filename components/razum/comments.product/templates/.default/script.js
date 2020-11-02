BX.ready(function(){   
    BX.bindDelegate(
        document.body, 'click', {className: 'add-comment' },
        function(e){
            let AJAX_ENABLE = 'Y';
            let fields = new Array();
            let err = 0;
            var element = this, arguments;
            fields['id_product'] = document.getElementsByName('ID_PRODUCT')[0].value;
            if (element.id == 'brief_comment'){
                fields['fio'] = document.getElementsByName('fio-bref')[0].value;
                fields['comment'] = document.getElementsByName('comment-brief')[0].value;
                fields['email'] = document.getElementsByName('breif-email')[0].value;
                fields['rew_email'] = document.getElementById('rew-checkbox2').checked;
                
                if(fields['fio'] == ''){
                    document.getElementsByName('fio-bref')[0].setAttribute('style','border-color:red');
                    err++;
                }else{
                    document.getElementsByName('fio-bref')[0].setAttribute('style','');
                }
                
                if(fields['comment'] == ''){
                    document.getElementsByName('comment-brief')[0].setAttribute('style','border-color:red');
                    err++;
                }else{
                    document.getElementsByName('comment-brief')[0].setAttribute('style','');
                }
                
                if(fields['email'] == ''){
                    document.getElementsByName('breif-email')[0].setAttribute('style','border-color:red');
                    err++;
                }else{
                    document.getElementsByName('breif-email')[0].setAttribute('style','');
                }           
            } else if(element.id == 'new-comment'){
                fields['fio'] = document.getElementsByName('fio')[0].value;
                fields['email'] = document.getElementsByName('email')[0].value;
                fields['rew-checkbox'] = document.getElementById('rew-checkbox').checked;
                fields['worth'] = document.getElementsByName('worth')[0].value;
                fields['disadvantages'] = document.getElementsByName('disadvantages')[0].value;
                fields['comment'] = document.getElementsByName('comment')[0].value;
                fields['rating'] = document.getElementsByName('rating')[0].value;
                
                if (fields['email'] == ''){
                    document.getElementsByName('email')[0].setAttribute('style','border-color:red');
                    err++;
                } else {
                   document.getElementsByName('email')[0].setAttribute('style',''); 
                }
                
                if(fields['fio'] == ''){
                    document.getElementsByName('fio')[0].setAttribute('style','border-color:red');
                    err++;
                } else {
                    document.getElementsByName('fio')[0].setAttribute('style','');
                }
                
                if(fields['comment'] == ''){
                    document.getElementsByName('comment')[0].setAttribute('style','border-color:red');
                    err++;
                } else {
                    document.getElementsByName('comment')[0].setAttribute('style','');
                }
            }
            if (err == 0){
                BX.ajax({
                    url:"/local/components/razum/comments.product/ajax.php",
                    data: {
                        fields,
                        AJAX_ENABLE,
                    },
                    method: 'POST',
                    timeout:0,
                    async:true,
                    processData: true,
                    scriptsRunFirst: true,
                    emulateOnload: true,
                    start: true,
                    cache: false,
                    onsuccess: function(data){
                        alert('Комментарий будет доступен после проверки администрацией');
                        document.getElementById("rew-form").reset();                   
                    },
                    onfailure: function(){

                    }
                });
            }            
        }           
    );
    
 BX.bindDelegate(
        document.body, 'click', {className: 'answer-quest' },
        function(e){
            let fields = new Array();
            fields['parent_comment'] = BX(this).id;
            fields['id_product'] = document.getElementsByName('ID_PRODUCT')[0].value;
            let err = 0;
            let AJAX_ENABLE = 'Y';
            let parent = BX.findParent(BX(this), {className:"answer"}, false);
            let child_input = BX.findChild(BX(parent), {class: 'form-control'}, true, true);
            child_input.forEach(function(element){
                if(element.value == ''){
                    element.setAttribute('style','border-color:red');
                    err++;
                } else {
                    element.setAttribute('style','');
                }
                fields[element.getAttribute('name')] = element.value;
                
            });
            if (err == 0){
                BX.ajax({
                    url:"/local/components/razum/comments.product/ajax.php",
                    data: {
                        fields,
                        AJAX_ENABLE,
                    },
                    method: 'POST',
                    timeout:0,
                    async:true,
                    processData: true,
                    scriptsRunFirst: true,
                    emulateOnload: true,
                    start: true,
                    cache: false,
                    onsuccess: function(data){
                        alert('Комментарий будет доступен после проверки администрацией');
                        document.getElementById("answer-form").reset();                   
                    },
                    onfailure: function(){

                    }
                });
            }            
        });    
});