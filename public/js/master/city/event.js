var Event = function () {
    this.__construct = function () {
        this.add_city();
        this.load_items();
        this.delete_city();
        this.edit_city();
        this.get_states_by_country();
        this.view_page();
        this.show_page();
        this.activate_action_btn();
        this.selectAll();
        this.filter();
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
            url += "/" + id;
            var form = $('form').eq(0);
            var postdata = form.serialize();
            $.post(url, '', function (out) {
                if (out.result === 1) {
                    $("#list-wrapper").html(out.itemlist);
                }
            });
        });
    };

    this.filter = function () {
        $(document).on('submit', "#filter-city-form", function (evt) {
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
                    $("#list-wrapper").html(out.itemlist);
                    alert(out.msg);
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
            var postData = $("#filter-city-form").serialize();
            $.post(url, postData, function (out) {
                if (out.result === 1) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-warning alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    $("#list-wrapper").html(out.itemlist);
                    alert(out.msg);
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

    this.add_city = function () {
        $("#city-form").submit(function (evt) {
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
                    if ($("#city_id").val() !== 'undefined') {
                        $("#city_sub_btn").val('Add City');
                        $("#city-form").attr('action', out.url);
                        $("#city-form").parent('.panel-body').siblings('.panel-heading').text('Add City');
                    }
                    form.reset();
                    city.load_items();
                    $(".form-group > .error").remove();
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                    alert(out.msg);
                }
            });
        });
    };
    this.edit_city = function () {
        $(document).on("click", ".edit-city", function (evt) {
            evt.preventDefault();
            var url = $(this).attr('href');
            $("#add-city-panel-btn").removeClass('hidden');
            $.get(url, '', function (out) {
                if (out.result === 1) {
                    var curl = $("#country_id").data('url');
                    var country_id = out.item.country_id;

                    curl += '/' + country_id;

                    for (var i in out.item) {
                        if (i !== 'city_id') {
                            if (i === 'is_active') {
                                $("#is_active option[value='" + out.item[i] + "']").prop('selected', true);
                            } if (i === 'country_id') {
                                $("#country_id option[value='" + out.item[i] + "']").prop('selected', true);
                            } else if (i === 'state_id') {
                                var state_id = out.item[i];
                                $.get(curl, '', function (cout) {
                                    var html = '';
                                    for (var j in cout.states) {
                                        if (state_id === j) {
                                            html += '<option value="' + j + '" selected>' + cout.states[j] + '</option>';
                                        } else {
                                            html += '<option value="' + j + '">' + cout.states[j] + '</option>';
                                        }
                                    }
                                    $("#state_id").html(html);
                                    $("#state_id").removeAttr('disabled');
                                });
//                                $("#state_id option[value='" + out.item[i] + "']").prop('selected', true);
                            }
                            else {
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
                    $("#cerfication_course_btn").removeClass('disabled');
                }

                $("#city_sub_btn").val('Update City');
                $("#city-form").attr('action', out.url);
                $("#city-form").append('<input type="hidden" name="city_id" id="city_id" value="' + out.item.city_id + '">');
                $("#city-form").append('<input type="hidden" name="added_date" id="added_date" value="' + out.item.added_date + '">');
                $("#city-form").append('<input type="hidden" name="modified_date" id="modified_date" value="' + out.item.modified_date + '">');
                $("#city-form").parent('.panel-body').siblings('.panel-heading').text('Update City');
            });
        });
    };
    this.delete_city = function () {
        $(document).on("click", ".delete-city", function (evt) {
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
                        $("#list-wrapper").html(out.itemlist);
                        alert(out.msg);
                    }
                });
            }
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
    this.__construct();
};
var city = new Event();