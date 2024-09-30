/**
 * A general handler for clicks on buttons in the admin panel.
 *
 * @param id Called function.
 * @param el
 */
function adminMigrationsAjaxHandler(id, el) {
    if (typeof id === "function") {
        id(el);
    }
}

function MigratorAjaxCall(action, data) {
    return jQuery.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: "POST",
        data: {
            action: action,
            data: data
        },
        dataType: "json"
    });
}

function runMigrations(el) {
    jQuery.preloader.start({ modal: true });
    MigratorAjaxCall('run_migrations', {}).done(function(response) {
        jQuery.preloader.stop();
        const resultDiv = jQuery ('#result_migrations');
        resultDiv.html('');

        if (response.success) {
            if (response.file && response.file.length > 0) {
                response.file.forEach(function (item) {
                    resultDiv.append("<div><span style='display:inline-block;width:280px'>" + item + "</span> <span style='color:darkseagreen'>runned successfully</span></div>");
                })
            }
        } else {
            if (response.message) {
                resultDiv.html("<div style='color:red'>" + response.message + "</div>");
            }
        }
    }).fail(function (error) {
        jQuery.preloader.stop();
        resultDiv.html("<div style='color:red'>" + 'Что-то пошло не так' + "</div>");
    });
}

function createMigration(el) {
    jQuery.preloader.start({ modal: true });
    MigratorAjaxCall('create_migration', {}).done(function(response) {
        jQuery.preloader.stop();
        window.location.reload();
    }).fail(function (error) {
        jQuery.preloader.stop();
        resultDiv.html("<div style='color:red'>" + 'Что-то пошло не так' + "</div>");
    });
}

function rollbackMigration(el) {
    jQuery.preloader.start({ modal: true });
    MigratorAjaxCall('rollback_migration', {}).done(function(response) {
        jQuery.preloader.stop();
        window.location.reload();
    }).fail(function (error) {
        jQuery.preloader.stop();
        resultDiv.html("<div style='color:red'>" + 'Что-то пошло не так' + "</div>");
    });
}

(function ($) {
    jQuery(document).ready(function () {

    });
})(jQuery);
