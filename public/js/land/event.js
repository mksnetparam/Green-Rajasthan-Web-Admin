var Land = function () {
    this.__construct = function () {
        this.get_states_by_country();
        this.get_city_by_state();
        this.add_land();
        this.load_items();
        this.delete_land();
        this.land_verify_assignment();
        this.filter();
        this.add_tree_suggestion();
        this.delete_tree_suggestion();
        this.selectivity_user();
        this.change_picture();
        this.reapply_land();
        this.loader();
    };
    
    this.load_items = function () {
        $(document).ready(function () {
            var url = $("#list-wrapper").data('url');
            $.post(url, '', function (out) {
                $("#list-wrapper").html(out.itemlist);
                $('table#sortable').DataTable();
            });
        });
    };
    this.loader = function () {
        $(document).ready(function () {
            $("#loading_div").hide();
            $(document).ajaxStart(function () {
                $("#loading_div").show();
            }).ajaxStop(function () {
                $("#loading_div").hide();
            });
        });
    };
    this.selectivity_user = function () {
        $("#user_id").selectize();
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
    this.add_land = function () {
        $("#add-land-form,#verify-land-form,#verify-soil-form").submit(function (evt) {
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
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
//                    form.reset();
                    alert(out.msg);
                }
            });
        });
    };
    this.delete_land = function () {
        $(document).on('click', ".delete-land", function (evt) {
            evt.preventDefault();
            if (confirm("Are you sure you want to delete? ")) {
                var url = $(this).attr('href');
                $.post(url, '', function (out) {
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
                        $("#error_msg").removeClass('alert-danger alert-success').addClass('alert-success').show();
                        $("#error_msg").html(message + out.msg);
                        alert(out.msg);
                        obj.load_items();
                    }
                });
            }
        });
    };

    this.land_verify_assignment = function () {
        $(document).on('change', '.land-verified-assigned-to', function (evt) {
            evt.preventDefault();
            var url = $(this).data('url');
            var user_id = $(this).val();
            $.post(url, {user_id: user_id}, function (out) {
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
                    $.post(out.url, '', function (output) {
                        $("#list-wrapper").html(output.itemlist);
                    });
                }
            });
        });
    };
    this.soil_verify = function () {
        $(document).on('change', '.land-verified-assigned-to', function (evt) {
            evt.preventDefault();
            var url = $(this).data('url');
            var user_id = $(this).val();
            $.post(url, {user_id: user_id}, function (out) {
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
                    $("#list-wrapper").data('url', out.url);
                    alert(out.msg);
                    obj.load_items();
                }
            });
        });
    };

    this.filter = function () {
        $(document).on('click', '.filter-land', function (evt) {
            evt.preventDefault();
            var url = $(this).attr('href');
            $('.filter-land').removeClass('btn-primary');
            $(this).addClass('btn-primary');
            $.post(url, '', function (out) {
                $("#list-wrapper").html(out.itemlist);
                $("table#sortable").DataTable();
            });
        });
    };
    this.add_tree_suggestion = function () {
        $("#add_tree_btn").click(function () {
            var tree = $("#select_tree_name").val();
            var season = $("#season").val();
            var land_id = $(this).data('id');
            var url = $(this).data('url');

            $.post(url, {tree: tree, season: season, land_id: land_id}, function (out) {
                console.log(out);
                if (out.result === -1) {
                    alert('Already Added');
                    return;
                }
                if (out.result === 0) {
                    alert('Can\'t be Added');
                    return;
                }
                if (out.result === 1) {
                    $("#suggested-tree").html(out.itemlist);
                    return;
                }
            });
        });
    };
    this.delete_tree_suggestion = function () {
        $(document).on('click', ".delete", function (evt) {
            evt.preventDefault();
            if (confirm("Are you sure you want to delete? ")) {
                var url = $(this).attr('href');
                $.post(url, '', function (out) {
                    if (out.result === 1) {
                        $("#suggested-tree").html(out.itemlist);
                    }
                });
            }
        });
    };
    this.change_picture = function () {
        $(document).on('click', "#change-land-picture", function (evt) {
            evt.preventDefault();
            $(this).parents().find('.thumbnail').hide();
            $(this).parents().find('.thumbnail').next('.dropzone').removeClass('hidden');
        });
    };

    this.reapply_land = function () {
        $(document).on('click', ".reapply-land", function (evt) {
            evt.preventDefault();
            var url = $(this).attr('href');
            $.post(url, '', function (out) {
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
                    window.location.href = out.url;
                }
            });
        });
    };
    this.loader = function () {
        $('#loading_div').hide().ajaxStart(function () {
            $(this).show();
        }).ajaxStop(function () {
            $(this).hide();
        });
    };
    this.__construct();
};
var obj = new Land();