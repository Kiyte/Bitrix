BX.ready(function(){  
    var Confirmer = new BX.PopupWindow("ajax-add-answer", null, {
        content: '<div id="mainshadow"></div>'+'<h3>Элемент добавлен/удалён</h3>',
        closeIcon: {right: "0px", top: "0px"},
        zIndex: 0,
        offsetLeft: 0,
        offsetTop: 0,
        draggable: {restrict: false},
        overlay: {backgroundColor: 'black', opacity: '80' },  /* затемнение фона */
        buttons: [
            new BX.PopupWindowButton({
                text: "Закрыть",
                className: "webform-button-link-cancel",
                events: {click: function(){
                 this.popupWindow.close(); // закрытие окна
                }}
            })
        ]
    });

    var failerEmpty = new BX.PopupWindow("ajax-add-answer", null, {
        content: '<div id="mainshadow"></div>'+'<h3>Заполните поле "Город"</h3>',
        closeIcon: {right: "0px", top: "0px"},
        zIndex: 0,
        offsetLeft: 0,
        offsetTop: 0,
        draggable: {restrict: false},
        overlay: {backgroundColor: 'black', opacity: '80' },  /* затемнение фона */
        buttons: [
            new BX.PopupWindowButton({
                 text: "Закрыть",
                 className: "webform-button-link-cancel",
                 events: {click: function(){
                  this.popupWindow.close(); // закрытие окна
                 }}
            })
        ]
    });

    BX.bindDelegate(
        document.body, 'click', {className: 'function-button' },
        function(e){
            document.body.classList.remove('loaded_hiding');
            document.body.classList.remove('loaded');
            let url = '/local/components/razum/city.list/ajax.php';
            let err = 0;
            let fields = [];
            let element = e['target'];
            fields["action_type"] = element .getAttribute('data-action');
            if(fields["action_type"] == 'delete'){
                fields["element"] = element .nextElementSibling.id;
            } else if(fields["action_type"] == 'add'){
                fields["name"] = BX('city').value;
                if (fields["name"] == ''){
                    failerEmpty.show();
                    err++;
                }
                fields["iblock-id"] = BX('iblock-id').getAttribute('data-id');
            }
            if(err == 0){
                BX.ajax.post(
                    url,
                    fields,
                    function (data) {
                        document.body.classList.add('loaded');
                        document.body.classList.remove('loaded_hiding');
                        Confirmer.show();
                        BX('city-list').innerHTML = data;
                        
                    }
                );
            }
 
        }
    );
});