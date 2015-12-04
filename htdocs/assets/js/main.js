$(document).ready( function () {

    countDown();

    setInterval(countDown, 60000);

    function countDown() {

        var counter = $('#count-down');

        var remainingTime = (new Date('2016-03-05T07:00:00')).getTime() - Date.now();

        if (remainingTime < 0) {
            if (! counter.hasClass('label-success')) switchLabelClass('label-success');
            counter.html('Voľby sa skončili!');
            return;
        } else {
            var daysInfo = 90;
            var daysWarning = 60;
            var daysDanger = 30;

            if (daysNumber <= daysDanger && ! counter.hasClass('label-danger'))
                switchLabelClass('label-danger');
            else if (daysNumber <= daysWarning && ! counter.hasClass('label-warning'))
                switchLabelClass('label-warnign');
            else if (daysNumber <= daysInfo && ! counter.hasClass('label-info'))
                switchLabelClass('label-info')
        }

        var daysNumber = Math.floor(remainingTime / (24 * 60 * 60 * 1000));
        var dayInflection;
        if (daysNumber == 1)
            dayInflection = 'deň';
        else if (daysNumber > 1 && daysNumber < 5)
            dayInflection = 'dni';
        else
            dayInflection = 'dní';

        remainingTime -= daysNumber * (24 * 60 * 60 * 1000);

        var hoursNumber = Math.floor(remainingTime / (60 * 60 * 1000));
        var hourInflection;
        if (hoursNumber == 1)
            hourInflection = 'hodina';
        else if (hoursNumber > 1 && hoursNumber < 5)
            hourInflection = 'hodiny';
        else
            hourInflection = 'hodín';

        remainingTime -= hoursNumber * (60 * 60 * 1000);

        var minutesNumber = Math.floor(remainingTime / (60 * 1000));
        var minuteInflection;
        if (minutesNumber == 1)
            minuteInflection = 'minúta';
        else if (minutesNumber > 1 && minutesNumber < 5)
            minuteInflection = 'minúty';
        else
            minuteInflection = 'minút';

        var text = 'Do volieb zostáva '+ daysNumber +' '+ dayInflection + ', '+ hoursNumber +' '+ hourInflection +' a '+ minutesNumber +' '+ minuteInflection;

        counter.html(text);


        function switchLabelClass(lc) {
            counter.removeClass (function (index, css) {
                return (css.match (/(^|\s)label-\S+/g) || []).join(' ');
            });
            counter.addClass(lc);
        }
    }
});