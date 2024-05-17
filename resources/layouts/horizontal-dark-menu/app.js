var App = function() {
    var MediaSize = {
        xl: 1200,
        lg: 992,
        md: 991,
        sm: 576
    };
    var Dom = {
        main: document.querySelector('html, body'),
        id: {
            container: document.querySelector("#container"),
        },
        class: {
            navbar: document.querySelector(".navbar"),
            overlay: document.querySelector('.overlay'),
            search: document.querySelector('.toggle-search'),
            searchOverlay: document.querySelector('.search-overlay'),
            searchForm: document.querySelector('.search-form-control'),
            mainContainer: document.querySelector('.main-container'),
            mainHeader: document.querySelector('.header.navbar')
        }
    }

    var categoryScroll = {
        scrollCat: function() {
            var sidebarWrapper = document.querySelectorAll('.sidebar-wrapper li.active')[0];
            var sidebarWrapperTop = sidebarWrapper.offsetTop - 50;
            setTimeout(() => {
                const scroll = document.querySelector('.menu-categories');
                scroll.scrollTop = sidebarWrapperTop;
            }, 50);
        }
    }

    var toggleFunction = {
        sidebar: function($recentSubmenu) {

            var sidebarCollapseEle = document.querySelectorAll('.sidebarCollapse');

            sidebarCollapseEle.forEach(el => {
                el.addEventListener('click', function (sidebar) {
                    sidebar.preventDefault();
                    getSidebar = document.querySelector('.sidebar-wrapper');

                    if ($recentSubmenu === true) {
                        if (document.querySelector('.collapse.submenu').classList.contains('show')) {
                            document.querySelector('.submenu.show').classList.add('mini-recent-submenu');
                            getSidebar.querySelector('.collapse.submenu').classList.remove('show');
                            getSidebar.querySelector('.collapse.submenu').classList.remove('show');
                            document.querySelector('.collapse.submenu').parentNode.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'false');
                        } else {
                            if (Dom.class.mainContainer.classList.contains('sidebar-closed')) {
                                if (document.querySelector('.collapse.submenu').classList.contains('recent-submenu')) {
                                    getSidebar.querySelector('.collapse.submenu.recent-submenu').classList.add('show');
                                    document.querySelector('.collapse.submenu.recent-submenu').parentNode.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'true');
                                    document.querySelector('.submenu').classList.remove('mini-recent-submenu');
                                } else {
                                    document.querySelector('li.active .submenu').classList.add('recent-submenu');
                                    getSidebar.querySelector('.collapse.submenu.recent-submenu').classList.add('show');
                                    document.querySelector('.collapse.submenu.recent-submenu').parentNode.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'true');
                                    document.querySelector('.submenu').classList.remove('mini-recent-submenu');
                                }
                            }
                        }
                    }
                    Dom.class.mainContainer.classList.toggle("sidebar-closed");
                    Dom.class.mainHeader.classList.toggle('expand-header');
                    Dom.class.mainContainer.classList.toggle("sbar-open");
                    Dom.class.overlay.classList.toggle('show');
                    Dom.main.classList.toggle('sidebar-noneoverflow');
                });
            });
        },
        onToggleSidebarSubmenu: function() {
            ['mouseenter', 'mouseleave'].forEach(function(e){
                document.querySelector('.sidebar-wrapper').addEventListener(e, function() {
                    if (document.querySelector('body').classList.contains('alt-menu')) {
                        if (document.querySelector('.main-container').classList.contains('sidebar-closed')) {
                            if (e === 'mouseenter') {
                                document.querySelector('li.menu .submenu').classList.remove('show');
                                document.querySelector('li.menu.active .submenu').classList.add('recent-submenu');
                                document.querySelector('li.menu.active').querySelector('.collapse.submenu.recent-submenu').classList.add('show');
                                document.querySelector('.collapse.submenu.recent-submenu').parentNode.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'true');
                            } else if (e === 'mouseleave') {
                                getMenuList = document.querySelectorAll('li.menu');
                                getMenuList.forEach(element => {

                                    var submenuShowEle = element.querySelector('.collapse.submenu.show');

                                    if (submenuShowEle) {
                                        submenuShowEle.classList.remove('show');
                                    }

                                    var submenuExpandedToggleEle = element.querySelector('.dropdown-toggle[aria-expanded="true"]');

                                    if (submenuExpandedToggleEle) {
                                        submenuExpandedToggleEle.setAttribute('aria-expanded', 'false');
                                    }

                                });
                            }
                        }
                    } else {
                        if (document.querySelector('.main-container').classList.contains('sidebar-closed')) {
                            if (e === 'mouseenter') {
                                document.querySelector('li.menu .submenu').classList.remove('show');

                                if (document.querySelector('li.menu.active .submenu')) {
                                    document.querySelector('li.menu.active .submenu').classList.add('recent-submenu');
                                    document.querySelector('li.menu.active').querySelector('.collapse.submenu.recent-submenu').classList.add('show');
                                    document.querySelector('.collapse.submenu.recent-submenu').parentNode.querySelector('.dropdown-toggle').setAttribute('aria-expanded', 'true');
                                }

                            } else if (e === 'mouseleave') {
                                getMenuList = document.querySelectorAll('li.menu');
                                getMenuList.forEach(element => {

                                    var submenuShowEle = element.querySelector('.collapse.submenu.show');

                                    if (submenuShowEle) {
                                        submenuShowEle.classList.remove('show');
                                    }


                                    var submenuExpandedToggleEle = element.querySelector('.dropdown-toggle[aria-expanded="true"]');

                                    if (submenuExpandedToggleEle) {
                                        submenuExpandedToggleEle.setAttribute('aria-expanded', 'false');
                                    }

                                });
                            }
                        }
                    }

                });
            });
        },
        offToggleSidebarSubmenu: function () {
            // $('.sidebar-wrapper').off('mouseenter mouseleave');
        },
        overlay: function() {
            document.querySelector('#dismiss, .overlay').addEventListener('click', function () {
                // hide sidebar
                Dom.class.mainContainer.classList.add('sidebar-closed');
                Dom.class.mainContainer.classList.remove('sbar-open');
                // hide overlay
                Dom.class.overlay.classList.remove('show');
                Dom.main.classList.remove('sidebar-noneoverflow');
            });
        },
        search: function() {

            if (Dom.class.search) {

                Dom.class.search.addEventListener('click', function(event) {
                    this.classList.add('show-search');
                    Dom.class.searchOverlay.classList.add('show');
                    document.querySelector('body').classList.add('search-active');
                });

                Dom.class.searchOverlay.addEventListener('click', function(event) {
                    this.classList.remove('show');
                    Dom.class.search.classList.remove('show-search');
                    document.querySelector('body').classList.remove('search-active');
                });

                document.querySelector('.search-close').addEventListener('click', function(event) {
                    event.stopPropagation();
                    Dom.class.searchOverlay.classList.remove('show');
                    Dom.class.search.classList.remove('show-search');
                    document.querySelector('body').classList.remove('search-active');
                    document.querySelector('.search-form-control').value = ''
                });
            }

        },
        themeToggle: function (layoutName) {

            var togglethemeEl = document.querySelector('.theme-toggle');
            var getBodyEl = document.body;

            console.log('miks')

            togglethemeEl.addEventListener('click', function() {
                console.log('miks 2')

                var getLocalStorage = localStorage.getItem("theme");
                var parseObj = JSON.parse(getLocalStorage);

                if (parseObj.settings.layout.darkMode) {

                    console.log('miks 3')

                    var getObjectSettings = parseObj.settings.layout;

                    var newParseObject = {...getObjectSettings, darkMode: false};

                    var newObject = { ...parseObj, settings: { layout: newParseObject }}

                    localStorage.setItem("theme", JSON.stringify(newObject))

                    var getUpdatedLocalObject = localStorage.getItem("theme");
                    var getUpdatedParseObject = JSON.parse(getUpdatedLocalObject);

                    if (!getUpdatedParseObject.settings.layout.darkMode) {
                        document.body.classList.remove('layout-dark')
                        var ifStarterKit = document.body.getAttribute('page') === 'starter-pack' ? true : false;
                        if (ifStarterKit) {
                            document.querySelector('.navbar-logo').setAttribute('src', '../../src/assets/img/logo2.svg')
                        } else {
                            // document.querySelector('.navbar-logo').setAttribute('src', getUpdatedParseObject.settings.layout.logo.lightLogo)
                        }
                    }

                } else {

                    var getObjectSettings = parseObj.settings.layout;

                    var newParseObject = {...getObjectSettings, darkMode: true};

                    var newObject = { ...parseObj, settings: { layout: newParseObject }}

                    localStorage.setItem("theme", JSON.stringify(newObject))

                    var getUpdatedLocalObject = localStorage.getItem("theme");

                    var getUpdatedParseObject = JSON.parse(getUpdatedLocalObject);

                    if (getUpdatedParseObject.settings.layout.darkMode) {
                        document.body.classList.add('layout-dark')

                        var ifStarterKit = document.body.getAttribute('page') === 'starter-pack' ? true : false;

                        if (ifStarterKit) {
                            document.querySelector('.navbar-logo').setAttribute('src', '../../src/assets/img/logo.svg')
                        } else {
                            // document.querySelector('.navbar-logo').setAttribute('src', getUpdatedParseObject.settings.layout.logo.darkLogo)
                        }

                    }

                }

                // localStorage.clear()
            })

        }
    }

    var inBuiltfunctionality = {
        mainCatActivateScroll: function() {

            if (document.querySelector('.menu-categories')) {

                const ps = new PerfectScrollbar('.menu-categories', {
                    wheelSpeed:.5,
                    swipeEasing:!0,
                    minScrollbarLength:40,
                    maxScrollbarLength:300
                });

            }
        },
        notificationScroll: function() {

            if (document.querySelector('.notification-scroll')) {
                const notificationS = new PerfectScrollbar('.notification-scroll', {
                    wheelSpeed:.5,
                    swipeEasing:!0,
                    minScrollbarLength:40,
                    maxScrollbarLength:300
                });
            }

        },
        preventScrollBody: function() {
            var nonScrollableElement = document.querySelectorAll('#sidebar, .user-profile-dropdown .dropdown-menu, .notification-dropdown .dropdown-menu,  .language-dropdown .dropdown-menu')

            var preventScrolling = function(e) {
                e = e || window.event;
                if (e.preventDefault)
                    e.preventDefault();
                e.returnValue = false;

                nonScrollableElement.scrollTop -= e. wheelDeltaY;
            }

            nonScrollableElement.forEach(preventScroll => {

                preventScroll.addEventListener('mousewheel', preventScrolling);
                preventScroll.addEventListener('DOMMouseScroll', preventScrolling);

            });
        },
        searchKeyBind: function() {

            if (Dom.class.search) {
                Mousetrap.bind('ctrl+/', function() {
                    document.body.classList.add('search-active');
                    Dom.class.search.classList.add('show-search');
                    Dom.class.searchOverlay.classList.add('show');
                    Dom.class.searchForm.focus();
                    return false;
                });
            }

        },
        bsTooltip: function() {
            var bsTooltip = document.querySelectorAll('.bs-tooltip')
            for (let index = 0; index < bsTooltip.length; index++) {
                var tooltip = new bootstrap.Tooltip(bsTooltip[index])
            }
        },
        bsPopover: function() {
            var bsPopover = document.querySelectorAll('.bs-popover')
            for (let index = 0; index < bsPopover.length; index++) {
                var popover = new bootstrap.Popover(bsPopover[index])
            }
        },
        onCheckandChangeSidebarActiveClass: function() {
            if (document.body.classList.contains('alt-menu')) {
                document.querySelector('.sidebar-wrapper li.menu.active [aria-expanded="true"]').setAttribute('aria-expanded', 'false');
            }
        },
        MaterialRippleEffect: function() {
            var getAllBtn = document.querySelectorAll('button.btn, a.btn');

            getAllBtn.forEach(btn => {

                if (!btn.classList.contains('_no--effects')) {
                    btn.classList.add('_effect--ripple');
                }

            });

            if (document.querySelector('._effect--ripple')) {
                Waves.attach('._effect--ripple', 'waves-light');
                Waves.init();
            }
        },
        functionalDropdown: function() {
            var getDropdownElement = document.querySelectorAll('.more-dropdown .dropdown-item');
            for (var i = 0; i < getDropdownElement.length; i++) {
                getDropdownElement[i].addEventListener('click', function() {
                    document.querySelectorAll('.more-dropdown .dropdown-toggle > span')[0].innerText = this.getAttribute('data-value');
                })
            }
        },
        EnableNavBarPopper: function() {
            window.bootstrap.Dropdown.prototype._detectNavbar = function(){ return false; };
        },
        EnableMenuDropdownOnHover: function() {

            // Main Menu

            let GET_SIDEBAR_MENU_ITEMS = document.querySelectorAll('.menu');

            GET_SIDEBAR_MENU_ITEMS.forEach(menuItems => {

                menuItems.addEventListener('mouseover', function() {

                    let GET_CURRENT_SIDEBAR_ANCHORS = this.querySelector('[data-bs-toggle="dropdown"]');

                    // console.log(GET_CURRENT_SIDEBAR_ANCHORS)
                    if (GET_CURRENT_SIDEBAR_ANCHORS != null) {
                            let myDropdown = new bootstrap.Dropdown(GET_CURRENT_SIDEBAR_ANCHORS)
                            myDropdown.show();

                    }


                })


                menuItems.addEventListener('mouseleave', function() {


                    let GET_CURRENT_SIDEBAR_ANCHORS = this.querySelector('.dropdown-toggle');
                    // console.log(GET_CURRENT_SIDEBAR_ANCHORS)
                    if (GET_CURRENT_SIDEBAR_ANCHORS != null) {

                            let myDropdown = new bootstrap.Dropdown(GET_CURRENT_SIDEBAR_ANCHORS)
                            myDropdown.hide();
                    }


                })


            });


            // Sub Menu

            let GET_SIDEBAR_SUBMENU_ITEMS = document.querySelectorAll('.sub-submenu');

            GET_SIDEBAR_SUBMENU_ITEMS.forEach(submenuMenuItems => {

                submenuMenuItems.addEventListener('mouseover', function() {

                    // console.log('kisiks')

                    // let GET_CURRENT_SIDEBAR_ANCHORS = this.querySelector('.dropdown-toggle');
                    let GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS = this.querySelector('[data-bs-toggle="dropdown"]');

                    // console.log(GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS)

                    // console.log(GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS)
                    if (GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS != null) {

                        // let getNextEl = GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS.nextElementSibling;

                        // GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS.forEach(sidebarAnchors => {
                            let myDropdown = new bootstrap.Dropdown(GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS)
                            // console.log(sidebarAnchors)

                            // if (_core_Menu_ === 'horizontal') {
                                myDropdown.show();
                            // }

                        // });

                    }


                })


                submenuMenuItems.addEventListener('mouseleave', function() {

                    // console.log('kisiks')

                    let GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS = this.querySelector('.dropdown-toggle');
                    // console.log(GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS)
                    if (GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS != null) {

                        // let getNextEl = GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS.nextElementSibling;

                        // GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS.forEach(sidebarAnchors => {
                            let myDropdown = new bootstrap.Dropdown(GET_CURRENT_SIDEBAR_SUBMENU_ANCHORS)
                            // console.log(sidebarAnchors)

                            // if (_core_Menu_ === 'horizontal') {
                                myDropdown.hide();
                            // }

                        // });

                    }


                })


            });

        },
        ChangeToCollapsible: function() {

            // Main Menu

            let GET_SIDEBAR_MENU_ITEMS = document.querySelectorAll('.menu');

            GET_SIDEBAR_MENU_ITEMS.forEach(menuItems => {

                // menuItems.addEventListener('mouseover', function() {

                    // console.log('kisiks')

                    let GET_CURRENT_SIDEBAR_ANCHORS = menuItems.querySelector('.dropdown-toggle');
                    // console.log(GET_CURRENT_SIDEBAR_ANCHORS)
                    if (GET_CURRENT_SIDEBAR_ANCHORS != null) {


                        // GET_CURRENT_SIDEBAR_ANCHORS.
                        // console.log('k989899')
                        // console.log(GET_CURRENT_SIDEBAR_ANCHORS)

                        if (GET_CURRENT_SIDEBAR_ANCHORS.hasAttribute('data-bs-toggle')) {
                            let GET_NEXT_EL = GET_CURRENT_SIDEBAR_ANCHORS.nextElementSibling;
                            // GET_CURRENT_SIDEBAR_ANCHORS.
                            // var myDropdown = new bootstrap.dispose(GET_CURRENT_SIDEBAR_ANCHORS);
                            // myDropdown.dispose();
                            GET_CURRENT_SIDEBAR_ANCHORS.setAttribute('data-bs-toggle', 'collapse');

                            // console.log('5299*')
                            // console.log(GET_NEXT_EL)

                            GET_NEXT_EL.classList.remove('dropdown-menu');
                            GET_NEXT_EL.classList.add('collapse');
                            GET_NEXT_EL.removeAttribute('style');

                        }

                    }


                // })


                // menuItems.addEventListener('mouseleave', function() {

                //     console.log('kisiks')

                //     let GET_CURRENT_SIDEBAR_ANCHORS = this.querySelector('.dropdown-toggle');
                //     // console.log(GET_CURRENT_SIDEBAR_ANCHORS)
                //     if (GET_CURRENT_SIDEBAR_ANCHORS != null) {

                //         // let getNextEl = GET_CURRENT_SIDEBAR_ANCHORS.nextElementSibling;

                //         // GET_CURRENT_SIDEBAR_ANCHORS.forEach(sidebarAnchors => {
                //             let myDropdown = new bootstrap.Dropdown(GET_CURRENT_SIDEBAR_ANCHORS)
                //             // console.log(sidebarAnchors)

                //             // if (_core_Menu_ === 'horizontal') {
                //                 myDropdown.hide();
                //             // }

                //         // });

                //     }


                // })


            });


            // Sub Menu

            let GET_SIDEBAR_SUBMENU_ITEMS = document.querySelectorAll('.sub-submenu');

            GET_SIDEBAR_SUBMENU_ITEMS.forEach(subMenuItems => {

                // subMenuItems.addEventListener('mouseover', function() {

                    // console.log('kisiks')

                    let GET_CURRENT_SIDEBAR_ANCHORS = subMenuItems.querySelector('.dropdown-toggle');
                    // console.log(GET_CURRENT_SIDEBAR_ANCHORS)
                    if (GET_CURRENT_SIDEBAR_ANCHORS != null) {


                        // GET_CURRENT_SIDEBAR_ANCHORS.
                        // console.log('k989899')
                        // console.log(GET_CURRENT_SIDEBAR_ANCHORS)

                        if (GET_CURRENT_SIDEBAR_ANCHORS.hasAttribute('data-bs-toggle')) {
                            let GET_NEXT_EL = GET_CURRENT_SIDEBAR_ANCHORS.nextElementSibling;
                            // GET_CURRENT_SIDEBAR_ANCHORS.
                            // var myDropdown = new bootstrap.dispose(GET_CURRENT_SIDEBAR_ANCHORS);
                            // myDropdown.dispose();
                            GET_CURRENT_SIDEBAR_ANCHORS.setAttribute('data-bs-toggle', 'collapse');

                            // console.log('5299*')
                            // console.log(GET_NEXT_EL)

                            GET_NEXT_EL.classList.remove('dropdown-menu');
                            GET_NEXT_EL.classList.add('collapse');
                            GET_NEXT_EL.removeAttribute('style');

                        }

                    }


                // })


                // menuItems.addEventListener('mouseleave', function() {

                //     console.log('kisiks')

                //     let GET_CURRENT_SIDEBAR_ANCHORS = this.querySelector('.dropdown-toggle');
                //     // console.log(GET_CURRENT_SIDEBAR_ANCHORS)
                //     if (GET_CURRENT_SIDEBAR_ANCHORS != null) {

                //         // let getNextEl = GET_CURRENT_SIDEBAR_ANCHORS.nextElementSibling;

                //         // GET_CURRENT_SIDEBAR_ANCHORS.forEach(sidebarAnchors => {
                //             let myDropdown = new bootstrap.Dropdown(GET_CURRENT_SIDEBAR_ANCHORS)
                //             // console.log(sidebarAnchors)

                //             // if (_core_Menu_ === 'horizontal') {
                //                 myDropdown.hide();
                //             // }

                //         // });

                //     }


                // })


            });
        },
        ChangeToDropdown: function() {

            // Main Menu

            let GET_SIDEBAR_MENU_ITEMS = document.querySelectorAll('.menu');

            GET_SIDEBAR_MENU_ITEMS.forEach(menuItems => {

                // menuItems.addEventListener('mouseover', function() {

                    // console.log('kisiks')

                    let GET_CURRENT_SIDEBAR_ANCHORS = menuItems.querySelector('.dropdown-toggle');
                    // console.log(GET_CURRENT_SIDEBAR_ANCHORS)
                    if (GET_CURRENT_SIDEBAR_ANCHORS != null) {


                        // GET_CURRENT_SIDEBAR_ANCHORS.
                        // console.log('k989899')
                        // console.log(GET_CURRENT_SIDEBAR_ANCHORS)

                        if (GET_CURRENT_SIDEBAR_ANCHORS.hasAttribute('data-bs-toggle')) {
                            let GET_NEXT_EL = GET_CURRENT_SIDEBAR_ANCHORS.nextElementSibling;
                            GET_CURRENT_SIDEBAR_ANCHORS.setAttribute('data-bs-toggle', 'dropdown');

                            // console.log('5299*')
                            // console.log(GET_NEXT_EL)

                            GET_NEXT_EL.classList.remove('collapse');
                            GET_NEXT_EL.classList.add('dropdown-menu');
                        }

                    }


                // })

            });


            // Sub Menu

            let GET_SIDEBAR_SUBMENU_ITEMS = document.querySelectorAll('.sub-submenu');

            GET_SIDEBAR_SUBMENU_ITEMS.forEach(menuItems => {

                // menuItems.addEventListener('mouseover', function() {

                    // console.log('kisiks')

                    let GET_CURRENT_SIDEBAR_ANCHORS = menuItems.querySelector('.dropdown-toggle');
                    // console.log(GET_CURRENT_SIDEBAR_ANCHORS)
                    if (GET_CURRENT_SIDEBAR_ANCHORS != null) {


                        // GET_CURRENT_SIDEBAR_ANCHORS.
                        // console.log('k989899')
                        // console.log(GET_CURRENT_SIDEBAR_ANCHORS)

                        if (GET_CURRENT_SIDEBAR_ANCHORS.hasAttribute('data-bs-toggle')) {
                            let GET_NEXT_EL = GET_CURRENT_SIDEBAR_ANCHORS.nextElementSibling;
                            GET_CURRENT_SIDEBAR_ANCHORS.setAttribute('data-bs-toggle', 'dropdown');

                            // console.log('5299*')
                            // console.log(GET_NEXT_EL)

                            GET_NEXT_EL.classList.remove('collapse');
                            GET_NEXT_EL.classList.add('dropdown-menu');
                        }

                    }


                // })

            });
        },
        AddAnimationClass: function() {
            // document.body.classList('scale-up-vertical-top')

            var getMenus = document.querySelectorAll('.menu')

            // console.log(getMenus.classList.contains('menu-heading'))

            getMenus.forEach(menu => {
                if (!menu.classList.contains('menu-heading')) {
                    var dropMenu = menu.querySelector('.dropdown-menu');

                    var subSubmenu = menu.querySelectorAll('.dropdown-menu.sub-submenu')

                    dropMenu.classList.add('scale-up-top-left')

                    subSubmenu.forEach(subMenu => {
                        subMenu.classList.add('scale-up-top-left-submenu')
                    });
                }
            });



        }
    }

    var _mobileResolution = {
        onRefresh: function() {
            var windowWidth = window.innerWidth;
            if ( windowWidth <= MediaSize.md ) {
                // categoryScroll.scrollCat();
                toggleFunction.sidebar();
                inBuiltfunctionality.ChangeToCollapsible();
            }
        },

        onResize: function() {
            window.addEventListener('resize', function(event) {
                event.preventDefault();
                var windowWidth = window.innerWidth;
                if ( windowWidth <= MediaSize.md ) {
                    toggleFunction.offToggleSidebarSubmenu();
                    inBuiltfunctionality.ChangeToCollapsible();
                }
            });
        }
    }

    var _desktopResolution = {
        onRefresh: function() {
            var windowWidth = window.innerWidth;
            if ( windowWidth > MediaSize.md ) {
                categoryScroll.scrollCat();
                toggleFunction.sidebar();
                toggleFunction.onToggleSidebarSubmenu();
                inBuiltfunctionality.ChangeToDropdown();
            }
        },

        onResize: function() {
            window.addEventListener('resize', function(event) {
                event.preventDefault();
                var windowWidth = window.innerWidth;
                if ( windowWidth > MediaSize.md ) {
                    toggleFunction.onToggleSidebarSubmenu();
                    inBuiltfunctionality.ChangeToDropdown();
                }
            });
        }

    }

    function sidebarFunctionality() {
        function sidebarCloser() {

            if (window.innerWidth <= 991 ) {

                if (!document.querySelector('body').classList.contains('alt-menu')) {

                    Dom.id.container.classList.add("sidebar-closed");
                    Dom.class.overlay.classList.remove('show');
                } else {
                    Dom.class.navbar.classList.remove("expand-header");
                    Dom.class.overlay.classList.remove('show');
                    Dom.id.container.classList.remove('sbar-open');
                    Dom.main.classList.remove('sidebar-noneoverflow');
                }

            } else if (window.innerWidth > 991 ) {

                if (!document.querySelector('body').classList.contains('alt-menu')) {

                    Dom.id.container.classList.remove("sidebar-closed");
                    Dom.class.navbar.classList.remove("expand-header");
                    Dom.class.overlay.classList.remove('show');
                    Dom.id.container.classList.remove('sbar-open');
                    Dom.main.classList.remove('sidebar-noneoverflow');
                } else {
                    Dom.main.classList.add('sidebar-noneoverflow');
                    Dom.id.container.classList.add("sidebar-closed");
                    Dom.class.navbar.classList.add("expand-header");
                    Dom.class.overlay.classList.add('show');
                    Dom.id.container.classList.add('sbar-open');
                    document.querySelector('.sidebar-wrapper [aria-expanded="true"]').parentNode.querySelector('.collapse').classList.remove('show');
                }
            }
        }

        function sidebarMobCheck() {
            if (window.innerWidth <= 991 ) {

                if ( document.querySelector('.main-container').classList.contains('sbar-open') ) {
                    return;
                } else {
                    sidebarCloser()
                }
            } else if (window.innerWidth > 991 ) {
                sidebarCloser();
            }
        }

        sidebarCloser();

        window.addEventListener('resize', function(event) {
            sidebarMobCheck();
        });

    }

    return {
        init: function(Layout) {
            toggleFunction.overlay();
            toggleFunction.search();
            toggleFunction.themeToggle(Layout);

            /*
                Desktop Resoltion fn
            */
            _desktopResolution.onRefresh();
            _desktopResolution.onResize();

            /*
                Mobile Resoltion fn
            */
            _mobileResolution.onRefresh();
            _mobileResolution.onResize();

            sidebarFunctionality();

            /*
                In Built Functionality fn
            */
            inBuiltfunctionality.mainCatActivateScroll();
            inBuiltfunctionality.notificationScroll();
            inBuiltfunctionality.preventScrollBody();
            inBuiltfunctionality.searchKeyBind();
            inBuiltfunctionality.bsTooltip();
            inBuiltfunctionality.bsPopover();
            inBuiltfunctionality.onCheckandChangeSidebarActiveClass();
            inBuiltfunctionality.MaterialRippleEffect();
            inBuiltfunctionality.functionalDropdown();
            inBuiltfunctionality.EnableNavBarPopper();
            inBuiltfunctionality.EnableMenuDropdownOnHover();
            inBuiltfunctionality.AddAnimationClass();

        }
    }

}();

window.addEventListener('load', function() {
    App.init('layout');
})
