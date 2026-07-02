<?php
/**
 * Template 404 — Page introuvable
 * Design system Délices de la Mer
 */

get_header();
?>

<div class="dm-404-content">
    <div class="dm-404-number">404</div>
    <div class="dm-404-icon">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
    </div>
    <h1>Oups ! Cette page est introuvable</h1>
    <p>La page que vous recherchez n'existe pas, a été déplacée ou n'est plus disponible.</p>
    <p>Nous vous invitons à retourner à l'accueil ou à parcourir notre catalogue.</p>
    <div class="dm-404-actions">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            Retour à l'accueil
        </a>
        <a href="<?php echo esc_url(home_url('/shop')); ?>" class="btn-outline">
            Voir le catalogue
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
    </div>
</div>

<?php get_footer(); ?>
