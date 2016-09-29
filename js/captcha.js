function ident_addNumber(number_value, number_crypt){

    if( $('#answer_value').val().length >= 88 )
        return false;

    $('#answer').append(number_value);
    $('#answer_value').val($('#answer_value').val() + number_crypt);

}


function ident_reset() {
    $('#answer_value').val('');
    $('#answer').html('');
}
