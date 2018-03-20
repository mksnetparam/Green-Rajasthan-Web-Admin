var User = function () {
    this.__construct = function () {
        this.load_items();
        this.change_status();
        this.show_page();
        this.view_page();
        this.selectAll();
        this.filter();
        this.activate_action_btn();
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
        $(document).on('click', "#change-status-btn", function (evt) {
            evt.preventDefault();
            var url = $(this).attr('href');
            $.post(url, '', function (out) {
                if (out.result === 1) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger alert-warning').addClass('alert-success').show();
                    $("#error_msg").html(message + out.msg);
                }else if(out.result ===0) {
                    var message = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    $("#error_msg").removeClass('alert-danger alert-success').addClass('alert-danger').show();
                    $("#error_msg").html(message + out.msg);
                }
                $("#list-wrapper").html(out.itemlist);
            });
        });
    };
    this.__construct();
};
var user = new User();

