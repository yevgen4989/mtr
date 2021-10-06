<?php


class ACFFastBlock
{
    private $filename, $prefixe;

    public function __construct()
    {
        $this->prefixe = 'acf-fast-block';
        $this->filename = TEMPLATEPATH . "/acf_block/";
        add_action('admin_head', [$this, 'init']);
        // add_action('admin_menu', [$this, 'admin_menu']);
        add_action('acf/init', [$this, 'my_acf_init']);
        add_shortcode('acf_clone_block', [$this, 'acf_clone_block']);
    }

    function my_acf_init()
    {


        $block_list = $this->read_folder();

        if (function_exists('acf_register_block')) {
            foreach ($block_list as $key => $name) {
                if (!empty($name['name'])) {
                    acf_register_block(array(
                        'name' => $name['key'],
                        'title' => $name['name'],
                        'render_callback' => [$this, 'my_acf_init_block'],
                        'category' => 'formatting',
                        'icon' => 'admin-comments',
                        'keywords' => array($key, $name['name']),
                    ));
                    $this->import_block_acf_custom('Acf block: ' . $name['name'], $name['key']);
                }
            }
        }
    }

    function my_acf_init_block($block)
    {
        $slug = explode('/', trim($block['name'], '/'));
        $slug = $slug[count($slug) - 1];
        $file = '/acf_block/' . $slug . '.php';
        if (file_exists(get_theme_file_path($file))) {
            if (is_user_logged_in()) {
                ob_start();
                $html = ob_get_clean();
                echo $html;

            }
            include(get_theme_file_path($file));
        }
    }

    function translit($str)
    {

        $rus = array('-', " ", 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
        $lat = array('_', '_', 'A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
        return strtolower(str_replace($rus, $lat, $str));
    }

    public function get_acf_block_by_id($id, $_post_id = null)
    {
        $_post_id = (empty($_post_id)) ? get_option('page_on_front') : $_post_id;
        $home_post = get_post($_post_id);
        $blocks = parse_blocks($home_post->post_content);
        foreach ($blocks as $block) {
            if ($block['attrs']['id'] === $id) {
                echo render_block($block);
                break;
            }
        }
    }

    function acf_clone_block($atts)
    {
        ob_start();
        $id = $atts['id'];
        $_post_id = $atts['post_id'];
        $this->get_acf_block_by_id($id, $_post_id);
        $html = ob_get_clean();
        return $html;
    }

    function import_block_acf_custom($title, $key)
    {

        $group = 'group_' . md5($key);
        $post = acf_get_field_group_post($group);
        if (!$post) {
            $post_data = array(
                'post_title' => $title,
                'post_name' => $group,
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'acf-field-group'
            );
            $post_id = wp_insert_post($post_data);
            $post = acf_get_field_group_post($post_id);

            $time_f = str_replace('Acf block:', '', $title);
            $time_f = trim($time_f);
            $f_key = 'field_' . md5($time_f);
            $field_group = array(
                'ID' => $post->ID,
                'key' => $group,
                'title' => $title,
                'fields' => array(
                    array(
                        'key' => $f_key,
                        'label' => $time_f,
                        'name' => $this->translit($time_f),
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/' . $key,
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
            );

            $asf_list = get_option($this->prefixe . "list");
            $asf_list = is_array($asf_list) ? $asf_list : [];
            $asf_list[$post->ID] = [
                'title' => $title,
                'key' => $key
            ];
            update_option($this->prefixe . "list", $asf_list);
            $field_group = acf_import_field_group($field_group);
            return $field_group->ID;
        }
        return false;
    }

    function read_folder()
    {

        $data = [];
        $default_headers = [
            'name' => 'Acf block',
        ];

        foreach (glob($this->filename . "*.php") as $file) {

            $data[] = $this->get_file_data_dex($file, $default_headers);
        }
        return $data;
    }

    function get_file_data_dex($file, $default_headers)
    {

        $fp = fopen($file, 'r');
        $file_data = fread($fp, 8192);
        fclose($fp);
        $file_data = str_replace("\r", "\n", $file_data);
        $all_headers = $default_headers;

        foreach ($all_headers as $field => $regex) {
            if (preg_match('/^[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi', $file_data, $match)
                && $match[1])
                $all_headers[$field] = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $match[1]));
            else
                $all_headers[$field] = '';
        }

        $all_headers['key'] = basename($file, '.php');
        return $all_headers;
    }

    function init()
    {
        if (!file_exists($this->filename)) {
            mkdir($this->filename, 0777);
            if (!is_file($this->filename . 'example.php')) {
                $html = '<?php
                    /**
                     * Acf block: Пример
                     */
                    $setting = get_field(\'example\');
                    ?>';
                $fp = fopen($this->filename . 'example.php', 'w');
                fwrite($fp, $html);
                fclose($fp);
            }

        }
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page();

        }
    }

    function admin_menu()
    {
        $page = [];
        $page[] = add_menu_page('ACF Fast Block', 'ACF Fast Block', 'read', 'page-' . $this->prefixe, [$this, 'setting'], 'dashicons-format-image', 5);
    }

    function setting()
    {
        echo '<pre>';
        $asf_list = get_option($this->prefixe . "list");
        print_r($asf_list);
        foreach ($asf_list as $id => $data) {
            $post = get_post($id);
            if ($post) {

            } else {
                unset($asf_list[$id]);
            }

        }
        update_option($this->prefixe . "list", $asf_list);

    }
}

$acf_fast = new ACFFastBlock();


