$(document).ready(function () {

    initLoader();

    render();
    renderDatagrids();

});

function renderDatagrids() {
    $("body").on('change, keyup', 'form.app-datagrid-filter input, form.app-datagrid-filter select', $.debounce(500, function () {
        updateGrid($(this).parents('.app-datagrid:first'));
    }));

    $("body").on('submit', 'form.app-datagrid-filter', function () {
        updateGrid($(this).parents('.app-datagrid:first'));
        return false;
    });

    $("body").on("click", ".app-datagrid-pagination a", function (e) {
            e.preventDefault();
            var page = $(this).data('page');
            updateGrid($(this).parents('.app-datagrid:first'), page);
        }
    );
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
        $(grid).replaceWith(data);
        loaderHide($(grid).find('.app-datagrid-loader:first'));
    });
}

function render() {

    $('[data-toggle="tooltip"]').tooltip();

    $(".app-price").inputmask("numeric", {
        "digits": 2,
        "autoGroup": true,
        "groupSize": 3,
        "groupSeparator": ' ',
        "rightAlign": false,
    });

    $('[data-toggle="delete-confirmation"]').confirmation({
        btnCancelClass: 'btn btn-sm btn-default margin-left-5',
        placement: 'bottom',
        onConfirm: function (event, element) {
            //window.location.href = $(element).prop('href');
        }
    });

    $(document).on('click', '[data-toggle="lightbox"]', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    $('.select2').select2().on("select2:open", function () {
        $(".select2-search__field").attr("placeholder", Translator.trans('app.typeahead_placeholder'));
    }).on("select2:close", function () {
        $(".select2-search__field").attr("placeholder", null);
    });
    ;

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
