$(document).ready(function() {
    let oLoader = {
        init: () => {
            oLoader.cacheDOM();
        },

        cacheDOM: () => {
            oLoader.oLoader = $('#loader');
            oLoader.oModalBackDrop = $('#modalBackDrop');
            oLoader.oImportBtn = $('#importButton');
            oLoader.oExportPageBtn = $('#exportPageButton');
            oLoader.oDeleteAllBtn = $('#deleteAllButton');
            oLoader.cacheEvents();
        },

        cacheEvents: () => {
            oLoader.oImportBtn.on('click', () => {
                oLoader.showLoader();
            });
            oLoader.oExportPageBtn.on('click', () => {
                oLoader.showLoader();
                setTimeout(() => {
                    oLoader.hideLoader();
                }, 2000);
            });
            oLoader.oDeleteAllBtn.on('click', () => {
                oLoader.showLoader();
            });
        },

        showLoader: () => {
            oLoader.oModalBackDrop.addClass('show');
            oLoader.oModalBackDrop.show();
            oLoader.oLoader.show();
        },

        hideLoader: () => {
            oLoader.oModalBackDrop.removeClass('show');
            oLoader.oModalBackDrop.hide();
            oLoader.oLoader.hide();
        },
    };

    oLoader.init();
});