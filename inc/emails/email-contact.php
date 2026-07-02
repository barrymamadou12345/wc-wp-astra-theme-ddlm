<?php
/**
 * Template email — Formulaire de contact.
 *
 * Variables attendues :
 *   $name    — Nom de l'expéditeur (string)
 *   $email   — Email de l'expéditeur (string)
 *   $phone   — Téléphone de l'expéditeur (string, peut être vide)
 *   $subject — Objet du message (string)
 *   $message — Contenu du message (string)
 *   $date    — Date/heure de soumission (string)
 *   $ip      — Adresse IP (string)
 *
 * Ce fichier génère uniquement le $body_html injecté dans email-template.php
 */
if (! defined('ABSPATH')) {
    exit;
}
?>
<span class="salut" style="color: #1B6BB1; font-weight: 700; font-size: 16px; display: block; margin-bottom: 16px;">Bonjour,</span>

<p class="p1" style="color: #475569; font-size: 15px; line-height: 1.6; margin: 12px 0;">
    Un nouveau message a été soumis via le formulaire de contact du site par
    <strong style="color: #ff6b00; font-weight: 600; background: rgba(255,107,0,0.1); padding: 3px 10px; border-radius: 6px;"><?php echo esc_html($name); ?></strong>.
</p>

<!-- Objet -->
<div class="info-card" style="background: #f8fafc; border-left: 4px solid #ff6b00; border-radius: 6px; padding: 16px 20px; margin: 16px 0;">
    <p style="margin: 4px 0; font-size: 14px; color: #334155;"><strong style="color: #1B6BB1;">Objet :</strong> <?php echo esc_html($subject ?: '(Aucun objet)'); ?></p>
</div>

<!-- Contenu du message -->
<p class="p1" style="color: #475569; font-size: 15px; line-height: 1.6; margin: 12px 0;"><strong style="color: #1B6BB1;">Message :</strong></p>
<div class="message-box" style="margin-top: 8px; padding: 16px 20px; background-color: #f8fafc; border-left: 4px solid #ff6b00; border-radius: 6px; white-space: pre-wrap; color: #334155; font-size: 15px; line-height: 1.6;">
<?php echo esc_html($message); ?>
</div>

<!-- Informations de l'expéditeur -->
<div class="info-card" style="background: #f8fafc; border-left: 4px solid #ff6b00; border-radius: 6px; padding: 16px 20px; margin: 16px 0;">
    <p style="margin: 4px 0; font-size: 14px; color: #334155;"><strong style="color: #1B6BB1;">Nom :</strong> <?php echo esc_html($name); ?></p>
    <p style="margin: 4px 0; font-size: 14px; color: #334155;"><strong style="color: #1B6BB1;">Email :</strong> <a href="mailto:<?php echo esc_attr($email); ?>" style="color: #ff6b00; text-decoration: none;"><?php echo esc_html($email); ?></a></p>
    <?php if (!empty($phone)) : ?>
    <p style="margin: 4px 0; font-size: 14px; color: #334155;"><strong style="color: #1B6BB1;">Téléphone :</strong> <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" style="color: #ff6b00; text-decoration: none;"><?php echo esc_html($phone); ?></a></p>
    <?php endif; ?>
    <p style="margin: 4px 0; font-size: 14px; color: #334155;"><strong style="color: #1B6BB1;">Date :</strong> <?php echo esc_html($date); ?></p>
</div>

<!-- Bouton répondre -->
<p style="text-align: center; margin-top: 24px;">
    <a href="mailto:<?php echo esc_attr($email); ?>?subject=Re: <?php echo esc_attr($subject ?: 'Votre message'); ?>" class="btn" style="display: inline-block; padding: 14px 32px; font-size: 15px; color: #ffffff !important; text-decoration: none; border-radius: 8px; background: linear-gradient(135deg, #ff6b00 0%, #e55a00 100%); box-shadow: 0 4px 14px rgba(255,107,0,0.35); font-weight: 500; letter-spacing: 0.3px;">
        Répondre à <?php echo esc_html($name); ?>
    </a>
</p>
