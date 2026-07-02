<?php
/**
 * Bouton flottant WhatsApp — carte dépliable + JS.
 *
 * Affiché sur toutes les pages via wp_footer.
 * Utilise les helpers dm_get_whatsapp_number() et dm_wa_link().
 */

if (! defined('ABSPATH')) {
    exit;
}

function dm_floating_whatsapp()
{
    $wa_number = dm_get_whatsapp_number();
    ?>
    <div class="dm-wa-float" id="dm-wa-float">
        <!-- Carte dépliable -->
        <div class="dm-wa-card" id="dm-wa-card" style="display:none;">
            <div class="dm-wa-card-header">
                <div class="dm-wa-card-avatar">
                    <svg class="dm-wa-icon-sm" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </div>
                <div>
                    <div class="dm-wa-card-name">Délices de la Mer</div>
                    <div class="dm-wa-card-status">
                        <span class="dm-wa-pulse-dot"></span>
                        <span>En ligne maintenant</span>
                    </div>
                </div>
            </div>
            <p class="dm-wa-card-msg">Bonjour 👋 Comment pouvons-nous vous aider ?</p>
            <div class="dm-wa-card-actions">
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-wa-card-link dm-wa-card-link--contact">✉️ Laisser un message</a>
                <a href="<?php echo esc_url(dm_wa_link('Bonjour, je souhaite connaître le statut de ma commande.')); ?>" target="_blank" rel="noreferrer" class="dm-wa-card-link">📦 Statut commande</a>
                <a href="<?php echo esc_url(dm_wa_link('Bonjour, je suis intéressé par un partenariat B2B.')); ?>" target="_blank" rel="noreferrer" class="dm-wa-card-link">💼 Demande B2B</a>
                <a href="<?php echo esc_url(dm_wa_link("Bonjour, j'aimerais un conseil pour préparer vos produits.")); ?>" target="_blank" rel="noreferrer" class="dm-wa-card-link">🍳 Conseil culinaire</a>
            </div>
        </div>

        <!-- Bouton principal -->
        <button type="button" id="dm-wa-btn" class="dm-wa-btn" aria-label="WhatsApp">
            <svg class="dm-wa-icon dm-wa-icon-wa" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            <svg class="dm-wa-icon dm-wa-icon-close" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24" style="display:none;">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
            <span class="dm-wa-chat-badge">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
            </span>
        </button>
    </div>

    <script>
    (function() {
        var btn = document.getElementById('dm-wa-btn');
        var card = document.getElementById('dm-wa-card');
        var iconWa = btn ? btn.querySelector('.dm-wa-icon-wa') : null;
        var iconClose = btn ? btn.querySelector('.dm-wa-icon-close') : null;
        if (!btn || !card) return;
        btn.addEventListener('click', function() {
            var open = card.style.display === 'none';
            card.style.display = open ? 'block' : 'none';
            if (iconWa) iconWa.style.display = open ? 'none' : 'block';
            if (iconClose) iconClose.style.display = open ? 'block' : 'none';
        });
    })();
    </script>
    <?php
}
add_action('wp_footer', 'dm_floating_whatsapp');
