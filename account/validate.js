$("document").ready(function() {
    $("#signIn_status").hide();
    $("#signIn_disabled").hide();
    var reply = "";

    function isTaken(field, data, action) {
        $.ajax({
            url: action,
            method: "POST",
            dataType: "JSON",
            data: { field: data, check: field },
            success: function(data) {
                reply = data;
            }
        });
        return reply;
    }

    $("form.sign-in-form").submit(function(event) {
        event.preventDefault();
        username = $("#username").val();
        password = $("#password").val();
        action = $(this).attr("action");
        data = $(this).serialize();
        $.ajax({
            url: action,
            method: "POST",
            dataType: "JSON",
            data: data,
            beforeSend: function() {
                spinner = '<span class="spinner-border" style="width: 1.2rem; height: 1.2rem;" id="spinner" role="status" aria-hidden="true"></span>  Signing In...';
                $("input").attr("disabled", true);
                $('#singIn_status').slideUp();
                $("#btn-sign-in").attr("disabled", true).html(spinner);
            },
            success: function(data) {

                $("input").attr("disabled", false),
                    $("#btn-sign-in").attr("disabled", false).text("Sign In");
                if (data.uname_err !== undefined) {
                    $("#username_err").text(data.uname_err);
                    $("#username").addClass("border border-danger");
                } else {
                    $("#username_err").text("");
                    $("#username").removeClass("border border-danger");
                }
                if (data.psw_err !== undefined) {
                    $("#password_err").text(data.psw_err);
                    $("#password").addClass("border border-danger");
                } else {
                    $("#password_err").text("");
                    $("#password").removeClass("border border-danger");
                }

                if (data.disabled !== undefined) {
                    $("#signIn_disabled").addClass('alert-danger').html(data.disabled).fadeIn("slow");
                } else {
                    $("#signIn_disabled").removeClass('alert-danger').text('').hide();
                }

                if (data.redirect !== undefined) {
                    $("input, button").attr("disabled", true);
                    $("#signIn_status").addClass('alert-success').text('You are  now signed in.').fadeIn("slow");
                    setTimeout(function() {
                        window.location.assign(data.redirect);
                    }, 2000);
                } else {
                    $("#signIn_status").removeClass('alert-success').text('').hide();
                }

            },
            error: function() {
                $("input").attr("disabled", false);
                $("#btn-sign-in").attr("disabled", false).text("Sign In");
                alert('Something went wrong');
            }

        });
    });

    $("form.sign-up-form").submit(function(event) {
        event.preventDefault();
        action = $(this).attr("action");
        data = $(this).serialize();
        $.ajax({
            url: action,
            method: "POST",
            dataType: "JSON",
            data: data,
            beforeSend: function() {
                spinner = '<span class="spinner-border" style="width: 1.2rem; height: 1.2rem;" id="spinner" role="status" aria-hidden="true"></span>  Signing you up...';
                $("input").attr("disabled", true),
                    $("#btn-sign-up").attr("disabled", true).html(spinner);
            },
            success: function(data) {
                $("input").attr("disabled", false),
                    $("#btn-sign-up").attr("disabled", false).text("Sign Up");
                if (data.fname_err != undefined) {
                    $("#firstname_err").text(data.fname_err);
                    $(".sign-up-form #firstname").removeClass("border border-success").addClass('border border-danger');
                } else {
                    $("#firstname_err").text("");
                    $(".sign-up-form #firstname").addClass("border border-success").removeClass('border border-danger');
                }

                if (data.lname_err != undefined) {
                    $("#lastname_err").text(data.lname_err);
                    $(".sign-up-form #lastname").removeClass("border border-success").addClass('border border-danger');
                } else {
                    $("#lastname_err").text("");
                    $(".sign-up-form #lastname").addClass("border border-success").removeClass('border border-danger');
                }

                if (data.mname_err != undefined) {
                    $("#middlename_err").text(data.mname_err);
                    $(".sign-up-form #middlename").removeClass("border border-success").addClass('border border-danger');
                } else {
                    $("#middlename_err").text("");
                    $(".sign-up-form #middlename").addClass("border border-success").removeClass('border border-danger');
                }
                if (data.uname_err != undefined) {
                    $("#username_err").text(data.uname_err);
                    $(".sign-up-form #username").removeClass("border border-success").addClass('border border-danger');
                } else {
                    $("#username_err").text("");
                    $(".sign-up-form #username").addClass("border border-success").removeClass('border border-danger');
                }
                if (data.email_err != undefined) {
                    $("#email_err").text(data.email_err);
                    $(".sign-up-form #email").removeClass("border border-success").addClass('border border-danger');
                } else {
                    $("#email_err").text("");
                    $(".sign-up-form #email").addClass("border border-success").removeClass('border border-danger');
                }
                if (data.phone_err != undefined) {
                    $("#phone_err").text(data.phone_err);
                    $(".sign-up-form #phone").removeClass("border border-success").addClass('border border-danger');
                } else {
                    $("#phone_err").text("");
                    $(".sign-up-form #phone").addClass("border border-success").removeClass('border border-danger');
                }
                if (data.country_err != undefined) {
                    $("#wallet_err").text(data.country_err);
                    $(".sign-up-form #wallet").removeClass("border border-success").addClass('border border-danger');
                } else {
                    $("#wallet_err").text("");
                    $(".sign-up-form #wallet").addClass("border border-success").removeClass('border border-danger');
                }
                if (data.psw1_err != undefined) {
                    $("#password1_err").text(data.psw1_err);
                    $(".sign-up-form #password1").removeClass("border border-success").addClass('border border-danger');
                } else {
                    $("#password1_err").text("");
                    $(".sign-up-form #password1").addClass("border border-success").removeClass('border border-danger');
                }
                if (data.psw2_err != undefined) {
                    $("#password2_err").text(data.psw2_err);
                    $(".sign-up-form #password2").removeClass("border border-success").addClass('border border-danger');
                } else {
                    $("#password2_err").text("");
                    $(".sign-up-form #password2").addClass("border border-success").removeClass('border border-danger');
                }
                if (data.success != undefined) {
                    $('#success').html('<div class="card text-center border-0 p-3 m-4" ><h3 class="mb-1 text-success"> Congratulations!!! </h3> Your sign up process was successful. <br/>Proceed to sign in page.<a href ="signin.php" class="btn btn-outline-primary px-3 my-2"> Sign In </a></div>');
                }

                if (data.fail != undefined) {
                    $("div#status").html('<div class="alert alert-danger alert-dismissible fade show"  role="alert" ><strong> Error: </strong> Something went wrong! <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times; </span></button></div>').removeClass("d-none");

                } else {
                    $("div#status").text("").addClass("d-none");
                }



            },
            error: function() {
                $("input").attr("disabled", false),
                    $("#btn-sign-up").attr("disabled", false).text("Sign Up");
                alert("Something went wrong!");
            }

        });
    });

    $("form.sign-up-form #email").on('change keyup', function() {

        data = $(this).val();
        field = "email";
        action = $("form.sign-up-form").attr("action");
        var reply = isTaken(field, data, action);


        if (reply === "taken") {
            $("#email_err").text("Email already exist.");
            $("#email").removeClass("border border-success").addClass("border border-danger");

        } else if (reply === "available") {
            $("#email_err").text("");
            $("#email").removeClass("border border-danger").addClass("border border-success");

        } else if (reply === "invalid") {
            $("#email_err").text("Invalid Email.");
            $("#email").removeClass("border border-success").addClass("border border-danger");

        }

    });
    $("form.sign-up-form #username").on('change keyup', function() {
        data = $(this).val();
        field = "uname";
        action = $("form.sign-up-form").attr("action");
        var reply = isTaken(field, data, action);

        if (reply === "taken") {
            $("#username_err").text("Username is taken!");
            $("#username").removeClass("border border-success").addClass("border border-danger");


        } else if (reply === "available") {
            $("#username_err").text("");
            $("#username").removeClass("border border-danger").addClass("border border-success");
        } else if (reply === "invalid") {
            $("#username_err").text("Invalid username!");
            $("#username").removeClass("border border-success").addClass("border border-danger");



        }


    });
    $("form.sign-up-form #firstname").on('change keyup', function() {
        data = $(this).val();
        field = "fname";
        action = $("form.sign-up-form").attr("action");
        var reply = isTaken(field, data, action);

        if (reply == "false") {
            $("#firstname_err").text("Invalid name!");
            $("#firstname").removeClass("border border-success");
            $("#firstname").addClass("border border-danger");

        } else if (reply == "true") {
            $("#firstname_err").text("");
            $("#firstname").removeClass("border border-danger");
            $("#firstname").addClass("border border-success");
        }


    });
    $("form.sign-up-form #lastname").on('change keyup', function() {
        data = $(this).val();
        field = "lname";
        action = $("form.sign-up-form").attr("action");
        var reply = isTaken(field, data, action);

        if (reply == "false") {
            $("#lastname_err").text("Invalid name!");
            $("#lastname").removeClass("border border-success");
            $("#lastname").addClass("border border-danger");

        } else if (reply == "true") {
            $("#lastname_err").text("");
            $("#lastname").removeClass("border border-danger");
            $("#lastname").addClass("border border-success");
        }


    });
    $("form.sign-up-form #middlename").on('change keyup', function() {
        data = $(this).val();
        field = "mname";
        action = $("form.sign-up-form").attr("action");
        var reply = isTaken(field, data, action);

        if (reply == "false") {
            $("#middlename_err").text("Invalid name!");
            $("#middlename").removeClass("border border-success");
            $("#middlename").addClass("border border-danger");

        } else if (reply == "true") {
            $("#middlename_err").text("");
            $("#middlename").removeClass("border border-danger");
            $("#middlename").addClass("border border-success");
        }


    });

    $("#countryId").on('click change', function() {
        data = $(this).val();


        if (data !== "") {
            $("#country_err").text("");
            $("#countryId").removeClass("border border-danger").addClass("border border-success");


        } else {
            $("#country_err").text("Country required");
            $("#countryId").addClass("border border-danger").removeClass("border border-success");

        }

    });

    function phoneCheck(str) {
        var isphone = /^(\+{0,})(\d{0,})([(]{1}\d{1,3}[)]{0,}){0,}(\s?\d+|\+\d{2,3}\s{1}\d+|\d+){1}[\s|-]?\d+([\s|-]?\d+){1,2}(\s){0,}$/gm.test(str);
        return isphone;
    }
    //telephoneCheck("+234 8060297332");

    $(".sign-up-form #phone").on('change keyup', function() {
        phone = $(this).val();
        if (phoneCheck(phone)) {
            $("#phone_err").text("");
            $("#phone").removeClass("border border-danger");
            $("#phone").addClass("border border-success");
        } else {
            $("#phone_err").text("Invalid phone number!");
            $("#phone").removeClass("border border-success");
            $("#phone").addClass("border border-danger");
        }
    });
    $("#password2").keyup(function() {

        var pass1 = $("#password1").val();

        var pass2 = $("#password2").val();

        if (pass1 !== pass2 && error !== "") {
            $("#btn-sign-up").attr("disabled", true);
            $("#password2_err").text("Password does not match!");
            error = "set";
            $("#password1").addClass("border border-danger");
            $("#password1").removeClass("border border-success")

        } else {
            $("#btn-sign-up").attr("disabled", false);
            $("#password2_err").text("");

            $("#password1_err").text("");
            $("#password1").addClass("border border-success");
            $("#password2").addClass("border border-success");
            $("#password1").removeClass("border border-danger");
            $("#password2").removeClass("border border-danger");

        }

    });

    $("#password1").keyup(function() {
        error = "set";
        var pass1 = $("#password1").val();

        if (pass1.length < 8) {
            $("#btn-sign-up").attr("disabled", true);
            $("#password1_err").text("Password must be more than 8 characters");
            $("#password1").addClass("border border-danger");
            $("#password1").removeClass("border border-success")

        } else if (pass1.length = 0) {

            $("#password1_err").text("");
            $("#password1").removeClass("border border-danger");
            $("#password1").addClass("border border-success")

        } else {

            $("#password1_err").text("");

        }
        if ($("#password2").val() > 7) {

            var pass1 = $("#password1").val();

            var pass2 = $("#password2").val();

            if (pass1 !== pass2) {
                error = "set";
                $("#btn-sign-up").attr("disabled", true);
                $("#password1_err").text("Password does not match!");
                $("#password1").addClass("border border-danger");
                $("#password1").removeClass("border border-success")
            } else {
                $("#btn-sign-up").attr("disabled", false);
                $("#password1_err").text("");
                $("#password2_err").text("");
                $("#password1").addClass("border border-success");
                $("#password2").addClass("border border-success");
                $("#password1").removeClass("border border-danger");
                $("#password2").removeClass("border border-danger");
            }

        }
    });
});