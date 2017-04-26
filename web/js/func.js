$(document).ready(function () {

    initLoader();
    initAjaxForms();
    initDatagrids();

    render();

});

function initAjaxForms() {

    $('body').on('submit', 'form.app-ajax-form', function (e) {
        e.preventDefault();

        var form = $(this);
        ajaxFormHandler(form);
    });
}

function ajaxFormHandler(form, callback) {
    var formData = $(form).serialize();
    var url = $(form).prop('action');

    loaderShow();

    $.post(url, formData, function (data) {

        data = $.parseJSON(data);

        if (data.message) {
            var notificationClass = 'info';

            if (typeof data.error !== 'undefined') {
                if (data.error) {
                    notificationClass = 'danger';
                }
                if (!data.error) {
                    notificationClass = 'success';
                }
            }

            notify(data.message, notificationClass);
        }

        // if form in modal - then close it on success
        if (!data.error && $(form).parents('.modal:first').length) {
            var modal = $(form).parents('.modal:first');
            $(modal).modal('hide');
        }

        if (typeof data.form !== 'undefined') {
            $(form).replaceWith(data.form);
        }

        loaderHide();

        if (typeof callback === 'function') {
            callback(data);
        }

    });
}

function initDatagrids() {
    $("body").on('change, keyup', '.app-datagrid-filter input[type="text"]', $.debounce(500, function () {
        updateGrid($(this).parents('.app-datagrid:first'));
    }));

    $("body").on('change', '.app-datagrid-filter :checkbox, .app-datagrid-filter select', $.debounce(500, function () {
        updateGrid($(this).parents('.app-datagrid:first'));
    }));

    $("body").on("click", ".app-datagrid-pagination a:not(.disabled)", function (e) {
            e.preventDefault();
            var page = $(this).data('page');
            updateGrid($(this).parents('.app-datagrid:first'), page);
        }
    );

    $("body").on('submit', 'form.app-datagrid-filter', function () {
        updateGrid($(this).parents('.app-datagrid:first'));
        return false;
    });
}

function updateGrid(grid, page) {

    page = page ? 'page=' + page.toString() : false;

    var filterForm = $(grid).find("form.app-datagrid-filter:first");
    var filterData = $(filterForm) ? $(filterForm).serialize() : '';

    var queryParams = [];

    if (filterData) {
        queryParams.push(filterData);
    }

    if (page) {
        queryParams.push(page);
    }

    var queryStr = queryParams.join('&');
    var url = window.location.toString();

    loaderShow($(grid).find('.app-datagrid-loader:first'));
    $.post(url, queryStr, function (data) {
        var refreshablePart = $.parseHTML(data);
        $(grid).find('.app-datagrid-refreshable:first').replaceWith($(refreshablePart).find('.app-datagrid-refreshable:first'));
        loaderHide($(grid).find('.app-datagrid-loader:first'));
    });
}

function render() {

    // Init typeaheads

    $('.app-patient-referrer').typeahead('destroy');

    $.post(Routing.generate('patient_names'), function (data) {
        var patients = [];

        $.map($.parseJSON(data), function (value) {
            patients.push(value);
        });

        $(".app-patient-referrer").typeahead({
            source: patients
        });
    });

    // Init tooltips

    $('[data-toggle="tooltip"]').tooltip();

    // Init price fields

    $(".app-price").inputmask("numeric", {
        "digits": 2,
        "autoGroup": true,
        "groupSize": 3,
        "groupSeparator": ' ',
        "rightAlign": false,
    });

    // Init delete confirmation

    $('body').on("click", '[data-toggle="delete-confirmation"]', function (e) {
        var deleteUrl = $(this).data('href');
        var entityLabel = $(this).data('entityLabel') ? $(this).data('entityLabel') : '';

        bootbox.confirm({
            title: Translator.trans('app.modals.delete.title'),
            message: Translator.trans('app.modals.delete.message', {
                item: entityLabel.toLowerCase()
            }),
            buttons: {
                cancel: {
                    className: 'btn-default',
                    label: Translator.trans('app.action.cancel')
                },
                confirm: {
                    className: 'btn-danger',
                    label: Translator.trans('app.action.delete')
                }
            },
            callback: function (result) {
                if (result) {
                    window.location.href = deleteUrl;
                }
            }
        });
    });

    // Init image lightbox

    $(document).on('click', '[data-toggle="lightbox"]', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    // Init select2 selector

    $('.select2').select2().on("select2:open", function () {
        $(".select2-search__field").attr("placeholder", Translator.trans('app.typeahead_placeholder'));
    }).on("select2:close", function () {
        $(".select2-search__field").attr("placeholder", null);
    });

    // Init colorpicker

    $('.cp').colorpicker();
}

function notify(message, type) {
    if (message !== '') {
        $.notify({
            message: Translator.trans(message)
        }, {
            type: type,
            placement: {
                from: "top",
                align: "center"
            },
            z_index: 9999
        });
    }
}

function initLoader() {
    $(window).bind('beforeunload', function () {
        loaderShow();
    });
}

function loaderShow(customLoader) {
    if (customLoader) {
        $(customLoader).show();
    } else {
        $('.loader').show();
    }
}

function loaderHide(customLoader) {
    if (customLoader) {
        $(customLoader).hide();
    } else {
        $('.loader').hide();
    }
}
