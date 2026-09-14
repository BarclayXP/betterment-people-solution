// Tells the check in header.php that this script loaded, so hidden content will be revealed.
window.bpsScriptsLoaded = true;

(function () {
    var menuToggle = document.querySelector('.menu-toggle');
    var siteNav = document.querySelector('.site-nav');

    if (!menuToggle || !siteNav) {
        return;
    }

    function closeMenu() {
        siteNav.classList.remove('open');
        menuToggle.setAttribute('aria-expanded', 'false');
    }

    menuToggle.addEventListener('click', function () {
        var isOpen = siteNav.classList.toggle('open');
        menuToggle.setAttribute('aria-expanded', String(isOpen));
    });

    siteNav.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && siteNav.classList.contains('open')) {
            closeMenu();
            menuToggle.focus();
        }
    });

    document.addEventListener('click', function (event) {
        if (siteNav.classList.contains('open') && !siteNav.contains(event.target) && !menuToggle.contains(event.target)) {
            closeMenu();
        }
    });
})();

// ===== SCROLL REVEAL ANIMATIONS =====
(function () {
    var elements = document.querySelectorAll('.fade-in-up, .fade-in');

    elements.forEach(function (el) {
        // Once the animation has finished, drop it so hover effects on cards work again.
        el.addEventListener('animationend', function (event) {
            if (event.target === el) {
                el.classList.add('revealed');
            }
        });
    });

    if (!('IntersectionObserver' in window)) {
        elements.forEach(function (el) {
            el.classList.add('in-view', 'revealed');
        });
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    elements.forEach(function (el) {
        observer.observe(el);
    });
})();

// ===== CONTACT FORM HELPERS =====
(function () {
    var callback = document.getElementById('callback');
    var callbackTime = document.getElementById('callback_time');

    if (callback && callbackTime) {
        var syncCallbackTime = function () {
            callbackTime.required = callback.value === 'yes';
        };
        callback.addEventListener('change', syncCallbackTime);
        syncCallbackTime();
    }

    var message = document.getElementById('message');
    var messageCount = document.getElementById('message-count');

    if (message && messageCount) {
        var maxChars = Number(message.getAttribute('maxlength')) || 500;
        var updateCount = function () {
            var text = message.value.trim();
            var words = text === '' ? 0 : text.split(/\s+/).length;
            messageCount.textContent = message.value.length + ' / ' + maxChars + ' characters, ' + words + ' / 250 words';
        };
        message.addEventListener('input', updateCount);
        messageCount.hidden = false;
        updateCount();
    }
})();
