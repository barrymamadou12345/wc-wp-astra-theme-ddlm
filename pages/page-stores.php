<?php
/**
 * Template Name: Points de Vente
 *
 * Page Points de Vente — Délices de la Mer.
 * Reproduit le prototype StoreLocator.jsx :
 *   - Hero avec image de fond + overlay + titre
 *   - Stats strip avec recherche + filtres par zone
 *   - Grille de cartes points de vente (données dynamiques via repeater)
 *   - État vide si aucun résultat
 *   - CTA devenir distributeur
 */

if (!defined('ABSPATH')) exit;

get_header();

$stores = dm_get_stores();
$partner_steps = dm_get_partner_steps();
$partner_advantages = dm_get_partner_advantages();

// Extraction des zones uniques pour les filtres
$zones = array('Toutes');
foreach ($stores as $store) {
    $zone = $store['zone'] ?? '';
    if ($zone && !in_array($zone, $zones)) {
        $zones[] = $zone;
    }
}

// Image hero : bannière centralisée depuis la base de données
$hero_image = function_exists('dm_get_banner_image') ? dm_get_banner_image() : '';
if (empty($hero_image)) {
    $hero_image = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
}
?>

<!-- Hero -->
<div class="dm-stores-hero"<?php echo !empty($hero_image) ? ' style="background-image:url(' . esc_url($hero_image) . ');"' : ''; ?>>
    <div class="dm-stores-hero-overlay"></div>
    <div class="dm-stores-hero-content">
        <p class="dm-stores-hero-label">Où nous trouver</p>
        <h1 class="dm-stores-hero-title">Points de Vente</h1>
        <p class="dm-stores-hero-desc">Retrouvez nos produits chez nos partenaires distributeurs à travers le Sénégal.</p>
    </div>
</div>

<!-- Barre de filtres — carte flottante qui chevauche le hero -->
<div class="dm-stores-filters">
    <div class="dm-stores-filters-inner">
        <!-- Ligne 1 : Compteur + Recherche -->
        <div class="dm-stores-filter-row-top">
            <div class="dm-stores-filter-count">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span class="dm-stores-filter-num"><?php echo esc_html(count($stores)); ?>+</span>
                <span class="dm-stores-filter-label">Points de vente</span>
            </div>
            <div class="dm-stores-filter-search">
                <svg class="dm-stores-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                <input type="text" id="dm-stores-search" placeholder="Rechercher un point de vente..." />
            </div>
        </div>
        <!-- Ligne 2 : Filtres par zone (scrollable) -->
        <div class="dm-stores-filter-row-zones">
            <div class="dm-stores-zones-scroll">
                <?php foreach ($zones as $z) : ?>
                <button class="dm-stores-zone-pill<?php echo $z === 'Toutes' ? ' is-active' : ''; ?>" data-zone="<?php echo esc_attr($z); ?>"><?php echo esc_html($z); ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Contenu -->
<div class="dm-stores-body">
    <div class="dm-stores-container">

        <!-- Grille des stores -->
        <div class="dm-stores-grid" id="dm-stores-grid">
            <?php foreach ($stores as $i => $store) :
                $name    = $store['name'] ?? '';
                $type    = $store['type'] ?? '';
                $zone    = $store['zone'] ?? '';
                $address = $store['address'] ?? '';
                $rayon   = $store['rayon'] ?? '';
                $phone   = $store['phone'] ?? '';
                $image   = $store['image'] ?? '';
            ?>
            <div class="dm-store-card reveal-el" data-name="<?php echo esc_attr(strtolower($name)); ?>" data-type="<?php echo esc_attr(strtolower($type)); ?>" data-address="<?php echo esc_attr(strtolower($address)); ?>" data-zone="<?php echo esc_attr($zone); ?>">
                <div class="dm-store-card-image">
                    <?php if ($image) : ?>
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr('Photo de ' . $name); ?>" loading="lazy" />
                    <?php endif; ?>
                    <div class="dm-store-card-image-overlay"></div>
                    <?php if ($type) : ?>
                    <span class="dm-store-card-badge"><?php echo esc_html($type); ?></span>
                    <?php endif; ?>
                </div>
                <div class="dm-store-card-info">
                    <div class="dm-store-card-header">
                        <div class="dm-store-card-icon">
                            <?php echo dm_get_store_type_icon($type); ?>
                        </div>
                        <div>
                            <h3 class="dm-store-card-name"><?php echo esc_html($name); ?></h3>
                            <p class="dm-store-card-address"><?php echo esc_html($address); ?></p>
                        </div>
                    </div>
                    <?php if ($zone || $rayon) : ?>
                    <p class="dm-store-card-rayon">📍 <?php echo esc_html($zone); ?><?php echo $rayon ? ' — ' . esc_html($rayon) : ''; ?></p>
                    <?php endif; ?>
                    <?php if ($phone) : ?>
                    <a href="tel:<?php echo esc_attr($phone); ?>" class="dm-store-card-phone">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <?php echo esc_html($phone); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty state -->
        <div class="dm-stores-empty" id="dm-stores-empty" style="display:none;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <p>Aucun point de vente trouvé pour ces critères.</p>
        </div>

    </div>
</div>

<!-- Étapes : Comment devenir distributeur -->
<?php if (!empty($partner_steps)) : ?>
<section class="dm-section bg-white">
    <div class="dm-container">
        <div class="section-head">
            <p class="section-label">Partenariat</p>
            <h2 class="section-title">Comment devenir <span class="accent-orange">distributeur</span></h2>
            <div class="divider-orange"></div>
            <p class="section-subtitle">Rejoignez notre réseau de partenaires en quelques étapes simples.</p>
        </div>

        <div class="dm-steps-grid">
            <?php
            $s_total = count($partner_steps);
            $s_idx = 0;
            foreach ($partner_steps as $step) :
                $num   = $step['num'] ?? '';
                $title = $step['title'] ?? '';
                $desc  = $step['desc'] ?? '';
                $img   = $step['image'] ?? '';
            ?>
                <div class="dm-step-card reveal-el" style="transition-delay: <?php echo esc_attr($s_idx * 100); ?>ms;">
                    <?php if (!empty($img)) : ?>
                        <div class="dm-step-card-img">
                            <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($title); ?>" />
                        </div>
                    <?php else : ?>
                        <span class="dm-step-num"><?php echo esc_html($num); ?></span>
                    <?php endif; ?>
                    <h3 class="dm-step-title"><?php echo esc_html($title); ?></h3>
                    <?php if ($desc) : ?>
                    <p class="dm-step-desc"><?php echo esc_html($desc); ?></p>
                    <?php endif; ?>
                </div>
                <?php if ($s_idx < $s_total - 1) : ?>
                <div class="dm-step-arrow reveal-el" style="transition-delay: <?php echo esc_attr(($s_idx + 1) * 100); ?>ms;" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
                <?php endif; ?>
            <?php $s_idx++; endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Section CTA Contact — pleine largeur avec wave -->
<section class="dm-stores-contact-section">
    <div class="dm-contact-wave">
        <svg viewBox="0 0 1200 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C300,80 600,0 900,40 C1050,60 1150,30 1200,40 L1200,0 L0,0 Z" fill="var(--cream)"></path>
        </svg>
    </div>
    <div class="dm-contact-section-inner">
        <div class="dm-contact-card-label">Partenariat</div>
        <h2 class="dm-contact-card-title">Devenez distributeur<br><span class="accent-orange">Délices de la Mer</span></h2>
        <p class="dm-contact-card-desc">Rejoignez notre réseau de partenaires et développez votre activité avec des produits de qualité. Contactez-nous via le canal de votre choix.</p>
        <div class="dm-contact-options">
            <a href="tel:<?php echo esc_attr(dm_get_phone_tel()); ?>" class="dm-contact-option">
                <div class="dm-contact-option-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                </div>
                <div class="dm-contact-option-text">
                    <span class="dm-contact-option-name">Téléphone</span>
                    <span class="dm-contact-option-value"><?php echo esc_html(dm_get_phone()); ?></span>
                </div>
            </a>
            <a href="mailto:<?php echo esc_attr(dm_get_email()); ?>?subject=<?php echo esc_attr(rawurlencode('Demande de partenariat — Délices de la Mer')); ?>&amp;body=<?php echo esc_attr(rawurlencode("Bonjour,\n\nJe souhaite devenir partenaire/distributeur de Délices de la Mer et je voudrais obtenir plus de renseignements sur les conditions de partenariat.\n\nVoici quelques informations sur mon enseigne :\n- Nom de l'enseigne : \n- Zone d'activité : \n- Type de commerce : \n\nMerci de me recontacter.\n\nCordialement.")); ?>" class="dm-contact-option">
                <div class="dm-contact-option-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                <div class="dm-contact-option-text">
                    <span class="dm-contact-option-name">Email</span>
                    <span class="dm-contact-option-value"><?php echo esc_html(dm_get_email()); ?></span>
                </div>
            </a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-contact-option dm-contact-option-wa">
                <div class="dm-contact-option-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <div class="dm-contact-option-text">
                    <span class="dm-contact-option-name">WhatsApp</span>
                    <span class="dm-contact-option-value"><?php echo esc_html(dm_get_phone()); ?></span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Avantages : Pourquoi nous choisir -->
<?php if (!empty($partner_advantages)) : ?>
<section class="dm-section bg-white">
    <div class="dm-container">
        <div class="section-head">
            <p class="section-label">Avantages</p>
            <h2 class="section-title">Pourquoi nous <span class="accent-orange">choisir</span></h2>
            <div class="divider-orange"></div>
            <p class="section-subtitle">Les avantages de devenir distributeur Délices de la Mer.</p>
        </div>

        <div class="dm-advantages-grid">
            <?php foreach ($partner_advantages as $idx => $adv) :
                $title = $adv['title'] ?? '';
                $desc  = $adv['desc'] ?? '';
                $img   = $adv['image'] ?? '';
            ?>
            <div class="dm-advantage-card reveal-el" style="transition-delay: <?php echo esc_attr($idx * 100); ?>ms;">
                <?php if (!empty($img)) : ?>
                <div class="dm-advantage-img">
                    <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($title); ?>" />
                </div>
                <?php endif; ?>
                <div class="dm-advantage-body">
                    <h3 class="dm-advantage-title"><?php echo esc_html($title); ?></h3>
                    <p class="dm-advantage-desc"><?php echo esc_html($desc); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
(function() {
    var searchInput = document.getElementById('dm-stores-search');
    var zoneBtns = document.querySelectorAll('.dm-stores-zone-pill');
    var cards = document.querySelectorAll('.dm-store-card');
    var emptyState = document.getElementById('dm-stores-empty');
    var currentZone = 'Toutes';

    function filterCards() {
        var search = searchInput.value.toLowerCase().trim();
        var visibleCount = 0;

        cards.forEach(function(card) {
            var name = card.getAttribute('data-name') || '';
            var type = card.getAttribute('data-type') || '';
            var addr = card.getAttribute('data-address') || '';
            var zone = card.getAttribute('data-zone') || '';

            var zoneMatch = (currentZone === 'Toutes' || zone === currentZone);
            var searchMatch = (!search ||
                name.indexOf(search) !== -1 ||
                addr.indexOf(search) !== -1 ||
                type.indexOf(search) !== -1
            );

            if (zoneMatch && searchMatch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', filterCards);

    zoneBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            zoneBtns.forEach(function(b) { b.classList.remove('is-active'); });
            this.classList.add('is-active');
            currentZone = this.getAttribute('data-zone');
            filterCards();
        });
    });

    // Reveal animation
    var revealEls = document.querySelectorAll('.reveal-el');
    if (!('IntersectionObserver' in window)) {
        revealEls.forEach(function(el) { el.classList.add('is-visible'); });
    } else {
        var obs = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        revealEls.forEach(function(el) { obs.observe(el); });
    }
})();
</script>

<?php get_footer(); ?>
