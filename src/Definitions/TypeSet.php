<?php

namespace AVASTech\Demeter\Definitions;

use AVASTech\Demeter\Definitions\Traits\HoldsData;

/**
 * Class TypeSet
 *
 * @package AVASTech\Demeter\Definitions
 */
class TypeSet
{
    use HoldsData;

    /**
     * @param  array|string  $data
     */
    public function __construct(array|string $data)
    {
        $this->set((array)$data);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        // @todo Remove duplicates due to ImportIterables
        return implode(
            '|',
            array_map(
                function ($value) {
                    if ($value instanceof \UnitEnum) {
                        return ($value instanceof \BackedEnum) ? $value->value : $value->name;
                    }

                    if ($value instanceof Import) {
                        return $value->importedAs();
                    }

                    if (class_exists($value, true)) {
                        return '\\' . trim($value, '\\');
                    }

                    return $value;
                },
                $this->data
            )
        );
    }

    /**
     * @return string
     */
    public function asAnnotation(): string
    {
        return implode(
            '|',
            array_map(
                function ($value) {
                    if ($value instanceof \UnitEnum) {
                        return ($value instanceof \BackedEnum) ? $value->value : $value->name;
                    }

                    if ($value instanceof ImportIterable) {
                        return $value->annotatedAs();
                    }

                    if ($value instanceof Import) {
                        return $value->importedAs();
                    }

                    if (class_exists($value, true)) {
                        return '\\' . trim($value, '\\');
                    }

                    return $value;
                },
                $this->data
            )
        );
    }

    /**
     * @param  mixed  $value
     * @return bool
     */
    protected function validate(mixed $value): bool
    {
        return (($value instanceof Type)
            || ($value instanceof Import)
            || (is_string($value) && class_exists(strval($value), true))
        );
    }
}
