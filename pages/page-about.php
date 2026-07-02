<?php
/**
 * Template Name: À Propos
 *
 * Page À Propos — Délices de la Mer.
 * Reproduit le prototype About.jsx :
 *   - Hero full-bleed avec image de fond + overlay
 *   - Carte flottante (overlap) avec chiffres clés
 *   - Section histoire avec slogan
 *   - Section valeurs (cards dynamiques)
 *   - Section chiffres clés (navy)
 *   - Timeline alternée gauche/droite avec support média (image/vidéo)
 *   - Section contact avec wave
 * Données dynamiques via repeaters (admin : À Propos).
 */

if (!defined('ABSPATH')) exit;

get_header();

$values   = dm_get_about_values();
$stats    = dm_get_about_stats();
$timeline = dm_get_about_timeline();
$team     = dm_get_about_team();
$story    = get_option('dm_about_story', '');
$slogan   = get_option('dm_about_slogan', '');

// Image hero : bannière centralisée depuis la base de données
$hero_img = function_exists('dm_get_banner_image') ? dm_get_banner_image() : '';
if (empty($hero_img)) {
    $hero_img = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
}

// Texte histoire par défaut
if (empty($story)) {
    $story = "Les Délices de la Mer est née en 2016 d'une conviction simple : le Sénégal mérite des snacks et apéritifs de qualité supérieure, fabriqués avec des ingrédients frais et selon des recettes qui respectent les saveurs authentiques de notre terroir.\n\nFondée à Dakar, notre entreprise a grandi avec la confiance de nos clients — des familles sénégalaises aux hôtels 5 étoiles, en passant par les grandes enseignes de distribution. Aujourd'hui, avec plus de 40 employés passionnés, nous fabriquons chaque jour une gamme variée de snacks croustillants et de produits fumés d'exception.";
}

if (empty($slogan)) {
    $slogan = 'Une symbiose de saveurs';
}
?>

<!-- Hero -->
<div class="dm-about-hero" style="background-image:url('<?php echo esc_url($hero_img); ?>');">
    <div class="dm-about-hero-overlay"></div>
    <div class="dm-about-hero-content">
        <p class="dm-about-hero-label">Qui sommes-nous</p>
        <h1 class="dm-about-hero-title">Notre Histoire</h1>
    </div>
</div>

<!-- Carte flottante — chiffres clés qui chevauchent le hero -->
<div class="dm-about-overlap">
    <div class="dm-about-overlap-inner">
        <?php foreach ($stats as $stat) : ?>
        <div class="dm-about-overlap-stat">
            <span class="dm-about-overlap-num"><?php echo esc_html($stat['value']); ?></span>
            <span class="dm-about-overlap-label"><?php echo esc_html($stat['label']); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Histoire -->
<section class="dm-about-story-section">
    <div class="dm-container">
        <div class="dm-about-story reveal-el">
            <p class="dm-about-story-label">Notre histoire</p>
            <h2 class="dm-about-story-title">Depuis 2016, une passion <span class="accent-orange">pour le goût</span></h2>
            <div class="dm-about-story-text">
                <?php echo wpautop(esc_html($story)); ?>
            </div>
            <?php if (!empty($slogan)) : ?>
            <p class="dm-about-story-slogan">« <?php echo esc_html($slogan); ?> »</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Valeurs -->
<section class="dm-about-values-section">
    <div class="dm-container">
        <div class="dm-about-section-head reveal-el">
            <p class="dm-about-section-label">Ce qui nous anime</p>
            <h2 class="dm-about-section-title">Nos <span class="accent-orange">Valeurs</span></h2>
        </div>
        <div class="dm-about-values-grid">
            <?php foreach ($values as $i => $val) : ?>
            <div class="dm-about-value-card reveal-el" style="transition-delay:<?php echo esc_attr($i * 80); ?>ms;">
                <div class="dm-about-value-icon">
                    <?php echo dm_get_icon_svg($val['icon'] ?? 'fish'); ?>
                </div>
                <h3 class="dm-about-value-title"><?php echo esc_html($val['title']); ?></h3>
                <p class="dm-about-value-desc"><?php echo esc_html($val['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Chiffres clés — section navy -->
<section class="dm-about-stats-section">
    <div class="dm-container">
        <div class="dm-about-section-head reveal-el">
            <p class="dm-about-section-label">En chiffres</p>
            <h2 class="dm-about-section-title-white">Délices de la Mer <span class="accent-orange">en bref</span></h2>
        </div>
        <div class="dm-about-stats-grid">
            <?php foreach ($stats as $stat) : ?>
            <div class="dm-about-stat-item reveal-el">
                <div class="dm-about-stat-value"><?php echo esc_html($stat['value']); ?></div>
                <div class="dm-about-stat-label"><?php echo esc_html($stat['label']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Timeline — chronologie alternée avec média -->
<section class="dm-about-timeline-section">
    <div class="dm-container">
        <div class="dm-about-section-head reveal-el">
            <p class="dm-about-section-label">Notre parcours</p>
            <h2 class="dm-about-section-title">Étapes <span class="accent-orange">Clés</span></h2>
        </div>

        <div class="dm-about-timeline">
            <div class="dm-about-timeline-line"></div>
            <?php foreach ($timeline as $idx => $item) :
                $side = ($idx % 2 === 0) ? 'left' : 'right';
                $media_url = $item['media'] ?? '';
                $is_youtube = (strpos($media_url, 'youtube') !== false || strpos($media_url, 'youtu.be') !== false);
                $is_vimeo   = (strpos($media_url, 'vimeo') !== false);
                $is_video   = (preg_match('/\.(mp4|webm|ogg|mov)(\?.*)?$/i', $media_url));
            ?>
            <div class="dm-about-timeline-item reveal-el<?php echo $side === 'right' ? ' is-right' : ' is-left'; ?>" style="transition-delay:<?php echo esc_attr($idx * 100); ?>ms;">
                <div class="dm-about-timeline-card">
                    <span class="dm-about-timeline-year"><?php echo esc_html($item['year']); ?></span>
                    <h3 class="dm-about-timeline-card-title"><?php echo esc_html($item['title']); ?></h3>
                    <p class="dm-about-timeline-card-desc"><?php echo esc_html($item['desc']); ?></p>
                    <?php if (!empty($media_url)) : ?>
                        <?php if ($is_youtube || $is_vimeo) : ?>
                        <div class="dm-about-timeline-media">
                            <iframe src="<?php echo esc_url($media_url); ?>" width="100%" height="200" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <?php elseif ($is_video) : ?>
                        <div class="dm-about-timeline-media">
                            <video controls preload="metadata">
                                <source src="<?php echo esc_url($media_url); ?>" type="video/mp4" />
                            </video>
                        </div>
                        <?php else : ?>
                        <div class="dm-about-timeline-media">
                            <img src="<?php echo esc_url($media_url); ?>" alt="<?php echo esc_attr($item['title']); ?>" />
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="dm-about-timeline-dot"><span></span></div>
                <div class="dm-about-timeline-spacer"></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Équipe — cartes membres -->
<?php if (!empty($team)) : ?>
<section class="dm-about-team-section">
    <div class="dm-container">
        <div class="dm-about-section-head reveal-el">
            <p class="dm-about-section-label">Notre équipe</p>
            <h2 class="dm-about-section-title">Les visages <span class="accent-orange">derrière la marque</span></h2>
        </div>
        <div class="dm-about-team-grid">
            <?php foreach ($team as $i => $member) : ?>
            <div class="dm-about-team-card reveal-el" style="transition-delay:<?php echo esc_attr($i * 60); ?>ms;">
                <div class="dm-about-team-photo">
                    <?php if (!empty($member['photo'])) : ?>
                        <img src="<?php echo esc_url($member['photo']); ?>" alt="<?php echo esc_attr($member['name']); ?>" />
                    <?php else : ?>
                        <div class="dm-about-team-photo-placeholder">
                            <?php echo esc_html(mb_substr($member['name'] ?? '?', 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h3 class="dm-about-team-name"><?php echo esc_html($member['name']); ?></h3>
                <span class="dm-about-team-role"><?php echo esc_html($member['role']); ?></span>
                <?php if (!empty($member['desc'])) : ?>
                <p class="dm-about-team-desc"><?php echo esc_html($member['desc']); ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Section Contact — pleine largeur avec wave -->
<section class="dm-about-contact-section">
    <div class="dm-about-contact-wave">
        <svg viewBox="0 0 1200 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C300,80 600,0 900,40 C1050,60 1150,30 1200,40 L1200,0 L0,0 Z" fill="#ffffff"></path>
        </svg>
    </div>
    <div class="dm-about-contact-inner">
        <div class="dm-about-contact-label">Une question ?</div>
        <h2 class="dm-about-contact-title">Envie de travailler <span class="accent-orange">avec nous ?</span></h2>
        <p class="dm-about-contact-desc">Que vous soyez un hôtel, un restaurant ou un distributeur, contactez-nous pour un partenariat.</p>
        <div class="dm-about-contact-actions">
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn-orange">Nous contacter</a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn-whatsapp">WhatsApp</a>
        </div>
    </div>
</section>

<script>
(function() {
    var els = document.querySelectorAll('.reveal-el');
    if (!('IntersectionObserver' in window)) {
        els.forEach(function(el) { el.classList.add('is-visible'); });
        return;
    }
    var obs = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    els.forEach(function(el) { obs.observe(el); });
})();
</script>

<?php get_footer(); ?>
