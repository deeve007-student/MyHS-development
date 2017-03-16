$(document).ready(function () {

    render();
    loader();

});

function render() {
    renderPriceFields();
    $('[data-toggle="tooltip"]').tooltip();
}

function renderPriceFields() {
    $(".price").inputmask("numeric", {
        "digits": 2,
        "autoGroup": true,
        "groupSize": 3,
        "groupSeparator": ' ',
        "rightAlign": false,
    });
}

function notify(message, type) {
    $.notify({
        message: message
    }, {
        type: type,
        placement: {
            from: "top",
            align: "center"
        },
    });
}

function loader() {
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
