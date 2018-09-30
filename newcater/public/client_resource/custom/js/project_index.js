$(document).ready(function () {
    $('.btn_donate_amount').click(function () {
        let amount = $(this).val();
        $('.donate_amount').val(amount);
    });

    function getTimeRemaining(endtime) {
        let t = Date.parse(endtime) - Date.parse(new Date());
        let seconds = Math.floor((t / 1000) % 60);
        let minutes = Math.floor((t / 1000 / 60) % 60);
        let hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        let days = Math.floor(t / (1000 * 60 * 60 * 24));
        return {
            'total': t,
            'days': days,
            'hours': hours,
            'minutes': minutes,
            'seconds': seconds
        };
    }

    function initializeClock(id, endtime) {
        let clock = document.getElementById(id);
        let daysSpan = clock.querySelector('.days');
        let hoursSpan = clock.querySelector('.hours');
        let minutesSpan = clock.querySelector('.minutes');
        let secondsSpan = clock.querySelector('.seconds');

        function updateClock() {
            let t = getTimeRemaining(endtime);
            if (t.days > 0) daysSpan.innerHTML = ('0' + t.days).slice(-2);
            else daysSpan.innerHTML = '00';

            if (t.hours > 0) hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
            else hoursSpan.innerHTML = '00';
            if (t.minutes > 0) minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
            else minutesSpan.innerHTML = '00';
            if (t.seconds > 0) secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
            else secondsSpan.innerHTML = '00';


            if (t.total <= 0) {
                clearInterval(timeinterval);
            }
        }

        updateClock();
        let timeinterval = setInterval(updateClock, 1000);
    }

    let vote_start = parseInt($(".project_info").attr('data-start'));
    let vote_end = parseInt($(".project_info").attr('data-end'));
    let date = Date.parse(new Date()) * 1;
    if (vote_end === 0) {
        vote_end = vote_start + 3 * 60 * 60 * 24 * 1000;
    }
    if (date < vote_start) {
        let deadline = new Date(vote_start);
        initializeClock('clockdiv', deadline);
        $(".name_time").html("Voting Withdrawal starts in");
    }

    if (date > vote_start && date < vote_end) {
        let deadline = new Date(vote_end);
        initializeClock('clockdiv', deadline);
        $(".name_time").html("Voting Withdrawal ends in");

    }
    if (vote_end > 0 && date > vote_end) {
        $("#clockdiv").remove();
        $(".name_time").html("Pioneer voting round ended");
    }
    if (vote_start === 0) {
        $("#clockdiv").remove();
        $(".name_time").html("");
    }
    getTimestamp('12/30/2018 17:00')

});
