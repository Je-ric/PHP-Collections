(function () {
    
    
    function saveScrollPosition() {
        sessionStorage.setItem('scrollPosition', window.scrollY); 
    }

    
    function restoreScrollPosition() {
        const savedScrollPosition = sessionStorage.getItem('scrollPosition');
        if (savedScrollPosition !== null) {
            
            window.scrollTo(0, savedScrollPosition); 
        }
    }

    
    function resetScrollPosition() {
        window.scrollTo(0, 0); 
    }

    
    function refreshPage() {
        const currentUrl = window.location.href.split('?')[0];(function() {
            const scrollManager = {
              positions: {},
              currentPath: window.location.pathname,
          
              init() {
                this.loadScrollPositions();
                this.setupEventListeners();
                this.setupIntersectionObserver();
                this.handleInitialScroll();
              },
          
              loadScrollPositions() {
                const savedPositions = localStorage.getItem('scrollPositions');
                if (savedPositions) {
                  this.positions = JSON.parse(savedPositions);
                }
              },
          
              saveScrollPositions() {
                localStorage.setItem('scrollPositions', JSON.stringify(this.positions));
              },
          
              setupEventListeners() {
                window.addEventListener('scroll', this.debounce(this.updateScrollPosition.bind(this), 100));
                window.addEventListener('popstate', this.handleNavigation.bind(this));
                document.addEventListener('click', this.handleLinkClick.bind(this));
              },
          
              updateScrollPosition() {
                this.positions[this.currentPath] = window.scrollY;
                this.saveScrollPositions();
              },
          
              handleNavigation(event) {
                const newPath = window.location.pathname;
                if (newPath !== this.currentPath) {
                  this.resetScrollPosition();
                  this.currentPath = newPath;
                } else {
                  this.restoreScrollPosition();
                }
              },
          
              handleLinkClick(event) {
                const link = event.target.closest('a');
                if (link && link.getAttribute('href').startsWith('/')) {
                  event.preventDefault();
                  const newPath = link.getAttribute('href');
                  history.pushState(null, '', newPath);
                  this.handleNavigation();
                }
              },
          
              resetScrollPosition() {
                window.scrollTo(0, 0);
              },
          
              restoreScrollPosition() {
                const savedPosition = this.positions[this.currentPath] || 0;
                window.scrollTo(0, savedPosition);
              },
          
              handleInitialScroll() {
                if (performance.navigation.type === performance.navigation.TYPE_BACK_FORWARD) {
                  this.restoreScrollPosition();
                } else {
                  this.resetScrollPosition();
                }
              },
          
              setupIntersectionObserver() {
                const options = {
                  threshold: 0.1
                };
          
                const observer = new IntersectionObserver((entries) => {
                  entries.forEach(entry => {
                    if (entry.isIntersecting) {
                      this.refreshContent(entry.target);
                    }
                  });
                }, options);
          
                const refreshTriggers = document.querySelectorAll('[data-refresh-trigger]');
                refreshTriggers.forEach(trigger => observer.observe(trigger));
              },
          
              refreshContent(element) {
                const url = element.dataset.refreshUrl || window.location.href;
                fetch(url)
                  .then(response => response.text())
                  .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.querySelector(element.dataset.refreshTarget);
                    if (newContent) {
                      element.innerHTML = newContent.innerHTML;
                    }
                  })
                  .catch(error => console.error('Error refreshing content:', error));
              },
          
              debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                  const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                  };
                  clearTimeout(timeout);
                  timeout = setTimeout(later, wait);
                };
              }
            };
          
            scrollManager.init();
          })(); 
        history.pushState(null, null, currentUrl + '?refreshed=true'); 
        window.location.reload(); 
    }

    
    function isSamePage() {
        return window.location.href === document.referrer; 
    }

    
    window.onload = function () {
        if (isSamePage()) {
            
            restoreScrollPosition();
        } else {
            resetScrollPosition(); 
        }

        
        setInterval(refreshPage, 35000); 
    };

    
    window.onbeforeunload = function () {
        saveScrollPosition(); 
    };

    
    window.saveScrollPositions = function (theForm) {
        if (theForm) {
            const scrollX = window.scrollX || document.documentElement.scrollLeft;
            const scrollY = window.scrollY || document.documentElement.scrollTop;
            theForm.scrollX.value = scrollX;
            theForm.scrollY.value = scrollY;
        }
    };

})();
