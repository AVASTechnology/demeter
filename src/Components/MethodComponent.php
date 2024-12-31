<?php

namespace AVASTech\Demeter\Components;

use AVASTech\Demeter\Components\Annotations\ParamType;
use AVASTech\Demeter\Components\Annotations\Raw;
use AVASTech\Demeter\Components\Annotations\ReturnType;
use AVASTech\Demeter\Components\Annotations\Section;
use AVASTech\Demeter\Components\Interfaces\AnnotatedComponentInterface;
use AVASTech\Demeter\Components\Interfaces\VisibilityComponentInterface;
use AVASTech\Demeter\Components\Traits\HasAccessScoping;
use AVASTech\Demeter\Components\Traits\HasAnnotation;
use AVASTech\Demeter\Components\Traits\HasInheritanceScoping;
use AVASTech\Demeter\Components\Traits\HasName;
use AVASTech\Demeter\Components\Traits\HasVisibility;
use AVASTech\Demeter\Definitions\Import;
use AVASTech\Demeter\Definitions\Type;
use AVASTech\Demeter\Definitions\TypeSet;

/**
 * Class MethodComponent
 *
 * @package AVASTech\Demeter\Components
 */
class MethodComponent extends AbstractComponent implements AnnotatedComponentInterface, VisibilityComponentInterface
{
    use HasAnnotation;
    use HasAccessScoping;
    use HasAnnotation;
    use HasInheritanceScoping;
    use HasName;
    use HasVisibility;

    /**
     * @var AbstractComponent[] $contents
     */
    protected array $contents = [];

    /**
     * @var ParameterComponent[] $parameters
     */
    protected array $parameters = [];

    /**
     * @var string $sortedAs
     */
    protected string $sortedAs;

    /**
     * @var TypeSet $returns
     */
    protected TypeSet $returns;

    /**
     * MethodComponent constructor.
     *
     * @param  string  $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        $this->returns = new TypeSet([]);

        $this->getAnnotationSection();
    }

    /**
     * @return AbstractComponent[]
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * @param  AbstractComponent[]  $contents
     */
    public function setContents(array $contents): void
    {
        $this->contents = $contents;
    }

    /**
     * @return ParameterComponent[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param  ParameterComponent[]  $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getSortedAs(): string
    {
        return $this->sortedAs ?? $this->name;
    }

    /**
     * @param  string  $sortedAs
     */
    public function setSortedAs(string $sortedAs): void
    {
        $this->sortedAs = $sortedAs;
    }

    /**
     * @return array
     */
    public function getReturns(): array
    {
        return $this->returns->get();
    }

    /**
     * @param  array  $returns
     */
    public function setReturns(array $returns): void
    {
        $this->returns->set($returns);

        $this->getAnnotationSection()
            ->findAnnotation('return:' . $this->getName())
            ?->setTypes($returns);
    }

    /**
     * @param  Type|Import|string  $return
     */
    public function addReturn(Type|Import|string $return): void
    {
        $this->returns->add($return);
    }

    /**
     * @return string
     */
    public function getReturnString(): string
    {
        return $this->returns->__toString();
    }

    /**
     * @return array
     */
    public function getReturnAnnotationTypes(): array
    {
        return $this->compileReturnAnnotation()->getTypes();
    }

    /**
     * @param  array  $returns
     */
    public function setReturnAnnotation(array $returns): void
    {
        $this->compileReturnAnnotation()->setTypes($returns);
    }

    /**
     * @param  string  $return
     */
    public function addReturnAnnotation(string $return): void
    {
        $this->compileReturnAnnotation()->addType($return);
    }

    /**
     * @return Import[]
     */
    public function extractImports(): array
    {
        $imports = $this->getAnnotationSection()->extractImports();

        foreach ($this->parameters as $parameter) {
            array_push($imports, ...array_values($parameter->extractImports()));
        }

        foreach ($this->getReturns() as $returnType) {
            if (!($returnType instanceof \UnitEnum)) {
                $imports[] = ($returnType instanceof Import) ? $returnType : new Import($returnType);
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

        foreach ($this->parameters as $parameter) {
            $parameter->applyImportAliasing($imports);
        }

        $this->returns->map(
            function ($returnType) use ($imports) {
                return ($returnType instanceof Import && isset($imports[$returnType->getIdentifier()])
                    ? $imports[$returnType->getIdentifier()]
                    : $returnType);
            }
        );
    }

    /**
     * Integrate Component dependencies into Annotation
     * @return Section
     */
    public function compileAnnotation(): Section
    {
        $annotationSection = $this->getAnnotationSection();
        $annotationSection->addAnnotation(new Raw($this->getDescription()));

        foreach ($this->parameters as $parameter) {
            $annotationSection->addAnnotation(
                new ParamType($parameter->getName(), $parameter->getTypes())
            );
        }

        $this->compileReturnAnnotation();

        return $annotationSection;
    }

    /**
     * @return ReturnType
     */
    protected function compileReturnAnnotation(): ReturnType
    {
        $returnType = $this->getAnnotationSection()->findAnnotation('return::' . $this->getName());

        if (!isset($returnType)) {
            $returnType = new ReturnType($this->getName(), $this->getReturns());

            $this->getAnnotationSection()->addAnnotation($returnType);
        }

        return $returnType;
    }

    /**
     * @param  string  $indentation
     * @return string
     */
    public function renderAnnotation(string $indentation = ''): string
    {
        $parts = [
            '/**',
            ' * ' . $this->description,
        ];

        foreach ($this->parameters as $parameter) {
            $parts[] = sprintf(
                ' * @param %s $%s%s',
                $parameter->getType(),
                $parameter->getName(),
                !empty($parameter->getDescription()) ? ' ' . $parameter->getDescription() : ''
            );
        }

        $parts[] = sprintf(
            ' * @returns %s',
            implode('|', $this->returnAnnotation ?? ($this->returns ?? ['void']))
        );

        $parts[] = ' */';

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
            'function',
            $this->name,
        ];

        $parameters = [];

        foreach ($this->parameters as $parameter) {
            $parameters[] = $parameter->render();
        }

        $contents = [];

        foreach ($this->contents as $contentComponent) {
            $contents[] = $contentComponent->render($indentation);
        }

        $returnTypeDeclaration = '';
        if (!empty($this->getReturns())) {
            $returnTypeDeclaration = ': ' . implode('|', $this->getReturns());
        }

        return sprintf(
            "%s%s(%s)%s\n%s{\n%s%s\n%s}\n",
            $indentation,
            implode(' ', array_filter($parts)),
            implode(', ', $parameters),
            $returnTypeDeclaration,
            $indentation,
            $indentation,
            implode("\n" . $indentation, $contents),
            $indentation,
        );
    }
}
