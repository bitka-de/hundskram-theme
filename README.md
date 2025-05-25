# Hundskram Theme

Ein modernes, flexibles WooCommerce-Theme für WordPress mit Fokus auf Modularität, Customizer-Flexibilität und zeitgemäßes UI/UX.

## Features

- **Modularer Header**: Logo, Navigation, Warenkorb, Suche und User-Login als wiederverwendbare Methoden (PHP 8 Syntax).
- **AJAX-Livesuche**: Schnelle Produktsuche mit modernen UI-Elementen.
- **Responsiver Footer**: 4-Spalten-Grid, dynamisch befüllbar über den Customizer.
- **Customizer-Integration**: 
  - Alle Footer-Spalten (1: HTML, 2-4: Überschrift + bis zu 5 Seiten-Links) pflegbar.
  - Bis zu 6 Social-Links (freie URLs) im Footer, Netzwerkname wird automatisch aus Domain extrahiert.
- **Dynamische Social-Links**: Automatische Erkennung und Ausgabe des Netzwerk-Namens im Footer.
- **Wartbare, moderne Codebasis**: PHP 8, OOP, best practices.

## Theme-Struktur

```
wp-content/themes/hundskram-theme/
├── assets/
│   └── css/
│       └── styles.css         # Styles für Header, Footer, Suche etc.
├── classes/
│   ├── Header.php            # Header-Methoden (Logo, Nav, Cart, Suche, User)
│   ├── Theme.php             # Theme-Setup, Enqueue, etc.
│   └── WooSupport.php        # WooCommerce-spezifische Anpassungen
├── footer.php                # Footer-Template (Customizer-gesteuert)
├── functions.php             # Customizer-Logik, AJAX, Hilfsfunktionen
├── header.php                # Header-Template (modularisiert)
├── README.md                 # Diese Datei
└── ... (weitere WP-Standarddateien)
```

## Installation

1. Theme-Ordner `hundskram-theme` nach `wp-content/themes/` kopieren.
2. Im WordPress-Backend unter **Design > Themes** aktivieren.
3. (Optional) WooCommerce installieren und aktivieren.

## Customizer-Konfiguration

Gehe zu **Design > Customizer** und passe folgende Bereiche an:

### Footer
- **Footer Spalte 1**: Überschrift & freier HTML-Inhalt (z.B. Slogan, Logo).
- **Footer Spalten 2-4**: Überschrift & bis zu 5 auswählbare Seiten-Links pro Spalte.
- **Footer Social Media**: Bis zu 6 Social-Links (freie URLs, z.B. https://instagram.com/deinshop). Das Theme erkennt automatisch das Netzwerk und zeigt den Namen im Footer an.

### Header
- Logo, Navigation, Warenkorb, Suche und User-Login werden automatisch aus den Theme-Einstellungen und WooCommerce generiert.

## How to Use

- **Header anpassen**: Passe das Logo im Customizer an. Navigation erfolgt über WordPress-Menüs.
- **Footer pflegen**: Trage Inhalte und Links im Customizer ein. Social-Links werden automatisch erkannt und ausgegeben.
- **Styles anpassen**: Bearbeite `assets/css/styles.css` für eigene Anpassungen.
- **Livesuche**: Die AJAX-Suche ist direkt im Header integriert und funktioniert für Produkte und Inhalte.

## Entwicklung & Erweiterung

- Neue Header-Elemente können als Methode in `classes/Header.php` ergänzt werden.
- Zusätzliche Footer-Spalten oder Social-Icons können über die Customizer-Logik in `functions.php` erweitert werden.
- Für eigene WooCommerce-Anpassungen siehe `classes/WooSupport.php`.

## Hinweise

- Theme ist für PHP 8+ optimiert.
- Social-Icons werden aktuell als Netzwerkname ausgegeben. Für Icon-Integration siehe TODO in `footer.php`.
- Standardwerte für Footer-Spalten sind im Customizer hinterlegt.

## Support & Lizenz

Dieses Theme ist ein individuelles Projekt. Für Support oder Erweiterungen bitte den Entwickler kontaktieren.

---

© Hundskram – Alle Rechte vorbehalten.
