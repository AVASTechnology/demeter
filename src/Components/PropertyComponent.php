<?php

namespace AVASTech\Demeter\Components;

use AVASTech\Demeter\Components\Annotations\Section;
use AVASTech\Demeter\Components\Interfaces\AnnotatedComponentInterface;
use AVASTech\Demeter\Components\Interfaces\VisibilityComponentInterface;
use AVASTech\Demeter\Components\Traits\HasAccessScoping;
use AVASTech\Demeter\Components\Traits\HasAnnotation;
use AVASTech\Demeter\Components\Traits\HasName;
use AVASTech\Demeter\Components\Traits\HasTypes;
use AVASTech\Demeter\Components\Traits\HasVisibility;
use AVASTech\Demeter\Definitions\Type;
use AVASTech\Demeter\Definitions\TypeSet;

/**
 * Class PropertyComponent
 *
 * @package AVASTech\Demeter\Components
 */
class PropertyComponent extends AbstractComponent implements AnnotatedComponentInterface, VisibilityComponentInterface
{
    use HasAnnotation;
    use HasAccessScoping;
    use HasName;
    use HasTypes;
    use HasVisibility;

    /**
     * @var mixed $default
     */
    protected mixed $default;

    /**
     * PropertyComponent constructor.
     *
     * @param  string  $name
     * @param  string|array|null  $types
     * @param  string|null  $description
     */
    public function __construct(string $name, array|string $types = null, string $description = null)
    {
        $this->name     = $name;
        $this->types    = new TypeSet($types ?? [Type::BUILT_IN_MIXED]);

        $this->setDescription($description ?? $this->description);
    }

    /**
     * @return mixed
     */
    public function getDefault(): mixed
    {
        return $this->default;
    }

    /**
     * @param  mixed  $default
     */
    public function setDefault(mixed $default): void
    {
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return sprintf(
            '%s::%s',
            $this->getVisibility(),
            $this->getName()
        );
    }

    /**
     * @return Section
     */
    public function compileAnnotation(): Section
    {
        return $this->getAnnotationSection();
    }

    /**
     * @param  string  $indentation
     * @return string
     */
    public function renderAnnotation(string $indentation = ''): string
    {
        $parts = [
            '/**',
            $this->description,
            sprintf(
                ' * @var %s $%s%s',
                $this->getTypeString(),
                $this->getName(),
                !empty($this->getDescription()) ? ' ' . $this->getDescription() : ''
            ),
            ' */'
        ];

        return $indentation . implode("\n" . $indentation, array_filter($parts));
    }

    /**
     * @inheritDoc
     */
    public function render(string $indentation = ''): string
    {
        $parts = [
            $this->getVisibility(),
            $this->isStatic() ? 'static' : '',
            '$' . $this->name,
        ];

        if (!is_null($this->default)) {
            $parts[] = '=';

            switch (gettype($this->default)) {
                case 'boolean':
                    $parts[] = $this->default ? 'true' : 'false';
                    break;
                case 'integer':
                case 'double':
                    $parts[] = $this->default;
                    break;
                case 'string':
                    $parts[] = sprintf(
                        "'%s'",
                        addslashes($this->default)
                    );
                    break;
                case 'NULL':
                    $parts[] = 'null';
                    break;
                case 'array':
                    if (empty($this->default)) {
                        $parts = '[]';
                    } else {
                        $length = array_reduce(
                            array_keys($this->default),
                            function ($carry, $item) {
                                if (is_numeric($item)) {
                                    return $carry;
                                }

                                return strlen($item) > $carry ? strlen($item) : $carry;
                            },
                            0
                        );

                        $arrayParts = [];
                        foreach ($this->default as $key => $value) {
                            if (is_numeric($key)) {
                                $arrayParts[] = sprintf(
                                    '%s,',
                                    var_export($value, true)
                                );
                            } else {
                                $arrayParts[] = sprintf(
                                    '%s => %s,',
                                    str_pad(
                                        "'" . addslashes($key) . "'",
                                        $length + 2,
                                        ' '
                                    ),
                                    var_export($value, true)
                                );
                            }
                        }

                        $parts[] = sprintf(
                            "[\n%s%s\n]",
                            $indentation,
                            implode("\n" . $indentation, $arrayParts)
                        );
                    }
                    break;
                default:
                    throw new \RuntimeException('Invalid default type');
            }
        }

        return sprintf(
            '%s%s;',
            $indentation,
            str_replace(
                "\n",
                "\n" . $indentation,
                implode(' ', array_filter($parts))
            )
        );
    }
}
