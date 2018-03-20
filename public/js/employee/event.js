/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var User = function () {
    this.__construct = function () {
        this.login();
        this.update_password();
        this.update_profile();
        this.loader();
    };

    this.login = function () {
        $("#login-form").submit(function (evt) {
            evt.preventDefault();
            var url = $(this).attr("action");
            var postdata = $(this).serialize();

            $.post(url, postdata, function (out) {
                if (out.result === 0) {
                    $(".form-group > .error").remove();
                    for (var i in out.errors) {
                        $("#" + i).parents(".form-group").append('<span class="error">' + out.errors[i] + '</span>');
                    }
                }
                if (out.result === -1) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
                if (out.result === 1) {
                    window.location.href = out.url;
                }
            });
        });
    };

    this.update_profile = function () {
        $("#profile-form").submit(function (evt) {
            evt.preventDefault();
            var url = $(this).attr('action');
            var postdata = $(this).serialize();
            $.post(url, postdata, function (out) {
                if (out.result === 0) {
                    $(".form-group > .error").remove();
                    for (var i in out.errors) {
                        $("#" + i).parents(".form-group").append('<span class="error">' + out.errors[i] + '</span>');
                    }
                }
                if (out.result === -1) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-success alert-danger').addClass('alert-warning').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
                if (out.result === 1) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                    window.location.reload();
                }
            });
        });
    };
    this.update_password = function () {
        $("#change-password-form").submit(function (evt) {
            evt.preventDefault();
            var url = $(this).attr('action');
            var postdata = $(this).serialize();
            var form = $(this)[0];
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
                    alert(out.msg);
                }
                if (out.result === 1) {
                    form.reset();
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger alert-warning').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
            });
        });
    };
    this.loader = function () {
        $(document).ready(function () {
            $("#loading_div").hide();
        });
    };
    this.__construct();
};

user = new User();
