
// =========================================
// MEDILINK MAIN PORTAL
// Dashboard JavaScript
// =========================================

document.addEventListener('DOMContentLoaded', function () {

    const navigationLinks = document.querySelectorAll('.nav-link');

    // Update the active navigation link when clicked.
    navigationLinks.forEach(function (link) {

        link.addEventListener('click', function () {

            navigationLinks.forEach(function (item) {
                item.classList.remove('active');
            });

            this.classList.add('active');

        });

    });

    if ('IntersectionObserver' in window) {

        const observer = new IntersectionObserver(function (entries) {

            entries.forEach(function (entry) {

                if (entry.isIntersecting) {

                    navigationLinks.forEach(function (link) {

                        link.classList.remove('active');

                        if (link.getAttribute('href') === '#' + entry.target.id) {
                            link.classList.add('active');
                        }

                    });

                }

            });

        }, {
            rootMargin: '-20% 0px -65% 0px'
        });

        sections.forEach(function (section) {
            observer.observe(section);
        });

    }

});
