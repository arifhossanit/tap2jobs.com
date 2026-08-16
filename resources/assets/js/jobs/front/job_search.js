$(window).scrollTop(0);

document.addEventListener('DOMContentLoaded', loadJobSearchData);

function loadJobSearchData() {
    let salaryRangeSlider = $('#salaryRange');
    let jobExperienceSlider = $('#jobExperience');
    if (!salaryRangeSlider.length && !jobExperienceSlider.length) {
        return;
    }

    ['#searchCategories', '#searchSkill', '#searchGender', '#searchCareerLevel', '#searchFunctionalArea']
        .forEach(function (selector) {
            if ($(selector).length) {
                $(selector).select2({ width: '100%' });
            }
        });

    let input = $('#input').val() ? JSON.parse($('#input').val()) : {};
    let currentLanguage = (typeof lancode !== 'undefined' ? lancode : document.documentElement.lang || 'en');
    let localizeNumber = function (value) {
        let number = String(value);
        if (currentLanguage !== 'bn') {
            return number;
        }

        const banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return number.replace(/\d/g, function (digit) {
            return banglaDigits[digit];
        });
    };
    let prettifyNumber = function (value) {
        return localizeNumber(String(value).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
    };

    if (jobExperienceSlider.length) {
        let maximumExperience = Math.max(1, Number(jobExperienceSlider.data('max')) || 30);
        let hasExperienceRange = Object.prototype.hasOwnProperty.call(input, 'jobExperienceFrom') ||
            Object.prototype.hasOwnProperty.call(input, 'jobExperienceTo');
        let legacyExperience = input.jobExperience ?? input.experience;
        let selectedExperienceFrom = hasExperienceRange ? Number(input.jobExperienceFrom || 0) : 0;
        let selectedExperienceTo = hasExperienceRange
            ? Number(input.jobExperienceTo || maximumExperience)
            : (legacyExperience !== undefined ? Number(legacyExperience) : maximumExperience);
        selectedExperienceFrom = Math.min(Math.max(0, selectedExperienceFrom), maximumExperience);
        selectedExperienceTo = Math.min(Math.max(selectedExperienceFrom, selectedExperienceTo), maximumExperience);
        let dispatchExperienceFilter = function (from, to) {
            Livewire.dispatch('changeExperienceRange', {
                from: from,
                to: to,
                maximum: maximumExperience
            });
        };

        $('#jobExperience').ionRangeSlider({
            type: 'double',
            min: 0,
            from: selectedExperienceFrom,
            to: selectedExperienceTo,
            step: 1,
            max: maximumExperience,
            max_postfix: '+',
            prettify: localizeNumber,
            onFinish: function (data) {
                dispatchExperienceFilter(data.from, data.to);
            },
        });
        jobExperienceSlider.addClass('irs-hidden-input');
    }

    if (salaryRangeSlider.length) {
        let salaryMaximum = Number(salaryRangeSlider.data('max')) || 150000;
        salaryRangeSlider.ionRangeSlider({
            type: 'double',
            min: 0,
            max: salaryMaximum,
            from: 0,
            to: salaryMaximum,
            step: 1000,
            max_postfix: '+',
            prettify: prettifyNumber,
            onFinish: function (data) {
                Livewire.dispatch('changeSalaryRange', {
                    from: data.from,
                    to: data.to,
                    maximum: salaryMaximum
                });
            },
        });
        salaryRangeSlider.addClass('irs-hidden-input');
    }

    if (input.location) {
        $('#searchByLocation').val(input.location);
    }

    listenClick('.reset-filter',function (event) {
        event.preventDefault();
        Livewire.dispatch('resetFilter');
        let salaryInstance = salaryRangeSlider.data('ionRangeSlider');
        let experienceInstance = jobExperienceSlider.data('ionRangeSlider');
        if (salaryInstance) {
            salaryInstance.update({ from: 0, to: Number(salaryRangeSlider.data('max')) || 150000 });
        }
        if (experienceInstance) {
            experienceInstance.update({ from: 0, to: Number(jobExperienceSlider.data('max')) || 30 });
        }
        $('#searchByLocation').val("");
        $('#searchFunctionalArea').val('').trigger("change");
        $('#searchCareerLevel').val('').trigger("change");
        $('#searchGender').val('').trigger("change");
        $('#searchSkill').val('').trigger("change");
        $("#searchCategories").val('').trigger("change");
        $('.jobType').prop('checked', false);
        $('#fresherJobs').prop('checked', false);
    });
}

listenChange('.jobType', function () {
    let jobType = [];
    $('.jobType:checked').each(function () {
        jobType.push($(this).val());
    });
    Livewire.dispatch('changeFilter', {
        param: 'types',
        value: jobType
    });
});

listenChange('#fresherJobs', function () {
    Livewire.dispatch('changeFilter', {
        param: 'freshersOnly',
        value: $(this).is(':checked')
    });
});

document.addEventListener('livewire:load', function () {
    window.livewire.hook('message.processed', () => {
        $(window).scrollTop(0);
        $(document).on('click', '#jobsSearchResults ul li', function () {
            $('#searchByLocation').val($(this).text());
            $('#jobsSearchResults').fadeOut();
        });
    });
});


listenChange('#searchCategories', function () {
    Livewire.dispatch('changeFilter', {
        param: 'category',
        value: $(this).val()
    });
})

listenChange('#searchSkill', function () {
    Livewire.dispatch('changeFilter', {
        param: 'skill',
        value: $(this).val()
    });
})

listenChange('#searchGender', function () {
    Livewire.dispatch('changeFilter', {
        param: 'gender',
        value: $(this).val()
    });
})

listenChange('#searchCareerLevel', function () {
    Livewire.dispatch('changeFilter', {
        param: 'careerLevel',
        value: $(this).val()
    });
})

listenChange('#searchFunctionalArea', function () {
    Livewire.dispatch('changeFilter', {
        param: 'functionalArea',
        value: $(this).val()
    });
})

listenKeyup('#searchByLocation', function () {
    Livewire.dispatch('changeFilter', {
        param: 'searchByLocation',
        value: $(this).val(),
    });
});
