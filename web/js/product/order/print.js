$(document).ready(function () {
    $('#start').click(function(){
        $("#print").jqprint({
            debug: false,
            importCSS: true,
            printContainer: true,
            operaSupport: false
        });
    });
});