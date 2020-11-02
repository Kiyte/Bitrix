BX.ready(function(){    
    BX.bind(BX('credit'), 'click', function() {
        let AJAX_ENABLE = 'Y';
        let action_type = 'credit';
        let error = 0;
        let name = '';
        let phone = '';
        let product = document.getElementById("product").value;
        if (document.getElementById("name-credit").value != ''){
            name = document.getElementById("name-credit").value;
            BX.style(BX('name-credit'),'border', '1px solid #c4c6cc');
        } else {
            BX.style(BX('name-credit'),'border-color', 'red');
            error++;
        };
        if (document.getElementById("contact-credit").value != ''){
            BX.style(BX('contact-credit'),'border', '1px solid #c4c6cc');
            phone = document.getElementById("contact-credit").value;           
        }else{
            BX.style(BX('contact-credit'),'border-color', 'red');
            error++;            
        };
        
        if (error == 0){
            BX.ajax({
                url:"/local/components/razum/web.form.detail/ajax.php",
                data: {
                    name,
                    phone,
                    action_type,
                    product,
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
                    BX.style(BX('success-in-credit'), 'opacity', 'unset');
                    BX.style(BX('success-in-credit'), 'visibility','unset');
                    BX.style(BX('credit-form'), 'visibility','hidden');                    
                },
                onfailure: function(){
                    BX.style(BX('success-error-credit'), 'opacity', 'unset');
                    BX.style(BX('success-error-credit'), 'visibility','unset');
                    BX.style(BX('credit-form'), 'visibility','hidden');
                }
            });
        }
    });  
    BX.bind(BX('try-again'),'click', function(){
        BX.style(BX('success-error-credit'), 'opacity', '0');
        BX.style(BX('success-error-credit'), 'visibility','hidden');
        BX.style(BX('credit-form'), 'visibility','unset');
    });   
});