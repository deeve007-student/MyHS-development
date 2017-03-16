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
        $('.loader').fadeIn(200);
    });
}
