<?php

declare(strict_types=1);

namespace TrueToneTeam\MoreItem\item;

use pocketmine\item\{Item, ItemFactory, Armor, Food};

class NetheriteHelmet extends Armor{
	
	public function __construct(int $meta = 0){
		parent::__construct(ItemIds::NETHERITE_HELMET, $meta, "Netherite Helmet");
	}

	public function getDefensePoints() : int{
		return 3;
	}

	public function getMaxDurability() : int{
		return 408;
	}
}
