$('document').ready(function() {
    $("#settings_btn").attr('disabled', true);
    $('#settings_status').hide();
    $('#profile_status').hide();
    // Password 1 Validation
    var pass1 = $("#password1");
    var pass2 = $("#password2");
    var btn = $('#settings_btn');
    var pass1_err = $('#password1_err');
    var pass2_err = $('#password2_err');
    var btn = $('#settings_btn');
    pass1.keyup(function() {
        var pass1_val = pass1.val();
        var pass2_val = pass2.val();
        if (pass1_val.length < 8) {
            pass1_err.text("Password must be at least 8 characters");
            pass1.addClass("border border-danger").removeClass("border border-success");
            btn.attr('disabled', true);
            if (pass2_val !== "") {
                pass2.addClass('border border-danger').removeClass('border border-success');
            }
        } else if (pass2_val !== "") {
            if (pass2_val !== pass1_val && pass1_val.length < 8) {
                pass1_err.text("Password must be at least 8 characters!");
                pass1.addClass("border border-danger").removeClass('border border-success');
                pass2_err.text('Passwords does not match!');
                pass2.addClass('border border-danger').removeClass('border border-success');
                btn.attr('disabled', true);
            } else if (pass2_val !== pass1_val || pass1_val.length > 7) {
                pass1_err.text("Password must be at least 8 characters!");
                pass1.addClass("border border-danger").removeClass('border border-success');
                pass2_err.text('Passwords does not match!');
                pass2.addClass('border border-danger').removeClass('border border-success');
                btn.attr('disabled', true);
            } else {
                pass1_err.text("");
                pass1.addClass('border border-success').removeClass('border border-danger');
                pass2_err.text('');
                pass2.addClass('border border-success').removeClass('border border-danger');
                btn.attr("disabled", false);
            }
        } else {
            pass1_err.text("");
            pass1.removeClass("border border-danger").addClass('border border-success');
        }
    });
    // Password 2 validation
    pass2.keyup(function() {
        var pass1_val = pass1.val();
        var pass2_val = pass2.val();
        if (pass1_val.length > 7) {
            if (pass2_val !== pass1_val) {
                pass2_err.text("Password does not match!");
                pass2.addClass('border border-danger').removeClass('border border-success');
                btn.attr('disabled', true);
            } else {
                pass1_err.text('');
                pass1.addClass('border border-success').removeClass("border border-danger");
                pass2_err.text('');
                pass2.addClass('border border-success').removeClass('border border-danger');
                btn.attr('disabled', false);
            }
        } else {
            btn.attr('disabled', true);
            pass1_err.text('Password must be at least 8 characters');
            pass1.addClass('border border-danger').removeClas('border border-success');
        }


    });

    // ajax submit for password
    $('form#settings').submit(function(event) {
        event.preventDefault();
        action = $(this).attr("action");
        data = $(this).serialize();
        spinner = '<div class="spinner spinner-border spinner-border-sm" style="width: 1.2rem; height: 1.2rem;" id="spinner"></div> Changing password...'
        $.ajax({
            url: action,
            method: "POST",
            data: data,
            dataType: "JSON",
            beforeSend: function() {
                $('input').attr('disabled', true);
                $('#settings_btn').html(spinner).attr('disabled', true);
            },
            success: function(response) {
                $('input').attr('disabled', false);
                $('#settings_btn').text('Change Password');
                $("input[type='text'], input[type='password']").val("").removeClass('border border-success');
                if (response.pass1_err !== undefined) {
                    $("#password1_err").text(response.pass1_err);
                }
                if (response.pass2_err !== undefined) {
                    $("#password2_err").text(response.pass2_err);
                }
                if (response.success !== undefined) {
                    $("#settings_status").text(response.success).addClass('alert-success').fadeIn(function() {
                        setTimeout(function() {
                            $("#settings_status").fadeOut("slow");
                        }, 3000);
                    });
                }

                if (response.fail !== undefined) {
                    $("#settings_status").text(response.success).addClass('alert-danger').fadeIn(function() {
                        setTimeout(function() {
                            $("#settings_status").fadeOut("slow");
                        }, 3000);
                    });
                }
            },
            error: function() {
                $('#spinner').addClass('d-none');
                $('input').attr('disabled', false);
                $('#settings_btn').text('Change Password');
                alert("Something went wrong!");
            }
        });

    });

    // ajax submit for update profile
    $("form#profile").submit(function(event) {
        event.preventDefault();
        var action = $(this).attr("action");
        var data = $(this).serialize();
        var spinner = '<div class="spinner spinner-border spinner-border-sm" style="height: 1.2rem; width: 1.2rem;"></div> Updating Record(s)...';
        $.ajax({
            url: action,
            method: "POST",
            dataType: "JSON",
            data: data,
            beforeSend: function() {
                $("#profile_btn").html(spinner).attr('disabled', true);
                $('input, select').attr('disabled', true);
            },
            success: function(reply) {
                $('input:not(#email):not(#username), select').attr('disabled', false);
                $("#profile_btn").text("Update").attr('disabled', false);
                if (reply.fname_err !== undefined) {
                    $('#firstname_err').text(reply.fname_err)
                    $('#firstname').addClass("border border-danger");
                } else {
                    $('#firstname_err').text("")
                    $('#firstname').removeClass("border border-danger");
                }
                if (reply.lname_err !== undefined) {
                    $('#lastname_err').text(reply.lname_err)
                    $('#lastname').addClass("border border-danger");
                } else {
                    $('#lastname_err').text("")
                    $('#lastname').removeClass("border border-danger");
                }
                if (reply.mname_err !== undefined) {
                    $('#middlename_err').text(reply.mname_err)
                    $('#middlename').addClass("border border-danger");
                } else {
                    $('#middlename_err').text("")
                    $('#middlename').removeClass("border border-danger");
                }
                if (reply.phone_err !== undefined) {
                    $('#phone_err').text(reply.phone_err)
                    $('#phone').addClass("border border-danger");
                } else {
                    $('#phone_err').text("")
                    $('#phone').removeClass("border border-danger");
                }
                if (reply.country_err !== undefined) {
                    $('#country_err').text(reply.country_err)
                    $('#country').addClass("border border-danger");
                } else {
                    $('#country_err').text("")
                    $('#country').removeClass("border border-danger");
                }
                if (reply.success !== undefined) {
                    $("#profile_status").text(reply.success).addClass('alert-success').fadeIn(function() {
                        setTimeout(function() {
                            $("#profile_status").fadeOut("slow");
                        }, 3000);
                    });
                }

                if (reply.fail !== undefined) {
                    $("#profile_status").text(reply.success).addClass('alert-success').fadeIn(function() {
                        setTimeout(function() {
                            $("#profile_status").fadeOut("flow");
                        }, 3000);
                    });
                }


            },
            error: function() {
                alert("Unable to process request!");
            }


        });
    });

    // Desposit Page
    function paymentID(min, max) {
        return Math.floor(Math.random() * (max - min)) +
            min;
    }

    $('form#basic_plan').submit(function(event) {
        event.preventDefault();
        $("#payment").modal("show");
        var amt = $("#basic_amount").val();
        var amount = (new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amt));
        var plan = "BASIC";
        var payID = "REF-B" + paymentID(10009, 99999);
        $('#transaction_id').text(payID);
        $("#plan").text(plan);
        $("#amount").text(amount);
        $("#copy_btn").text("Copy");
        $("#copy_btn").removeClass("bg-success");
        $("#copy_btn").addClass("btn-outline-primary");
        $("#copy_btn").removeClass("bg-success text-white");

    });

    $('form#regular_plan').submit(function(event) {
        event.preventDefault();
        $("#payment").modal("show");
        var amt = $("#regular_amount").val();
        var amount = (new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amt));
        var plan = "REGULAR";
        var payID = "REF-R" + paymentID(10009, 99999);
        $('#transaction_id').text(payID);
        $("#plan").text(plan);
        $("#amount").text(amount);
        $("#copy_btn").text("Copy");
        $("#copy_btn").removeClass("bg-success");
        $("#copy_btn").addClass("btn-outline-primary");
        $("#copy_btn").removeClass("bg-success text-white");

    });

    $('form#vip_plan').submit(function(event) {
        event.preventDefault();
        $("#payment").modal("show");
        var amt = $("#vip_amount").val();
        var amount = (new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amt));
        var plan = "VIP";
        var payID = "REF-V" + paymentID(10009, 99999);
        $('#transaction_id').text(payID);
        $("#plan").text(plan);
        $("#amount").text(amount);
        $("#copy_btn").text("Copy");
        $("#copy_btn").removeClass("bg-success");
        $("#copy_btn").addClass("btn-outline-primary");
        $("#copy_btn").removeClass("bg-success text-white");

    });

    $('form#vip_gold_plan').submit(function(event) {
        event.preventDefault();
        $("#payment").modal("show");
        var amt = $("#vip_gold_amount").val();
        var amount = (new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amt));
        var plan = "VIP GOLD";
        var payID = "REF-VG" + paymentID(10009, 99999);
        $('#transaction_id').text(payID);
        $("#plan").text(plan);
        $("#amount").text(amount);
        $("#copy_btn").text("Copy");
        $("#copy_btn").removeClass("bg-success");
        $("#copy_btn").addClass("btn-outline-primary");
        $("#copy_btn").removeClass("bg-success text-white");

    });
    $('#payment').on('hide.bs.modal', function(e) {
        $('input').val("");
    });
    $("#copy_btn").click(function() {
        $("#wallet").select();
        if (document.execCommand("Copy")) {
            $("#copy_btn").text("Copied!");
            $("#copy_btn").addClass("bg-success text-white");
            $("#copy_btn").removeClass("btn-outline-primary");
            setTimeout(function() {
                    alert("Wallet Address Copied");
                },
                1000);
        }


    });
});