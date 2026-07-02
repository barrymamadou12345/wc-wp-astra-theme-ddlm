<?php
/**
 * Template Name: Contact
 *
 * Page Contact — Délices de la Mer.
 * Reproduit le prototype Contact.jsx :
 *   - Hero navy avec image de fond optionnelle
 *   - Grid 2 colonnes : formulaire (gauche) + sidebar (droite)
 *   - Sidebar : contact rapide (WhatsApp, Email, Téléphone, Adresse), horaires, carte
 *   - Formulaire : nom, email, téléphone, sujet, message + envoi via admin-post
 *   - Success state avec boutons WhatsApp et Email
 * Données dynamiques via options admin (Coordonnées).
 */

if (!defined('ABSPATH')) exit;

get_header();

$hero_img   = dm_get_contact_hero_image();
$hours      = dm_get_contact_hours();
$map_lat    = dm_get_contact_map_lat();
$map_lng    = dm_get_contact_map_lng();
$map_zoom   = dm_get_contact_map_zoom();
$contact_ok = isset($_GET['contact']) && $_GET['contact'] === 'success';

// URL OpenStreetMap embed avec marker
$osm_bbox_left   = $map_lng - 0.02;
$map_bbox_bottom = $map_lat - 0.02;
$osm_bbox_right  = $map_lng + 0.02;
$osm_bbox_top    = $map_lat + 0.02;
$osm_embed_url = 'https://www.openstreetmap.org/export/embed.html?bbox='
    . $osm_bbox_left . ',' . $map_bbox_bottom . ',' . $osm_bbox_right . ',' . $osm_bbox_top
    . '&layer=mapnik&marker=' . $map_lat . ',' . $map_lng;
$osm_full_url = 'https://www.openstreetmap.org/?mlat=' . $map_lat . '&mlon=' . $map_lng . '#map=' . $map_zoom . '/' . $map_lat . '/' . $map_lng;
?>

<!-- Hero -->
<div class="dm-contact-hero"<?php echo !empty($hero_img) ? ' style="background-image:url(' . esc_url($hero_img) . ');"' : ''; ?>>
    <div class="dm-contact-hero-overlay"></div>
    <div class="dm-contact-hero-content">
        <p class="dm-contact-hero-label">Parlons ensemble</p>
        <h1 class="dm-contact-hero-title">Contactez-<span class="accent-orange">nous</span></h1>
        <p class="dm-contact-hero-desc">Une question, une commande spéciale ou un partenariat ? Nous sommes à votre écoute.</p>
    </div>
</div>

<!-- Contenu -->
<div class="dm-contact-body">
    <div class="dm-contact-container">
        <div class="dm-contact-grid">
            <!-- Formulaire -->
            <div class="dm-contact-form-col">
                <div class="dm-contact-form-card">
                    <?php if ($contact_ok) : ?>
                    <div class="dm-contact-success">
                        <div class="dm-contact-success-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <h2 class="dm-contact-success-title">Message envoyé !</h2>
                        <p class="dm-contact-success-text">Votre message a bien été reçu. Nous vous répondrons dans les meilleurs délais.</p>
                        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-contact-success-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                            Envoyer un autre message
                        </a>
                    </div>
                    <?php else : ?>
                    <form class="dm-contact-form" id="dm-contact-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                        <input type="hidden" name="action" value="dm_contact_form">
                        <?php wp_nonce_field('dm_contact_form', 'dm_contact_nonce'); ?>
                        <div class="dm-form-row-2">
                            <div class="dm-form-field">
                                <label for="dm-name">Nom complet <span class="dm-required">*</span></label>
                                <input type="text" id="dm-name" name="dm_name" required placeholder="Votre nom" />
                            </div>
                            <div class="dm-form-field">
                                <label for="dm-email">Email <span class="dm-required">*</span></label>
                                <input type="email" id="dm-email" name="dm_email" required placeholder="votre@email.com" />
                            </div>
                        </div>
                        <div class="dm-form-row-2">
                            <div class="dm-form-field">
                                <label for="dm-phone">Téléphone</label>
                                <input type="text" id="dm-phone" name="dm_phone" placeholder="+221 7X XXX XX XX" />
                            </div>
                            <div class="dm-form-field">
                                <label for="dm-subject">Sujet</label>
                                <input type="text" id="dm-subject" name="dm_subject" placeholder="Objet de votre message" />
                            </div>
                        </div>
                        <div class="dm-form-field">
                            <label for="dm-message">Message <span class="dm-required">*</span></label>
                            <textarea id="dm-message" name="dm_message" rows="4" required placeholder="Écrivez votre message ici..."></textarea>
                        </div>
                        <!-- Bouton unique avant saisie -->
                        <div class="dm-contact-actions" id="dm-contact-actions-placeholder">
                            <button type="submit" class="dm-contact-submit" disabled>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                Envoyer le message
                            </button>
                        </div>
                        <!-- Boutons mail/WhatsApp après saisie -->
                        <div class="dm-contact-actions dm-contact-actions-dual" id="dm-contact-actions-dual" style="display:none;">
                            <button type="submit" class="dm-contact-btn-mail" id="dm-btn-mail">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                Envoyer par Email
                            </button>
                            <button type="button" class="dm-contact-btn-wa-send" id="dm-btn-wa">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                Envoyer par WhatsApp
                            </button>
                        </div>
                    </form>
                    <script>
                    (function() {
                        var textarea = document.getElementById('dm-message');
                        var placeholderBtns = document.getElementById('dm-contact-actions-placeholder');
                        var dualBtns = document.getElementById('dm-contact-actions-dual');
                        var waBtn = document.getElementById('dm-btn-wa');
                        var submitBtn = document.querySelector('#dm-contact-actions-placeholder .dm-contact-submit');
                        var waNumber = '<?php echo esc_js(dm_get_whatsapp_number()); ?>';

                        // Auto-expand textarea
                        function autoExpand(el) {
                            el.style.height = 'auto';
                            el.style.height = Math.max(120, el.scrollHeight) + 'px';
                        }
                        textarea.addEventListener('input', function() {
                            autoExpand(this);
                            // Show dual buttons when user has typed at least a few characters
                            if (this.value.trim().length >= 5) {
                                placeholderBtns.style.display = 'none';
                                dualBtns.style.display = 'flex';
                            } else {
                                placeholderBtns.style.display = 'flex';
                                dualBtns.style.display = 'none';
                            }
                        });

                        // Enable submit button when required fields are filled
                        var nameField = document.getElementById('dm-name');
                        var emailField = document.getElementById('dm-email');
                        function checkRequired() {
                            if (nameField.value.trim() && emailField.value.trim() && textarea.value.trim()) {
                                submitBtn.disabled = false;
                            } else {
                                submitBtn.disabled = true;
                            }
                        }
                        nameField.addEventListener('input', checkRequired);
                        emailField.addEventListener('input', checkRequired);
                        textarea.addEventListener('input', checkRequired);

                        // WhatsApp button: format message and redirect
                        waBtn.addEventListener('click', function() {
                            var name = nameField.value.trim();
                            var email = emailField.value.trim();
                            var phone = document.getElementById('dm-phone').value.trim();
                            var subject = document.getElementById('dm-subject').value.trim();
                            var message = textarea.value.trim();

                            if (!name || !email || !message) {
                                alert('Veuillez remplir les champs obligatoires (Nom, Email, Message).');
                                return;
                            }

                            var waMsg = '*Nouveau message depuis le site Les Délices de la Mer*\n\n';
                            waMsg += '*Nom :* ' + name + '\n';
                            waMsg += '*Email :* ' + email + '\n';
                            if (phone) waMsg += '*Téléphone :* ' + phone + '\n';
                            if (subject) waMsg += '*Objet :* ' + subject + '\n';
                            waMsg += '\n*Message :*\n' + message;

                            var url = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(waMsg);
                            window.open(url, '_blank');

                            // Vider le formulaire après envoi WhatsApp
                            document.getElementById('dm-contact-form').reset();
                            textarea.style.height = '120px';
                            placeholderBtns.style.display = 'flex';
                            dualBtns.style.display = 'none';
                            submitBtn.disabled = true;
                        });

                        // Vider le formulaire après envoi par mail
                        var form = document.getElementById('dm-contact-form');
                        form.addEventListener('submit', function() {
                            // Laisser le formulaire se soumettre normalement
                            // Le navigateur gère la redirection, on vide juste visuellement
                            setTimeout(function() {
                                form.reset();
                                textarea.style.height = '120px';
                                placeholderBtns.style.display = 'flex';
                                dualBtns.style.display = 'none';
                                submitBtn.disabled = true;
                            }, 100);
                        });
                    })();
                    </script>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="dm-contact-sidebar">
                <!-- Contact rapide -->
                <div class="dm-contact-quick-card">
                    <h3 class="dm-contact-card-title">Contact rapide</h3>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-contact-quick-item dm-contact-quick-wa">
                        <div class="dm-contact-quick-icon dm-contact-quick-icon-wa">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div class="dm-contact-quick-text">
                            <span class="dm-contact-quick-label">WhatsApp</span>
                            <span class="dm-contact-quick-value">Laisser un message</span>
                        </div>
                    </a>
                    <a href="mailto:<?php echo esc_attr(dm_get_email()); ?>" class="dm-contact-quick-item">
                        <div class="dm-contact-quick-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <div class="dm-contact-quick-text">
                            <span class="dm-contact-quick-label">Email</span>
                            <span class="dm-contact-quick-value"><?php echo esc_html(dm_get_email()); ?></span>
                        </div>
                    </a>
                    <a href="tel:<?php echo esc_attr(dm_get_phone_tel()); ?>" class="dm-contact-quick-item">
                        <div class="dm-contact-quick-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div class="dm-contact-quick-text">
                            <span class="dm-contact-quick-label">Téléphone</span>
                            <span class="dm-contact-quick-value"><?php echo esc_html(dm_get_phone()); ?></span>
                        </div>
                    </a>
                    <div class="dm-contact-quick-item dm-contact-quick-no-link">
                        <div class="dm-contact-quick-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div class="dm-contact-quick-text">
                            <span class="dm-contact-quick-label">Adresse</span>
                            <span class="dm-contact-quick-value"><?php echo dm_get_address_html(); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Horaires -->
                <div class="dm-contact-hours-card">
                    <h3 class="dm-contact-card-title">Horaires d'ouverture</h3>
                    <div class="dm-contact-hours-list">
                        <?php foreach ($hours as $h) : ?>
                        <div class="dm-contact-hours-row">
                            <span class="dm-contact-hours-day"><?php echo esc_html($h['day']); ?></span>
                            <span class="dm-contact-hours-time<?php echo ($h['closed'] ?? '0') === '1' ? ' is-closed' : ''; ?>"><?php echo esc_html($h['hours']); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Carte -->
                <div class="dm-contact-map-card">
                    <iframe
                        src="<?php echo esc_url($osm_embed_url); ?>"
                        class="dm-contact-map-iframe"
                        title="Carte de localisation"
                        loading="lazy"
                    ></iframe>
                    <a href="<?php echo esc_url($osm_full_url); ?>" target="_blank" rel="noreferrer" class="dm-contact-map-link">
                        Voir sur OpenStreetMap
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ + Infos complémentaires -->
<div class="dm-contact-faq-section">
    <div class="dm-contact-container">
        <!-- Bandeau temps de réponse -->
        <div class="dm-contact-response-banner">
            <div class="dm-contact-response-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="dm-contact-response-text">
                <strong>Temps de réponse : sous 24h</strong>
                <span>Nous traitons toutes les demandes dans les meilleurs délais.</span>
            </div>
        </div>

        <h2 class="dm-contact-faq-title">Questions fréquentes</h2>
        <div class="dm-contact-faq-grid">
            <div class="dm-contact-faq-item">
                <h3 class="dm-contact-faq-q">Comment passer une commande ?</h3>
                <p class="dm-contact-faq-a">Rendez-vous sur notre <a href="<?php echo esc_url(home_url('/shop')); ?>">boutique en ligne</a>, ajoutez vos produits au panier et suivez les étapes de paiement. Livraison à Dakar.</p>
            </div>
            <div class="dm-contact-faq-item">
                <h3 class="dm-contact-faq-q">Quels sont vos délais de livraison ?</h3>
                <p class="dm-contact-faq-a">La livraison s'effectue généralement sous 24h à Dakar et banlieue. Pour les zones éloignées, contactez-nous pour un délai estimé.</p>
            </div>
            <div class="dm-contact-faq-item">
                <h3 class="dm-contact-faq-q">Proposez-vous des commandes en gros ?</h3>
                <p class="dm-contact-faq-a">Oui, nous proposons des tarifs préférentiels pour les professionnels (hôtels, restaurants, distributeurs). Contactez-nous via le formulaire pour un devis.</p>
            </div>
            <div class="dm-contact-faq-item">
                <h3 class="dm-contact-faq-q">Quels modes de paiement acceptez-vous ?</h3>
                <p class="dm-contact-faq-a">Nous acceptons le paiement à la livraison, Orange Money, et le paiement en ligne sécurisé via WooCommerce.</p>
            </div>
        </div>

        <!-- Options de contact rapides -->
        <div class="dm-contact-options-strip">
            <p class="dm-contact-options-label">Vous pouvez aussi nous contacter via :</p>
            <div class="dm-contact-options-list">
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="dm-contact-option-pill">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
                <a href="mailto:<?php echo esc_attr(dm_get_email()); ?>" class="dm-contact-option-pill">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Email
                </a>
                <a href="tel:<?php echo esc_attr(dm_get_phone_tel()); ?>" class="dm-contact-option-pill">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    Téléphone
                </a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
