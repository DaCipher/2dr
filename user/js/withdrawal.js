$(document).ready(function () {
    $("#btc-tab").click(function () {
        $("#btc").addClass("show active");
        $("#bank_wire").removeClass("show active");
        $('#bank_form .form-group span.help-block').text('');
    });

    $("#bank_wire-tab").click(function () {
        $("#bank_wire").addClass("show active");
        $("#btc").removeClass("show active");
        $('#btc_form .form-group span.help-block').text('');
    });
});