<?php
/**
 * Template email central — Layout HTML pour Les Délices de la Mer.
 *
 * Variables attendues :
 *   $title       — Titre du mail (string)
 *   $body_html   — Contenu HTML spécifique (string)
 *   $logo_url    — URL du logo (string)
 *   $site_name   — Nom de l'entreprise (string)
 *   $phone       — Téléphone affichage (string)
 *   $email       — Email de contact (string)
 *   $address     — Adresse postale (string)
 *   $socials     — Tableau des réseaux sociaux (array)
 *
 * Couleurs :
 *   Navy  #1B6BB1
 *   Orange #ff6b00
 */
if (! defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($title); ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', Arial, sans-serif; line-height: 1.6; background-color: #f1f5f9; color: #334155; -webkit-font-smoothing: antialiased; }
        .wrapper { background-color: #f1f5f9; width: 100%; padding: 30px 10px; }
        .container { max-width: 600px; margin: 0 auto; }
        .cadre { background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 16px rgba(27, 107, 177,0.08); overflow: hidden; border: 1px solid rgba(27, 107, 177,0.06); }
        .header { background: linear-gradient(135deg, #1B6BB1 0%, #3A82C4 100%); padding: 28px 20px; text-align: center; }
        .logo { max-width: 64px; max-height: 64px; border-radius: 10px; vertical-align: middle; display: inline-block; }
        .app-name { color: #ffffff; font-weight: 800; font-size: 24px; margin: 0; vertical-align: middle; display: inline-block; line-height: 1.2; letter-spacing: -0.5px; }
        .accent-bar { height: 4px; background: linear-gradient(90deg, #ff6b00 0%, #ff8c40 100%); }
        .msgContainer { padding: 32px 24px; background: #ffffff; }
        .titre { color: #ff6b00; font-weight: 700; font-size: 22px; margin-bottom: 20px; text-align: center; letter-spacing: -0.3px; }
        .p1 { color: #475569; font-size: 15px; line-height: 1.6; margin: 12px 0; }
        .salut { color: #1B6BB1; font-weight: 700; font-size: 16px; display: block; margin-bottom: 16px; }
        .origin { color: #64748b; font-size: 14px; line-height: 1.6; text-align: center; margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 20px; }
        .footer { background: #1B6BB1; padding: 24px 20px; text-align: center; }
        .footer-socials { margin-bottom: 16px; }
        .footer-socials a { display: inline-block; margin: 0 4px; padding: 8px 16px; background: rgba(255,255,255,0.1); color: #ffffff !important; text-decoration: none; font-size: 13px; font-weight: 600; border-radius: 20px; transition: background 0.2s ease; }
        .footer-socials a:hover { background: rgba(255,107,0,0.3); }
        .footer-info { color: #ffffff; font-size: 13px; line-height: 1.8; }
        .footer-info a { color: #ff8c40; text-decoration: none; }
        .footer-info a:hover { text-decoration: underline; }
        .footer-disclaimer { color: rgba(255,255,255,0.5); font-size: 11px; margin-top: 10px; }
        .btn { display: inline-block; padding: 14px 32px; font-size: 15px; color: #ffffff !important; text-decoration: none; border-radius: 8px; background: linear-gradient(135deg, #ff6b00 0%, #e55a00 100%); box-shadow: 0 4px 14px rgba(255,107,0,0.35); font-weight: 500; letter-spacing: 0.3px; }
        .info-card { background: #f8fafc; border-left: 4px solid #ff6b00; border-radius: 6px; padding: 16px 20px; margin: 16px 0; }
        .info-card p { margin: 4px 0; font-size: 14px; color: #334155; }
        .info-card strong { color: #1B6BB1; }
        .message-box { margin-top: 16px; padding: 16px 20px; background-color: #f8fafc; border-left: 4px solid #ff6b00; border-radius: 6px; white-space: pre-wrap; color: #334155; font-size: 15px; line-height: 1.6; }
        @media screen and (max-width: 600px) {
            .wrapper { padding: 10px !important; }
            .header { padding: 20px 16px !important; }
            .msgContainer { padding: 24px 16px !important; }
            .footer { padding: 20px 16px !important; }
        }
    </style>
</head>
<body style="background-color: #f1f5f9; margin: 0; padding: 0; font-family: 'Montserrat', Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <div class="wrapper" style="background-color: #f1f5f9; width: 100%; padding: 30px 10px;">
        <div class="container" style="max-width: 600px; margin: 0 auto;">
            <div class="cadre" style="background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 16px rgba(27, 107, 177,0.08); overflow: hidden; border: 1px solid rgba(27, 107, 177,0.06);">

                <!-- Header -->
                <div class="header" style="background: linear-gradient(135deg, #1B6BB1 0%, #3A82C4 100%); background-color: #1B6BB1; padding: 28px 20px; text-align: center;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td align="center" style="padding: 0;">
                                <h1 class="app-name" style="color: #ffffff; font-weight: 800; font-size: 26px; margin: 0; line-height: 1.2; letter-spacing: -0.5px;"><?php echo esc_html($site_name); ?></h1>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Accent bar -->
                <div class="accent-bar" style="height: 4px; background: linear-gradient(90deg, #ff6b00 0%, #ff8c40 100%);"></div>

                <!-- Content -->
                <div class="msgContainer" style="padding: 32px 24px; background: #ffffff;">
                    <h2 class="titre" style="color: #ff6b00; font-weight: 700; font-size: 22px; margin-bottom: 20px; text-align: center; letter-spacing: -0.3px;"><?php echo esc_html($title); ?></h2>
                    <?php echo $body_html; ?>
                    <p class="origin" style="color: #64748b; font-size: 14px; text-align: center; margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 20px;">Cordialement,<br>L'équipe <?php echo esc_html($site_name); ?></p>
                </div>

                <!-- Footer -->
                <div class="footer" style="background-color: #1B6BB1; padding: 24px 20px; text-align: center;">
                    <!-- Réseaux sociaux -->
                    <?php if (!empty($socials)) : ?>
                    <div class="footer-socials" style="margin-bottom: 16px;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center">
                            <tr>
                                <?php foreach ($socials as $social) : ?>
                                    <td align="center" style="padding: 0 4px;">
                                        <a href="<?php echo esc_url($social['url']); ?>" target="_blank" style="display: inline-block; padding: 8px 16px; background: rgba(255,255,255,0.1); color: #ffffff !important; text-decoration: none; font-size: 13px; font-weight: 600; border-radius: 20px;">
                                            <?php echo esc_html($social['name']); ?>
                                        </a>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </table>
                    </div>
                    <?php endif; ?>

                    <!-- Coordonnées -->
                    <div class="footer-info" style="color: #ffffff; font-size: 13px; line-height: 1.8;">
                        <strong style="color: #ff8c40;"><?php echo esc_html($site_name); ?></strong><br>
                        <?php if (!empty($phone)) : ?>
                            <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" style="color: #ffffff; text-decoration: none;"><?php echo esc_html($phone); ?></a>
                        <?php endif; ?>
                        <?php if (!empty($email)) : ?>
                            <br><a href="mailto:<?php echo esc_attr($email); ?>" style="color: #ff8c40; text-decoration: none;"><?php echo esc_html($email); ?></a>
                        <?php endif; ?>
                        <?php if (!empty($address)) : ?>
                            <br><?php echo esc_html($address); ?>
                        <?php endif; ?>
                    </div>

                    <p class="footer-disclaimer" style="color: rgba(255,255,255,0.5); font-size: 11px; margin-top: 10px;">Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
