$(document).ready(function () {
  $("#buscador").click(function () {
    $("h4").each(function () {
      var texto = $("#textoAbuscar").val().toUpperCase();
      var cabeceras = $(this).text().toUpperCase();


      if (texto === cabeceras) {
        var ident = $(this).attr("id");
        $(this).addClass("text-primary");
        setTimeout(function () {
          $("#" + ident).removeClass("text-primary");
        }, 5000);
        $("a").each(function () {
          if ($(this).attr("href") === "#" + ident) {
            $(this).get(0).click();
          }
        });
      }
    });
  });
});
$(document).ready(function () {
  $("#textoAbuscar").on("keyup", function () {
    var value = $(this).val().toLowerCase();
    $("#zonaScroll").filter(function () {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });
});

$(document).ready(function () {

  $("#boton").on("click", function () {
    var contador = 0;

    var nombre = $("#nombre").val();
    var correo = $("#correo").val();
    $("#formularioCentral input").each(function () {
      if ($(this).val() == "") {
        $(this).addClass("is-invalid");
        contador++;
      } else {
        $(this).addClass("is-valid");
        $(this).removeClass("is-invalid");
      }


    });
    if (contador == 0) {
      $("#Modal").modal("toggle");
      $(".modal-title").text("¡Gracias por contactar con nostros " + nombre + "!");
      $(".modal-body").text("En breve nos pondremos en contacto contigo en el correo " + correo + " ." +
        "No debería, pero estate atento por si nuestro mensaje llegara a tu carpeta de span");
    }

  });
  $("#cerrarModal").on("click", function () {
    $("#formularioCentral").submit();
  });
});



$(window).resize(function () {
  if ($(window).width() <= 768) {
    $("#navCambiable").removeClass("flex-column");
    $("#navCambiable2").removeClass("flex-column");
    $("#navbar-medio").css("height", "200px");
  } else {
    $("#navCambiable").addClass("flex-column");
    $("#navCambiable2").addClass("flex-column");
    $("#navbar-medio").css("height", "400px");
  }
  if ($(window).width() <= 567) {
    $("#navbar-medio").css("height", "350px");
    $("#navCambiable").addClass("flex-column");
    $("#navCambiable2").addClass("flex-column");
  }
});