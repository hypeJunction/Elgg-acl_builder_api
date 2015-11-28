<?php

namespace hypeJunction\Access;

use ElggEntity;

/**
 * Performs basic filering operations on a group of entities
 */
class EntitySet {

	protected $guids = array();

	/**
	 * Create a new group from a mixed data set
	 *
	 * @param array $data Data set
	 * @return EntitySet
	 */
	public static function create($data) {
		$group = new EntitySet;
		return $group->add($data);
	}

	/**
	 * Add new elements
	 *
	 * @param mixed $data Element(s)
	 * @return EntitySet
	 */
	public function add($data = null) {
		if (is_array($data)) {
			foreach ($data as $elem) {
				$this->add($elem);
			}
		} else {
			$guid = $this->toGUID($data);
			if ($guid) {
				array_push($this->guids, $guid);
				sort($this->guids);
			}
		}
		return $this;
	}

	/**
	 * Retrurns an array of all valid GUIDs in a data set
	 * @return int[]
	 */
	public function guids() {
		return $this->guids;
	}

	/**
	 * Returns an array of all valid entities in a data set
	 * @return ElggEntity[]
	 */
	public function entities() {
		$entities = array_map(array($this, 'toEntity'), $this->guids);
		return array_filter($entities);
	}

	/**
	 * Get guids from an entity attribute
	 *
	 * @param ElggEntity|int $entity Entity or GUID
	 * @return int
	 */
	protected function toGUID($entity = null) {
		if ($entity instanceof ElggEntity) {
			return (int) $entity->getGUID();
		} else if ($this->exists($entity)) {
			return (int) $entity;
		}
		return false;
	}

	/**
	 * Get an entity from GUID
	 *
	 * @param ElggEntity|int $guid Entity or guid
	 * @return ElggEntity
	 * @codeCoverageIgnore
	 */
	protected function toEntity($guid = null) {
		return get_entity($guid);
	}

	/**
	 * Verifies if entity exists
	 *
	 * @param int $guid Entity guid
	 * @return bool
	 * @codeCoverageIgnore
	 */
	protected function exists($guid = null) {
		return elgg_entity_exists($guid);
	}

}
