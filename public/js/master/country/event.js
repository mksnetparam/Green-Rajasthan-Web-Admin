var Event = function () {
    this.__construct = function () {
        this.add_country();
        this.load_items();
        this.delete_country();
        this.edit_country();
        this.view_page();
        this.show_page();
        this.filter();
        this.selectAll();
        this.activate_action_btn();
        this.change_status();
        this.change_status_for_all();
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
            var id = $(this).val();
            var url = $(this).data('url');
            var form = $('form').eq(0);
            var postdata = form.serialize();
            url += "/" + id;
            $.post(url, postdata, function (out) {
                if (out.result === 1) {
                    $("#list-wrapper").html(out.itemlist);
                }
            });
        });
    };

    this.filter = function () {
        $(document).on('submit', "#filter-country-form", function (evt) {
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
    this.change_status = function () {
        $(document).on('click', '.status-btn', function (evt) {
            evt.preventDefault();
            var url = $(this).attr('href');
            $.post(url, '', function (out) {
                if (out.result === 1) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-warning alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                    $("#list-wrapper").html(out.itemlist);
                }
                else if (out.result === -1) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
            });
        });
    };
    this.change_status_for_all = function () {
        $(document).on('change', '#action', function (evt) {
            evt.preventDefault();
            var url = $(this).data('url');
            var action = $(this).val();
            url += "/" + action;
            var postData = $("#filter-country-form").serialize();
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

    this.add_country = function () {
        $("#country-form").submit(function (evt) {
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
                    if ($("#country_id").val() !== 'undefined') {
                        $("#country_sub_btn").val('Add Country');
                        $("#country-form").attr('action', out.url);
                        $("#country-form").parent('.panel-body').siblings('.panel-heading').text('Add Country');
                    }
                    form.reset();
                    country.load_items();
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
            });
        });
    };
    this.edit_country = function () {
        $(document).on("click", ".edit-country", function (evt) {
            evt.preventDefault();
            var url = $(this).attr('href');
            $("#add-country-panel-btn").removeClass('hidden');
            $.get(url, '', function (out) {
                if (out.result === 1) {
                    for (var i in out.item) {
                        if (i !== 'country_id') {
                            if (i === 'status') {
                                $("#status option[value='" + out.item[i] + "']").prop('selected', true);
                            } else {
                                $("#" + i).val(out.item[i]);
                            }
                        }
                    }
                }
                if (out.result === -1) {
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-success alert-danger').addClass('alert-warning').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                    $("#country_sub_btn").removeClass('disabled');
                }

                $("#country_sub_btn").val('Update Country');
                $("#country-form").attr('action', out.url);
                $("#country-form").append('<input type="hidden" name="country_id" id="country_id" value="' + out.item.country_id + '">');
                $("#country-form").append('<input type="hidden" name="added_date" id="added_date" value="' + out.item.added_date + '">');
                $("#country-form").append('<input type="hidden" name="modified_date" id="modified_date" value="' + out.item.modified_date + '">');
                $("#country-form").parent('.panel-body').siblings('.panel-heading').text('Update Country');
            });
        });
    };
    this.delete_country = function () {
        $(document).on("click", ".delete-country", function (evt) {
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
                        $("#list-wrapper").html(out.itemlist);
                    }
                });
            }
        });
    };
    this.__construct();
};
var country = new Event();