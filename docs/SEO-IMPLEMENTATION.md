# SEO intégré au thème WordPress — Guide complet

## Contexte

Ce document explique l'implémentation SEO réalisée dans le thème enfant WordPress **Astra Délices de la Mer**. Il est conçu pour être réutilisable : un développeur ou une IA peut s'en inspirer pour reproduire la même approche dans un autre projet WordPress.

---

## Pourquoi le code dans le thème plutôt qu'un plugin ?

### Les deux approches fonctionnent pour Google

| Critère | Code dans le thème | Plugin SEO (Yoast/Rank Math) |
|---|---|---|
| Google voit les balises ? | ✅ Oui | ✅ Oui |
| Meta description par page | ✅ Via admin du thème | ✅ Boîte SEO sous chaque page |
| Open Graph (Facebook/WhatsApp) | ✅ Codé | ✅ Automatique |
| Sitemap XML | ⚠️ À coder (ou plugin) | ✅ Automatique |
| Données structurées Schema.org | ✅ Codé | ✅ Automatique |
| Analyse de contenu (mots-clés) | ❌ Non | ✅ Oui |
| Maintenance | Code à maintenir | Mises à jour du plugin |
| Simplicité pour l'utilisateur | Page admin dédiée | Interface WordPress standard |

### Pourquoi nous avons choisi le code dans le thème

1. **Zéro dépendance** — Pas de plugin supplémentaire à installer, mettre à jour, ou qui peut casser lors d'une mise à jour WordPress/WooCommerce.
2. **Intégration native** — Le SEO est géré dans la même interface admin que le reste du contenu ("Délices Content"), ce qui est plus cohérent pour l'utilisateur.
3. **Contrôle total** — On contrôle exactement quelles balises sont générées, comment, et avec quelles données. Pas de balises en double ou de conflits avec d'autres plugins.
4. **Performance** — Moins de code exécuté qu'un plugin complet. Seules les balises nécessaires sont générées.
5. **Données structurées personnalisées** — Le Schema.org est adapté au type d'entreprise (FoodEstablishment) avec les coordonnées, horaires, et réseaux sociaux déjà gérés par le thème.

### Quand utiliser un plugin à la place

- Si vous avez besoin d'un **sitemap XML automatique** (bien que WordPress 5.5+ en génère un de base)
- Si vous voulez l'**analyse de contenu** (suggestions de mots-clés, score SEO)
- Si vous gérez un **blog avec beaucoup d'articles** et besoin de meta descriptions par article
- Si vous voulez l'**intégration Google Search Console** dans l'admin

---

## Comment ça fonctionne

### Architecture

```
functions.php
  └── inc/functions/seo.php  (tout le code SEO)
        ├── Helpers : dm_get_seo_title(), dm_get_seo_description(), dm_get_seo_image()
        ├── wp_head hook (priorité 1) : meta tags + Open Graph + Twitter Cards
        ├── wp_head hook (priorité 2) : Schema.org JSON-LD
        ├── Settings API : enregistrement des options
        └── Admin page : page "SEO" sous "Délices Content"
```

### Principe de fonctionnement

1. **WordPress génère le HTML** — Quand un visiteur (ou Googlebot) charge une page, WordPress exécute le thème, qui génère le HTML final.
2. **Le hook `wp_head`** — WordPress appelle `wp_head()` dans le `<head>` du HTML. Notre code s'y injecte via `add_action('wp_head', 'dm_seo_output_meta', 1)`.
3. **Détection de page** — La fonction `dm_seo_current_slug()` détecte la page courante (home, shop, services, contact, product, etc.).
4. **Récupération des données** — Les helpers (`dm_get_seo_title()`, `dm_get_seo_description()`, etc.) récupèrent les valeurs depuis les options WordPress, avec des fallbacks intelligents (post excerpt, product name, image à la une, etc.).
5. **Sortie HTML** — Les balises `<meta>`, `<link>`, et `<script type="application/ld+json">` sont echo'ées dans le `<head>`.

### Ce que Google voit

Quand Googlebot crawl une page, il reçoit le HTML suivant dans le `<head>` :

```html
<!-- ===== SEO by Délices de la Mer Theme ===== -->
<meta name="description" content="Découvrez Les Délices de la Mer : snacks croustillants artisanaux..." />
<meta name="robots" content="index, follow" />
<link rel="canonical" href="https://delices-de-la-mer.com/" />

<!-- Open Graph -->
<meta property="og:site_name" content="Les Délices de la Mer" />
<meta property="og:title" content="Les Délices de la Mer — Snacks & Produits de la Mer Artisanaux à Dakar" />
<meta property="og:description" content="Découvrez Les Délices de la Mer..." />
<meta property="og:url" content="https://delices-de-la-mer.com/" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="fr_FR" />
<meta property="og:image" content="https://delices-de-la-mer.com/wp-content/uploads/og-image.jpg" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="Les Délices de la Mer — Snacks..." />
<meta name="twitter:description" content="Découvrez..." />
<meta name="twitter:image" content="https://delices-de-la-mer.com/wp-content/uploads/og-image.jpg" />

<!-- Theme color -->
<meta name="theme-color" content="#0a1929" media="(prefers-color-scheme: dark)" />
<meta name="theme-color" content="#f8f5f0" media="(prefers-color-scheme: light)" />
<!-- ===== End SEO ===== -->

<!-- Schema.org JSON-LD -->
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"Organization","name":"Les Délices de la Mer","url":"https://delices-de-la-mer.com/","logo":{"@type":"ImageObject","url":"https://..."},"sameAs":["https://facebook.com/...","https://instagram.com/..."]}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"FoodEstablishment","name":"Les Délices de la Mer","url":"https://delices-de-la-mer.com/","priceRange":"$$","servesCuisine":"Sénégalaise","address":{"@type":"PostalAddress","streetAddress":"Dakar","addressLocality":"Dakar","addressCountry":"SN"},"telephone":"+221...","email":"contact@...","openingHours":["Lundi 08:00-18:00",...],"geo":{"@type":"GeoCoordinates","latitude":"14.6928","longitude":"-17.4467"}}
</script>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Accueil","item":"https://delices-de-la-mer.com/"},{"@type":"ListItem","position":2,"name":"Catalogue","item":"https://delices-de-la-mer.com/shop"}]}
</script>
```

---

## Ce qui a été implémenté

### 1. Meta description dynamique par page

- Chaque page du site (Accueil, Catalogue, Services, Points de Vente, À Propos, Contact, Témoignages, Galerie, Promotions) a sa propre meta description.
- Les valeurs par défaut sont pré-remplies avec du contenu optimisé SEO.
- L'admin peut modifier chaque titre et description depuis la page "SEO" sous "Délices Content".
- **Fallback intelligent** : si aucune valeur n'est définie, le système utilise l'excerpt du post, le nom du produit WooCommerce, ou une description générique.

### 2. Open Graph tags

- `og:site_name`, `og:title`, `og:description`, `og:url`, `og:type`, `og:locale`
- `og:image` avec dimensions (`og:image:width`, `og:image:height`) — 1200x630px recommandé
- Permet un affichage professionnel lors du partage sur Facebook, WhatsApp, LinkedIn, Slack
- `og:type` adapté : `website` pour les pages, `product` pour les produits WooCommerce, `article` pour les posts

### 3. Twitter Card tags

- `twitter:card` = `summary_large_image` (carte avec grande image)
- `twitter:title`, `twitter:description`, `twitter:image`
- `twitter:site` optionnel (handle Twitter)

### 4. Schema.org JSON-LD (données structurées)

Quatre types de schémas sont générés :

#### a) Organization Schema (toutes les pages)
- Nom de l'entreprise, URL, logo
- Liens vers les réseaux sociaux (`sameAs`)
- Crée une entrée dans le Knowledge Graph Google

#### b) LocalBusiness / FoodEstablishment Schema (toutes les pages)
- Type configurable (FoodEstablishment, Restaurant, Bakery, etc.)
- Nom, URL, logo, image
- Adresse postale (Dakar, Sénégal)
- Téléphone, email
- Horaires d'ouverture
- Coordonnées géographiques (lat/lng)
- Fourchette de prix, type de cuisine
- Liens réseaux sociaux
- **Effet** : rich snippets dans Google (horaires, adresse, téléphone affichés directement dans les résultats)

#### c) Product Schema (pages produits WooCommerce)
- Nom, description, image du produit
- Prix et devise
- Disponibilité (en stock / rupture)
- Marque
- **Effet** : prix et disponibilité affichés dans les résultats Google Shopping

#### d) BreadcrumbList Schema (toutes les pages)
- Fil d'Ariane structuré (Accueil > Catalogue > Nom du produit)
- **Effet** : navigation en fil d'Ariane dans les résultats Google

### 5. Canonical URL

- `<link rel="canonical" href="...">` généré automatiquement pour chaque page
- Évite le duplicate content aux yeux de Google

### 6. Robots meta tag

- Configurable globalement et par page
- Par défaut : `index, follow`
- Permet de cacher des pages spécifiques des moteurs de recherche

### 7. Theme color

- `<meta name="theme-color">` pour mobile
- Couleure l'UI du navigateur mobile selon le thème du site (dark/light)

---

## Page admin SEO

Accessible depuis : **WordPress Admin → Délices Content → SEO**

### Sections de la page admin

1. **Paramètres globaux**
   - Nom du site (pour Open Graph)
   - Image OG par défaut (1200x630px minimum)
   - Handle Twitter
   - Directive robots globale

2. **Données structurées — Entreprise (Schema.org)**
   - Nom officiel de l'entreprise
   - Type d'établissement (FoodEstablishment, Restaurant, etc.)
   - Logo
   - Fourchette de prix
   - Type de cuisine

3. **SEO par page**
   - Pour chaque page : titre SEO, meta description, image de partage, directive robots
   - Champs pré-remplis avec des valeurs par défaut optimisées
   - Upload d'image intégré (media uploader WordPress)

---

## Fichiers créés/modifiés

| Fichier | Action | Description |
|---|---|---|
| `inc/functions/seo.php` | Créé | Tout le code SEO (meta tags, Schema.org, admin page, settings) |
| `functions.php` | Modifié | Ajout de `require_once ... '/inc/functions/seo.php'` |
| `inc/functions/contact-options.php` | Modifié | Ajout de l'entrée "SEO" dans le dashboard Délices Content |

---

## Options WordPress (Settings API)

| Option | Type | Description |
|---|---|---|
| `dm_seo_site_name` | string | Nom du site pour Open Graph |
| `dm_seo_default_image` | string (URL) | Image OG par défaut |
| `dm_seo_twitter_handle` | string | Handle Twitter (@handle) |
| `dm_seo_business_name` | string | Nom officiel pour Schema.org |
| `dm_seo_business_logo` | string (URL) | Logo pour Schema.org |
| `dm_seo_business_type` | string | Type Schema.org (FoodEstablishment, etc.) |
| `dm_seo_business_price_range` | string | Fourchette de prix ($$, $$$) |
| `dm_seo_business_cuisine` | string | Type de cuisine |
| `dm_seo_robots_global` | string | Directive robots globale |
| `dm_seo_pages` | array | SEO par page : [slug => [title, description, image, robots]] |

---

## Helpers disponibles pour les développeurs

```php
// Titre SEO de la page courante
$title = dm_get_seo_title();

// Description SEO de la page courante
$desc = dm_get_seo_description();

// Image OG de la page courante
$img = dm_get_seo_image();

// Type OG (website, product, article)
$type = dm_get_seo_og_type();

// Slug SEO courant
$slug = dm_seo_current_slug();

// Directive robots de la page courante
$robots = dm_get_seo_robots();
```

---

## Comment reproduire cette implémentation dans un autre projet

### Étape 1 : Créer le fichier SEO

Créer un fichier `inc/functions/seo.php` dans le thème enfant avec :

1. **Fonctions de defaults** — `dm_seo_default_pages()` retourne un array avec les pages du site et leurs meta par défaut.
2. **Helper de détection de page** — `dm_seo_current_slug()` détecte la page courante.
3. **Helpers de récupération** — `dm_get_seo_title()`, `dm_get_seo_description()`, `dm_get_seo_image()` avec fallbacks.
4. **Hook wp_head (priorité 1)** — `dm_seo_output_meta()` echo les balises meta, OG, Twitter.
5. **Hook wp_head (priorité 2)** — `dm_seo_output_schema()` echo les JSON-LD.
6. **Settings API** — `register_setting()` pour chaque option.
7. **Admin page** — `add_submenu_page()` sous le menu parent, avec formulaire HTML.

### Étape 2 : Enregistrer dans functions.php

```php
require_once DM_THEME_DIR . '/inc/functions/seo.php';
```

### Étape 3 : Ajouter au dashboard admin (optionnel)

Ajouter une entrée dans le tableau de bord admin existant :

```php
array('slug' => 'dm-seo', 'title' => 'SEO', 'desc' => '...', 'icon' => 'dashicons-search'),
```

### Étape 4 : Adapter les defaults

- Modifier `dm_seo_default_pages()` avec les pages du nouveau projet
- Adapter `dm_seo_local_business_schema()` avec le bon type Schema.org
- Changer les textes de meta description selon l'activité

---

## Vérification et test

### Outils pour vérifier le SEO

1. **Google Rich Results Test** — https://search.google.com/test/rich-results
   - Vérifie les données structurées JSON-LD
   
2. **Facebook Sharing Debugger** — https://developers.facebook.com/tools/debug/
   - Vérifie les Open Graph tags
   
3. **Google PageSpeed Insights** — https://pagespeed.web.dev/
   - Vérifie la performance et le SEO mobile

4. **Schema.org Validator** — https://validator.schema.org/
   - Valide les données structurées

5. **Voir le code source** — Ouvrir n'importe quelle page du site, faire Ctrl+U, chercher `<!-- ===== SEO` pour voir les balises générées.

### Checklist de vérification

- [ ] La meta description apparaît dans le `<head>` de chaque page
- [ ] Les Open Graph tags sont présents avec une image
- [ ] Les Twitter Card tags sont présents
- [ ] Le JSON-LD Organization est valide
- [ ] Le JSON-LD LocalBusiness est valide avec adresse, téléphone, horaires
- [ ] Le JSON-LD BreadcrumbList est correct
- [ ] Le canonical URL est présent
- [ ] Les pages produits WooCommerce ont le JSON-LD Product
- [ ] La page admin SEO est accessible et éditable
- [ ] Les valeurs par défaut sont pré-remplies

---

## Bonnes pratiques SEO appliquées

1. **Titre SEO : 50-60 caractères** — Évite la troncature dans Google
2. **Meta description : 150-160 caractères** — Maximise la visibilité sans troncature
3. **Image OG : 1200x630px minimum** — Ratio standard pour tous les réseaux sociaux
4. **Une seule balise H1 par page** — Déjà géré par les templates du thème
5. **URLs propres** — WordPress gère déjà les permaliens
6. **Données structurées JSON-LD** — Format recommandé par Google (pas de microdata)
7. **Canonical URL** — Évite le duplicate content
8. **Robots meta** — Contrôle indexation par page
9. **Theme color** — Améliore l'expérience mobile
10. **Locale** — Indique la langue aux réseaux sociaux

---

## Notes techniques

- Le code utilise `wp_json_encode()` (et non `json_encode()`) pour assurer la compatibilité WordPress et l'échappement correct des caractères Unicode.
- Les balises sont échappées avec `esc_attr()`, `esc_url()`, `esc_html()` selon le contexte.
- La sanitization utilise `sanitize_text_field()`, `sanitize_textarea_field()`, `esc_url_raw()` selon le type de champ.
- Le hook `wp_head` est utilisé avec priorité 1 (meta tags) et 2 (JSON-LD) pour apparaître tôt dans le `<head>`.
- Aucune modification du `header.php` n'est nécessaire — le thème parent (Astra) gère déjà `wp_head()`.
