# Orr Store

A one-page WooCommerce theme for **store.orrbiologicals.com**: a sticky header with
the cart count, one hero, and a grid of every published product. Nothing else.

There are no product pages and no cart page. Add to cart comes straight back to
the page with the cart panel open (`/#cart`), and product, shop and cart URLs all
301 back to the page. Checkout is the one exception — WooCommerce needs a page of
its own to take payment.

## What the shop sells

The theme creates the catalog itself the first time it is activated on a store
that has no products yet — six strains at $15, marine salt (1,000 g) at $5,
liquid f/2 (50 mL) at $10, the salt + f/2 bundle at $13, and the one-gallon kit
at $35.

| SKU | Product | Price |
| --- | --- | --- |
| ORR-NANNO | Nannochloropsis oculata, 16 oz culture | $15 |
| ORR-TISO | Isochrysis aff. galbana (T-iso), 16 oz culture | $15 |
| ORR-PAVLOVA | Pavlova lutheri, 16 oz culture | $15 |
| ORR-CHAETO | Chaetoceros muelleri, 16 oz culture | $15 |
| ORR-THAL | Thalassiosira pseudonana (3H), 16 oz culture | $15 |
| ORR-TETRA | Tetraselmis suecica, 16 oz culture | $15 |
| ORR-SALT | Marine salt, 1,000 g | $5 |
| ORR-F2 | f/2 nutrient media, 50 mL liquid | $10 |
| ORR-SALT-F2 | Salt + f/2 bundle | $13 |
| ORR-KIT | One-gallon culture kit | $35 |

Photos come from `assets/` and are copied into the media library as the products
are created. Salt, f/2 and the bundle have no photo yet, so WooCommerce shows its
placeholder until you add one.

The catalog is created once and only when the store is empty (`inc/catalog.php`).
An existing catalog is never modified or duplicated, so it is safe to activate
the theme on a live shop. To run it again after deleting the products, remove the
`orr_store_catalog_version` option.

## Install on store.orrbiologicals.com

1. The docroot must be empty of the old static site — delete `index.html` and its
   `assets/` folder first. WordPress and a static `index.html` cannot share a
   document root.
2. In hPanel, install WordPress on **store.orrbiologicals.com**.
3. In WordPress, install and activate **WooCommerce**. Let it create its pages
   (Shop, Cart, Checkout, My account).
4. hPanel → Advanced → **GIT** → connect `cameronorr2011-beep/orr-store-theme`,
   branch `main`. This repo is laid out as `wp-content/themes/orr-store/`, so the
   files land in the right place.
5. Appearance → Themes → activate **Orr Store**. The ten products appear.
6. Settings → Permalinks → **Post name**, and save.
7. WooCommerce → Settings → Shipping: add a US zone with a flat rate or free
   shipping, otherwise checkout says there is no shipping method.

Do not activate the theme before WooCommerce — the catalog waits and creates
itself on the next admin page load once WooCommerce is active.

## Layout

```
wp-content/themes/orr-store/
  style.css        theme header + all CSS
  functions.php    setup, stylesheet, cart count, product query
  front-page.php   the one page: hero + product grid
  page.php         cart, checkout, account, product pages
  header.php       brand and cart count
  footer.php       email line
  index.php        falls back to the front page
  inc/catalog.php  the ten products and their photos
  assets/          product photos copied into the media library
```
