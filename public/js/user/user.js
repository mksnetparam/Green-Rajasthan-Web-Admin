var User = function () {
    this.__construct = function () {
        this.load_items();
        this.add_user();
        this.change_status();
        this.show_page();
        this.view_page();
        this.selectAll();
        this.filter();
        this.activate_action_btn();
        this.change_status_for_all();
        this.get_states_by_country();
        this.get_city_by_state();
        this.change_picture();
        this.loader();
        this.delete();
        this.datepicker();
        this.get_payments();
        this.load_payments();
        this.update_payment_status();
        this.add_role();
        this.action_modal();
        this.selectize_dropdown();
        this.allocate_area();
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
    this.datepicker = function () {
        $("#datefrom,#dateto").datepicker({dateFormat: 'dd/mm/yy'});
        $(document).on('focus', "#payment_date", function () {
            $(this).datepicker({dateFormat: 'dd/mm/yy'});
        });
    };

    /*User CRUD Operation*/
    this.get_states_by_country = function () {
        $(document).on('change','#country_id',function(){
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
        $(document).on('change','#state_id',function(){
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
    this.add_user = function () {
        $("#add-user-form").submit(function (evt) {
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
    this.view_page = function () {
        $(document).on('click', '.pg', function (evt) {
            evt.preventDefault();
            var form = $('form').eq(0);
            var postdata = form.serialize();
            var isActive = $(this).parent('li').attr('class');
            if (isActive === "disabled") {
                return false;
            }
            var url = $(this).attr('href');
            $.post(url, postdata, function (out) {
                if (out.result === 1) {
                    $("#list-wrapper").html(out.itemlist);
                }
            });
        });
    };
    this.show_page = function () {
        $(document).on('change', '#page', function (evt) {
            evt.preventDefault();
            var form = $('form').eq(0);
            var postdata = form.serialize();
            var id = $(this).val();
            var url = $(this).data('url');
            url += "/" + id;
            $.post(url, postdata, function (out) {
                if (out.result === 1) {
                    $("#list-wrapper").html(out.itemlist);
                }
            });
        });
    };

    this.filter = function () {
        $(document).on('submit', "#filter-user-form", function (evt) {
            evt.preventDefault();
            var postData = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url, postData, function (out) {
                if (out.result === 1) {
                    $("#list-wrapper").html(out.itemlist);
                }
            });
        });
    };

    this.selectAll = function () {
        $(document).on('click', '#select-all', function (evt) {
            var status = $(this).prop('checked');
            $(".select").each(function () {
                this.checked = status;
                if (status) {
                    $("#action").removeAttr('disabled');
                } else {
                    $("#action").attr('disabled', 'disabled');
                }
            });
        });
    };

    this.activate_action_btn = function () {
        $(document).on('change', '.select', function (evt) {
            var checkCount = $(".select:checked").length;
            if (checkCount > 0) {
                $("#action").removeAttr('disabled');
            } else {
                $("#action").attr('disabled', 'disabled');
            }
        });
    };
    this.change_status_for_all = function () {
        $(document).on('change', '#action', function (evt) {
            evt.preventDefault();
            var url = $(this).data('url');
            var action = $(this).val();
            url += "/" + action;
            var postData = $("#filter-user-form").serialize();
            $.post(url, postData, function (out) {
                if (out.result === 1) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-warning alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                    $("#list-wrapper").html(out.itemlist);
                }
                else if (out.result === -1) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger alert-success').addClass('alert-warning').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
            });
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
                    user.load_items();
                }
            });
        });
    };

    this.delete = function () {
        $(document).on('click', '.delete-user', function (evt) {
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
                        user.load_items();
                    }
                });
            }
        });
    };
    this.change_picture = function () {
        $(document).on('click', "#change-user-picture", function (evt) {
            evt.preventDefault();
            $(this).parents().find('.thumbnail').hide();
            $(this).parents().find('.thumbnail').next('.dropzone').removeClass('hidden');
        });
    };
    this.loader = function () {
        $('#loading_div').hide().ajaxStart(function () {
            $(this).show();
        }).ajaxStop(function () {
            $(this).hide();
        });
    };
    this.get_payments = function () {
        $("#view-donation-payments-form").submit(function (evt) {
            evt.preventDefault();
            var url = $("#list-wrapper").data('url');
            var postData = $(this).serialize();
            $.post(url, postData, function (out) {
                if (out.result === 1) {
                    $("#list-wrapper").html(out.page);
                }
            });
        });
    };
    this.load_payments = function () {
        $(document).ready(function () {
            var url = $("#list-wrapper").data('url');
            var datefrom = $('#datefrom').val();
            var dateto = $('#dateto').val();
            $.post(url, {datefrom: datefrom, dateto: dateto}, function (out) {
                if (out.result === 1) {
                    $("#list-wrapper").html(out.page);
                }
            });
        });
    };

    this.update_payment_status = function () {
        $("#offline-payment-form").submit(function (evt) {
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
                    $("#user-add-btn").addClass('disabled');
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
                    window.location.href = out.url;
                    form.reset();
                }
            });
        });
    };

    this.add_role = function () {
        $(document).on('change', '#user_role', function () {
            var url = $(this).data('url');
            var user_role = $(this).val();
            $.post(url, {user_role: user_role}, function (out) {
                if (out.result === 1) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
            });
        });
    };

    this.action_modal = function () {
        $(document).on('click', '.allocate-area,.user-payment-btn', function (evt) {
            evt.preventDefault();
            var url = $(this).attr('href');
            $.post(url, '', function (out) {
                if(out.result === 1) {
                    $("#extra").html(out.modal);
                    $("#my-modal").modal('show');
                }
            });
        });
    };

    this.selectize_dropdown = function () {
        $(document).on('focus', "#city_id", function () {
            $(this).selectize({maxItems:3});
        });
    };
    this.allocate_area = function () {
        $(document).on('submit','#allocate-area-form,#payment-form',function(evt) {
            evt.preventDefault();
            var url = $(this).attr('action');
            var postData = $(this).serialize();
            $.post(url,postData,function (out) {
                if(out.result === 0) {
                    $(".form-group > .error").remove();
                    for (var i in out.errors) {
                        $("#" + i).parents(".form-group").append('<span class="error">' + out.errors[i] + '</span>');
                    }
                }
                if(out.result === -2) {
                    alert(out.msg);
                }
                if(out.result === -1) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-success alert-danger').addClass('alert-warning').show();
                    $("#error_msg").html(message + out.msg);
                }
                if(out.result === 1) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    $("#my-modal").modal('hide');
                    user.load_items();
                }
            });
        });
    };
    this.__construct();
};

var user = new User();

