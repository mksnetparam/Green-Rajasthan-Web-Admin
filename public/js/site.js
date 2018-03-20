var Site = function () {
    this.__construct = function () {
        this.login();
        this.intilialize();
        this.send_otp();
        this.resend_otp();
        this.update_password();
        this.loader();
    };
    this.intilialize = function () {
        $(document).ready(function () {
            var url = $("#page-wrapper").data('url');
            if (url !== null) {
                $.post(url, '', function (out) {
                    $("#page-wrapper").html(out.output);
                });
            }
        });
    };
    this.login = function () {
        $(document).ready(function () {
            $("#emplogin").submit(function (evt) {
                evt.preventDefault();
                var url = $(this).attr('action');
                var data = $(this).serialize();
                $("#login-btn").addClass('disabled');
                $.post(url, data, function (out) {
                    if (out.result === 0) {
                        $(".form-group > .error").remove();
                        $("#login-btn").removeClass('disabled');
                        for (var i in out.errors) {
                            $("#" + i).parents(".form-group").append('<span class="error">' + out.errors[i] + '</span>');
                        }
                    }
                    if (out.result === -1) {
                        var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        $("#error_msg").show();
                        $("#error_msg").html(message + out.msg);
                        $("#login-btn").removeClass('disabled');
                    }
                    if (out.result === 1) {
                        window.location.href = out.url;
                    }
                });
            });
        });
    };
    this.send_otp = function () {
        $(document).on('submit', "#send-otp-form,#match-otp-form", function (evt) {
            evt.preventDefault();
            var url = $(this).attr('action');
            var data = $(this).serialize();
            $("#send-otp-btn").addClass('disabled');
            $.post(url, data, function (out) {
                if (out.result === 0) {
                    $(".form-group > .error").remove();
                    $("#send-otp-btn").removeClass('disabled');
                    for (var i in out.errors) {
                        $("#" + i).parents(".form-group").append('<span class="error">' + out.errors[i] + '</span>');
                    }
                    $("#send-otp-btn").removeClass('disabled');
                }
                if (out.result === -1) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-success alert-warning').addClass('alert-danger').show();
                    $("#error_msg").html(message + out.msg);
                    $("#send-otp-btn").removeClass('disabled');
                }
                if (out.result === 1) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger alert-warning').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    $("#send-otp-btn").removeClass('disabled');

                    var url = out.url;
                    $.post(url, '', function (output) {
                        $("#page-wrapper").html(output.output);
                    });
                }
            });
        });
    };

    this.resend_otp = function () {
        $(document).on('click', '#resend-otp-link', function (evt) {
            evt.preventDefault();
            var url = $(this).attr('href');

            $.post(url, '', function (out) {
                if (out.result === 1) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger alert-warning').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                }
            });
        });
    };

    this.update_password = function () {
        $(document).on('submit', "#change-password-form", function (evt) {
            evt.preventDefault();
            var url = $(this).attr('action');
            var postdata = $(this).serialize();
            var form = $(this)[0];
            $("#change-password-btn").addClass('disabled');
            $.post(url, postdata, function (out) {
                if (out.result === 0) {
                    $(".form-group > .error").remove();
                    for (var i in out.errors) {
                        $("#" + i).parents(".form-group").append('<span class="error">' + out.errors[i] + '</span>');
                    }
                }
                if (out.result === -1) {
                    form.reset();
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-success alert-danger').addClass('alert-warning').show();
                    $("#error_msg").html(message + out.msg);
                }
                if (out.result === 1) {
                    form.reset();
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger alert-warning').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                }
                $("#change-password-btn").removeClass('disabled');
            });
        });
    };
    this.loader = function () {
        $(document).ready(function () {
            $("#loading_div").hide();
//            $(document).ajaxStart(function () {
//                $("#loading_div").show();
//            }).ajaxStop(function () {
//
//            });
        });
    };
    this.__construct();
};
var obj = new Site();