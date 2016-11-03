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
    });

});