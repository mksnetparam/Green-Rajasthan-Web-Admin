/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var Organization = function () {
    this.__construct = function () {
        this.get_states_by_country();
        this.get_city_by_state();
        this.add_organization();
        this.generate_enroll_id();
        this.load_items();
        this.delete();
        this.change_status();
        this.change_picture();
        this.loader();
    };
    this.loader = function () {
        $(document).ready(function () {
            $(document).ajaxStart(function () {
                $("#loading_div").show();
            }).ajaxStop(function () {
                $("#loading_div").hide();
            });
        });
    };
    this.load_items = function () {
        $(document).ready(function () {
            var url = $("#list-wrapper").data('url');
            $.post(url, '', function (out) {
                $("#list-wrapper").html(out.itemlist);
            });
        });
    };
    this.get_states_by_country = function () {
        $("#country_id").change(function () {
            var url = $(this).data('url');
            var country_id = $(this).val();
            url += '/' + country_id;
            $.get(url, '', function (out) {
                var html = '';
                for (var i in out.states) {
                    html += '<option value="' + i + '">' + out.states[i] + '</option>';
                }
                $("#state_id").html(html);
                $("#state_id").removeAttr('disabled');
            });
        });
    };
    this.get_city_by_state = function () {
        $("#state_id").change(function () {
            var url = $(this).data('url');
            var country_id = $(this).val();
            url += '/' + country_id;
            $.get(url, '', function (out) {
                var html = '';
                for (var i in out.cities) {
                    html += '<option value="' + i + '">' + out.cities[i] + '</option>';
                }
                $("#city_id").html(html);
                $("#city_id").removeAttr('disabled');
            });
        });
    };
    this.add_organization = function () {
        $("#add-organization-form").submit(function (evt) {
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
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-success alert-danger').addClass('alert-warning').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
                if (out.result === -2) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-success alert-danger').addClass('alert-warning').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
                if (out.result === 1) {
//                    if ($("#city_id").val() !== 'undefined') {
//                        $("#city_sub_btn").val('Add City');
//                        $("#city-form").attr('action', out.url);
//                        $("#city-form").parent('.panel-body').siblings('.panel-heading').text('Add City');
//                    }
//                    form.reset();
//                    city.load_items();
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
            });
        });
    };

    this.generate_enroll_id = function () {
        $("#organization_name").blur(function (evt) {
            evt.preventDefault();
            var name = $(this).val().substr(0, 2).toUpperCase();
            var rand = Date.now() % 10000;
            $("#organization_entroll_id").val(name + "-" + rand);
        });
    };

    this.delete = function () {
        $(document).on('click', '.delete-organization', function (evt) {
            evt.preventDefault();
            if (confirm("Are you sure you want to delete? ")) {
                var url = $(this).attr('href');
                $.post(url, '', function (out) {
                    if (out.result === 0) {
                        $(".form-group > .error").remove();
                        var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        $("#error_msg").removeClass('alert-success alert-danger').addClass('alert-warning').show();
                        $("#error_msg").html(message + out.msg);
                        alert(out.msg);
                    }
                    if (out.result === 1) {
                        $(".form-group > .error").remove();
                        var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        $("#error_msg").removeClass('alert-danger alert-success').addClass('alert-success').show();
                        $("#error_msg").html(message + out.msg);
                        alert(out.msg);
                        obj.load_items();
                    }
                });
            }
        });
    };

    this.change_status = function () {
        $(document).on('click', '.change-status', function (evt) {
            evt.preventDefault();
            var url = $(this).attr('href');
            var status = $(this).data('status');
            $.post(url, {status: status}, function (out) {
                if (out.result === 0) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-success alert-danger').addClass('alert-warning').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
                if (out.result === 1) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger alert-success').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                    $("#list-wrapper").html(out.itemlist);
                    obj.load_items();
                }
            });
        });
    };
    this.change_picture = function () {
        $(document).on('click', "#change-organization-picture", function (evt) {
            evt.preventDefault();
            $(this).parents().find('.thumbnail').hide();
            $(this).parents().find('.thumbnail').next('.dropzone').removeClass('hidden');
        });
    };
    this.__construct();
};
var obj = new Organization();