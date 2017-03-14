$(document).ready(function () {

    render();

});

function render() {
    renderPriceFields();
    $('[data-toggle="tooltip"]').tooltip();
}

function renderPriceFields() {
    $(".price").inputmask("numeric",{
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
    },{
		type: type,
		placement: {
			from: "top",
			align: "center"
		},
    });
}
