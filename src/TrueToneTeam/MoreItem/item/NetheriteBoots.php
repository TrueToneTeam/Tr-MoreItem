<?php

declare(strict_types=1);

namespace TrueToneTeam\MoreItem\item;

use pocketmine\item\{Item, ItemFactory, Armor, Food};

class NetheriteBoots extends Armor{
	
	public function __construct(int $meta = 0){
		parent::__construct(ItemIds::NETHERITE_BOOTS, $meta, "Netherite Boots");
	}

	public function getDefensePoints() : int{
		return 3;
	}

	public function getMaxDurability() : int{
		return 482;
	}
}
