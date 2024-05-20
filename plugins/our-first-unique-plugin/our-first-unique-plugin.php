<?php

    /*
      Plugin Name: Our Test Plugin
      Description: A truly amazing plugin.
      Version: 1.0
      Author: Choi
      Author URI: https://www.fictional.saichoiblog.com/
    */

    class WordCountAndTimePlugin {
        function __construct() {
            add_action('admin_menu', array($this, 'adminPage'));
            add_action('admin_init',array($this, 'settings'));
            add_filter('the_content', array($this, 'ifWrap')); ; // 호출하고 워드프레스 훅의 타이밍에 맞게 실행하기 위함.
        }

        function ifWrap($content) {
            if (is_main_query() AND is_single() AND
                (
                        get_option('wcp_wordcount', '1') OR
                        get_option('wcp_charactercount', '1') OR
                        get_option('wcp_readtime', '1')
                )) {
                return $this->createHTML($content); // 호출하고 바로 실행하기 위함.
            }
            return $content;
        }

        function createHTML($content) {
            $html = '<h3>'.esc_html(get_option('wcp_headline', 'Post Statistics')).'</h3><p>';

            // get word count once because both wordcount and read time will need it.
            if (get_option('wcp_wordcount', '1') == '1' OR get_option('wcp_readtime', '1') == '1') {
                $wordCount = str_word_count(strip_tags($content));
            }

            if (get_option('wcp_wordcount', '1') == '1') {
                $html .= 'This post has ' . $wordCount . 'words.<br>';
            }

            if (get_option('wcp_charactercount', '1') == '1') {
                $html .= 'This post has ' . strlen(strip_tags($content)) . 'characters.<br>';
            }

            if (get_option('wcp_readtime', '1') == '1') {
                $html .= 'This post will take about ' . round($wordCount/225) . 'minute(s) to read.<br>';
            }

            $html .= '</p>';

            // Beginning of post
            if (get_option('wcp_location', '0') == '0') {
                return $html.$content;
            }

            // End of post
            return $content.$html;
        }

        function settings() {
            add_settings_section('wcp_first_section', null, null, 'word-count-settings-page'); // Section, Sub Title, Content, Slug

            add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section'); // Setting Name, Label Text, HTML, Slug, Section
            register_setting('wordcountplugin', 'wcp_location', array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => '0')); // Group,  Setting Name, Options

            add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
            register_setting('wordcountplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistic'));

            add_settings_field('wcp_wordcount', 'Word Count', array($this, 'checkboxHtml'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_wordcount'));
            register_setting('wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

            add_settings_field('wcp_charactercount', 'Character Count', array($this, 'checkboxHtml'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_charactercount'));
            register_setting('wordcountplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

            add_settings_field('wcp_readtime', 'Read Time', array($this, 'checkboxHtml'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_readtime'));
            register_setting('wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
        }

        function sanitizeLocation($input) {
            if ($input != '0' AND $input != '1') {
                add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be either beginning or end.');
                return get_option('wcp_location');
            }

            return $input;
        }

        // reusable checkbox function
        function checkboxHtml($args) {?>
            <input type="checkbox" name="<?php echo $args['theName'] ?>" value="1" <?php checked(get_option($args['theName']))?>>
        <?php }

        function headlineHTML() { ?>
            <input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline'))?>">
        <?php }

        function locationHTML() { ?>
            <select name="wcp_location">
                <option value="0" <?php selected(get_option('wcp_location'), '0') ?>>Beginning of post</option>
                <option value="1" <?php selected(get_option('wcp_location'), '1') ?>>End of post</option>
            </select>
        <?php }
        function adminPage() {
            add_options_page('Word Count Settings', 'Word Count', 'manage_options', 'word-count-settings-page', array($this, 'ourHtml')); // Document Title, Setting Menu Name, Role(Manage_options : 옵션 변경 권한을 가진 경우), Slug, HTML
        }

        function ourHtml() { ?>
            <div class="wrap">
                <h1>Word Count Settings</h1>
                <form action="options.php" method="POST">
                    <?php
                        settings_fields('wordcountplugin');
                        do_settings_sections('word-count-settings-page');
                        submit_button();
                    ?>
                </form>
            </div>
        <?php }
    }

    $wordCountAndTimePlugin = new WordCountAndTimePlugin();




