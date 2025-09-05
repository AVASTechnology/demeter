<?php

namespace AVASTech\Demeter\PHP\Definitions\Traits;

/**
 * Trait HoldsData
 *
 * @package AVASTech\Demeter\Definitions\Traits
 */
trait HoldsData
{
    /**
     * @var array $data
     */
    protected array $data = [];

    /**
     * @param  mixed  $value
     * @return bool
     */
    abstract protected function validate(mixed $value): bool;

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->data;
    }

    /**
     * @param  array  $data
     */
    public function set(array $data): void
    {
        $this->data = [];

        foreach ($data as $value) {
            $this->add($value);
        }
    }

    /**
     * @param  mixed  $value
     * @return void
     */
    public function add(mixed $value): void
    {
        if (!$this->validate($value)) {
            throw new \TypeError(
                sprintf(
                    'Invalid Type: "%s"',
                    $value
                )
            );
        }

        $this->data[] = $value;
    }

    /**
     * @param  callable  $callback
     * @return void
     */
    public function map(callable $callback): void
    {
        $this->data = array_map($callback, $this->data);
    }
}
