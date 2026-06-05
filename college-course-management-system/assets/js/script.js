document.addEventListener('DOMContentLoaded', function () {
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (navToggle && navLinks) {
        navToggle.addEventListener('click', function () {
            const isOpen = navLinks.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    document.querySelectorAll('form[data-validate]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.classList.add('was-validated');
                form.reportValidity();
                return;
            }

            const password = form.querySelector('input[name="password"]');
            const confirmPassword = form.querySelector('input[name="confirm_password"]');

            if (password && confirmPassword && password.value !== confirmPassword.value) {
                event.preventDefault();
                confirmPassword.setCustomValidity('Passwords do not match.');
                confirmPassword.reportValidity();
            } else if (confirmPassword) {
                confirmPassword.setCustomValidity('');
            }
        });
    });

    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const message = form.getAttribute('data-confirm') || 'Are you sure?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    const liveSearch = document.querySelector('[data-live-course-search]');
    const courseCards = Array.from(document.querySelectorAll('[data-course-card]'));
    const resultCount = document.querySelector('[data-course-result-count]');

    if (liveSearch && courseCards.length > 0) {
        liveSearch.addEventListener('input', function () {
            const query = liveSearch.value.trim().toLowerCase();
            let visibleCount = 0;

            courseCards.forEach(function (card) {
                const text = card.getAttribute('data-search-text') || card.textContent.toLowerCase();
                const isVisible = text.includes(query);
                card.style.display = isVisible ? '' : 'none';

                if (isVisible) {
                    visibleCount++;
                }
            });

            if (resultCount) {
                resultCount.textContent = 'Showing ' + visibleCount + ' course' + (visibleCount === 1 ? '' : 's');
            }
        });
    }
});
