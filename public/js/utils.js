function validaCpf(input) {    
    var split = $(input).val().substring(0, $(input).val().length - 2).replace(/\D/g, '').split("");        
    if(split.length == 0) {
        return true;
    }

    var valido = false;

    split.forEach(function(value, index) {
        if(value != split[0]) {
            valido = true;
        }
    });

    var c = 10;
    var verificado = 0;
    var verificador = $(input).val().substring($(input).val().length - 2, $(input).val().length);            

    split.forEach(function(value, index) {
        verificado += value * c;
        c--;
    });

    verificado = verificado * 10 % 11;

    if(verificado == 10) {
        verificado = 0;
    }

    if(verificador.substring(0, 1) != verificado) {
        valido = false;
    }

    split = $(input).val().substring(0, $(input).val().length - 1).replace(/\D/g, '').split("");
    c = 11;
    verificado = 0;            

    split.forEach(function(value, index) {
        verificado += value * c;
        c--;
    });

    verificado = verificado * 10 % 11;

    if(verificado == 10) {
        verificado = 0;
    }

    if(verificador.substring(1, 2) != verificado) {
        valido = false;
    }

    // if($(input).val() == "") {
    //     valido = false
    // }

    if(!valido) {                
        alert('CPF Inválido.');
        $(input).val("");
        $(input).focus();
    }

    return valido;
}