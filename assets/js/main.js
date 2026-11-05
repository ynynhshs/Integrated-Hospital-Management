document.addEventListener('DOMContentLoaded', function () {

    const elasticGrid = document.getElementById('elasticGrid');

    if (elasticGrid) {
        const panels = Array.prototype.slice.call(
            elasticGrid.querySelectorAll('.elastic-panel')
        );

        function setActive(index) {
            panels.forEach(function (panel) {
                const isActive = Number(panel.dataset.index) === index;
                panel.classList.toggle('active', isActive);
                panel.setAttribute('aria-expanded', isActive ? 'true' : 'false');
            });
        }

        panels.forEach(function (panel) {
            const index = Number(panel.dataset.index);

            // Expand on hover (desktop)
            panel.addEventListener('mouseenter', function () {
                setActive(index);
            });

            // Expand on click / tap
            panel.addEventListener('click', function (event) {
                if (event.target.closest('a')) {
                    return;
                }
                setActive(index);
            });

            // Keyboard accessibility
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