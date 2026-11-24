# Orr Store

A one-page WooCommerce theme for **store.orrbiologicals.com**. One hero, one grid of every published product, a cart link. Cart, checkout and single-product pages inherit a plain page template.

## Install

The repository root is already laid out for Hostinger's WordPress Git deployment:

```
wp-content/themes/orr-store/
├── style.css        theme header + all styles
├── functions.php    setup, WooCommerce support, product query
├── front-page.php   the single page
├── page.php         cart / checkout / product pages
├── header.php
├── footer.php
└── index.php        fallback
```

1. WordPress must be installed at `store.orrbiologicals.com`, with **WooCommerce** activated.
2. Deploy this repo (hPanel → Advanced → GIT, or `git pull` in the docroot) and activate **Orr Store** under Appearance → Themes.
3. Remove or rename any `index.html` in the docroot, otherwise the web server serves that static file instead of WordPress.
4. Settings → Reading → *Your homepage displays* → **A static page** is not required: `front-page.php` renders the storefront for `/` automatically.
5. Create the WooCommerce pages (WooCommerce → Status → Tools → *Create default pages*) so Cart, Checkout and My Account exist.

## Editing

- Hero copy: `orr_store_heading` and `orr_store_intro` filters in `front-page.php`.
- Product order: the grid follows WooCommerce *menu order*, then date.
- Colours and type: CSS custom properties at the top of `style.css`.
