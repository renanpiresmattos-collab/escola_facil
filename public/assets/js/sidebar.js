document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('app-sidebar');
    const sidebarMenu = document.getElementById('sidebar-menu');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarBackdrop = document.getElementById('sidebar-backdrop');
    const apiUrl = window.sidebarMenuApiUrl;

    if (sidebar && sidebarMenu && apiUrl) {
        fetch(apiUrl, { credentials: 'same-origin' })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Menu fetch failed');
                }
                return response.json();
            })
            .then(function (data) {
                const menuItems = data.data || [];
                renderMenu(menuItems, sidebarMenu, sidebar.dataset.activeMenu, sidebar.dataset.activeSubmenu);
                attachToggles();
            })
            .catch(function () {
                sidebarMenu.innerHTML = '<li class="sidebar-error">Erro ao carregar menu</li>';
            });
    } else {
        attachToggles();
    }

    if (sidebarToggle && sidebarBackdrop) {
        sidebarToggle.addEventListener('click', function () {
            const isOpen = document.body.classList.toggle('sidebar-open');
            sidebarToggle.setAttribute('aria-expanded', String(isOpen));
            sidebarBackdrop.hidden = !isOpen;
        });

        sidebarBackdrop.addEventListener('click', function () {
            closeSidebar();
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    function renderMenu(items, container, activeMenu, activeSubmenu) {
        container.innerHTML = '';

        items.forEach(function (item) {
            const hasSub = Array.isArray(item.submenu) && item.submenu.length > 0;
            const itemActive = activeMenu === item.title;
            const submenuOpen = hasSub && (itemActive || item.submenu.some(function (sub) { return sub.title === activeSubmenu; }));

            const li = document.createElement('li');
            li.className = 'sidebar-item' + (hasSub ? ' has-sub' : '');
            if (itemActive) li.classList.add('active');
            if (submenuOpen) li.classList.add('expanded');

            const link = document.createElement('a');
            link.className = 'sidebar-link';
            link.setAttribute('href', hasSub ? '#' : (item.url || '#'));

            const title = document.createElement('span');
            title.className = 'title';
            title.textContent = item.title || '';

            link.appendChild(title);

            if (hasSub) {
                const indicator = document.createElement('span');
                indicator.className = 'submenu-indicator';
                link.appendChild(indicator);
            }

            li.appendChild(link);

            if (hasSub) {
                const submenu = document.createElement('ul');
                submenu.className = 'submenu';

                item.submenu.forEach(function (sub) {
                    const subLi = document.createElement('li');
                    subLi.className = 'submenu-item' + (activeSubmenu === sub.title ? ' active' : '');

                    const subLink = document.createElement('a');
                    subLink.setAttribute('href', sub.url || '#');
                    subLink.textContent = sub.title || '';

                    subLi.appendChild(subLink);
                    submenu.appendChild(subLi);
                });

                li.appendChild(submenu);
            }

            container.appendChild(li);
        });
    }

    function attachToggles() {
        document.querySelectorAll('.sidebar-item.has-sub > .sidebar-link').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const li = this.closest('.sidebar-item');
                li.classList.toggle('expanded');
            });
        });
    }

    function closeSidebar() {
        if (!sidebarToggle || !sidebarBackdrop) {
            return;
        }

        document.body.classList.remove('sidebar-open');
        sidebarToggle.setAttribute('aria-expanded', 'false');
        sidebarBackdrop.hidden = true;
    }
});
