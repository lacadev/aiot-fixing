<?php

/**
 * Theme header partial.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WPEmergeTheme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light">

<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_head(); ?>

	<link rel="apple-touch-icon" sizes="57x57" href="<?php theAsset('favicon/apple-icon-57x57.png'); ?>">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php theAsset('favicon/apple-icon-60x60.png'); ?>">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php theAsset('favicon/apple-icon-72x72.png'); ?>">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php theAsset('favicon/apple-icon-76x76.png'); ?>">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php theAsset('favicon/apple-icon-114x114.png'); ?>">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php theAsset('favicon/apple-icon-120x120.png'); ?>">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php theAsset('favicon/apple-icon-144x144.png'); ?>">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php theAsset('favicon/apple-icon-152x152.png'); ?>">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php theAsset('favicon/apple-icon-180x180.png'); ?>">
	<link rel="icon" type="image/png" sizes="192x192" href="<?php theAsset('favicon/android-icon-192x192.png'); ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php theAsset('favicon/favicon-32x32.png'); ?>">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php theAsset('favicon/favicon-96x96.png'); ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php theAsset('favicon/favicon-16x16.png'); ?>">
	<link rel="manifest" href="<?php theAsset('favicon/manifest.json'); ?>">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php theAsset('favicon/ms-icon-144x144.png'); ?>">
	<meta name="theme-color" content="#ffffff">
	<script src="https://code.iconify.design/3/3.0.0/iconify.min.js"></script>

	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-KRRTBFFN');</script>
		<!-- End Google Tag Manager -->
			<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-G6D9SPWNE5"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-G6D9SPWNE5');
	</script>
</head>

<body <?php body_class(); ?>>

	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KRRTBFFN" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

	<?php
	app_shim_wp_body_open();

	if (is_home() || is_front_page()) :
		echo '<h1 class="site-name d-none">' . get_bloginfo('name') . '</h1>';
	endif;
	?>

	<div class="wrapper_mm" id="swup">
		<header id="header" class="header">
			<div class="inner-header">
				<div id="logo" class="logo">
					<a href="<?php bloginfo('url');?>" title="<?php bloginfo('name');?>">
						<img src="<?php theOptionImage('logo');?>" width="170" height="75" alt="<?php bloginfo('name');?>">
					</a>
				</div>
				<div class="content-menu">
					<?php
					wp_nav_menu([
						'theme_location' => 'main-menu',
						'menu_class'     => 'nav_menu',
						'container'      => 'nav',
						'container_class'=> 'header__nav-container',
						'walker'         => new MMS_Menu_Walker(),
					]);
					?>

					<div class="language">
						<div class="global">
							<?php theLanguageSwitcher();?>
						</div>
						<div class="current-language">
							<?php
							$current_language_name = pll_current_language('name');
							echo $current_language_name;
							?>
						</div>
					</div>

					<div class="contact">
						<a href="/contact" target="_blank">
							<svg viewBox="0 0 28 19" xmlns="http://www.w3.org/2000/svg">
							<path d="M5.89153 9.60178C5.80696 9.60178 5.72285 9.57795 5.65139 9.5316L0.264398 6.04566C0.123825 5.95471 0.0561156 5.79378 0.0928988 5.63766C0.129448 5.48199 0.263461 5.36216 0.43168 5.33462L27.4215 0.910455C27.6351 0.875253 27.842 0.997259 27.9006 1.19186C27.9592 1.38624 27.85 1.59089 27.6485 1.66458L6.0457 9.57467C5.99603 9.59282 5.94355 9.60178 5.89177 9.60178H5.89153ZM1.58766 5.94443L5.94706 8.76523L23.4285 2.36448L1.58766 5.94443Z" ></path>
							<path d="M15.9787 16.8496C15.8938 16.8496 15.8096 16.8269 15.739 16.7806L7.78951 11.5661C7.66748 11.4858 7.59802 11.3514 7.6061 11.2109C7.61443 11.0704 7.69959 10.9443 7.83019 10.8773L27.2847 0.952809C27.4617 0.863338 27.6786 0.900655 27.8104 1.04228C27.9419 1.18435 27.9519 1.39364 27.8347 1.54651C23.373 7.36008 16.6355 16.1768 16.361 16.6383C16.302 16.7376 16.199 16.8127 16.0815 16.8381C16.0477 16.8455 16.0132 16.8491 15.979 16.8491L15.9787 16.8496ZM8.85045 11.2768L15.8688 15.8807C17.2362 14.0016 23.3989 5.95283 26.0399 2.50776L8.85045 11.2768Z" ></path>
							<path d="M8.62085 18.2992C8.49498 18.2992 8.39703 18.2321 8.2354 17.8804H8.22854C8.22854 17.8767 8.2283 17.8713 8.2283 17.8642C7.98757 17.3339 7.60506 16.1797 6.78591 13.7085C6.07328 11.5576 5.36801 9.40557 5.36801 9.40557C5.29674 9.18946 5.42825 8.96061 5.66138 8.89486C5.89353 8.82912 6.14063 8.9506 6.21115 9.16694C6.77783 10.8952 7.50539 13.1025 8.08504 14.842C8.02602 13.6248 7.95794 12.2473 7.90186 11.1185C7.8906 10.8924 8.07867 10.7011 8.3216 10.6906C8.566 10.6813 8.77072 10.8549 8.78223 11.0806C8.81578 11.7562 9.10989 17.6868 9.10989 17.8804C9.10989 18.0739 8.94925 18.2587 8.73301 18.2885C8.69187 18.2942 8.65514 18.2992 8.62061 18.2992H8.62085Z" ></path>
							<path d="M8.75414 18.3008C8.65363 18.3008 8.55312 18.2587 8.47597 18.1744C8.31969 18.0043 8.3177 17.7257 8.47134 17.5525L12.1914 13.3571C12.3448 13.1836 12.5961 13.1819 12.7524 13.352C12.9087 13.5221 12.9107 13.8007 12.757 13.974L9.03694 18.1693C8.95935 18.257 8.85664 18.3008 8.75414 18.3008Z" ></path>
							</svg>
							<?= _e('Contact','mms');?>
						</a>
					</div>
				</div>
			</div>
		</header>

		<div id="list-content" class="list-content">
			<aside class="list-menu">
				<button class="list-menu__toggle" type="button">
					<span class="list-menu__label">Business List</span>
					<span class="list-menu__icon">
						<span class="list-menu__bar"></span>
						<span class="list-menu__bar"></span>
						<span class="list-menu__bar"></span>
					</span>
				</button>

				<div class="list-modal mm-container">
					<div class="list-modal__overlay">
					</div>
					<div class="list-modal__content">
						<div class="list-modal__header">
							<h3>Business List</h3>
						</div>
						<nav class="list-sidebar">
							<ul class="list-sidebar__child-list">
								<li><a href="#">トップメッセージ</a></li>
								<li><a href="#">沿革</a></li>
								<li><a href="#">ビジョン・ミッション</a></li>
								<li><a href="#">パートナーズ・ネットワーク</a></li>
								<li><a href="#">会社概要</a></li>
								<li><a href="#">役員紹介</a></li>
								<li><a href="#">開発実績</a></li>
							</ul>
						</nav>
					</div>
				</div>
			</aside>
		</div>
		<!-- Bottom Navigation for mobile -->
		<div class="mobile-header">
			<?php
			wp_nav_menu([
				'theme_location' => 'mobile-menu',
				'menu_class'     => 'nav_menu',
				'container'      => 'nav',
				'container_class'=> 'header__mobile-container',
				'walker'         => new MMS_Menu_Walker(),
			]);
			?>

			<!-- Menu -->
			<div class="mobile-header__menu-dropdown">
				<button type="button" class="mobile-header__menu-toggle">
					<span class="iconify" data-icon="mdi:menu"></span>
					<a class="menu-toggle__label">Menu</a>
				</button>
			</div>
		</div>

		<div class="mobile-menu">
			<div class="mobile-menu__inner">
				<div class="mobile-menu__header">
					<div id="logo" class="logo">
						<a href="<?php bloginfo('url');?>" title="<?php bloginfo('name');?>">
							<img src="<?php theOptionImage('logo');?>" width="170" height="75" alt="<?php bloginfo('name');?>">
						</a>
					</div>
					<div class="language">
						<div class="global">
							<?php theLanguageSwitcher();?>
						</div>
						<div class="current-language">
							<?php
							$current_language_name = pll_current_language('name');
							echo $current_language_name;
							?>
						</div>
					</div>
				</div>
				<div class="mobile-menu__content">
					<?php
					wp_nav_menu([
					'theme_location' => 'main-menu',
					'menu_class'     => 'mobile-menu__menu',
					'container'      => false,
					]);
					?>
				</div>
			</div>
			<div class="mobile-menu__close">
				<button class="button-close">
					<span class="button-close__icon"></span>
  					<span class="button-close__label">Close</span>
				</button>
			</div>
		</div>