
listenChange('#countryId', function (){
    const selectedCountry = $(this).val();
    const selectedState = $('#stateId').val();
    const selectedCity = $('#cityId').val();

    $('#stateId').empty().append(
        $('<option value=""></option>').text(Lang.get('js.select_state'))
    );
    $('#cityId').empty().append(
        $('<option value=""></option>').text(Lang.get('js.select_city'))
    );

    if (!selectedCountry) {
        $('#stateId, #cityId').trigger('change.select2');
        return;
    }

    $.ajax({
        url: route('states-list'),
        type: 'get',
        dataType: 'json',
        data: { postal: selectedCountry },
        success: function (data) {
            $.each(data.data || {}, function (i, v) {
                $('#stateId').append($('<option></option>').attr('value', i).text(v));
            });

            const stateStillExists = selectedState &&
                $('#stateId option[value="' + selectedState + '"]').length > 0;

            $('#stateId').val(stateStillExists ? selectedState : '').trigger('change.select2');

            if (stateStillExists) {
                loadCities(selectedState, selectedCity);
            }
        },
    });
})

listenChange('#stateId', function (){
    loadCities($(this).val(), null);
})

function loadCities(stateId, selectedCity = null) {
    $('#cityId').empty().append(
        $('<option value=""></option>').text(Lang.get('js.select_city'))
    );

    if (!stateId) {
        $('#cityId').trigger('change.select2');
        return;
    }

    $.ajax({
        url: route('cities-list'),
        type: 'get',
        dataType: 'json',
        data: {
            state: stateId,
            country: $('#countryId').val(),
        },
        success: function (data) {
            $.each(data.data || {}, function (i, v) {
                $('#cityId').append($('<option></option>').attr('value', i).text(v));
            });

            const cityStillExists = selectedCity &&
                $('#cityId option[value="' + selectedCity + '"]').length > 0;

            $('#cityId').val(cityStillExists ? selectedCity : '').trigger('change.select2');
        },
    });
}
