$(document).ready(function() {
    let oProgress = {
        getProgress: () => {
            $.ajax({
                url: '/home',
                success: function(oResponse) {
                    console.log(oResponse);
                }
            });
        }
    };

    oProgress.getProgress();
});