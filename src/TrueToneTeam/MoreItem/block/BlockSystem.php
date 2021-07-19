<?php

namespace TrueToneTeam\MoreItem\block;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};
use pocketmine\item\{Item, ItemFactory};
use pocketmine\block\{Block, BlockFactory};
use pocketmine\block\UnknownBlock;

use TrueToneTeam\MoreItem\MoreItem;

class BlockSystem implements Listener {
	
	private $plugin;
	
	public function __construct(MoreItem $plugin) {
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}
	
	public static function init(){
		
	}
	
	public static function register(Block $block, $override = true, $creative = true){
		BlockFactory::registerBlock($block, $override);
	}
}
?>