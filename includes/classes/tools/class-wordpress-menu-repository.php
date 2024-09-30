<?php

Class WordpressMenuRepository
{
    /**
     * @var mixed $menuId ID menu.
     */
    private $menuId;

    /**
     * @var string $location Locations menu.
     */
    private $location;

    /**
     * @var string $menuCode Code menu.
     */
    private $menuCode;

    /**
     * @param string $menuCode Code menu.
     * @param string $location Locations menu.
     *
     * @throws Exception When the menu is not found or it could not be created.
     */
    public function __construct(string $menuCode, string $location)
    {
        $this->location = $location;
        $this->menuCode = $menuCode;

        $menuExists = wp_get_nav_menu_object( $menuCode );
        if (!$menuExists) {
            $this->create();
        }
    }

    /**
     * Facade.
     *
     * @param string $menuCode Code menu.
     * @param string $location Locations menu.
     * @param string $filePath Array with menu data.
     *
     * @return void
     * @throws Exception
     */
    public static function fromFile( string $menuCode, string $location, string $filePath ): void
    {
        if (!@file_exists($filePath)) {
            return;
        }

        $data = include $filePath;

        $self = new static($menuCode, $location);

        if (is_array($data)) {
            $self->process($data);
        }
    }

    /**
     * Update menu from file.
     *
     * @param string $menuCode Code menu.
     * @param string $filePath Array with menu data.
     *
     * @return void
     * @throws Exception
     */
    public static function updateFromFile( string $menuCode, string $filePath ): void
    {
        if (!@file_exists($filePath)) {
            return;
        }

        $data = include $filePath;

        $menu = wp_get_nav_menu_object( $menuCode );
        if (!$menu) {
            return;
        }

        $menuId = $menu->term_id;

        if (!$menuId) {
            return;
        }

        $items = wp_get_nav_menu_items($menu, ["object"=>"page"]);

        $alreadyExistsMenuItems = [];
        foreach ($items as $menuItem) {
            $alreadyExistsMenuItems[$menuItem->title][] = $menuItem->db_id;
        }

        if (is_array($data)) {
            foreach ($data as $item) {
                if (!$item['name'] || !$item['url']) {
                    continue;
                }

                $title = $item['name'];

                if (!empty($alreadyExistsMenuItems[$title])) {
                    foreach ($alreadyExistsMenuItems[$title] as $menuItemId) {
                        wp_delete_post($menuItemId);
                    }
                }

                wp_update_nav_menu_item( $menuId, 0, [
                    'menu-item-title'   => $title,
                    'menu-item-classes' => $item['classes'],
                    'menu-item-url'     => $item['url'],
                    'menu-item-status'  => 'publish'
                ]);
            }
        }
    }

    /**
     * Create a menu.
     *
     * @param array $data Array with datamenu.
     *
     * @return void
     */
    public function process( array $data ) : void
    {
        foreach ($data as $item) {
            if (!$item['name'] || !$item['url']) {
                continue;
            }

            $this->addItem($item['name'], $item['url'], $item['classes']);
        }

        $this->setLocation();
    }

    /**
     * Delete items of menu by code.
     *
     * @param string $menuCode Code of menu.
     *
     * @return void
     */
    public static function deleteMenuItems( string $menuCode ) : void
    {
        $menu      = wp_get_nav_menu_object( $menuCode );
        $pagesItem = wp_get_nav_menu_items( $menu, ["object" => "page"]);
        if ( is_array( $pagesItem ) ) {
            foreach ( $pagesItem as $page ) {
                wp_delete_post($page->db_id);
            }
        }
    }

    /**
     * Add item menu.
     *
     * @param string $name    Item name menu.
     * @param string $url     URL.
     * @param string $classes Item classes menu.
     *
     * @return void
     */
    private function addItem(string $name, string $url, string $classes = '') : void
    {
        $items = wp_get_nav_menu_items($this->menuId, ["object"=>"page"]);

        $alreadyExistsMenuItems = [];
        if ($items) {
            foreach ($items as $menuItem) {
                $alreadyExistsMenuItems[$menuItem->title][] = $menuItem->db_id;
            }
        }

        $title = $name;

        if (!empty($alreadyExistsMenuItems[$title])) {
            foreach ($alreadyExistsMenuItems[$title] as $menuItemId) {
                wp_delete_post($menuItemId);
            }
        }

        wp_update_nav_menu_item( $this->menuId, 0, [
            'menu-item-title'   => $title,
            'menu-item-classes' => $classes,
            'menu-item-url'     => $url,
            'menu-item-status'  => 'publish'
        ]);
    }

    /**
     * Create menu.
     *
     * @return mixed
     * @throws Exception
     */
    private function create()
    {
        $this->menuId = wp_create_nav_menu( $this->menuCode );

        if (is_wp_error($this->menuId)) {
            throw new Exception(implode(',', $this->menuId->errors));
        }

        return $this->menuId;
    }

    /**
     * Set location.
     *
     * @return void
     */
    private function setLocation() : void
    {
        if ( ! has_nav_menu( $this->location ) ) {
            $locations                    = get_theme_mod( 'nav_menu_locations' );
            $locations[ $this->location ] = $this->menuId;
            set_theme_mod( 'nav_menu_locations', $locations );
        }
    }
}