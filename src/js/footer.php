<script>
    class FooterAccordion {
        constructor() {
            this.init();
            window.addEventListener('resize', () => this.handleResize());
        }

        init() {
            this.sections = document.querySelectorAll('.hk-footer .grid > div');
            this.titles = [];
            this.sections.forEach(section => {
                const title = section.querySelector('h3');
                if (title) {
                    title.classList.add('footer-accordion-title', 'cursor-pointer', 'select-none');
                    // Add arrow icon if not present
                    if (!title.querySelector('.footer-accordion-arrow')) {
                        const arrow = document.createElement('span');
                        arrow.className = 'footer-accordion-arrow';
                        arrow.innerHTML = `<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M7 8l3 3 3-3" stroke="#222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
                        title.appendChild(arrow);
                    }
                    this.titles.push(title);
                    title.addEventListener('click', (e) => this.toggleSection(e, section));
                }
            });
            this.handleResize();
        }

        handleResize() {
            if (window.innerWidth < 640) {
                this.sections.forEach(section => {
                    const ul = section.querySelector('ul');
                    const title = section.querySelector('h3');
                    if (ul) ul.style.display = 'none';
                    if (title) title.setAttribute('aria-expanded', 'false');
                });
            } else {
                this.sections.forEach(section => {
                    const ul = section.querySelector('ul');
                    const title = section.querySelector('h3');
                    if (ul) ul.style.display = '';
                    if (title) title.removeAttribute('aria-expanded');
                });
            }
        }

        toggleSection(e, section) {
            if (window.innerWidth >= 640) return;
            const ul = section.querySelector('ul');
            const title = section.querySelector('h3');
            if (ul && title) {
                const isOpen = ul.style.display === 'block';
                ul.style.display = isOpen ? 'none' : 'block';
                title.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        new FooterAccordion();
    });
</script>