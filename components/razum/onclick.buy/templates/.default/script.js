BX.ready(function(){    
    BX.bind(BX('onClickBuy'), 'click', function() {
        
        let AJAX_ENABLE = 'Y';
        let error = 0;
        let name = '';
        let phone = '';
        let ID_PRODUCT = document.getElementById("id_product").value;
        
        if (document.getElementById("name-one-buy").value != ''){
            name = document.getElementById("name-one-buy").value;
            BX.style(BX('name-one-buy'),'border', '1px solid #c4c6cc');
        } else {
            BX.style(BX('name-one-buy'),'border-color', 'red');
            error++;
        };
        
        if (document.getElementById("contact-one-buy").value != ''){
            BX.style(BX('contact-one-buy'),'border', '1px solid #c4c6cc');
            phone = document.getElementById("contact-one-buy").value;           
        }else{
            BX.style(BX('contact-one-buy'),'border-color', 'red');
            error++;            
        };
        console.log(phone);
        if (error == 0){
            BX.ajax({
                url:"/local/components/razum/onclick.buy/ajax.php",
                data: {
                    name,
                    phone,
                    ID_PRODUCT,
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
                    BX.style(BX('suc_buy'), 'opacity', 'unset');
                    BX.style(BX('suc_buy'), 'visibility','unset');
                    BX.style(BX('form_click'), 'visibility','hidden');                    
                },
                onfailure: function(){
                    BX.style(BX('err_buy'), 'opacity', 'unset');
                    BX.style(BX('err_buy'), 'visibility','unset');
                    BX.style(BX('form_click'), 'visibility','hidden');
                }
            });
        }
    });  
    BX.bind(BX('try-again'),'click', function(){
        BX.style(BX('success-error-credit'), 'opacity', '0');
        BX.style(BX('success-error-credit'), 'visibility','hidden');
        BX.style(BX('credit-form'), 'visibility','unset');
    });
    $('.close-mag').click(function(){
       $('.mfp-close').trigger('click'); 
    });
});