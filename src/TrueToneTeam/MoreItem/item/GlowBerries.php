<?php

declare(strict_types=1);

namespace TrueToneTeam\MoreItem\item;

use pocketmine\item\{Item, ItemFactory, Armor, Food};

class GlowBerries extends Food{
	
	public function __construct(int $meta = 0){
		parent::__construct(ItemIds::GLOW_BERRIES, $meta, "GlowBerries");
	}

	public function getFoodRestore() : int{
		return 2;
	}

	public function getSaturationRestore() : float{
		return 4;
	}
}