(function($){
    $(document).ready(function()
    {
        var URLactual = window.location.hostname+""; 

        if ((window.location.pathname == '/' || window.location.pathname == '/node') && document.getElementById("usuario_id") == null) 
        {
            if (typeof $(".first") !== 'undefined') {
              //$(".first")[0].outerHTML = '<li class="first last" style="font-size: 10px;">usted acepta los <a href="http://conectatepublicar.linkpruebas.com/sites/default/files/conectate-terminos.pdf" title="" target="_blank">Términos y Condiciones</a> y la <a href="http://conectatepublicar.web/sites/default/files/politicadeprivacidad.pdf" target="_blank" title="">Política de Privacidad</a> del Programa "Conéctate Publicar"</li>';
              $(".first")[0].outerHTML = '';
            }
        }
        if (document.getElementById("administrarimagen") != null) {
          $(".sec-que-es").removeClass('sec-que-es');
          $(".block-title")[1].style.display = 'none';
          //$(".sec-que-es")[0].style.removeProperty("background-image");
        }
        
        var d = new Date();
        var data = {
            documento: document.getElementById("usuario_id").value,
            dia : d.getDate(),
            mes : d.getMonth()+1,
            ano : d.getFullYear(),
            dominio:  window.location.hostname
        };
        $.ajax({
            url: "http://"+URLactual+ "/conect/index.php/admin/job/puntosPorUsuario",
            type: 'post',
            data: data,
            success: function(info){
                modResponse = $.parseJSON(info);
                if (modResponse.estado=== false) {
                    for (i = 0; i < $(".puntosTotal").length; i++) {
                        $(".puntosTotal")[i].textContent = 0;
                    }
                }else{
                    for (i = 0; i < $(".puntosTotal").length; i++) {
                        $(".puntosTotal")[i].textContent = modResponse.carga;
                    } 
                }
            }       
        });
        $.ajax({
            url: "http://"+URLactual+ "/conect/index.php/admin/job/metasPorUsuario/1",
            type: 'post',
            data: data,
            success: function(info){
                modResponse = $.parseJSON(info);
                if (modResponse.estado=== false) {
                    document.getElementById("rendimiento").outerHTML = '<div id="rendimiento">'+modResponse.carga+'</div>';
                }else{
                    for (i = 0; i < $("#rendimiento").length; i++) {
                        $("#rendimiento")[i].outerHTML = '<div id="rendimiento">'+modResponse.carga+'</div>';
                    }
                }
            }       
        });
        function cargarmetodo()
        {
            $( ".mes" ).change(function(ev) {
                data.mes = ev.currentTarget[ev.currentTarget.selectedIndex].value;
                $.ajax({
                    url: "http://"+URLactual+ "/conect/index.php/admin/job/metasPorUsuario",
                    type: 'post',
                    data: data,
                    success: function(info){
                        modResponse = $.parseJSON(info);
                        if (modResponse.estado=== false) {
                            document.getElementById("rendimientoTotal").outerHTML = '<div id="rendimientoTotal">'+modResponse.carga+'</div>';
                        }else{
                            for (i = 0; i < $(".rendimientoTotal").length; i++) {
                                $(".rendimientoTotal")[i].outerHTML = '<div id="rendimientoTotal">'+modResponse.carga+'</div>';
                            }
                        }
                        cargarmetodo();
                    }     
                });
            });
        }

        $.ajax({
            url: "http://"+URLactual+ "/conect/index.php/admin/job/metasPorUsuario",
            type: 'post',
            data: data,
            success: function(info){
                modResponse = $.parseJSON(info);
                if (modResponse.estado=== false) {
                    document.getElementById("rendimientoTotal").outerHTML = '<div id="rendimientoTotal">'+modResponse.carga+'</div>';
                }else{
                    for (i = 0; i < $(".rendimientoTotal").length; i++) {
                        $(".rendimientoTotal")[i].outerHTML = '<div id="rendimientoTotal">'+modResponse.carga+'</div>';
                    }
                }
                cargarmetodo();
            }     
        });
        console.log(document.getElementById('quicktabs-tab-tabs_rendimiento-1'));
        if(document.getElementById('quicktabs-tab-tabs_rendimiento-1') !=null)
        {
            document.getElementById("quicktabs-tab-tabs_rendimiento-1").onclick = function()
            {
               $.ajax({
                    url: "http://"+URLactual+ "/conect/index.php/admin/job/rankingxgrupo",
                    type: 'post',
                    data: data,
                    success: function(info){
                        modResponse = $.parseJSON(info);
                        if (modResponse.estado=== false) {
                            document.getElementById("rendimientoRanking").outerHTML = '<div id="rendimientoRanking">'+modResponse.carga+'</div>';
                        }else{
                            for (i = 0; i < $(".rendimientoRanking").length; i++) {
                                $(".rendimientoRanking")[i].outerHTML = '<div id="rendimientoRanking" class="rendimientoRanking">'+modResponse.carga+'</div>';
                            }
                            for (i = 0; i < $(".rendimientoRanking").length; i++) {
                                $(".rendimientoRanking")[i].outerHTML = '<div id="rendimientoRanking" class="rendimientoRanking">'+modResponse.carga+'</div>';
                            }
                        }
                    }       
                });
            }
        }
        console.log(document.getElementById('quicktabs-tab-rendimiento_sumate-1'));
        if(document.getElementById('quicktabs-tab-rendimiento_sumate-1') !=null)
        {
            document.getElementById('quicktabs-tab-rendimiento_sumate-1').onclick = function()
            {
               $.ajax({
                    url: "http://"+URLactual+ "/conect/index.php/admin/job/rankingxgrupo",
                    type: 'post',
                    data: data,
                    success: function(info){
                        modResponse = $.parseJSON(info);
                        if (modResponse.estado=== false) {
                            document.getElementById("rendimientoRanking").outerHTML = '<div id="rendimientoRanking">'+modResponse.carga+'</div>';
                        }else{
                            for (i = 0; i < $(".rendimientoRanking").length; i++) {
                                $(".rendimientoRanking")[i].outerHTML = '<div id="rendimientoRanking" class="rendimientoRanking">'+modResponse.carga+'</div>';
                            }
                            for (i = 0; i < $(".rendimientoRanking").length; i++) {
                                $(".rendimientoRanking")[i].outerHTML = '<div id="rendimientoRanking" class="rendimientoRanking">'+modResponse.carga+'</div>';
                            }
                        }
                    }       
                });
            }
        }
    }); 
})(jQuery);