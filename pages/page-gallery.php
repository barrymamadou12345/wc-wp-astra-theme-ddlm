<?php
/**
 * Template Name: Galerie
 *
 * Page Galerie — Délices de la Mer.
 *   - Banner par défaut de la page (page-banner)
 *   - Carte flottante chevauchant le banner (-3rem)
 *     - Ligne 1 : Tabs (Images / Vidéos)
 *     - Ligne 2 : Filtres dynamiques (catégories + années du tab actif)
 *   - Grille d'images indépendantes (chaque image garde son ratio)
 *   - Hover image : l'image survolée s'agrandit, les autres rétrécissent
 *   - Vidéos lisibles directement sur la carte + bouton agrandir
 *   - Likes / Dislikes / Hearts avec animations
 *   - Données dynamiques via repeater (admin : Galerie)
 */

if (!defined('ABSPATH')) exit;

get_header();

$items = dm_get_gallery_items_reversed();
$images = array();
$videos = array();
$image_cats = array();
$video_cats = array();
$image_years = array();
$video_years = array();

foreach ($items as $original_index => $item) {
    $type = $item['type'] ?? 'image';
    $cat = $item['category'] ?? 'Général';
    if (empty($cat)) $cat = 'Général';
    $date = $item['date_added'] ?? '';
    $year = $date ? substr($date, 0, 4) : '';

    // Compteurs réels depuis l'option séparée (persistante, visible par tous)
    $rx = dm_get_item_reactions($original_index);
    $item['likes']    = $rx['likes'];
    $item['dislikes'] = $rx['dislikes'];
    $item['hearts']   = $rx['hearts'];

    if ($type === 'video') {
        $videos[] = array('data' => $item, 'index' => $original_index);
        if (!in_array($cat, $video_cats)) $video_cats[] = $cat;
        if ($year && !in_array($year, $video_years)) $video_years[] = $year;
    } else {
        $images[] = array('data' => $item, 'index' => $original_index);
        if (!in_array($cat, $image_cats)) $image_cats[] = $cat;
        if ($year && !in_array($year, $image_years)) $image_years[] = $year;
    }
}
rsort($image_years);
rsort($video_years);

$nonce = wp_create_nonce('dm-gallery-nonce');
$ajax_url = admin_url('admin-ajax.php');
?>

<!-- Carte flottante — chevauche le banner de page -->
<div class="dm-gallery-overlap">
    <div class="dm-gallery-overlap-inner">
        <!-- Ligne 1 : Tabs -->
        <div class="dm-gallery-tabs">
            <button type="button" class="dm-gallery-tab is-active" data-tab="images">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                <span>Images</span>
                <span class="dm-gallery-tab-count"><?php echo count($images); ?></span>
            </button>
            <button type="button" class="dm-gallery-tab" data-tab="videos">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                <span>Vidéos</span>
                <span class="dm-gallery-tab-count"><?php echo count($videos); ?></span>
            </button>
        </div>
        <!-- Ligne 2 : Filtres dynamiques -->
        <div class="dm-gallery-filters" id="dm-gallery-filters-images">
            <div class="dm-gallery-filters-scroll">
                <button type="button" class="dm-gallery-filter is-active" data-filter="all">Tous</button>
                <button type="button" class="dm-gallery-filter dm-gallery-filter-likes" data-filter="most-liked">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                    Plus de likes
                </button>
                <span class="dm-gallery-filter-sep"></span>
                <?php foreach ($image_cats as $cat) : ?>
                    <button type="button" class="dm-gallery-filter" data-filter="cat:<?php echo esc_attr($cat); ?>"><?php echo esc_html($cat); ?></button>
                <?php endforeach; ?>
                <?php if (!empty($image_years)) : ?>
                <span class="dm-gallery-filter-sep"></span>
                <?php foreach ($image_years as $yr) : ?>
                    <button type="button" class="dm-gallery-filter dm-gallery-filter-year" data-filter="year:<?php echo esc_attr($yr); ?>"><?php echo esc_html($yr); ?></button>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="dm-gallery-filters is-hidden" id="dm-gallery-filters-videos">
            <div class="dm-gallery-filters-scroll">
                <button type="button" class="dm-gallery-filter is-active" data-filter="all">Tous</button>
                <button type="button" class="dm-gallery-filter dm-gallery-filter-likes" data-filter="most-liked">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                    Plus de likes
                </button>
                <span class="dm-gallery-filter-sep"></span>
                <?php foreach ($video_cats as $cat) : ?>
                    <button type="button" class="dm-gallery-filter" data-filter="cat:<?php echo esc_attr($cat); ?>"><?php echo esc_html($cat); ?></button>
                <?php endforeach; ?>
                <?php if (!empty($video_years)) : ?>
                <span class="dm-gallery-filter-sep"></span>
                <?php foreach ($video_years as $yr) : ?>
                    <button type="button" class="dm-gallery-filter dm-gallery-filter-year" data-filter="year:<?php echo esc_attr($yr); ?>"><?php echo esc_html($yr); ?></button>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Contenu galerie -->
<div class="dm-gallery-body">
    <div class="dm-container">
        <!-- Grille Images -->
        <div class="dm-gallery-grid-wrap is-active" id="dm-gallery-grid-images">
            <?php if (empty($images)) : ?>
                <p class="dm-gallery-empty">Aucune image pour le moment.</p>
            <?php else : ?>
                <div class="dm-gallery-grid">
                    <?php foreach ($images as $img) :
                        $d = $img['data'];
                        $idx = $img['index'];
                        $cat = $d['category'] ?? 'Général';
                        if (empty($cat)) $cat = 'Général';
                        $date = $d['date_added'] ?? '';
                        $year = $date ? substr($date, 0, 4) : '';
                        $total_reactions = intval($d['likes'] ?? 0) + intval($d['hearts'] ?? 0);
                    ?>
                    <div class="dm-gallery-item reveal-el" data-type="image" data-category="<?php echo esc_attr($cat); ?>" data-year="<?php echo esc_attr($year); ?>" data-likes="<?php echo esc_attr($total_reactions); ?>" data-index="<?php echo esc_attr($idx); ?>">
                        <div class="dm-gallery-item-media" data-image-url="<?php echo esc_attr($d['url'] ?? ''); ?>">
                            <?php if (!empty($d['url'])) : ?>
                                <img src="<?php echo esc_url($d['url']); ?>" alt="<?php echo esc_attr($d['title'] ?? ''); ?>" loading="lazy" />
                            <?php endif; ?>
                            <?php if (!empty($d['url'])) : ?>
                            <button type="button" class="dm-gallery-item-expand" aria-label="Agrandir l'image">+</button>
                            <?php endif; ?>
                            <div class="dm-gallery-item-overlay">
                                <?php if (!empty($d['title'])) : ?>
                                    <h3 class="dm-gallery-item-title"><?php echo esc_html($d['title']); ?></h3>
                                <?php endif; ?>
                                <?php if (!empty($d['description'])) : ?>
                                    <p class="dm-gallery-item-desc"><?php echo esc_html($d['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="dm-gallery-item-footer">
                            <div class="dm-gallery-item-reactions">
                                <button type="button" class="dm-react-btn dm-react-like" data-reaction="likes" data-index="<?php echo esc_attr($idx); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                                    <span class="dm-react-count"><?php echo esc_html(dm_format_count($d['likes'] ?? 0)); ?></span>
                                </button>
                                <button type="button" class="dm-react-btn dm-react-dislike" data-reaction="dislikes" data-index="<?php echo esc_attr($idx); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path></svg>
                                    <span class="dm-react-count"><?php echo esc_html(dm_format_count($d['dislikes'] ?? 0)); ?></span>
                                </button>
                                <button type="button" class="dm-react-btn dm-react-heart" data-reaction="hearts" data-index="<?php echo esc_attr($idx); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    <span class="dm-react-count"><?php echo esc_html(dm_format_count($d['hearts'] ?? 0)); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Grille Vidéos -->
        <div class="dm-gallery-grid-wrap is-hidden" id="dm-gallery-grid-videos">
            <?php if (empty($videos)) : ?>
                <p class="dm-gallery-empty">Aucune vidéo pour le moment.</p>
            <?php else : ?>
                <div class="dm-gallery-videos-grid">
                    <?php foreach ($videos as $vid) :
                        $d = $vid['data'];
                        $idx = $vid['index'];
                        $cat = $d['category'] ?? 'Général';
                        if (empty($cat)) $cat = 'Général';
                        $date = $d['date_added'] ?? '';
                        $year = $date ? substr($date, 0, 4) : '';
                        $total_reactions = intval($d['likes'] ?? 0) + intval($d['hearts'] ?? 0);
                        $thumb = !empty($d['thumbnail']) ? $d['thumbnail'] : '';
                        $video_url = $d['url'] ?? '';
                        // Detect YouTube/Vimeo for embed
                        $embed_url = '';
                        if (strpos($video_url, 'youtube.com/watch') !== false) {
                            $vid_id = '';
                            parse_str(parse_url($video_url, PHP_URL_QUERY), $params);
                            $vid_id = $params['v'] ?? '';
                            if ($vid_id) $embed_url = 'https://www.youtube.com/embed/' . $vid_id . '?autoplay=1';
                        } elseif (strpos($video_url, 'youtu.be/') !== false) {
                            $vid_id = substr(parse_url($video_url, PHP_URL_PATH), 1);
                            if ($vid_id) $embed_url = 'https://www.youtube.com/embed/' . $vid_id . '?autoplay=1';
                        } elseif (strpos($video_url, 'vimeo.com/') !== false) {
                            $vid_id = (int) substr(parse_url($video_url, PHP_URL_PATH), 1);
                            if ($vid_id) $embed_url = 'https://player.vimeo.com/video/' . $vid_id . '?autoplay=1';
                        } else {
                            $embed_url = $video_url;
                        }
                    ?>
                    <div class="dm-gallery-video-item reveal-el" data-type="video" data-category="<?php echo esc_attr($cat); ?>" data-year="<?php echo esc_attr($year); ?>" data-likes="<?php echo esc_attr($total_reactions); ?>" data-index="<?php echo esc_attr($idx); ?>">
                        <div class="dm-gallery-video-thumb" data-embed="<?php echo esc_attr($embed_url); ?>" data-video-url="<?php echo esc_attr($video_url); ?>">
                            <?php
                            $is_embed = (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false || strpos($video_url, 'vimeo.com') !== false);
                            if ($is_embed && !empty($embed_url)) :
                                // YouTube/Vimeo: render iframe directly (without autoplay)
                                $embed_no_autoplay = str_replace('autoplay=1', 'autoplay=0', $embed_url);
                            ?>
                                <iframe class="dm-gallery-video-inline" src="<?php echo esc_attr($embed_no_autoplay); ?>" allow="fullscreen; encrypted-media" allowfullscreen frameborder="0"></iframe>
                            <?php elseif (!empty($video_url)) :
                                // Direct video file: render video tag with native controls
                            ?>
                                <video class="dm-gallery-video-inline" src="<?php echo esc_attr($video_url); ?>" controls preload="metadata" playsinline></video>
                            <?php else : ?>
                                <div class="dm-gallery-video-nothumb">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                </div>
                            <?php endif; ?>
                            <button type="button" class="dm-gallery-video-expand" aria-label="Agrandir">+</button>
                        </div>
                        <div class="dm-gallery-video-info">
                            <?php if (!empty($d['title'])) : ?>
                                <h3 class="dm-gallery-video-title"><?php echo esc_html($d['title']); ?></h3>
                            <?php endif; ?>
                            <?php if (!empty($d['description'])) : ?>
                                <p class="dm-gallery-video-desc"><?php echo esc_html($d['description']); ?></p>
                            <?php endif; ?>
                            <div class="dm-gallery-item-reactions">
                                <button type="button" class="dm-react-btn dm-react-like" data-reaction="likes" data-index="<?php echo esc_attr($idx); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                                    <span class="dm-react-count"><?php echo esc_html(dm_format_count($d['likes'] ?? 0)); ?></span>
                                </button>
                                <button type="button" class="dm-react-btn dm-react-dislike" data-reaction="dislikes" data-index="<?php echo esc_attr($idx); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path></svg>
                                    <span class="dm-react-count"><?php echo esc_html(dm_format_count($d['dislikes'] ?? 0)); ?></span>
                                </button>
                                <button type="button" class="dm-react-btn dm-react-heart" data-reaction="hearts" data-index="<?php echo esc_attr($idx); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    <span class="dm-react-count"><?php echo esc_html(dm_format_count($d['hearts'] ?? 0)); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Lightbox vidéo -->
<div class="dm-gallery-lightbox" id="dm-gallery-lightbox">
    <div class="dm-gallery-lightbox-backdrop"></div>
    <div class="dm-gallery-lightbox-container">
        <button type="button" class="dm-gallery-lightbox-close" aria-label="Fermer">&times;</button>
        <div class="dm-gallery-lightbox-content"></div>
    </div>
</div>

<!-- Floating reaction animation layer -->
<div class="dm-gallery-float-anim" id="dm-gallery-float-anim"></div>

<script>
window.dmGalleryData = {
    nonce: '<?php echo esc_js($nonce); ?>',
    ajaxUrl: '<?php echo esc_js($ajax_url); ?>'
};
</script>

<?php get_footer(); ?>
