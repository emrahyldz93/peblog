# Peblog Theme Hooks Documentation

Bu döküman, Peblog temasında kullanılabilir tüm hook'ları ve bunların premium/child temalar için nasıl kullanılacağını açıklar.

## Template Part Hooks

Ana sayfa template partları için hook'lar mevcuttur. Premium sürümde veya child temada farklı template partlar kullanmak için bu hook'ları kullanabilirsiniz.

### Kullanım Örnekleri

#### 1. Default Template Part'ı Değiştirme

```php
// functions.php dosyanızda

// Önce default action'ı kaldır
remove_action('peblog_hero_slider', 'peblog_render_hero_slider', 10);

// Sonra kendi template part'ınızı ekleyin
function my_custom_hero_slider() {
    get_template_part('template-parts/hero/custom-hero-slider');
}
add_action('peblog_hero_slider', 'my_custom_hero_slider', 10);
```

#### 2. Template Part'a Önce veya Sonra İçerik Ekleme

```php
// Template part'tan önce içerik ekleme
function add_before_featured_posts() {
    echo '<div class="custom-banner">Özel Banner</div>';
}
add_action('peblog_featured_posts', 'add_before_featured_posts', 5);

// Template part'tan sonra içerik ekleme
function add_after_featured_posts() {
    echo '<div class="custom-footer">Özel Footer</div>';
}
add_action('peblog_featured_posts', 'add_after_featured_posts', 15);
```

#### 3. Conditional Template Part (Koşullu)

```php
function conditional_latest_posts() {
    if (is_user_logged_in()) {
        get_template_part('template-parts/latest-posts/premium-latest-posts');
    } else {
        get_template_part('template-parts/latest-posts/latest-posts');
    }
}

// Önce default'u kaldır
remove_action('peblog_latest_posts', 'peblog_render_latest_posts', 10);

// Yeni conditional function'ı ekle
add_action('peblog_latest_posts', 'conditional_latest_posts', 10);
```

## Mevcut Hook'lar

### 1. `peblog_hero_slider`
- **Öncelik**: 10
- **Default Function**: `peblog_render_hero_slider()`
- **Açıklama**: Ana sayfadaki hero slider bölümünü render eder.

### 2. `peblog_featured_posts`
- **Öncelik**: 10
- **Default Function**: `peblog_render_featured_posts()`
- **Açıklama**: Featured posts bölümünü render eder.

### 3. `peblog_latest_posts`
- **Öncelik**: 10
- **Default Function**: `peblog_render_latest_posts()`
- **Açıklama**: Latest posts bölümünü render eder.

### 4. `peblog_most_read`
- **Öncelik**: 10
- **Default Function**: `peblog_render_most_read()`
- **Açıklama**: Most read posts bölümünü render eder.

### 5. `peblog_category_filter`
- **Öncelik**: 10
- **Default Function**: `peblog_render_category_filter()`
- **Açıklama**: Category filter bölümünü render eder.

## Hook Priority (Öncelik) Sistemi

WordPress hook sistemi priority (öncelik) kullanır:
- **Düşük sayı** = Daha erken çalışır
- **Yüksek sayı** = Daha geç çalışır

Örnek:
```php
// İlk önce çalışır (5)
add_action('peblog_featured_posts', 'my_function', 5);

// Sonra çalışır (10 - default)
add_action('peblog_featured_posts', 'peblog_render_featured_posts', 10);

// En son çalışır (15)
add_action('peblog_featured_posts', 'my_other_function', 15);
```

## Premium Sürüm İçin Örnek

Premium sürümde tüm template partları değiştirmek için:

```php
// Premium theme functions.php

// Tüm default template part'ları kaldır
remove_action('peblog_hero_slider', 'peblog_render_hero_slider', 10);
remove_action('peblog_featured_posts', 'peblog_render_featured_posts', 10);
remove_action('peblog_latest_posts', 'peblog_render_latest_posts', 10);
remove_action('peblog_most_read', 'peblog_render_most_read', 10);
remove_action('peblog_category_filter', 'peblog_render_category_filter', 10);

// Premium template part'ları ekle
add_action('peblog_hero_slider', 'peblog_premium_render_hero_slider', 10);
add_action('peblog_featured_posts', 'peblog_premium_render_featured_posts', 10);
add_action('peblog_latest_posts', 'peblog_premium_render_latest_posts', 10);
add_action('peblog_most_read', 'peblog_premium_render_most_read', 10);
add_action('peblog_category_filter', 'peblog_premium_render_category_filter', 10);

// Premium render functions
function peblog_premium_render_hero_slider() {
    get_template_part('template-parts/hero/premium-hero-slider');
}

function peblog_premium_render_featured_posts() {
    get_template_part('template-parts/featured-posts/premium-featured-posts');
}

// ... diğer functions
```

## Notlar

1. Hook'ları kaldırmak için `remove_action()` kullanın, ancak theme'in tam yüklenmesinden sonra çalıştığından emin olun.
2. `after_setup_theme` veya `init` action hook'ları içinde `remove_action()` çağırın.
3. Priority değerlerini değiştirerek hook'ların çalışma sırasını kontrol edebilirsiniz.
4. Child theme kullanırken, parent theme'in functions.php'sinin yüklenmesini bekleyin.

## Sorun Giderme

Eğer hook çalışmıyorsa:
1. `remove_action()` ve `add_action()` çağrılarınızın doğru priority'ye sahip olduğundan emin olun.
2. Hook'ların theme yüklendikten sonra çalıştığından emin olun (ör. `init` veya `after_setup_theme` içinde).
3. Function isimlerinin benzersiz olduğundan emin olun.

