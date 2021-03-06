var ComingSoon = function () {

    return {
        //main function to initiate the module
        init: function () {

            $.backstretch([
    		        "assets/img/bg/1.jpg",
    		        "assets/img/bg/2.jpg",
    		        "assets/img/bg/3.jpg",
    		        "assets/img/bg/4.jpg"
    		        ], {
    		          fade: 1000,
    		          duration: 10000
    		    });

            var austDay = new Date( ComingSoon.year , ComingSoon.month - 1, ComingSoon.day );
            $('#defaultCountdown').countdown({until: austDay});
            $('#year').text(austDay.getFullYear());
        }

    };

}();