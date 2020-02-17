$(document).ready(function(){
    $("#buscador").click(function(){
      $("h4").each(function(){
      var texto=$("#textoAbuscar").val().toUpperCase();
      var cabeceras=$(this).text().toUpperCase();
      
      
      if(texto===cabeceras){
        var ident=$(this).attr("id");
        $(this).addClass("text-primary");
        setTimeout(function(){
            $("#"+ident).removeClass("text-primary");
          },5000);
        $("a").each(function(){
          if($(this).attr("href")==="#"+ident){
          $(this).get(0).click();
          }
        });
        }
        });
      });
    });
$(document).ready(function(){
  $("#textoAbuscar").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#zonaScroll").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });
});
 

     
  $(window).resize(function(){
    if($(window).width()<=768){
      $("#navCambiable").removeClass("flex-column");
      $("#navCambiable2").removeClass("flex-column");
      $("#navbar-medio").css("height", "200px");
    }else{
      $("#navCambiable").addClass("flex-column");
      $("#navCambiable2").addClass("flex-column");
      $("#navbar-medio").css("height", "400px");
    }
    if($(window).width()<=567){
      $("#navbar-medio").css("height", "350px");
      $("#navCambiable").addClass("flex-column");
      $("#navCambiable2").addClass("flex-column");
    }
  });