~~~$(document).ready(function () {
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

$("#toggler-icon").click(function () {
    $(this).toggleClass("open");
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

function initMobileNavbar() {
    var toggler = document.getElementById('mobileNavbarToggler') || document.querySelector('.navbar-toggler');
    var menu = document.getElementById('navbarNav');

    if (!toggler || !menu) return;

    var submenuParents = menu.querySelectorAll('.login_btn, .register_btn');

    function isMobileNav() {
        return window.innerWidth < 992;
    }

    function closeSubmenus(exceptParent) {
        submenuParents.forEach(function(parent) {
            if (parent === exceptParent) return;

            parent.classList.remove('is-open');

            var trigger = parent.querySelector(':scope > a');
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    function closeNavbarMenu() {
        menu.classList.remove('show', 'collapsing');
        menu.classList.add('collapse');
        menu.style.height = '';
        toggler.setAttribute('aria-expanded', 'false');
        toggler.classList.add('collapsed');
        closeSubmenus();
    }

    function openNavbarMenu() {
        menu.classList.add('collapse', 'show');
        menu.classList.remove('collapsing');
        menu.style.height = '';
        toggler.setAttribute('aria-expanded', 'true');
        toggler.classList.remove('collapsed');
    }

    var newToggler = toggler.cloneNode(true);
    toggler.parentNode.replaceChild(newToggler, toggler);
    toggler = newToggler;

    toggler.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var isOpen = menu.classList.contains('show');
        if (isOpen) {
            closeNavbarMenu();
        } else {
            openNavbarMenu();
        }
    });

    submenuParents.forEach(function(parent) {
        var trigger = parent.querySelector(':scope > a');

        if (!trigger) return;

        trigger.setAttribute('aria-haspopup', 'true');
        trigger.setAttribute('aria-expanded', 'false');

        trigger.addEventListener('click', function(e) {
            if (!isMobileNav()) return;

            e.preventDefault();
            e.stopPropagation();

            var willOpen = !parent.classList.contains('is-open');
            closeSubmenus();
            parent.classList.toggle('is-open', willOpen);
            trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        });
    });

    menu.addEventListener('click', function(e) {
        var trigger = e.target.closest('.login_btn > a, .register_btn > a');

        if (!trigger || !isMobileNav()) return;

        var parent = trigger.closest('.login_btn, .register_btn');
        if (!parent) return;

        e.preventDefault();
        e.stopPropagation();

        var willOpen = !parent.classList.contains('is-open');
        closeSubmenus();
        parent.classList.toggle('is-open', willOpen);
        trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    }, true);

    document.addEventListener('click', function(e) {
        if (menu.classList.contains('show') && !menu.contains(e.target) && !toggler.contains(e.target)) {
            closeNavbarMenu();
            return;
        }

        if (isMobileNav() && menu.classList.contains('show') && !e.target.closest('.login_btn, .register_btn')) {
            closeSubmenus();
        }
    });

    var navLinks = menu.querySelectorAll('.nav-link:not(.dropdown-toggle)');
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (isMobileNav() && link.closest('.login_btn, .register_btn')) {
                return;
            }

            if (isMobileNav() && menu.classList.contains('show')) {
                closeNavbarMenu();
            }
        });
    });

    window.addEventListener('resize', function() {
        if (!isMobileNav()) {
            closeSubmenus();
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMobileNavbar);
} else {
    initMobileNavbar();
}

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
