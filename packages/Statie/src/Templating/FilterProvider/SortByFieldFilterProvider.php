<?php declare(strict_types=1);

namespace Symplify\Statie\Templating\FilterProvider;

use Symplify\Statie\Contract\Templating\FilterProviderInterface;
use Symplify\Statie\Templating\ArraySorter;

/**
 * @inspiration https://github.com/victorhaggqvist/Twig-sort-by-field
 */
final class SortByFieldFilterProvider implements FilterProviderInterface
{
    /**
     * @var ArraySorter
     */
    private $arraySorter;

    public function __construct(ArraySorter $arraySorter)
    {
        $this->arraySorter = $arraySorter;
    }

    /**
     * @return callable[]
     */
    public function provide(): array
    {
        return [
            // usage in Twig: {% set entries = sort_by_field(entries, 'name', 'desc') %}
            // usage in Latte: {var $entries = ($entries|sort_by_field:'name', 'desc')}
            'sort_by_field' => function (array $items, $sortBy, $direction = 'ASC'): array {
                return $this->arraySorter->sortByField($items, $sortBy, $direction);
            },
        ];
    }
}