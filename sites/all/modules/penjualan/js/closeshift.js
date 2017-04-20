$(document).ready(function(){

  pathutama = Drupal.settings.basePath;
  $( "#print" ).click(function() {
    $.ajax({
        type: "POST",
        url: pathutama + "penjualan/updateshift",
        cache: false,
        success: function (data) {
            window.open(pathutama + "print/6?idshift=7");
            window.location = pathutama + 'penjualan/kasir';
        }
    });
});
});
