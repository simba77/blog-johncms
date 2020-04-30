<?php

declare(strict_types=1);

namespace Blog\Utils;

use Blog\Models\BlogSection;
use Johncms\NavChain;

class Helpers
{
    /**
     * Метод для построения навигационной цепочки
     *
     * @param BlogSection|null $parent_section
     */
    public static function buildAdminBreadcrumbs(BlogSection $parent_section = null): void
    {
        if ($parent_section) {
            /** @var NavChain $nav_chain */
            $nav_chain = di(NavChain::class);

            // Collecting parent sections to build a navigation chain
            $parent_tree = [];
            $parent = $parent_section;
            while ($parent !== null) {
                $parent_tree[] = [
                    'name' => $parent->name,
                    'url'  => '/blog/admin/content/?section_id=' . $parent->id,
                ];
                $parent = $parent->parentSection;
            }

            krsort($parent_tree);
            foreach ($parent_tree as $item) {
                $nav_chain->add($item['name'], $item['url']);
            }
        }
    }
}
