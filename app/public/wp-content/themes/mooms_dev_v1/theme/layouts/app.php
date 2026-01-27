<?php
/**
 * Base app layout.
 *
 * This layout controls the global structure of the theme.
 * It loads the header, main content, and footer sections.
 *
 * @link    https://docs.wpemerge.com/#/framework/views/layouts
 * @package WPEmergeTheme
 */

// If not a PJAX request, render the full header (includes <head> and site navigation)
if (empty($_GET['_pjax'])) :
    WPEmerge\render('header');
else :
    // For PJAX requests, only update the <title> tag for partial page updates
    echo '<title>';
    wp_title();
    echo '</title>';
endif;
?>

<!-- Main content area where page-specific content will be injected -->
<main id="main_content">
    <?php
    global $post;
    $business_list = [];
    if (is_page() && $post) {
        // Nếu là con → lấy cha, nếu là cha → lấy chính nó
        $root_id = $post->post_parent ?: $post->ID;

        // Lấy tất cả page con của root
        $business_list = get_pages([
            'parent' => $root_id,
            'sort_column' => 'menu_order',
            'sort_order' => 'ASC',
        ]);
    }

    if (!empty($business_list)) :
    ?>
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
                            <?php foreach ($business_list as $child) : ?>
                                <li class="<?php echo ($child->ID == $post->ID) ? 'current-menu-item' : ''; ?>">
                                    <a href="<?php echo get_permalink($child->ID); ?>"><?php echo get_the_title($child->ID); ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </aside>
    </div>
    <?php endif; ?>

    <?php
    // Render the main layout content
    WPEmerge\layout_content();
    ?>
</main>

<?php
// If not a PJAX request, render the footer (site-wide footer and scripts)
if (empty($_GET['_pjax'])) :
    WPEmerge\render('footer');
endif;
?>
