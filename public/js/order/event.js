var Order = function () {
    this.__construct = function () {
        this.add();
        this.load_items();
        this.delete_plant();
        this.change_status();
        this.update();
        this.change_picture();
        this.get_organization_land();
        this.do_allocate();
        this.loader();
        this.change_order_status();
        this.delete_order_allocation();
        this.filter_order();
        this.filter_order_by_payment_status();
        this.datepicker();
        this.get_payments();
        this.load_payments();
        this.fcmNotification();
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
    this.fcmNotification = function () {
        $('#fcmModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('name');
            var fcmId = button.data('fcmid');
            var modal = $(this);
            modal.find('.modal-title').text('Send Notification to ' + recipient);
            $('#fcm_id').val(fcmId);
        });
        $(document).on('click', "#sendFCMMessage", function (evt) {
            evt.preventDefault();
            var url = $('#fcm_noti_form').attr('action');
            var postdata = $('#fcm_noti_form').serialize();
            $.post(url, postdata, function (out) {
                if (out.result === 0) {
                    $(".form-group > .error").remove();
                    for (var i in out.errors) {
                        $("#" + i).parents(".form-group").append('<span class="error">' + out.errors[i] + '</span>');
                    }
                }
                if (out.result === 1) {
                    $(".form-group > .error").remove();
                    alert(out.msg);
                    $('#title').val('');
                    $('#message').val('');
                    $('#fcmModal').modal('hide');
                }
            });
        });
    };
    this.datepicker = function () {
        $(".datefrom,.dateto").datepicker({dateFormat: 'dd/mm/yy'});
    };
    this.add = function () {
        $("#add-plant-form,#offline-payment-form").submit(function (evt) {
            evt.preventDefault();
            var url = $(this).attr('action');
            var postdata = $(this).serialize();
            var form = $(this)[0];
            $("#user-add-btn").addClass('disabled');
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
                    $("#user-add-btn").addClass('disabled');
                    alert(out.msg);
                }
                if (out.result === 1) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    $("#user-add-btn").addClass('disabled');
                    alert(out.msg);
                    // alert(out.url);
                    if (out.url != undefined) {
                        window.location.href = out.url;
                    }
                    form.reset();
                }
            });
        });
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
    this.delete_plant = function () {
        $(document).on('click', ".delete-plant", function (evt) {
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
    this.update = function () {
        $("#update-plant-form").submit(function (evt) {
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
                    form.reset();
                }
            });
        });
    };

    this.change_picture = function () {
        $(document).on('click', "#change-plant-picture", function (evt) {
            evt.preventDefault();
            $(this).parents().find('.thumbnail').hide();
            $(this).parents().find('.thumbnail').next('.dropzone').removeClass('hidden');
        });
    };

    this.get_organization_land = function () {
        $(document).on('change', '#organization_id', function (evt) {
            var url = $(this).data('url');
            var id = $(this).val();
            url += "/" + id;
            var user_id = $(this).data('userid');
            var order_id = $(this).data('orderid');
            $.post(url, {user_id: user_id, order_id: order_id}, function (out) {
                $("#lands-list").html(out.lands);
            });
        });
    };
    this.do_allocate = function () {
        $("#plant-allocation-form").submit(function (evt) {
            evt.preventDefault();
            var url = $(this).attr('action');
            var postdata = $(this).serialize();
            var form = $(this)[0];
            $total = 0;
            $(".plant").each(function () {
                if ($(this).val() !== "") {
                    $total += parseInt($(this).val());
                }
            });
            $remaining_land_capacity = parseInt($("#remaining_capacity").text());
            $remaining_order_capacity = parseInt($("#remaining_order_capacity").text());

            if ($total > $remaining_land_capacity) {
                alert("Land Capacity exceeds");
                return;
            } else if ($total > $remaining_order_capacity) {
                alert("Order Capacity exceeds");
                return;
            }

            $.post(url, postdata, function (out) {
                if (out.result === 0) {
                    $(".form-group > .error").remove();
                    for (var i in out.errors) {
                        $(".form-group > .error").remove();
                        var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        $("#error_msg").removeClass('alert-success alert-danger').addClass('alert-warning').show();
                        $("#error_msg").html(message + out.errors[i]);
                        alert(out.errors[i]);
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
                    form.reset();
                    window.location.reload();
                }
            });
        });
    };
    this.change_order_status = function () {
        $(document).on('change', '.order-status', function (evt) {
            evt.preventDefault();
            var url = $(this).data('url');
            var order_status = $(this).val();
            var remaining_plants = $(this).data('plants');
//            alert(remaining_plants);
            $.post(url, {order_status: order_status, remaining_plants: remaining_plants}, function (out) {

                if (out.result === 0) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-success alert-danger').addClass('alert-warning').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                    $.post(out.url, '', function (output) {
                        $("#list-wrapper").html(output.itemlist);
                        $('table#sortable').DataTable();
                    });
                }
                if (out.result === 1) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger alert-success').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                    $.post(out.url, '', function (output) {
                        $("#list-wrapper").html(output.itemlist);
                        $('table#sortable').DataTable();
                    });
                }
                if (out.result === -1) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-success alert-warning').addClass('alert-danger').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
            });
        });
    };
    this.delete_order_allocation = function () {
        $(document).on('click', '.delete-allocation', function (evt) {
            evt.preventDefault();
            var url = $(this).attr('href');
            alert('Do you really want to delete the allocation for this order?');
            $.post(url, '', function (out) {
                if (out.result === 1) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger alert-success').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                    $.post(out.url, '', function (output) {
                        $("#list-wrapper").html(output.itemlist);
                        $('table#sortable').DataTable();
                    });
                }
                if (out.result === -1) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-success alert-warning').addClass('alert-danger').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
            });
        });
    };
    this.filter_order = function () {
        $(document).on('change', "#filter_dropdown", function () {
            var url = $(this).data('url');
            var val = $(this).val();
            url += "/" + val;
            $.post(url, '', function (out) {
                if (out.result === 1) {
                    $("#list-wrapper").html(out.itemlist);
                    $('table#sortable').DataTable();
                }
            });
        });
    };
    this.filter_order_by_payment_status = function () {
        $(document).on('change', '#payment_status_filter', function () {
            var url = $(this).data('url');
            var val = $(this).val();
            $.post(url, {payment_status_filter: val}, function (out) {
                if (out.result === 1) {
                    $("#list-wrapper").html(out.itemlist);
                    $('table#sortable').DataTable();
                }
            });
        });
    };
    this.get_payments = function () {
        $("#view-payments-form").submit(function (evt) {
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
    this.__construct();
};
var obj = new Order();