<?php

declare(strict_types=1);

namespace TrueToneTeam\MoreItem\item;

use pocketmine\item\{Item, ItemFactory, Armor, Food};

class NetheriteChestplate extends Armor{
	
	public function __construct(int $meta = 0){
		parent::__construct(ItemIds::NETHERITE_CHESTPLATE, $meta, "Netherite Chestplate");
	}

	public function getDefensePoints() : int{
		return 8;
	}

	public function getMaxDurability() : int{
		return 593;
	}
}
