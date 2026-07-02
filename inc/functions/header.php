<?php
/**
 * Header custom — remplace le header Astra.
 *
 * Inclut : navigation desktop, menu mobile, bottom navigation mobile,
 * bottom sheet pour pages secondaires, et JS associé.
 * Adapté du prototype Delices de la Mer (Navbar.jsx).
 */

if (! defined('ABSPATH')) {
    exit;
}

function dm_custom_header()
{
    $home        = home_url('/');
    $is_front    = is_front_page();
    $solid_class = $is_front ? '' : ' is-solid';

    // Détection des pages actives pour la navigation
    $is_catalogue  = function_exists('is_woocommerce') && is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page();
    $is_services   = is_page('services') || is_page_template('pages/page-services.php');
    $is_stores     = is_page('points-de-vente') || is_page_template('pages/page-stores.php');
    $is_about      = is_page('a-propos') || is_page_template('pages/page-about.php');
    $is_testimonials = is_page('temoignages') || is_page_template('pages/page-testimonials.php');
    $is_contact    = is_page('contact') || is_page_template('pages/page-contact.php');
    $is_gallery    = is_page('galerie') || is_page_template('pages/page-gallery.php');
    $is_more       = $is_services || $is_stores || $is_testimonials;

    $cart_count = 0;
    $cart_url   = $home;
    if (class_exists('WooCommerce') && function_exists('WC') && WC()->cart) {
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_url   = wc_get_cart_url();
    }

    $logo_path = DM_THEME_DIR . '/assets/images/logo/logo1.png';
    $logo_uri  = DM_THEME_URI . '/assets/images/logo/logo1.png';
    $has_logo  = file_exists($logo_path);
?>
    <div class="dm-navbar-wrapper<?php echo esc_attr($solid_class); ?>" id="dm-navbar-wrapper">
        <nav class="dm-navbar" aria-label="Navigation principale">
            <div class="dm-navbar-inner">
                <!-- Logo -->
                <a href="<?php echo esc_url($home); ?>" class="dm-logo">
                    <?php if ($has_logo) : ?>
                        <img src="<?php echo esc_url($logo_uri); ?>" alt="Délices de la Mer" class="dm-logo-img" />
                    <?php else : ?>
                        <span class="dm-logo-fallback">DM</span>
                    <?php endif; ?>
                    <span>
                        <span class="dm-logo-title">Délices de la Mer</span>
                        <span class="dm-logo-sub" style="display:block;">Une symbiose de saveurs</span>
                    </span>
                </a>

                <!-- Navigation desktop -->
                <div class="dm-nav">
                    <a href="<?php echo esc_url($home); ?>" class="dm-nav-link<?php echo $is_front ? ' active' : ''; ?>">Accueil</a>
                    <a href="<?php echo esc_url(home_url('/shop')); ?>" class="dm-nav-link<?php echo $is_catalogue ? ' active' : ''; ?>">Catalogue</a>

                    <div class="dm-dropdown">
                        <button type="button" class="dm-nav-link<?php echo $is_more ? ' active' : ''; ?>" aria-haspopup="true">
                            Plus
                            <svg class="dm-dropdown-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="dm-dropdown-menu">
                            <a href="<?php echo esc_url(home_url('/services')); ?>" class="dm-dropdown-item<?php echo $is_services ? ' active' : ''; ?>">Services</a>
                            <a href="<?php echo esc_url(home_url('/points-de-vente')); ?>" class="dm-dropdown-item<?php echo $is_stores ? ' active' : ''; ?>">Points de Vente</a>
                            <a href="<?php echo esc_url(home_url('/temoignages')); ?>" class="dm-dropdown-item<?php echo $is_testimonials ? ' active' : ''; ?>">Témoignages</a>
                        </div>
                    </div>

                    <a href="<?php echo esc_url(home_url('/a-propos')); ?>" class="dm-nav-link<?php echo $is_about ? ' active' : ''; ?>">À Propos</a>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-nav-link<?php echo $is_contact ? ' active' : ''; ?>">Contact</a>
                    <a href="<?php echo esc_url(home_url('/galerie')); ?>" class="dm-nav-link<?php echo $is_gallery ? ' active' : ''; ?>">Galerie</a>
                </div>

                <!-- Actions -->
                <div class="dm-nav-actions">
                    <a href="<?php echo esc_url($cart_url); ?>" class="dm-cart" aria-label="Panier">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <?php if ($cart_count > 0) : ?>
                            <span class="dm-cart-count"><?php echo esc_html($cart_count); ?></span>
                        <?php endif; ?>
                    </a>

                    <a href="<?php echo esc_url(home_url('/shop')); ?>" class="btn-orange dm-cta-desktop">Commander</a>

                    <?php if (is_user_logged_in()) :
                        $current_user   = wp_get_current_user();
                        $first_name     = $current_user->first_name;
                        $last_name      = $current_user->last_name;
                        $display_name   = $current_user->display_name;
                        $initials       = '';
                        if (!empty($first_name) && !empty($last_name)) {
                            $initials = strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1));
                        } elseif (!empty($first_name)) {
                            $initials = strtoupper(substr($first_name, 0, 2));
                        } elseif (!empty($last_name)) {
                            $initials = strtoupper(substr($last_name, 0, 2));
                        } else {
                            $initials = strtoupper(substr($display_name, 0, 2));
                        }
                        $profile_name = !empty($first_name) ? $first_name : $display_name;
                    ?>
                    <div class="dm-profile-dropdown">
                        <button type="button" class="dm-profile-toggle" aria-haspopup="true" aria-expanded="false">
                            <span class="dm-profile-avatar"><?php echo esc_html($initials); ?></span>
                            <span class="dm-profile-name"><?php echo esc_html($profile_name); ?></span>
                            <svg class="dm-profile-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="dm-profile-menu">
                            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="dm-profile-menu-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 11H5a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-4"></path>
                                    <rect x="9" y="3" width="6" height="8" rx="1"></rect>
                                </svg>
                                <span>Mes commandes</span>
                            </a>
                            <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" class="dm-profile-menu-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Mon profil</span>
                            </a>
                            <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>" class="dm-profile-menu-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <span>Mes adresses</span>
                            </a>
                            <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="dm-profile-menu-item dm-profile-logout">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span>Déconnexion</span>
                            </a>
                        </div>
                    </div>
                    <?php else : ?>
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="dm-profile-login">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                        <span class="dm-profile-login-text">Connexion</span>
                    </a>
                    <?php endif; ?>

                    <button type="button" id="dm-burger" class="dm-burger" aria-label="Menu" aria-controls="dm-mobile-menu" aria-expanded="false">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Menu mobile -->
        <div class="dm-mobile-menu" id="dm-mobile-menu">
            <div class="dm-mobile-inner">
                <a href="<?php echo esc_url($home); ?>" class="dm-mobile-link<?php echo $is_front ? ' active' : ''; ?>">Accueil</a>
                <a href="<?php echo esc_url(home_url('/shop')); ?>" class="dm-mobile-link<?php echo $is_catalogue ? ' active' : ''; ?>">Catalogue</a>
                <a href="<?php echo esc_url(home_url('/services')); ?>" class="dm-mobile-link<?php echo $is_services ? ' active' : ''; ?>">Services</a>
                <a href="<?php echo esc_url(home_url('/points-de-vente')); ?>" class="dm-mobile-link<?php echo $is_stores ? ' active' : ''; ?>">Points de Vente</a>
                <a href="<?php echo esc_url(home_url('/temoignages')); ?>" class="dm-mobile-link<?php echo $is_testimonials ? ' active' : ''; ?>">Témoignages</a>
                <a href="<?php echo esc_url(home_url('/galerie')); ?>" class="dm-mobile-link<?php echo $is_gallery ? ' active' : ''; ?>">Galerie</a>
                <a href="<?php echo esc_url(home_url('/a-propos')); ?>" class="dm-mobile-link<?php echo $is_about ? ' active' : ''; ?>">À Propos</a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-mobile-link<?php echo $is_contact ? ' active' : ''; ?>">Contact</a>
                <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="dm-mobile-link">Suivi de commande</a>
                <a href="<?php echo esc_url(home_url('/shop')); ?>" class="btn-orange">Commander maintenant</a>
                <?php if (is_user_logged_in()) :
                    $current_user = wp_get_current_user();
                    $mobile_name  = !empty($current_user->first_name) ? $current_user->first_name : $current_user->display_name;
                ?>
                <div class="dm-mobile-profile">
                    <span class="dm-mobile-profile-name"><?php echo esc_html($mobile_name); ?></span>
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="dm-mobile-link">Mes commandes</a>
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" class="dm-mobile-link">Mon profil</a>
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>" class="dm-mobile-link">Mes adresses</a>
                    <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="dm-mobile-link dm-mobile-logout">Déconnexion</a>
                </div>
                <?php else : ?>
                <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="btn-orange" style="background:transparent;border:1.5px solid var(--orange);color:var(--orange);margin-top:0.5rem;">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bottom navigation mobile (style app) -->
    <nav class="dm-bottom-nav" id="dm-bottom-nav" aria-label="Navigation mobile">
        <a href="<?php echo esc_url($home); ?>" class="dm-bottom-nav-item" data-page="home">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span>Accueil</span>
        </a>
        <a href="<?php echo esc_url(home_url('/shop')); ?>" class="dm-bottom-nav-item" data-page="shop">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            <span>Catalogue</span>
        </a>
        <a href="<?php echo esc_url(home_url('/a-propos')); ?>" class="dm-bottom-nav-item" data-page="about">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <span>À Propos</span>
        </a>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-bottom-nav-item" data-page="contact">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
            </svg>
            <span>Contact</span>
        </a>
        <button type="button" class="dm-bottom-nav-item dm-bottom-nav-more" id="dm-bottom-nav-more" aria-label="Plus de pages" aria-expanded="false">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="1"></circle>
                <circle cx="19" cy="12" r="1"></circle>
                <circle cx="5" cy="12" r="1"></circle>
            </svg>
            <span>Plus</span>
        </button>
    </nav>

    <!-- Bottom nav sheet (pages secondaires) -->
    <div class="dm-bottom-sheet" id="dm-bottom-sheet">
        <div class="dm-bottom-sheet-backdrop" id="dm-bottom-sheet-backdrop"></div>
        <div class="dm-bottom-sheet-menu">
            <div class="dm-bottom-sheet-handle"></div>
            <a href="<?php echo esc_url(home_url('/services')); ?>" class="dm-bottom-sheet-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"></path>
                    <line x1="16" y1="8" x2="2" y2="22"></line>
                    <line x1="17.5" y1="15" x2="9" y2="15"></line>
                </svg>
                <span>Services</span>
            </a>
            <a href="<?php echo esc_url(home_url('/points-de-vente')); ?>" class="dm-bottom-sheet-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span>Points de Vente</span>
            </a>
            <a href="<?php echo esc_url(home_url('/temoignages')); ?>" class="dm-bottom-sheet-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                </svg>
                <span>Témoignages</span>
            </a>
            <a href="<?php echo esc_url(home_url('/galerie')); ?>" class="dm-bottom-sheet-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <span>Galerie</span>
            </a>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="dm-bottom-sheet-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
                <span>Suivi de commande</span>
            </a>
        </div>
    </div>

    <script>
        (function() {
            var wrapper = document.getElementById('dm-navbar-wrapper');
            var burger = document.getElementById('dm-burger');
            var menu = document.getElementById('dm-mobile-menu');
            var isHome = document.body.classList.contains('home');

            if (isHome && wrapper) {
                var onScroll = function() {
                    var threshold = window.innerWidth < 1024 ? 10 : 60;
                    if (window.scrollY > threshold) {
                        wrapper.classList.add('is-solid');
                    } else {
                        wrapper.classList.remove('is-solid');
                    }
                };
                window.addEventListener('scroll', onScroll, { passive: true });
                window.addEventListener('resize', onScroll, { passive: true });
                onScroll();
            }

            if (burger && menu) {
                burger.addEventListener('click', function() {
                    var open = menu.classList.toggle('is-open');
                    burger.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
            }

            // Bottom nav active state
            var bottomNav = document.getElementById('dm-bottom-nav');
            if (bottomNav) {
                var items = bottomNav.querySelectorAll('.dm-bottom-nav-item');
                var currentPath = window.location.pathname.replace(/\/$/, '');
                items.forEach(function(item) {
                    var href = item.getAttribute('href');
                    if (!href) return;
                    var hrefPath = href.replace(/\/$/, '').replace(window.location.origin, '');
                    if (hrefPath === '' || hrefPath === '/') {
                        if (currentPath === '' || currentPath === '/') {
                            item.classList.add('is-active');
                        }
                    } else if (currentPath.indexOf(hrefPath) === 0) {
                        item.classList.add('is-active');
                    }
                });
            }

            // Profile dropdown
            var profileToggle = document.querySelector('.dm-profile-toggle');
            var profileDropdown = document.querySelector('.dm-profile-dropdown');
            if (profileToggle && profileDropdown) {
                profileToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var open = profileDropdown.classList.toggle('is-open');
                    profileToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
                document.addEventListener('click', function(e) {
                    if (!profileDropdown.contains(e.target)) {
                        profileDropdown.classList.remove('is-open');
                        profileToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // Bottom sheet (Plus)
            var moreBtn = document.getElementById('dm-bottom-nav-more');
            var sheet = document.getElementById('dm-bottom-sheet');
            var backdrop = document.getElementById('dm-bottom-sheet-backdrop');
            if (moreBtn && sheet && backdrop) {
                var toggleSheet = function(open) {
                    sheet.classList.toggle('is-open', open);
                    moreBtn.classList.toggle('is-active', open);
                    moreBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                };
                moreBtn.addEventListener('click', function() {
                    toggleSheet(!sheet.classList.contains('is-open'));
                });
                backdrop.addEventListener('click', function() {
                    toggleSheet(false);
                });
                sheet.querySelectorAll('.dm-bottom-sheet-link').forEach(function(link) {
                    link.addEventListener('click', function() {
                        toggleSheet(false);
                    });
                });
            }
        })();
    </script>
<?php
}
