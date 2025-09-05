<?php

namespace AVASTech\Demeter\PHP\Components;

use AVASTech\Demeter\PHP\Components\Traits\HasAnnotation;
use AVASTech\Demeter\PHP\Components\Traits\HasInheritanceScoping;
use AVASTech\Demeter\PHP\Components\Traits\HasInterfaces;
use AVASTech\Demeter\PHP\Components\Traits\HasParent;
use AVASTech\Demeter\PHP\Definitions\ClassReference;
use AVASTech\Demeter\PHP\Definitions\Import;

/**
 * Class ClassComponent
 *
 * @package AVASTech\Demeter\Components
 */
class ClassComponent extends AbstractComponent
{
    use HasAnnotation;
    use HasInheritanceScoping;
    use HasInterfaces;
    use HasParent;

    /**
     * @var string
     */
    protected string $description = '';

    /**
     * @var Import[] $imports
     */
    protected array $imports = [];

    /**
     * @var MethodComponent[] $methods
     */
    protected array $methods = [];

    /**
     * @var PropertyComponent[] $properties
     */
    protected array $properties = [];

    /**
     * @var TraitComponent[] $traits
     */
    protected array $traits = [];

    /**
     * ClassComponent constructor.
     *
     * @param  ClassReference  $name
     */
    public function __construct(protected ClassReference $name)
    {
        //
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name->className();
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->name->namespace();
    }

    /**
     * @return string
     */
    public function getFullyQualifiedClassName(): string
    {
        return $this->name->fullyQualifiedClassName();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param  string  $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Import[]
     */
    public function getImports(): array
    {
        return $this->imports;
    }

    /**
     * @param  Import[]  $imports
     */
    public function setImports(array $imports): void
    {
        foreach ($imports as $import) {
            $this->addImport($import);
        }
    }

    /**
     * @param  Import|string  $import
     * @param  string|null  $alias
     */
    public function addImport(Import|string $import, string $alias = null): void
    {
        if (is_string($import)) {
            $import = new Import($import);
            if (!empty($alias)) {
                $import->setAlias($alias);
            }
        }

        $this->imports[$import->getIdentifier()] = $import;
    }

    /**
     * @param  string|null  $excludeNamespace
     * @return Import[]
     */
    public function getUniqueImportedAs(string $excludeNamespace = null): array
    {
        $importedAs = [];

        foreach ($this->imports as $import) {
            if ($import->namespace() === $excludeNamespace) {
                continue;
            }

            $importedAs[trim($import->fullyQualifiedClassName(), '\\')] = $import->isAliased() ? $import->importedAs() : null;
        }

        ksort($importedAs);

        return $importedAs;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param  array  $methods
     */
    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
    }

    /**
     * @param  MethodComponent  $method
     */
    public function addMethod(MethodComponent $method): void
    {
        $this->methods[] = $method;
    }

    /**
     * @return PropertyComponent[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param  PropertyComponent[]  $properties
     */
    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    /**
     * @param  PropertyComponent  $property
     * @return void
     */
    public function addProperty(PropertyComponent $property): void
    {
        $this->properties[] = $property;
    }

    /**
     * @return TraitComponent[]
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * @param  TraitComponent[]  $traits
     */
    public function setTraits(array $traits): void
    {
        $this->traits = $traits;
    }

    /**
     * @return array
     */
    public function sortMethods(): array
    {
        usort(
            $this->methods,
            function (MethodComponent $a, MethodComponent $b) {
                return $a->getSortedAs() <=> $b->getSortedAs();
            }
        );

        return $this->methods;
    }

    /**
     * @return PropertyComponent[]
     */
    public function sortProperties(): array
    {
        usort(
            $this->properties,
            function (PropertyComponent $a, PropertyComponent $b) {
                return $a->getIdentifier() <=> $b->getIdentifier();
            }
        );

        return $this->properties;
    }

    /**
     * @return void
     */
    public function standardizeImports(): void
    {
        $importAs = [];

        foreach ($this->extractImports() as $import) {
            if (!isset($importAs[$import->className()])) {
                $importAs[$import->className()] = [];
            }

            $importAs[$import->className()][$import->getIdentifier()] = $import;
        }

        // loop through $importAs and check for duplicates, alias all duplicates not in current namespace
        $standardizedImports = [];
        foreach ($importAs as $imports) {
            if (count($imports) === 1) {
                // no duplicates
                $import = reset($imports);

                $standardizedImports[$import->getIdentifier()] = $import;
            } else {
                /** @var Import $import */
                foreach ($imports as $import) {
                    if ($import->namespace() !== $this->getNamespace()) {
                        $import->setAlias($import->predictAlias());
                    }
                    $standardizedImports[$import->getIdentifier()] = $import;
                }
            }
        }

        $this->imports = $standardizedImports;

        $this->applyImportAliasing($standardizedImports);
    }

    /**
     * @return Import[]
     */
    public function extractImports(): array
    {
        $imports = $this->getAnnotationSection()->extractImports();

        if (isset($this->extends)) {
            $imports[] = $this->extends;
        }

        array_push($imports, ...$this->interfaces);

        if (!empty($this->traits)) {
            foreach ($this->traits as $trait) {
                array_push($imports, ...array_values($trait->extractImports()));
            }
        }

        if (!empty($this->methods)) {
            foreach ($this->methods as $method) {
                array_push($imports, ...array_values($method->extractImports()));
            }
        }

        return $imports;
    }

    /**
     * @param  Import[]  $imports
     * @return void
     */
    public function applyImportAliasing(array $imports): void
    {
        $this->getAnnotationSection()->applyImportAliasing($imports);

        if (isset($this->extends) && isset($imports[$this->extends->getIdentifier()])) {
            $this->extends = $imports[$this->extends->getIdentifier()];
        }

        if (!empty($this->interfaces)) {
            $this->interfaces = array_map(
                function ($interface) use ($imports) {
                    return (isset($imports[$interface->getIdentifier()]) ? $imports[$interface->getIdentifier()] : $interface);
                },
                $this->interfaces
            );
        }

        if (!empty($this->traits)) {
            foreach ($this->traits as $trait) {
                $trait->applyImportAliasing($imports);
            }
        }

        if (!empty($this->methods)) {
            foreach ($this->methods as $method) {
                $method->applyImportAliasing($imports);
            }
        }
    }

    /**
     * @param  string  $indentation
     * @return string
     */
    public function render(string $indentation = ''): string
    {
        $parts = [];

        if ($this->final) {
            $parts[] = 'final';
        } elseif ($this->abstract) {
            $parts[] = 'abstract';
        }

        $parts[] = 'class';
        $parts[] = $this->getName();

        if (!empty($this->extends)) {
            $parts[] = 'extends';
            $extendClassParts = $this->explodeClassName($this->extends);

            if ($extendClassParts['class'] === $this->getName()) {
                // Class name overlaps with parent class, so alias is needed
                $parts[] = 'Parent' . $extendClassParts['class'];
            } else {
                $parts[] = $extendClassParts['class'];
            }
        }

        if (!empty($this->interfaces)) {
            $parts[] = 'implements';
            $implements = [];

            foreach ($this->interfaces as $interface) {
                $interfaceParts = $this->explodeClassName($interface);

                if (strpos($interface, '\\Contracts\\')) {
                    $implements[] = $interfaceParts['class'] . 'Contract';
                } else {
                    $implements[] = $interfaceParts['class'];
                }
            }

            $parts = array_merge($parts, $implements);
        }

        return implode(' ', array_filter($parts));
    }
}
