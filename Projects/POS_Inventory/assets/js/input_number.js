
// input number for quantity
    (function () {
        window.inputNumber = function (el) {
            var min = parseInt(el.attr('min')) || false;
            var max = parseInt(el.attr('max')) || false;

            var els = {};
            els.dec = el.prev('.input-number-decrement');
            els.inc = el.next('.input-number-increment');

            el.each(function () {
                var input = $(this);
                els.dec.on('click', function () {
                    var value = parseInt(input.val()) || 0;
                    value--;
                    if (!min || value >= min) {
                        input.val(value);
                    }
                });

                els.inc.on('click', function () {
                    var value = parseInt(input.val()) || 0;
                    value++;
                    if (!max || value <= max) {
                        input.val(value);
                    }
                });
            });
        };
    })();

    $(document).ready(function () {
        inputNumber($('.input-number'));
    });