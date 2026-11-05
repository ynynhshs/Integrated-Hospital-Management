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

    // Highlight navigation links based on the visible section.
    const sections = document.querySelectorAll('section[id]');

    if ('IntersectionObserver' in window && sections.length) {

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

    // =========================================
    // ELASTIC SUBSYSTEM GRID
    // Expanding accordion cards
    // =========================================

    const elasticGrid = document.getElementById('elasticGrid');

    if (elasticGrid) {

        const panels = Array.prototype.slice.call(
            elasticGrid.querySelectorAll('.elastic-panel')
        );

        function setActive(index) {

            panels.forEach(function (panel) {

                const isActive = Number(panel.dataset.index) === index;

                panel.classList.toggle('active', isActive);

                panel.setAttribute(
                    'aria-expanded',
                    isActive ? 'true' : 'false'
                );

            });

        }

        panels.forEach(function (panel) {

            const index = Number(panel.dataset.index);

            // Expand on hover (desktop) — mirrors onHoverStart.
            panel.addEventListener('mouseenter', function () {
                setActive(index);
            });

            // Expand on click / tap.
            panel.addEventListener('click', function (event) {

                // Let links inside the panel navigate normally.
                if (event.target.closest('a')) {
                    return;
                }

                setActive(index);

            });

            // Keyboard accessibility.
            panel.addEventListener('keydown', function (event) {

                if (
                    event.key === 'Enter' ||
                    event.key === ' ' ||
                    event.key === 'Spacebar'
                ) {
                    event.preventDefault();
                    setActive(index);
                }

            });

        });

    }

});