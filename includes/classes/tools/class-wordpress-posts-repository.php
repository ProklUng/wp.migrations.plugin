<?php

Class WordpressPostsRepository
{
    /**
     * @return self
     */
    public static function facade()
    {
        return new static;
    }

    /**
     * @param array $arCategories Вида ['Основные документы' => 'main_docs'].
     *
     * @return void
     * @throws Exception
     */
    public function createCategories(array $arCategories) : void
    {
        foreach ($arCategories as $catSlug => $catNiceName) {
            $cat  = category_exists($catSlug);
            if (!$cat){
                $cat_defaults = array(
                    'cat_name'             => $catSlug,
                    'category_nicename'    => $catNiceName,
                );

                $result = wp_insert_category($cat_defaults);
                if (!$result) {
                    throw new \Exception('Ошибка создания категории: ' . $catNiceName);
                }
            }
        }
    }

    /**
     * @param string      $title
     * @param string      $content
     * @param string|null $template
     *
     * @return int
     */
    public function createPost(string $title, string $content = '', ?string $template = null) : int
    {
        global $user_ID;

        $new_post = array(
            'post_title' => $title,
            'post_name' => SimpleWpTranslit::transliterate($title),
            'post_content' => $content,
            'post_status' => 'publish',
            'post_date' => date('Y-m-d H:i:s'),
            'post_author' => $user_ID,
            'post_type' => 'post',
            'page_template'  => $template
        );

        $post_id = wp_insert_post($new_post);

        if (is_wp_error($post_id)) {
            return 0;
        }

        return $post_id;
    }

    /**
     * @param string      $title
     * @param string|null $template
     *
     * @return int
     */
    public function createPageIfNotExists(string $title, ?string $template = null) : int
    {
        global $user_ID;

        $page = get_page_by_title($title);

        if ($page === null ) {
            $new_post = array(
                'post_title' => $title,
                'post_name' => SimpleWpTranslit::transliterate($title),
                'post_content' => '',
                'post_status' => 'publish',
                'post_date' => date('Y-m-d H:i:s'),
                'post_author' => $user_ID,
                'post_type' => 'page',
                'page_template'  => $template
            );

            $post_id = wp_insert_post($new_post);

            if (is_wp_error($post_id)) {
                return 0;
            }

            return $post_id;
        }

        if (is_object($page)) {
            return $page->ID;
        }

        return 0;
    }
}