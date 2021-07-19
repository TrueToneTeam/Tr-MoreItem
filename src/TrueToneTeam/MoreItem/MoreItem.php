<?php

namespace TrueToneTeam\MoreItem;

use pocketmine\{Player, Server};
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\{SingletonTrait};

use TrueToneTeam\MoreItem\block\{BlockSystem};
use TrueToneTeam\MoreItem\item\{ItemSystem};

class MoreItem extends PluginBase implements Listener{
	use SingletonTrait;
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		
		$this->getLogger()->notice("Tr-MoreItem 플러그인이 활성화 되었습니다.");
		$this->getLogger()->notice("해당 플러그인 적용하시고 아이템을 꺼낸 다음에는 플러그인을 제거하지 마세요. 오류가 발생됩니다.");
		
		BlockSystem::init();
		ItemSystem::init();
	}
}