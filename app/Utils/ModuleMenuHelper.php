<?php

namespace App\Utils;

use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Arr;

class ModuleMenuHelper
{
    /**
     * Ambil semua menu dari module dan gabungkan berdasarkan group.
     */
    public static function getMergedMenus(): array
    {
        $groups = [];

        foreach (Module::allEnabled() as $module) {
            $configPath = module_path($module->getName(), 'config/menu.php');

            if (file_exists($configPath)) {
                $config = include $configPath;

                $groupTitle = Arr::get($config, 'group', 'Ungrouped');
                $menus = Arr::get($config, 'menus', []);

                if (!isset($groups[$groupTitle])) {
                    $groups[$groupTitle] = [
                        'title' => $groupTitle,
                        'href' => '#',
                        'items' => [],
                    ];
                }

                foreach ($menus as $menu) {
                    self::mergeMenuItem($groups[$groupTitle]['items'], $menu);
                }
            }
        }

        return array_values($groups);
    }

    /**
     * Gabungkan menu ke dalam group, termasuk jika memiliki submenu dengan judul sama.
     */
    protected static function mergeMenuItem(array &$targetItems, array $menu)
    {
        foreach ($targetItems as &$existing) {
            if ($existing['title'] === $menu['title']) {
                if (isset($menu['items'])) {
                    $existing['items'] = array_merge($existing['items'] ?? [], $menu['items']);
                }
                return;
            }
        }

        $targetItems[] = $menu;
    }
}