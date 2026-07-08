# CONTEXTE — Projet Délices de la Mer

## 1. Objectif du projet

Créer un site e-commerce **WordPress + WooCommerce** pour **Les Délices de la Mer**, une entreprise sénégalaise de snacks et produits fumés. Le design est déjà prototypé et validé dans une application React/TailwindCSS (`delices-de-la-mer/src`). L'objectif est de **reproduire ce prototype** dans WordPress en utilisant le thème **Astra** comme base et en créant un **thème enfant** nommé `astra-delices-de-la-mer`.

## 2. Contraintes principales

- **Aucune ligne de TailwindCSS** — tout le styling se fait en **CSS pur et modulaire**.
- **Ne jamais modifier le thème parent Astra** — toutes les personnalisations se font via le thème enfant.
- **Suivre l'architecture du thème référence** `astra-kante-volaille` (même structure de dossiers, même approche modulaire, mêmes patterns).
- Le thème `astra-kante-volaille` sert de **guide architectural** uniquement — les design systems et les pages sont différents.

## 3. Architecture du thème enfant

```
astra-delices-de-la-mer/
├── style.css                    # En-tête du thème (Theme Name, Template: astra) + overrides structurels
├── functions.php                # Point d'entrée (que des require_once, ~30 lignes)
├── front-page.php               # Page d'accueil custom (Hero + sections)
│
├── inc/
│   ├── functions/               # Fonctionnalités modulaires (1 fichier = 1 domaine)
│   │   ├── setup.php            # WooCommerce coming soon, cache-busting helper
│   │   ├── contact-options.php  # Coordonnées centralisées (WhatsApp, tel, email, réseaux sociaux)
│   │   ├── enqueue.php          # Enqueue CSS modulaire + conditionnel + WooCommerce
│   │   ├── astra-overrides.php  # Force full-width, désactive sidebar, swap header/footer
│   │   ├── wc-translations.php  # Traductions WooCommerce (Shop → Catalogue, etc.)
│   │   ├── wc-account.php       # Personnalisation du compte client WooCommerce
│   │   ├── header.php           # Header custom (navbar, dropdown, mobile, bottom nav)
│   │   ├── footer.php           # Footer custom (4 colonnes + wave + réseaux sociaux)
│   │   ├── page-banner.php      # Bannière d'en-tête des pages internes (titre + breadcrumb)
│   │   ├── floating-whatsapp.php # Bouton flottant WhatsApp avec carte dépliable
│   │   ├── auto-pages.php       # Auto-création des pages WordPress avec template assigné
│   │   ├── admin-repeaters.php  # Repeaters Settings API (contenu dynamique admin)
│   │   └── repeaters/           # Repeaters individuels (à développer)
│   ├── cpt/                     # Custom Post Types (à développer)
│   └── seeders/                 # Seeders données démo (à développer)
│
├── pages/                       # Templates de pages personnalisés
│   ├── page-services.php        # Page Services (3 pôles)
│   ├── page-stores.php          # Page Points de Vente
│   ├── page-about.php           # Page À Propos
│   ├── page-testimonials.php    # Page Témoignages
│   └── page-contact.php         # Page Contact
│
├── assets/
│   ├── css/
│   │   ├── base.css             # Design system global (variables, typo, boutons, helpers, WhatsApp float)
│   │   ├── header.css           # Navbar custom
│   │   ├── footer.css           # Footer custom
│   │   ├── home.css             # Page d'accueil
│   │   ├── page-banner.css      # Bannière pages internes
│   │   ├── page-services.css    # Page Services
│   │   ├── page-stores.css      # Page Points de Vente
│   │   ├── page-about.css       # Page À Propos
│   │   ├── page-testimonials.css # Page Témoignages
│   │   ├── page-contact.css     # Page Contact
│   │   └── woocommerce/         # Design system WooCommerce modulaire
│   │       ├── wc-base.css           # Variables WC + reset
│   │       ├── wc-buttons.css        # Boutons WC
│   │       ├── wc-product-card.css   # Cartes produit
│   │       ├── wc-badges.css         # Badges (promo, rupture)
│   │       ├── wc-single-product.css # Fiche produit
│   │       ├── wc-reviews.css        # Avis
│   │       ├── wc-cart.css           # Panier
│   │       ├── wc-checkout.css       # Checkout
│   │       ├── checkout-style/       # Styles checkout détaillés
│   │       └── account-style/        # Styles compte client
│   └── images/
│       ├── logo/                # Logo Délices de la Mer
│       ├── banners/             # Images de bannières
│       └── gallery/             # Galerie photos
│
├── woocommerce/                 # Template overrides WooCommerce (dernier recours)
│
└── docs/
    └── CONTEXTE.md              # Ce fichier
```

## 4. Design System — Délices de la Mer

### Couleurs (CSS variables dans `base.css`)

| Variable          | Valeur     | Usage                                    |
|-------------------|------------|------------------------------------------|
| `--navy`          | `#0A1E30`  | Couleur primaire (headers, footer, texte)|
| `--navy-light`    | `#1a3a52`  | Variantes navy                           |
| `--navy-dark`     | `#051219`  | Navy foncé (hover)                       |
| `--orange`        | `#FF6B00`  | Couleur secondaire (CTA, accents)        |
| `--orange-light`  | `#ff8c33`  | Orange clair                             |
| `--orange-dark`   | `#CC5500`  | Orange foncé (hover)                     |
| `--cream`         | `#FDFCF8`  | Fond de page                             |
| `--cream-dark`    | `#f5f3ee`  | Fond sections alternées                  |
| `--whatsapp`      | `#25D366`  | Boutons WhatsApp                         |
| `--whatsapp-dark` | `#20bd5a`  | WhatsApp hover                           |

### Polices

| Variable         | Famille       | Usage              |
|------------------|---------------|--------------------|
| `--font-heading` | Montserrat    | Titres, labels, CTA|
| `--font-body`    | Inter         | Texte courant      |
| `--font-display` | Montserrat    | Display headings   |

Poids Montserrat : 400, 500, 600, 700, 800, 900
Poids Inter : 300, 400, 500, 600, 700

### Rayons de bordure

| Variable       | Valeur      |
|----------------|-------------|
| `--radius`     | `0.75rem`   |
| `--radius-lg`  | `1rem`      |
| `--radius-xl`  | `1.5rem`    |
| `--radius-full`| `9999px`    |

### Layout

| Variable          | Valeur     |
|-------------------|------------|
| `--container-max` | `1280px`   |

## 5. Pages du prototype à reproduire

| Route prototype    | Page WordPress     | Template                    | Statut       |
|--------------------|--------------------|-----------------------------|--------------|
| `/`                | Accueil            | `front-page.php`            | Placeholder  |
| `/catalogue`       | Shop WooCommerce   | (WooCommerce natif)         | À styliser   |
| `/produit/:id`     | Fiche produit WC   | (WooCommerce natif)         | À styliser   |
| `/panier`          | Panier WC          | (WooCommerce natif)         | À styliser   |
| `/commande`        | Checkout WC        | (WooCommerce natif)         | À styliser   |
| `/confirmation/:id`| Merci WC           | (WooCommerce natif)         | À styliser   |
| `/suivi-commande`  | Suivi commande     | (À créer)                   | À développer  |
| `/services`        | Services           | `pages/page-services.php`   | À développer  |
| `/points-de-vente` | Points de Vente    | `pages/page-stores.php`     | À développer  |
| `/a-propos`        | À Propos           | `pages/page-about.php`      | À développer  |
| `/temoignages`     | Témoignages        | `pages/page-testimonials.php` | À développer|
| `/contact`         | Contact            | `pages/page-contact.php`    | À développer  |
| `/mes-commandes`   | Mon compte WC      | (WooCommerce natif)         | À personnaliser |

## 6. Sections de la page d'accueil (prototype Home.jsx)

1. **HeroSection** — Image de fond + titre + description + 2 CTA + 3 statistiques (2016, 40+ employés, 10+ partenaires)
2. **WhyUs** — 4 cartes features (Qualité certifiée, Fraîcheur quotidienne, Livraison rapide, Ingrédients naturels) avec icônes
3. **HowItWorks** — 4 étapes (Choisissez, Commandez, Préparez, Savourez) sur fond navy avec effets de flou
4. **ServicesPreview** — 3 pôles (Restauration Événementielle, Gestion de Cantines, Produits Traiteur & Fumés) avec images
5. **Featured Products** — Grille de produits phares WooCommerce
6. **StatsSection** — Texte + statistiques (Années d'expérience, Employés, Partenaires) + galerie photos
7. **PartnersBar** — Barre de logos partenaires sur fond navy avec wave dividers
8. **TestimonialsSection** — Carrousel de témoignages clients avec notes en étoiles
9. **CTA** — Section call-to-action finale

## 7. Coordonnées de contact (prototype)

| Type      | Valeur                    |
|-----------|---------------------------|
| WhatsApp  | `221775630223`            |
| Téléphone | `+221 77 563 02 23`       |
| Email     | `contact@delicesdelamer.com` |
| Adresse   | Dakar, Sénégal            |

Ces valeurs sont stockées en base via `contact-options.php` et récupérées dynamiquement avec les helpers :
- `dm_get_whatsapp_number()`
- `dm_get_phone()` / `dm_get_phone_tel()`
- `dm_get_email()`
- `dm_get_address()` / `dm_get_address_html()`
- `dm_wa_link($message)`
- `dm_get_social_networks()`

## 8. Navigation (prototype Navbar.jsx)

### Liens principaux (desktop)
- Accueil
- Catalogue (`/shop`)
- Plus (dropdown) → Services, Points de Vente, Témoignages
- À Propos
- Contact

### Actions
- Icône panier avec compteur
- Icône suivi de commande
- Bouton "Commander" (CTA orange)
- Profil utilisateur (si connecté) ou bouton "Connexion"
- Burger menu (mobile)

### Navigation mobile (bottom nav)
- Accueil, Catalogue, À Propos, Contact, Plus (bottom sheet → Services, Points de Vente, Témoignages, Suivi commande)

## 9. Footer (prototype Footer.jsx)

4 colonnes :
1. **Marque** — Logo + slogan "Une symbiose de saveurs" + description + réseaux sociaux
2. **Navigation** — Accueil, Catalogue, Services, Points de Vente, À Propos
3. **Informations** — Témoignages, Suivi de commande, Espace Client, Contact
4. **Contact** — Adresse, téléphone, email + bouton WhatsApp

Wave divider SVG en haut du footer. Bas de page avec copyright et liens légaux.

## 10. Bouton flottant WhatsApp (prototype WhatsAppFloat.jsx)

Bouton rond vert en bas à droite avec carte dépliable contenant 3 options :
- 📦 Statut commande
- 💼 Demande B2B
- 🍳 Conseil culinaire

## 11. Spécificités produits WooCommerce

### Catégories
- Snacks & Beignets
- Produits Fumés

### Types
- Frais
- Surgelé

### Champs spécifiques (à gérer via attributs WooCommerce ou meta)
- `category` — Catégorie (Snacks & Beignets / Produits Fumés)
- `type` — Type (Frais / Surgelé)
- `format` — Format du produit
- `ingredients` — Liste d'ingrédients
- `preparation_methods` — Modes de préparation (Four, Friteuse, Micro-ondes, Poêle)
- `mode_emploi` — Instructions de préparation (Mode Chef)
- `featured` — Produit populaire (badge flame)
- `available` — Disponibilité

## 12. Points de vente (prototype StoreLocator.jsx)

10 partenaires distributeurs :
- Carrefour, Auchan, EDK (Grande distribution)
- Novotel, Pullman, Terrou-Bi (Hôtels)
- TotalEnergies, Shell (Stations-service)
- Seter (Transport — gares TER)
- Sen'Eau (Entreprise — cantine)

Types : Grande distribution, Hôtel, Station-service, Transport, Entreprise
Zones : Dakar, Dakar & Régions

## 13. Services (prototype Services.jsx)

3 pôles de service :
1. **Restauration Événementielle** — Mariages, séminaires, cocktails (50 à 2000+ convives)
2. **Gestion de Cantines & Cafétérias** — B2B (entreprises, écoles, institutions)
3. **Produits Traiteur & Fumés** — Fumage artisanal au beurre de coco, conservation -18°C

Chaque service a : image, icône, sous-titre, description, liste de features, bouton "Demander un devis" (WhatsApp)

## 14. Page À Propos (prototype About.jsx)

- Hero avec image + titre "Notre Histoire"
- Story : "Depuis 2016, une passion pour le goût" (3 paragraphes)
- Valeurs : Qualité Premium, Fraîcheur Absolue, Esprit d'Équipe (3 cartes)
- Timeline : 6 milestones (2016, 2017, 2019, 2021, 2023, 2024) — timeline alternée gauche/droite
- CTA : "Envie de travailler avec nous ?"

## 15. Page Contact (prototype Contact.jsx)

- Hero navy avec dégradé orange
- Formulaire (nom, email, téléphone, sujet, message)
- Sidebar : cartes contact rapide (WhatsApp, Email, Téléphone, Adresse) + horaires + carte OpenStreetMap

## 16. Différences avec le thème référence (astra-kante-volaille)

| Aspect              | Kanté Volaille                    | Délices de la Mer                    |
|---------------------|-----------------------------------|--------------------------------------|
| Préfixe PHP         | `kv_`                             | `dm_`                                |
| Constantes          | `KV_THEME_DIR`, `KV_THEME_URI`    | `DM_THEME_DIR`, `DM_THEME_URI`       |
| Couleur primaire    | Emerald `#0b4632`                 | Navy `#0A1E30`                       |
| Couleur secondaire  | Orange `#f37021`                  | Orange `#FF6B00`                     |
| Fond                | Parchment `#f9f7f2`               | Cream `#FDFCF8`                      |
| Police titres       | Inter                             | Montserrat                           |
| Police body         | Inter                             | Inter                                 |
| Pages personnalisées| KPG, Transport, Environment, Social, Gallery | Services, Stores, About, Testimonials, Contact |
| CPTs                | Restaurants, Menu items, Vehicles | (à définir : Testimonials, Stores)   |
| Menu admin          | "Kanté Content"                   | "Délices Content"                    |
| Text domain         | `astra-kante-volaille`            | `astra-delices-de-la-mer`            |

## 17. Comment s'y prendre (méthodologie)

### Phase 1 — Structure et design system (✅ FAIT)
- Créer la structure de dossiers
- Créer `style.css`, `functions.php`
- Créer tous les fichiers `inc/functions/` avec les bons préfixes `dm_`
- Créer `base.css` avec les variables CSS du design system
- Créer `header.css`, `footer.css`, `home.css`, `page-banner.css`
- Créer `front-page.php` (placeholder)

### Phase 2 — Header et Footer
- Adapter le header PHP avec la navigation du prototype
- Adapter le footer PHP avec les liens du prototype
- Ajouter le logo dans `assets/images/logo/`
- Tester l'affichage sur mobile et desktop

### Phase 3 — Page d'accueil
- Développer `front-page.php` section par section
- Ajouter le CSS correspondant dans `home.css`
- Intégrer les produits phares WooCommerce
- Créer les repeaters pour le contenu dynamique

### Phase 4 — Pages personnalisées
- Créer `pages/page-services.php` + CSS
- Créer `pages/page-stores.php` + CSS
- Créer `pages/page-about.php` + CSS
- Créer `pages/page-testimonials.php` + CSS
- Créer `pages/page-contact.php` + CSS

### Phase 5 — WooCommerce
- Créer le design system CSS WooCommerce (`assets/css/woocommerce/`)
- Styliser les cartes produit, fiche produit, panier, checkout
- Personnaliser le compte client
- Template overrides si nécessaire

### Phase 6 — Contenu dynamique
- Créer les repeaters Settings API (home sections, services, stores, testimonials, stats, milestones, partners)
- Créer les CPTs si nécessaire (témoignages, points de vente)
- Créer les seeders pour les données démo

### Phase 7 — Finalisation
- Bouton flottant WhatsApp (déjà en place, à styliser)
- Suivi de commande
- Optimisation mobile
- Tests et déploiement

## 18. Note sur les conflits de thème

Le thème `astra-kante-volaille` et `astra-delices-de-la-mer` sont **deux thèmes enfants indépendants** du même thème parent `astra`. La ligne `Template: astra` dans `style.css` indique simplement à WordPress que c'est un thème enfant d'Astra. WordPress identifie les thèmes par leur **nom de dossier** (`Theme Name`), pas par la ligne `Template`. 

**Il n'y a aucun conflit** : un seul thème peut être actif à la fois dans WordPress. Quand `astra-delices-de-la-mer` est activé, c'est son `style.css` et son `functions.php` qui sont chargés, pas ceux de `astra-kante-volaille`. Il n'est **pas nécessaire** de modifier quoi que ce soit dans `astra-kante-volaille`.
