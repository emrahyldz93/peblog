<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Set default dark mode from Customizer
if (get_theme_mod('peblog_default_dark_mode', true)) {
    ?>
    <script>
        if (localStorage.getItem('theme') === null) {
            localStorage.setItem('theme', 'dark');
        }
    </script>
    <?php
}
?>

<!-- header start -->
<header class="header">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo light-logo">
        <?php
        $custom_logo = get_theme_mod('peblog_logo', '');
        if ($custom_logo) {
            echo '<img src="' . esc_url($custom_logo) . '" alt="' . get_bloginfo('name') . '" />';
        } else {
            echo '<span class="site-logo-text">Peblog</span>';
        }
        ?>
    </a>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo dark-logo">
        <?php
        $custom_logo_white = get_theme_mod('peblog_logo_white', '');
        if ($custom_logo_white) {
            echo '<img src="' . esc_url($custom_logo_white) . '" alt="' . get_bloginfo('name') . '" />';
        } else {
            echo '<span class="site-logo-text">Peblog</span>';
        }
        ?>
    </a>
    
    <div class="mobile-burger-menu">
        <span></span>
        <span></span>
        <span></span>
    </div>
    
    <nav class="navbar">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class' => 'nav-menu',
            'container' => false,
            'fallback_cb' => 'peblog_fallback_menu',
            'walker' => new Peblog_Walker_Nav_Menu(),
        ));
        ?>
        
        <div class="navbar-controls">
            <span class="dark-mode" style="--i: 5">
                <i class="dark-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-opacity="0" stroke="currentColor" stroke-dasharray="64" stroke-dashoffset="64" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c-4.97 0 -9 4.03 -9 9c0 4.97 4.03 9 9 9c3.53 0 6.59 -2.04 8.06 -5c0 0 -6.06 1.5 -9.06 -3c-3 -4.5 1 -10 1 -10Z"><animate fill="freeze" attributeName="fill-opacity" begin="0.6s" dur="0.5s" values="0;1"/><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.6s" values="64;0"/></path></svg>
                </i>
                <i class="light-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-dasharray="2" stroke-dashoffset="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 19v1M19 12h1M12 5v-1M5 12h-1"><animate fill="freeze" attributeName="d" begin="1.2s" dur="0.2s" values="M12 19v1M19 12h1M12 5v-1M5 12h-1;M12 21v1M21 12h1M12 3v-1M3 12h-1"/><animate fill="freeze" attributeName="stroke-dashoffset" begin="1.2s" dur="0.2s" values="2;0"/></path><path d="M17 17l0.5 0.5M17 7l0.5 -0.5M7 7l-0.5 -0.5M7 17l-0.5 0.5"><animate fill="freeze" attributeName="d" begin="1.4s" dur="0.2s" values="M17 17l0.5 0.5M17 7l0.5 -0.5M7 7l-0.5 -0.5M7 17l-0.5 0.5;M18.5 18.5l0.5 0.5M18.5 5.5l0.5 -0.5M5.5 5.5l-0.5 -0.5M5.5 18.5l-0.5 0.5"/><animate fill="freeze" attributeName="stroke-dashoffset" begin="1.4s" dur="0.2s" values="2;0"/></path></g><g fill="currentColor"><path d="M15.22 6.03L17.75 4.09L14.56 4L13.5 1L12.44 4L9.25 4.09L11.78 6.03L10.87 9.09L13.5 7.28L16.13 9.09L15.22 6.03Z"><animate fill="freeze" attributeName="fill-opacity" dur="0.4s" values="1;0"/></path><path d="M19.61 12.25L21.25 11L19.19 10.95L18.5 9L17.81 10.95L15.75 11L17.39 12.25L16.8 14.23L18.5 13.06L20.2 14.23L19.61 12.25Z"><animate fill="freeze" attributeName="fill-opacity" begin="0.2s" dur="0.4s" values="1;0"/></path></g><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6 C7 12.08 11.92 17 18 17 C18.53 17 19.05 16.96 19.56 16.89 C17.95 19.36 15.17 21 12 21 C7.03 21 3 16.97 3 12 C3 8.83 4.64 6.05 7.11 4.44 C7.04 4.95 7 5.47 7 6 Z"><set fill="freeze" attributeName="opacity" begin="0.6s" to="0"/></path><mask id="SVGBZ2FMbRt"><circle cx="12" cy="12" r="12" fill="#fff"/><circle cx="18" cy="6" r="12" fill="#fff"><animate fill="freeze" attributeName="cx" begin="0.6s" dur="0.4s" values="18;22"/><animate fill="freeze" attributeName="cy" begin="0.6s" dur="0.4s" values="6;2"/><animate fill="freeze" attributeName="r" begin="0.6s" dur="0.4s" values="12;3"/></circle><circle cx="18" cy="6" r="10"><animate fill="freeze" attributeName="cx" begin="0.6s" dur="0.4s" values="18;22"/><animate fill="freeze" attributeName="cy" begin="0.6s" dur="0.4s" values="6;2"/><animate fill="freeze" attributeName="r" begin="0.6s" dur="0.4s" values="10;1"/></circle></mask><circle cx="12" cy="12" r="10" mask="url(#SVGBZ2FMbRt)" opacity="0" fill="currentColor"><animate fill="freeze" attributeName="r" begin="0.6s" dur="0.4s" values="10;6"/><set fill="freeze" attributeName="opacity" begin="0.6s" to="1"/></circle></svg>
                </i>
            </span>
            
            <a href="<?php echo esc_url(get_theme_mod('peblog_action_button_url', '#')); ?>" 
               target="<?php echo esc_attr(get_theme_mod('peblog_action_button_target', '_self')); ?>" 
               class="subscribe btn" 
               style="--i: 6">
                <?php echo esc_html(get_theme_mod('peblog_action_button_text', 'Subscribe')); ?>
            </a>
        </div>
    </nav>
    
    <div class="header-controls">
        <span class="dark-mode" style="--i: 5">
            <i class="dark-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-opacity="0" stroke="currentColor" stroke-dasharray="64" stroke-dashoffset="64" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c-4.97 0 -9 4.03 -9 9c0 4.97 4.03 9 9 9c3.53 0 6.59 -2.04 8.06 -5c0 0 -6.06 1.5 -9.06 -3c-3 -4.5 1 -10 1 -10Z"><animate fill="freeze" attributeName="fill-opacity" begin="0.6s" dur="0.5s" values="0;1"/><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.6s" values="64;0"/></path></svg>
            </i>
            <i class="light-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-dasharray="2" stroke-dashoffset="2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 19v1M19 12h1M12 5v-1M5 12h-1"><animate fill="freeze" attributeName="d" begin="1.2s" dur="0.2s" values="M12 19v1M19 12h1M12 5v-1M5 12h-1;M12 21v1M21 12h1M12 3v-1M3 12h-1"/><animate fill="freeze" attributeName="stroke-dashoffset" begin="1.2s" dur="0.2s" values="2;0"/></path><path d="M17 17l0.5 0.5M17 7l0.5 -0.5M7 7l-0.5 -0.5M7 17l-0.5 0.5"><animate fill="freeze" attributeName="d" begin="1.4s" dur="0.2s" values="M17 17l0.5 0.5M17 7l0.5 -0.5M7 7l-0.5 -0.5M7 17l-0.5 0.5;M18.5 18.5l0.5 0.5M18.5 5.5l0.5 -0.5M5.5 5.5l-0.5 -0.5M5.5 18.5l-0.5 0.5"/><animate fill="freeze" attributeName="stroke-dashoffset" begin="1.4s" dur="0.2s" values="2;0"/></path></g><g fill="currentColor"><path d="M15.22 6.03L17.75 4.09L14.56 4L13.5 1L12.44 4L9.25 4.09L11.78 6.03L10.87 9.09L13.5 7.28L16.13 9.09L15.22 6.03Z"><animate fill="freeze" attributeName="fill-opacity" dur="0.4s" values="1;0"/></path><path d="M19.61 12.25L21.25 11L19.19 10.95L18.5 9L17.81 10.95L15.75 11L17.39 12.25L16.8 14.23L18.5 13.06L20.2 14.23L19.61 12.25Z"><animate fill="freeze" attributeName="fill-opacity" begin="0.2s" dur="0.4s" values="1;0"/></path></g><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6 C7 12.08 11.92 17 18 17 C18.53 17 19.05 16.96 19.56 16.89 C17.95 19.36 15.17 21 12 21 C7.03 21 3 16.97 3 12 C3 8.83 4.64 6.05 7.11 4.44 C7.04 4.95 7 5.47 7 6 Z"><set fill="freeze" attributeName="opacity" begin="0.6s" to="0"/></path><mask id="SVGBZ2FMbRt"><circle cx="12" cy="12" r="12" fill="#fff"/><circle cx="18" cy="6" r="12" fill="#fff"><animate fill="freeze" attributeName="cx" begin="0.6s" dur="0.4s" values="18;22"/><animate fill="freeze" attributeName="cy" begin="0.6s" dur="0.4s" values="6;2"/><animate fill="freeze" attributeName="r" begin="0.6s" dur="0.4s" values="12;3"/></circle><circle cx="18" cy="6" r="10"><animate fill="freeze" attributeName="cx" begin="0.6s" dur="0.4s" values="18;22"/><animate fill="freeze" attributeName="cy" begin="0.6s" dur="0.4s" values="6;2"/><animate fill="freeze" attributeName="r" begin="0.6s" dur="0.4s" values="10;1"/></circle></mask><circle cx="12" cy="12" r="10" mask="url(#SVGBZ2FMbRt)" opacity="0" fill="currentColor"><animate fill="freeze" attributeName="r" begin="0.6s" dur="0.4s" values="10;6"/><set fill="freeze" attributeName="opacity" begin="0.6s" to="1"/></circle></svg>
            </i>
        </span>
        
        <a href="<?php echo esc_url(get_theme_mod('peblog_action_button_url', '#')); ?>" 
           target="<?php echo esc_attr(get_theme_mod('peblog_action_button_target', '_self')); ?>" 
           class="subscribe btn" 
           style="--i: 3">
            <?php echo esc_html(get_theme_mod('peblog_action_button_text', 'Subscribe')); ?>
        </a>
    </div>
</header>
<!-- header end -->

<?php
// Fallback menu if no menu is assigned
function peblog_fallback_menu() {
    echo '<a href="' . esc_url(home_url('/')) . '" class="active">Home</a>';
    echo '<a href="' . esc_url(home_url('/blog')) . '">Blog</a>';
    echo '<a href="' . esc_url(home_url('/category')) . '">Category</a>';
    echo '<a href="' . esc_url(home_url('/contact')) . '">Contact</a>';
}
?>
