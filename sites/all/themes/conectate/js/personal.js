(function($){
 	$(document).ready(function()
 	{
 		var URLactual = window.location.hostname+""; 
 		var d = new Date();
 		var data = {
            documento: document.getElementById("usuario_id").value,
            dia : d.getDate(),
            mes : d.getMonth(),
            ano : d.getFullYear()
        };
 		$.ajax({
            url: "http://"+URLactual+ "/conect/index.php/admin/job/puntosPorUsuario",
            type: 'post',
            data: data,
            success: function(info){
            	modResponse = $.parseJSON(info);
                if (modResponse.estado=== false) {
                    for (i = 0; i < $(".puntos").length; i++) {
					    $(".puntos")[i].textContent = 0;
					}
                }else{
                    for (i = 0; i < $(".puntos").length; i++) {
					    $(".puntos")[i].textContent = modResponse.carga;
					} 
                }
            }       
        });
        $.ajax({
            url: "http://"+URLactual+ "/conect/index.php/admin/job/metasPorUsuario",
            type: 'post',
            data: data,
            success: function(info){
                modResponse = $.parseJSON(info);
                if (modResponse.estado=== false) {
                    document.getElementById("rendimiento").outerHTML = '<div id="rendimiento">'+modResponse.carga+'</div>';
                }else{
                    document.getElementById("rendimiento").outerHTML = '<div id="rendimiento">'+modResponse.carga+'</div>';
                }
            }       
        });
	}); 
})(jQuery);