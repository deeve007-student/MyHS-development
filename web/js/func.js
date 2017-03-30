$(document).ready(function () {

    render();
    initLoader();

    $("body").on("click", ".app-pagination a", function () {
            var url = $(this).prop('href');
            console.log(url);
        }
    );

});

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

    $('.select2').select2();

    $('#cp2').colorpicker();
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

function loaderShow() {
    $('.loader').show();
}

function loaderHide() {
    $('.loader').hide();
}
