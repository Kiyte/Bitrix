BX.ready(function(){    
    BX.bind(BX('cheape'), 'click', function() {
        let AJAX_ENABLE = 'Y'
        let error = 0;
        let name = '';
        let contact = '';
        let price = '';
        let product_url = '';
        
        if (document.getElementById("name").value != ''){
            name = document.getElementById("name").value;
            BX.style(BX('name'),'border', '1px solid #c4c6cc');
        } else {
            BX.style(BX('name'),'border-color', 'red');
            error++;
        };
        if (document.getElementById("contact").value != ''){
            BX.style(BX('contact'),'border', '1px solid #c4c6cc');
            contact = document.getElementById("contact").value;           
        }else{
            BX.style(BX('contact'),'border-color', 'red');
            error++;            
        };
        if (document.getElementById("price").value != ''){
            BX.style(BX('price'),'border', '1px solid #c4c6cc');
            price = document.getElementById("price").value;
        } else {
            BX.style(BX('price'),'border-color', 'red');
            error++;                       
        };
        if (document.getElementById('product_url').value != ''){
            BX.style(BX('product_url'),'border', '1px solid #c4c6cc');
            product_url = document.getElementById("product_url").value;
        } else {
            BX.style(BX('product_url'),'border-color', 'red');
            error++;            
        };

        if (error == 0){
            BX.ajax({
                url:"/local/components/razum/web.form.detail/ajax.php",
                data: {
                    name,
                    contact,
                    price,
                    product_url,
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
                    BX.style(BX('success-in'), 'opacity', 'unset');
                    BX.style(BX('success-in'), 'visibility','unset');
                    BX.style(BX('cheape-form'), 'visibility','hidden');                    
                },
                onfailure: function(){
                    BX.style(BX('success-error'), 'opacity', 'unset');
                    BX.style(BX('success-error'), 'visibility','unset');
                    BX.style(BX('cheape-form'), 'visibility','hidden');
                }
            });
        }
    });
    
    BX.bind(BX('try-again'),'click', function(){
        BX.style(BX('success-error'), 'opacity', '0');
        BX.style(BX('success-error'), 'visibility','hidden');
        BX.style(BX('cheape-form'), 'visibility','unset');
    });   
});