define(function (require) {
    'use strict';

    var ReportJsView;
    var $ = require('jquery');

    var BaseView = require('oroui/js/app/views/base/view');

    ReportJsView = BaseView.extend({

        initialize: function (options) {
            $("body").on("click", ".row-toggler", {that: this}, this.toggleRows);
            $("body").on("click", ".report-form-collapse", {that: this}, this.toggleFilters);
            $(window).on("resize", {that: this}, this.resizeReportContainer);

            this.resizeReportContainer();
        },

        dispose: function () {
            $("body").off("click", ".row-toggler");
            $("body").off("click", ".report-form-collapse");
            $(window).off("resize");
        },

        // Функция, которая заставляет контейнер с таблицей отчета всега занимать
        // все свободное место по высоте страницы
        resizeReportContainer: function () {
            var viewportHeight = $(window).height();
            var tableY = $("#report-data-container").offset().top;
            var reportContainerHeight = viewportHeight - tableY - 25;
            $("#report-data-container").css('max-height', reportContainerHeight);
        },

        toggleFilters: function (e) {
            e.preventDefault();
            var that = e.data.that;
            $(".report-form").slideToggle('fast', function () {
                that.resizeReportContainer();
            });
        },

        toggleRows: function (e) {
            e.preventDefault();

            var table = $(this).parents('table:first');

            var id = $(this).attr('data-row-toggle-id');
            var levelToProcess = parseInt($(this).attr('data-row-toggle-level')) + 1;
            var newState = $(this).parents('tr:first').attr('data-row-state') === 'open' ? 'close' : 'open';

            $(this).parents('tr:first').attr('data-row-state', newState);

            $(table).find('tr[data-row-id*="' + id + '"][data-row-id!="' + id + '"]').each(function () {
                if (newState === 'open' && parseInt($(this).attr('data-row-level')) === levelToProcess) {
                    //$(this).attr('data-row-state', 'open');
                    $(this).show();
                } else {
                    $(this).attr('data-row-state', 'close');
                    $(this).hide();
                }
            });
        }

    });

    return ReportJsView;
});
