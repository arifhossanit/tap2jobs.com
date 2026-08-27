$(document).ready(function () {
    let counters = $(".count");
    let countersQuantity = counters.length;
    let counter = [];

    for (i = 0; i < countersQuantity; i++) {
        counter[i] = parseInt(counters[i].innerHTML);
    }

    let count = function (start, value, id) {
        let localStart = start;
        setInterval(function () {
            if (localStart < value) {
                localStart++;
                counters[id].innerHTML = localStart;
            }
        }, 100);
    };

    for (j = 0; j < countersQuantity; j++) {
        count(0, counter[j], j);
    }
});

  $(".counter").each(function () {
    let $this = $(this),
      countTo = $this.attr("data-countto");
    countDuration = parseInt($this.attr("data-duration"));
    $({ counter: $this.text() }).animate(
      {
        counter: countTo,
      },
      {
        duration: countDuration,
        easing: "linear",
        step: function () {
          $this.text(Math.floor(this.counter));
        },
        complete: function () {
          $this.text(this.counter);
        },
      }
    );
  });

$('#search-keywords').on('keyup', function () {
    let searchTerm = $(this).val();
    if (searchTerm != '') {
        $.ajax({
            url: route('get.jobs.search'),
            method: 'GET',
            data: { searchTerm: searchTerm },
            success: function (result) {
                $('#jobsSearchResults').fadeIn();
                $('#jobsSearchResults ul').empty();
                $('#jobsSearchResults ul').removeClass('d-none');
                if (result.results.length > 0) {
                    result.results.forEach(function (record) {
                        $('#jobsSearchResults ul').append('<li class="nav-item mb-3 mt-2">' + record + '</li>');
                    });
                } else {
                    $('#jobsSearchResults ul').append('<p class="ms-3 mt-3">'+Lang.get('js.no_keyword_found')+'</p>');
                }
            },
        });
    } else {
        $('#jobsSearchResults').fadeOut();
    }
});

$('#jobsSearchResults').on('click', 'li', function() {
    $('#search-keywords').val($(this).text().trim());
    $('#jobsSearchResults').fadeOut();
});



function initFrontFooterAccordion() {
    var footer = document.querySelector('.front-shared-footer');
    if (!footer) return;

    var accordions = footer.querySelectorAll('.front-footer-accordion');

    accordions.forEach(function(accordion) {
        var toggle = accordion.querySelector('.front-footer-accordion__toggle');
        if (!toggle) return;

        toggle.addEventListener('click', function() {
            var willOpen = !accordion.classList.contains('is-open');

            accordions.forEach(function(item) {
                var itemToggle = item.querySelector('.front-footer-accordion__toggle');
                item.classList.remove('is-open');
                if (itemToggle) {
                    itemToggle.setAttribute('aria-expanded', 'false');
                }
            });

            accordion.classList.toggle('is-open', willOpen);
            toggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFrontFooterAccordion);
} else {
    initFrontFooterAccordion();
}
