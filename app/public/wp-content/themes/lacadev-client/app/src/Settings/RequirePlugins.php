<?php

namespace App\Settings;

class RequirePlugins
{
    private $option_name = 'laca_required_plugins_settings';

    public function __construct()
    {
        require_once APP_DIR . 'app/lib/tgm-plugin-activation/class-tgm-plugin-activation.php';
        add_action('tgmpa_register', [$this, 'registerRequirePlugins']);
        add_action('admin_menu', [$this, 'addAdminMenu'], 20);
    }

    private function getAvailablePlugins()
    {
        return [
            [
                'name' => 'Wordfence Security – Firewall, Malware Scan, and Login Security',
                'slug' => 'wordfence',
                'required' => true,
                'force_activation' => true,
                'force_deactivation' => true,
            ],
            [
                'name' => 'WPS Hide Login',
                'slug' => 'wps-hide-login',
                'required' => true,
                'force_activation' => true,
                'force_deactivation' => true,
            ],
            [
                'name' => 'Rank Math SEO',
                'slug' => 'seo-by-rank-math',
                'required' => true,
                'force_activation' => true,
                'force_deactivation' => true,
            ],
        ];
    }

    public function addAdminMenu()
    {
        add_submenu_page(
            'laca-admin',
            __('Plugin Require', 'laca'),
            __('Plugin Require', 'laca'),
            'manage_options',
            'laca-plugin-require',
            [$this, 'renderAdminPage']
        );
    }

    public function renderAdminPage()
    {
        if (isset($_POST['laca_save_plugins']) && check_admin_referer('laca_save_plugins_nonce')) {
            $active_plugins = isset($_POST['laca_plugins']) ? (array) $_POST['laca_plugins'] : [];
            update_option($this->option_name, $active_plugins);
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved.', 'laca') . '</p></div>';
        }

        $all_plugins = $this->getAvailablePlugins();
        $saved_settings = get_option($this->option_name, false);
        
        if ($saved_settings === false) {
            $saved_settings = array_column($all_plugins, 'slug');
        }

        ?>
        <div class="wrap">
            <h1><?php _e('Plugin Require Settings', 'laca'); ?></h1>
            <form method="post" action="">
                <?php wp_nonce_field('laca_save_plugins_nonce'); ?>
                <table class="form-table">
                    <tbody>
                        <?php foreach ($all_plugins as $plugin): ?>
                            <tr>
                                <th scope="row"><?php echo esc_html($plugin['name']); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="laca_plugins[]" value="<?php echo esc_attr($plugin['slug']); ?>" <?php checked(in_array($plugin['slug'], $saved_settings)); ?>>
                                        <?php _e('Require this plugin', 'laca'); ?>
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <input type="hidden" name="laca_save_plugins" value="1">
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function registerRequirePlugins()
    {
        $all_plugins = $this->getAvailablePlugins();
        $saved_settings = get_option($this->option_name, false);
        
        if ($saved_settings === false) {
            $saved_settings = array_column($all_plugins, 'slug');
        }

        $plugins = [];
        foreach ($all_plugins as $plugin) {
            if (in_array($plugin['slug'], $saved_settings)) {
                $plugins[] = $plugin;
            }
        }

        $config = [
            'id' => 'laca',
            'default_path' => '',
            'menu' => 'tgmpa-install-plugins',
            'parent_slug' => 'themes.php',
            'capability' => 'edit_theme_options',
            'has_notices' => true,
            'dismissable' => true,
            'dismiss_msg' => '',
            'is_automatic' => false,
            'message' => '',

            'strings' => [
                'page_title' => __('Please install the necessary plugins', 'laca'),
                'menu_title' => __('Plugins', 'laca'),
                'installing' => __('Installing plugins: %s', 'laca'),
                'updating' => __('Updating plugins: %s', 'laca'),
                'oops' => __('An error occurred while communicating with the plugins API.', 'laca'),
                'notice_can_install_required' => _n_noop('This theme set requires the following plugins to be installed: %1$s.', 'This theme set requires the following plugins to be installed: %1$s.', 'laca'),
                'notice_can_install_recommended' => _n_noop('This set of themes recommends installing and using the following plugins: %1$s.', 'This set of themes recommends installing and using the following plugins: %1$s.', 'laca'),
                'notice_ask_to_update' => _n_noop(
                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                    'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                    'laca'
                ),
                'notice_ask_to_update_maybe' => _n_noop(
                    'There is an update available for: %1$s.',
                    'There are updates available for the following plugins: %1$s.',
                    'laca'
                ),
                'notice_can_activate_required' => _n_noop(
                    'The following required plugin is currently inactive: %1$s.',
                    'The following required plugins are currently inactive: %1$s.',
                    'laca'
                ),
                'notice_can_activate_recommended' => _n_noop(
                    'The following recommended plugin is currently inactive: %1$s.',
                    'The following recommended plugins are currently inactive: %1$s.',
                    'laca'
                ),
                'install_link' => _n_noop(
                    'Begin installing plugin',
                    'Begin installing plugins',
                    'laca'
                ),
                'update_link' => _n_noop(
                    'Begin updating plugin',
                    'Begin updating plugins',
                    'laca'
                ),
                'activate_link' => _n_noop(
                    'Begin activating plugin',
                    'Begin activating plugin',
                    'laca'
                ),
                'return' => __('Return to Required Plugins Installer', 'laca'),
                'plugin_activated' => __('Plugin activated successfully.', 'laca'),
                'activated_successfully' => __('The following plugin was activated successfully:', 'laca'),
                'plugin_already_active' => __('No action taken. Plugin %1$s was already active.', 'laca'),
                'plugin_needs_higher_version' => __('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'laca'),
                'complete' => __('All plugins installed and activated successfully. %1$s', 'laca'),
                'dismiss' => __('Dismiss this notice', 'laca'),
                'notice_cannot_install_activate' => __('There are one or more required or recommended plugins to install, update or activate.', 'laca'),
                'contact_admin' => __('Please contact the administrator of this site for help.', 'laca'),
                'nag_type' => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
            ],
        ];

        tgmpa($plugins, $config);
    }
}
