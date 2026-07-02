<?php
/**
 * Template Name: Services
 *
 * Page Nos Services — Délices de la Mer.
 * Reproduit le prototype Services.jsx :
 *   - Hero navy avec label, titre, description
 *   - Sections de services alternées (image gauche/droite)
 *   - Chaque section : icône, sous-titre, titre, description, features, CTA WhatsApp
 *   - Section contact pleine largeur avec wave
 * Données dynamiques via repeater (admin : Nos Services).
 */

if (!defined('ABSPATH')) exit;

get_header();

$services = dm_get_services_sections();

// Image hero : bannière centralisée depuis la base de données
$hero_image = function_exists('dm_get_banner_image') ? dm_get_banner_image() : '';
if (empty($hero_image)) {
    $hero_image = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
}
?>

<!-- Hero -->
<div class="dm-services-hero"<?php echo !empty($hero_image) ? ' style="background-image:url(' . esc_url($hero_image) . ');"' : ''; ?>>
    <div class="dm-services-hero-overlay"></div>
    <div class="dm-services-hero-content">
        <p class="dm-services-hero-label">Notre expertise</p>
        <h1 class="dm-services-hero-title">Nos Services</h1>
        <p class="dm-services-hero-desc">De l'événementiel à la gestion de cantines en passant par la vente de produits traiteur, nous mettons notre savoir-faire au service de vos besoins.</p>
    </div>
</div>

<!-- Carte flottante — chevauche le hero (pattern pages existantes) -->
<div class="dm-services-overlap">
    <div class="dm-services-overlap-inner">
        <div class="dm-services-overlap-count">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
            <span class="dm-services-overlap-num"><?php echo esc_html(count($services)); ?></span>
            <span class="dm-services-overlap-label">Services proposés</span>
        </div>
        <div class="dm-services-overlap-pills">
            <?php foreach ($services as $i => $service) : ?>
            <a href="#service-<?php echo esc_attr($i); ?>" class="dm-services-overlap-pill">
                <?php echo dm_get_icon_svg($service['icon'] ?? 'fish'); ?>
                <span><?php echo esc_html($service['title'] ?? ''); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Sections de services -->
<div class="dm-services-body">
    <?php foreach ($services as $i => $service) :
        $icon     = $service['icon'] ?? 'fish';
        $title    = $service['title'] ?? '';
        $subtitle = $service['subtitle'] ?? '';
        $desc     = $service['description'] ?? '';
        $image    = $service['image'] ?? '';
        $features = $service['features'] ?? array();
        $reverse  = ($i % 2 === 1);
    ?>
    <section class="dm-service-section reveal-el" id="service-<?php echo esc_attr($i); ?>">
        <div class="dm-container">
            <div class="dm-service-grid<?php echo $reverse ? ' dm-service-grid-reverse' : ''; ?>">
                <!-- Image -->
                <div class="dm-service-image-wrap<?php echo $reverse ? ' dm-service-image-left' : ''; ?>">
                    <div class="dm-service-image-card">
                        <?php if (!empty($image)) : ?>
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" />
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Content -->
                <div class="dm-service-content<?php echo $reverse ? ' dm-service-content-right' : ''; ?>">
                    <div class="dm-service-icon">
                        <?php echo dm_get_icon_svg($icon); ?>
                    </div>
                    <p class="dm-service-subtitle"><?php echo esc_html($subtitle); ?></p>
                    <h2 class="dm-service-title"><?php echo esc_html($title); ?></h2>
                    <p class="dm-service-desc"><?php echo esc_html($desc); ?></p>
                    <?php if (!empty($features)) : ?>
                    <ul class="dm-service-features">
                        <?php foreach ($features as $feature) : ?>
                        <li class="dm-service-feature">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <span><?php echo esc_html($feature); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-service-cta">
                        Demander un devis
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php endforeach; ?>
</div>

<!-- Section Contact — pleine largeur avec wave -->
<section class="dm-services-contact-section">
    <div class="dm-services-contact-wave">
        <svg viewBox="0 0 1200 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C300,80 600,0 900,40 C1050,60 1150,30 1200,40 L1200,0 L0,0 Z" fill="#ffffff"></path>
        </svg>
    </div>
    <div class="dm-services-contact-inner">
        <div class="dm-services-contact-label">Besoin d'un service sur-mesure ?</div>
        <h2 class="dm-services-contact-title">Contactez-nous pour <span class="accent-orange">discuter de votre projet</span></h2>
        <p class="dm-services-contact-desc">Notre équipe est à votre écoute pour étudier vos besoins spécifiques et vous proposer une solution adaptée.</p>
        <div class="dm-services-contact-options">
            <a href="tel:<?php echo esc_attr(dm_get_phone_tel()); ?>" class="dm-services-contact-option">
                <div class="dm-services-contact-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                </div>
                <div class="dm-services-contact-text">
                    <span class="dm-services-contact-name">Téléphone</span>
                    <span class="dm-services-contact-value"><?php echo esc_html(dm_get_phone()); ?></span>
                </div>
            </a>
            <a href="mailto:<?php echo esc_attr(dm_get_email()); ?>?subject=<?php echo esc_attr(rawurlencode('Demande de service — Délices de la Mer')); ?>" class="dm-services-contact-option">
                <div class="dm-services-contact-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                <div class="dm-services-contact-text">
                    <span class="dm-services-contact-name">Email</span>
                    <span class="dm-services-contact-value"><?php echo esc_html(dm_get_email()); ?></span>
                </div>
            </a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-services-contact-option dm-services-contact-option-wa">
                <div class="dm-services-contact-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <div class="dm-services-contact-text">
                    <span class="dm-services-contact-name">WhatsApp</span>
                    <span class="dm-services-contact-value"><?php echo esc_html(dm_get_phone()); ?></span>
                </div>
            </a>
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
