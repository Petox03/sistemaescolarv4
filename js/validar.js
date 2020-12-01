window.addEventListener("load", function() {
    miformulario.cali1.addEventListener("keypress", soloNumeros, false);
    miformulario.cali2.addEventListener("keypress", soloNumeros, false);
    miformulario.cali3.addEventListener("keypress", soloNumeros, false);
});

  //Solo permite introducir numeros.
function soloNumeros(x){
    var key = window.event ? x.which : x.keyCode;
    if (key < 46 || key > 57) {
        x.preventDefault();
    }
}