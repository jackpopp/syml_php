<?php namespace Syml;

class ModelCollection implements \Iterator, \ArrayAccess
{
	protected $position = 0;
	protected $models = array();

	public function __construct()
	{
		$this->position = 0;
	}

	public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->models[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->models[$this->position]);
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->models[] = $value;
        } else {
            $this->models[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->models[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->models[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->models[$offset]) ? $this->models[$offset] : null;
    }

    public function setModels($models)
	{
		$this->models = $models;
		return $this;
	}

	public function getModels()
	{
		return $this;
	}

	public function toArray()
	{
		$models = $this->getModels();
		$arrayModels = array();
		foreach ($models as $key => $value) {
			$arrayModels[] = $value->toArray();
		}

		return $arrayModels;
	}
}