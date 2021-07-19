<?php

declare(strict_types=1);

namespace TrueToneTeam\MoreItem\item;

use pocketmine\item\{Item, ItemFactory, Armor, Food};

class NetheriteLeggings extends Armor{
	
	public function __construct(int $meta = 0){
		parent::__construct(ItemIds::NETHERITE_LEGGINGS, $meta, "Netherite Leggings");
	}

	public function getDefensePoints() : int{
		return 6;
	}

	public function getMaxDurability() : int{
		return 556;
	}
}
