<?php

namespace WarpDriven\WgcCore;
class  WGCSettingPage
{


    public $fields;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_page'));
        add_action('admin_init', array($this, 'setting_init'));
        $this->fields = array(
            array("name" => "wgc_api_key", "label" => __("API Key", 'wd-wgc-woo'))
        );
    }

    public function setting_init()
    {

        register_setting(
            'general',
            'wgc_api_key'
        );

        $this->add_settings_fields($this->fields);

    }

    public function add_settings_fields($fields)
    {
        foreach ($fields as $field) {
            register_setting(
                'general',
                $field['name']
            );
        }
    }

    public function section_callback()
    {
        echo '<p>' . '</p>';
    }

    public function add_page()
    {
        $hookname = add_submenu_page(
            'warp-driven-wgc',
            __('WarpDriven GPT Copywriting Setting', 'warp-driven-wgc'),
            __('Setting', 'warp-driven-wgc'),
            'manage_options',
            'warp-driven-wgc-setting',
            array($this, 'page_html'),
            80
        );

        add_action('load-' . $hookname, array($this, 'submit'));
    }

    public function page_html()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        if (isset($_GET['settings-updated'])) {
            add_settings_error('warp-driven-wgc-setting', 'warp-driven-wgc-setting', __('Settings Saved', 'wd-wgc-woo'), 'updated');
        }
        settings_errors('warp-driven-wgc-setting');
        ?>
        <div id="gpt-app" class="wrap">
            <?php
            $data = array();
            foreach ($this->fields as $field) {
                $value = get_option($field['name']);
                $data[$field['name']] = isset($value) ? esc_attr($value) : '';
            }
          
            ?>
            <gpt-setting-page :action="'options.php'" :data="{'wgc_api_key':'<?php echo esc_attr($data['wgc_api_key']); ?>'}">
                <template #footer>
                    <?php
                        settings_fields('general');
                        do_settings_sections('warp-driven-setting');
                        submit_button(__('Save Settings', 'wd-wgc-woo'));
                    ?>
                </template>
            </gpt-setting-page>
        </div>
        <?php
    }

    public function submit()
    {

    }
}