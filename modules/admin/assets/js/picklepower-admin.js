document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.pj-toggle-key').forEach(function(button) {
        button.addEventListener('click', function() {
            const input = document.getElementById(this.dataset.target);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                this.textContent = 'Hide';
            } else {
                input.type = 'password';
                this.textContent = 'Show';
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('pj_enable_custom_login_url');
    const slugField = document.getElementById('pj_custom_login_slug_wrapper');

    function updateVisibility() {
        slugField.style.display = toggle.checked ? '' : 'none';
    }

    toggle.addEventListener('change', updateVisibility);
    updateVisibility();
});

document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('pj_disable_wp_org_menu');
    const wrapper = document.getElementById('pj_footer_text_wrapper');
    const textField = document.getElementById('pj_custom_admin_footer_text');

    function updateVisibility() {
        if (toggle.checked) {
            wrapper.style.display = 'block';
        } else {
            wrapper.style.display = 'none';
            textField.value = '';
        }
    }

    toggle.addEventListener('change', updateVisibility);
    updateVisibility();
});
