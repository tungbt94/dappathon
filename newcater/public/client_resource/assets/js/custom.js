$(document).ready(function () {
    var h = $(window).height();
    $(".js-toggle-sidenav").click(function () {
        $(this).toggleClass('active');
        $("header .collapse").toggleClass('show');
    });
    $(".showSearchBar").click(function () {
        $("#headerSearchBox").addClass("active");
    });
    $(".search-close-btn").click(function () {
        $("#headerSearchBox").removeClass("active");
    })
    if ($(".home-difference-connect").length > 0) {
        var off_1 = $("#page_animation").offset().top;
        $(window).scroll(function () {
            var scroll = $(this).scrollTop();
            // console.log(off_1);
            if ((scroll + h) > off_1) {
                $(".home-difference-connect").attr('data-is', 'active');
                $(".differences-solar-system").attr('data-is', 'active');
                $(".differences-satelites").attr('data-is', 'active');
                $(".differences-network").attr('data-is', 'active');
            } else {
                $(".home-difference-connect").attr('data-is', '');
                $(".differences-solar-system").attr('data-is', '');
                $(".differences-satelites").attr('data-is', '');
                $(".differences-network").attr('data-is', '');
            }
        });
    }


    if ($("#slider").length > 0) {
        $('#slider').owlCarousel({
            loop: true,
            margin: 10,
            nav: false,
            dots: false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });
    }

    $("#bid .box-item-cate").each(function () {
        var date = $(this).attr('data-date');
        var element = $(this).find(".time_countdown");
        countDown(date*1000, $(this));
    })


});


function countDown(date,element) {

// Update the count down every 1 second
        var x = setInterval(function() {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = date - now;
        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
            element.find(".time_countdown").html(days + "d " + hours + "h " + minutes + "m " + seconds + "s ");

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            element.find(".time_countdown").html("Ended");
        }
    }, 1000);
}
