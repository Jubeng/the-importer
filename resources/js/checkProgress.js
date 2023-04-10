$(document).ready(function() {
    let oProgress = {
        init: () => {
            oProgress.cacheDOM();
        },

        cacheDOM: () => {
            oProgress.oModalBackDrop = $('#modalBackDrop');
            oProgress.oModalProgress = $('#staticBackdrop');
            oProgress.oModalProgressAnimation = $('#progressAnimation');
            oProgress.oModalProgressBar = $('#progressBar');
            oProgress.getProgress();
        },

        getProgress: () => {
            $.ajax({
                url: '/check-job-progress',
                success: function(oResponse) {
                    if (oResponse.progress === undefined) {
                        return false;
                    }

                    if (oResponse.progress === false) {
                        return oResponse.progress;
                    }

                    oProgress.showProgress(oResponse.progress);
                }
            });
        },

        showProgress: (iProgress) => {
            oProgress.oModalBackDrop.addClass('show');
            oProgress.oModalProgress.addClass('show');
            oProgress.oModalBackDrop.show();
            oProgress.oModalProgress.show();
            oProgress.oModalProgressAnimation.attr('aria-valuenow', iProgress);
            oProgress.oModalProgressBar.css('width', iProgress + '%');
            if (iProgress === 100) {
                setTimeout(() => {
                    oProgress.hideProgress();
                    window.location.href = '/home';
                }, 2000);
                return;
            }
            setTimeout(() => {
                oProgress.getProgress();
            }, 3000);
        },

        hideProgress: () => {
            oProgress.oModalBackDrop.removeClass('show');
            oProgress.oModalProgress.removeClass('show');
            oProgress.oModalBackDrop.hide();
            oProgress.oModalProgress.hide();
        }
    };

    oProgress.init();
});