$( document ).ajaxStart(function() {    
    $('body').addClass('loading');
});

$( document ).ajaxStop(function() {    
    $('body').removeClass('loading');
});