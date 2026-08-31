listenChange('#countryId', function (){
    const selectedCountry = $(this).val();
    const selectedState = $('#stateId').val();
    const selectedCity = $('#cityId').val();
    const selectedThana = $('#thanaId').val();

    $('#stateId').empty().append(
        $('<option value=""></option>').text(Lang.get('js.select_state'))
    );
    $('#cityId').empty().append(
        $('<option value=""></option>').text(Lang.get('js.select_city'))
    );
    resetThanas();

    if (!selectedCountry) {
        $('#stateId, #cityId, #thanaId').trigger('change.select2');
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
                loadCities(selectedState, selectedCity, selectedThana);
            }
        },
    });
})

listenChange('#stateId', function (){
    loadCities($(this).val(), null);
})

listenChange('#cityId', function () {
    loadThanas($(this).val(), null);
})

function resetThanas() {
    if (!$('#thanaId').length) {
        return;
    }

    $('#thanaId').empty().append(
        $('<option value=""></option>').text(Lang.get('js.select_thana') || 'Select Thana')
    );
}

function loadCities(stateId, selectedCity = null, selectedThana = null) {
    $('#cityId').empty().append(
        $('<option value=""></option>').text(Lang.get('js.select_city'))
    );
    resetThanas();

    if (!stateId) {
        $('#cityId, #thanaId').trigger('change.select2');
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

            if (cityStillExists) {
                loadThanas(selectedCity, selectedThana);
            }
        },
    });
}

function loadThanas(cityId, selectedThana = null) {
    if (!$('#thanaId').length) {
        return;
    }

    resetThanas();

    if (!cityId) {
        $('#thanaId').trigger('change.select2');
        return;
    }

    $.ajax({
        url: route('thanas-list'),
        type: 'get',
        dataType: 'json',
        data: {
            city: cityId,
        },
        success: function (data) {
            $.each(data.data || {}, function (i, v) {
                $('#thanaId').append($('<option></option>').attr('value', i).text(v));
            });

            const thanaStillExists = selectedThana &&
                $('#thanaId option[value="' + selectedThana + '"]').length > 0;

            $('#thanaId').val(thanaStillExists ? selectedThana : '').trigger('change.select2');
        },
    });
}
