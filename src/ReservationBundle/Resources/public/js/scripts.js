$( document ).ready(function() {
    var formHolder = $('.passenger-form-holder');

    $('.passenger-add').click(function(){
        $(formHolder).html($(formHolder).attr('data-form'));
        $(formHolder).addClass('open');
    });

    $(formHolder).on('click', '.submit-form', function(){
        var form = $('<form></form>');
        var formFields = $(formHolder).find('.passenger-form').detach();
        $(form).append(formFields);
        $(formHolder).html('Please wait...');

        $.ajax({
            url     : $(formHolder).attr('data-submit'),
            type    : 'POST',
            dataType: 'json',
            data    : $(form).serialize(),
            success : function( data ) {
                if(data.message == 'ok'){
                    var input = $('<input type="checkbox" name="reservationbundle_reservation[passengers][]" />');
                    var inputId = 'reservationbundle_reservation_passengers_' + data.id;
                    $(input).val(data.id);
                    $(input).attr('id', inputId);

                    var label = $('<label>' + data.label + '</label>');
                    $(label).attr('for', inputId);

                    $('#reservationbundle_reservation_passengers').append($(input));
                    $('#reservationbundle_reservation_passengers').append($(label));

                    $(formHolder).removeClass('open');
                }

                if(data.message == 'error'){
                    $(formHolder).html(data.form);
                }
            }
        });
    });


    $(formHolder).on('click', '.close-form', function(){
        $(formHolder).removeClass('open');
        $(formHolder).html('');
    });


    var seatsFormHolder = $('.seats-form-holder');
    var seatErrors = {
        'reserved': 'This seat has been reserved by another user.',
        'not_found': 'This seat has not been found.'
    };


    $(seatsFormHolder).on('click', 'input', function(){
        var selectedPassengers = $('.form-row.passengers input:checked').length;
        var selectedSeats = $('.form-row.seats input:checked').length;

        var data = {
            seat: $(this).val()
        };

        if($(this).is(':checked')){
            if(selectedSeats > selectedPassengers){
                alert('You may pick a maximum of ' + selectedPassengers + ' seats.');
                $(this).removeAttr('checked');
                return true;
            }
        }else{
            data['action'] = 'unreserve';
        }

        $.ajax({
            url     : $(seatsFormHolder).attr('data-submit'),
            type    : 'POST',
            dataType: 'json',
            data    : data,
            success : function( data ) {
                if(data.error){
                    alert(seatErrors[data.error]);
                }

                $(seatsFormHolder).html(data.seats);
            }
        })
    });

    $('.form-row.passengers .next-button').click(function(event){
        if($('.form-row.passengers input:checked').length == 0){
            alert('You must pick at least one passenger.');
            event.stopImmediatePropagation();
            return true;
        }
    });

    $('.form-row.seats .next-button').click(function(event){
        var selectedPassengers = $('.form-row.passengers input:checked').length;
        var selectedSeats = $('.form-row.seats input:checked').length;
        if(selectedSeats < selectedPassengers){
            alert('You must pick ' + selectedPassengers + ' seats.');
            event.stopImmediatePropagation();
            return true;
        }
    });

    $('.next-button').click(function(){
        var parent = $(this).parents('.form-row');
        $(parent).next().show();
        $('body').scrollTop($(parent).next().offset().top);
    });

    $('#reservationbundle_reservation_baggages').collection({
        allow_up: false,
        allow_down: false,
        add: '<a href="#" class="btn btn-primary">Add baggage</span></a>',
        remove: '<a href="#" class="btn btn-danger">Delete baggage</span></a>',
        add_at_the_end: true,
        after_add: function (collection, element) {
            var selectedPassengers = $('.form-row.passengers input:checked').length;
            var selectedFreeBaggages = $(collection).find('.baggage[data-price="0"] input:checked').length;

            if(selectedFreeBaggages < selectedPassengers){
                $(element).find('.baggage[data-price="0"] input').prop("checked", true);
            }else{
                $(element).find('.baggage[data-price!="0"] input').eq(0).prop("checked", true);
                $(element).find('.baggage[data-price="0"] input').prop("disabled", true);
            }
        }
    });

    $('.btn-form-submit').click(function(event){
        var selectedPassengers = $('.form-row.passengers input:checked').length;
        var selectedBaggages = $('.baggages .baggage input:checked').length;

        if(selectedBaggages < selectedPassengers){
            alert('You must pick at least ' + selectedPassengers + ' baggage(s).');
            event.preventDefault();
            event.stopImmediatePropagation();
            return false;
        }
    });

});