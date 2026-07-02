<?php
/**
 * Template Name: Témoignages
 *
 * Page Témoignages — Délices de la Mer.
 * Affiche tous les témoignages clients avec filtres (tri par date, note).
 * Design moderne responsive — pattern page-stores.
 */

if (!defined('ABSPATH')) exit;

get_header();

$testimonials = dm_get_testimonials();
if (!is_array($testimonials)) $testimonials = array();

// Filtrer les témoignages vides
$valid_testimonials = array();
foreach ($testimonials as $tm) {
    $name = $tm['name'] ?? '';
    $text = $tm['text'] ?? '';
    if (!empty($name) && !empty($text)) {
        $valid_testimonials[] = $tm;
    }
}

// Stats
$total_tm = count($valid_testimonials);
$avg_rating = 0;
if ($total_tm > 0) {
    $sum = 0;
    foreach ($valid_testimonials as $tm) {
        $sum += intval($tm['rating'] ?? 5);
    }
    $avg_rating = round($sum / $total_tm, 1);
}
$five_star = 0;
foreach ($valid_testimonials as $tm) {
    if (intval($tm['rating'] ?? 5) === 5) $five_star++;
}

// Image hero : bannière centralisée depuis la base de données
$hero_image = function_exists('dm_get_banner_image') ? dm_get_banner_image() : '';
if (empty($hero_image)) {
    $hero_image = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
}
?>

<!-- Hero -->
<div class="dm-tm-hero"<?php echo !empty($hero_image) ? ' style="background-image:url(' . esc_url($hero_image) . ');"' : ''; ?>>
    <div class="dm-tm-hero-overlay"></div>
    <div class="dm-tm-hero-content">
        <p class="dm-tm-hero-label">Témoignages</p>
        <h1 class="dm-tm-hero-title">Ce que disent <span class="accent-orange">nos clients</span></h1>
        <p class="dm-tm-hero-desc">La satisfaction de nos clients et partenaires est notre plus belle récompense. Découvrez leurs expériences.</p>
    </div>
</div>

<!-- Barre de filtres — carte flottante qui chevauche le hero -->
<div class="dm-tm-filters">
    <div class="dm-tm-filters-inner">
        <!-- Ligne 1 : Compteur + Recherche -->
        <div class="dm-tm-filter-row-top">
            <div class="dm-tm-filter-count">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                <span class="dm-tm-filter-num"><?php echo esc_html($total_tm); ?></span>
                <span class="dm-tm-filter-label">Témoignages</span>
            </div>
            <div class="dm-tm-filter-search">
                <svg class="dm-tm-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                <input type="text" id="dm-tm-search" placeholder="Rechercher un témoignage..." />
            </div>
        </div>
        <!-- Ligne 2 : Tri -->
        <div class="dm-tm-filter-row-sort">
            <span class="dm-tm-sort-label">Trier par :</span>
            <div class="dm-tm-sort-pills">
                <button class="dm-tm-sort-pill is-active" data-sort="recent">Plus récents</button>
                <button class="dm-tm-sort-pill" data-sort="rating">Meilleures notes</button>
                <button class="dm-tm-sort-pill" data-sort="name">Alphabétique</button>
            </div>
        </div>
    </div>
</div>

<!-- Contenu -->
<div class="dm-tm-body">
    <div class="dm-tm-container">

        <!-- Grille des témoignages -->
        <div class="dm-tm-grid" id="dm-tm-grid">
            <?php foreach ($valid_testimonials as $i => $tm) :
                $tm_name = $tm['name'] ?? '';
                $tm_role = $tm['role'] ?? '';
                $tm_location = $tm['location'] ?? '';
                $tm_text = $tm['text'] ?? '';
                $tm_rating = intval($tm['rating'] ?? 5);
                $tm_photo = $tm['photo'] ?? '';
                $tm_date = $tm['date'] ?? '';
            ?>
            <div class="dm-tm-card reveal-el"
                 data-name="<?php echo esc_attr(strtolower($tm_name)); ?>"
                 data-text="<?php echo esc_attr(strtolower($tm_text)); ?>"
                 data-role="<?php echo esc_attr(strtolower($tm_role)); ?>"
                 data-rating="<?php echo esc_attr($tm_rating); ?>"
                 data-date="<?php echo esc_attr($tm_date); ?>"
                 data-index="<?php echo esc_attr($i); ?>">
                <div class="dm-tm-card-quote-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="1.5" opacity="0.2"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/></svg>
                </div>
                <div class="dm-tm-card-stars">
                    <?php for ($s = 0; $s < 5; $s++) : ?>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="<?php echo $s < $tm_rating ? 'var(--orange)' : 'none'; ?>" stroke="<?php echo $s < $tm_rating ? 'var(--orange)' : 'var(--border)'; ?>" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <?php endfor; ?>
                </div>
                <p class="dm-tm-card-text">"<?php echo esc_html($tm_text); ?>"</p>
                <div class="dm-tm-card-author">
                    <?php if (!empty($tm_photo)) : ?>
                    <div class="dm-tm-card-avatar" style="background:none;">
                        <img src="<?php echo esc_url($tm_photo); ?>" alt="<?php echo esc_attr($tm_name); ?>" style="width:100%;height:100%;border-radius:50%;object-fit:cover;" />
                    </div>
                    <?php else : ?>
                    <div class="dm-tm-card-avatar"><?php echo esc_html(strtoupper(substr($tm_name, 0, 1))); ?></div>
                    <?php endif; ?>
                    <div class="dm-tm-card-info">
                        <div class="dm-tm-card-name"><?php echo esc_html($tm_name); ?></div>
                        <div class="dm-tm-card-role"><?php echo esc_html($tm_role); ?><?php echo $tm_location ? ' · ' . esc_html($tm_location) : ''; ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty state -->
        <div class="dm-tm-empty" id="dm-tm-empty" style="display:none;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
            <p>Aucun témoignage trouvé pour ces critères.</p>
        </div>

    </div>
</div>

<!-- Section Stats -->
<section class="dm-section bg-white">
    <div class="dm-container">
        <div class="section-head">
            <p class="section-label">Satisfaction</p>
            <h2 class="section-title">La parole de <span class="accent-orange">nos clients</span></h2>
            <div class="divider-orange"></div>
            <p class="section-subtitle">Des chiffres qui reflètent notre engagement quotidien.</p>
        </div>
        <div class="dm-tm-stats-grid">
            <div class="dm-tm-stat-card reveal-el">
                <div class="dm-tm-stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div class="dm-tm-stat-value"><?php echo esc_html($total_tm); ?></div>
                <div class="dm-tm-stat-label">Témoignages</div>
            </div>
            <div class="dm-tm-stat-card reveal-el">
                <div class="dm-tm-stat-icon dm-tm-stat-icon--orange">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="var(--orange)" stroke="var(--orange)" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <div class="dm-tm-stat-value"><?php echo esc_html(number_format($avg_rating, 1)); ?>/5</div>
                <div class="dm-tm-stat-label">Note moyenne</div>
            </div>
            <div class="dm-tm-stat-card reveal-el">
                <div class="dm-tm-stat-icon dm-tm-stat-icon--green">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="dm-tm-stat-value"><?php echo esc_html($five_star); ?></div>
                <div class="dm-tm-stat-label">Notes 5 étoiles</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA : Partagez votre expérience -->
<section class="dm-tm-cta-section">
    <div class="dm-tm-cta-wave">
        <svg viewBox="0 0 1200 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C300,80 600,0 900,40 C1050,60 1150,30 1200,40 L1200,0 L0,0 Z" fill="var(--cream)"></path>
        </svg>
    </div>
    <div class="dm-tm-cta-inner">
        <div class="dm-tm-cta-label">Votre avis compte</div>
        <h2 class="dm-tm-cta-title">Partagez votre <span class="accent-orange">expérience</span></h2>
        <p class="dm-tm-cta-desc">Vous avez goûté nos produits ? Nous serions ravis d'entendre votre témoignage. Contactez-nous via le canal de votre choix.</p>
        <div class="dm-tm-cta-actions">
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-tm-cta-btn dm-tm-cta-btn--wa">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Partager sur WhatsApp
            </a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-tm-cta-btn dm-tm-cta-btn--outline">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                Nous écrire
            </a>
        </div>
    </div>
</section>

<script>
(function() {
    var searchInput = document.getElementById('dm-tm-search');
    var sortPills = document.querySelectorAll('.dm-tm-sort-pill');
    var cards = document.querySelectorAll('.dm-tm-card');
    var emptyState = document.getElementById('dm-tm-empty');
    var grid = document.getElementById('dm-tm-grid');
    var currentSort = 'recent';
    var cardArray = Array.from(cards);

    function filterAndSort() {
        var search = searchInput.value.toLowerCase().trim();
        var visibleCount = 0;

        // Filter
        cardArray.forEach(function(card) {
            var name = card.getAttribute('data-name') || '';
            var text = card.getAttribute('data-text') || '';
            var role = card.getAttribute('data-role') || '';
            var searchMatch = (!search ||
                name.indexOf(search) !== -1 ||
                text.indexOf(search) !== -1 ||
                role.indexOf(search) !== -1
            );
            if (searchMatch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Sort
        cardArray.sort(function(a, b) {
            if (currentSort === 'rating') {
                return parseInt(b.getAttribute('data-rating')) - parseInt(a.getAttribute('data-rating'));
            } else if (currentSort === 'name') {
                return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
            } else {
                // recent: by date desc, fallback to index
                var da = a.getAttribute('data-date') || '';
                var db = b.getAttribute('data-date') || '';
                if (da && db) {
                    return db.localeCompare(da);
                }
                return parseInt(a.getAttribute('data-index')) - parseInt(b.getAttribute('data-index'));
            }
        });

        // Re-append in sorted order
        cardArray.forEach(function(card) {
            grid.appendChild(card);
        });

        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', filterAndSort);

    sortPills.forEach(function(pill) {
        pill.addEventListener('click', function() {
            sortPills.forEach(function(p) { p.classList.remove('is-active'); });
            this.classList.add('is-active');
            currentSort = this.getAttribute('data-sort');
            filterAndSort();
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
