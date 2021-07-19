<?php

declare(strict_types=1);

namespace TrueToneTeam\MoreItem\item;

use pocketmine\item\{Item, ItemFactory, MaybeConsumable};
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\Liquid;
use pocketmine\entity\Living;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Bucket extends Item implements MaybeConsumable{
	public function __construct(int $meta = 0){
		parent::__construct(self::BUCKET, $meta, "Bucket");
	}

	public function getMaxStackSize() : int{
		return $this->meta === Block::AIR ? 16 : 1; //empty buckets stack to 16
	}

	public function getFuelTime() : int{
		if($this->meta === Block::LAVA or $this->meta === Block::FLOWING_LAVA){
			return 20000;
		}

		return 0;
	}

	public function getFuelResidue() : Item{
		if($this->meta === Block::LAVA or $this->meta === Block::FLOWING_LAVA){
			return ItemFactory::get(Item::BUCKET);
		}

		return parent::getFuelResidue();
	}

	public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector) : bool{
		$resultBlock = BlockFactory::get($this->meta);

		if($resultBlock instanceof Air){
			if($this->meta == 11){
				$stack->pop();
				$player->getInventory()->setItemInHand($stack);
				$player->getInventory()->addItem(ItemFactory::get(Item::BUCKET, 0, 1));
				$player->getLevelNonNull()->setBlock($blockClicked, BlockFactory::get(Block::SNOW), true, true);
			}
			
			if($this->meta == 12){
				$stack->pop();
				$player->getInventory()->setItemInHand($stack);
				$player->getInventory()->addItem(ItemFactory::get(Item::BUCKET, 0, 1));
				$player->getLevelNonNull()->setBlock($blockClicked, BlockFactory::get(Block::WATER), true, true);
			}
			
			else{
				if($blockClicked instanceof Liquid and $blockClicked->getDamage() === 0){
					$stack = clone $this;

					$stack->pop();
					$resultItem = ItemFactory::get(Item::BUCKET, $blockClicked->getFlowingForm()->getId());
					$ev = new PlayerBucketFillEvent($player, $blockReplace, $face, $this, $resultItem);
					$ev->call();
					if(!$ev->isCancelled()){
						$player->getLevelNonNull()->setBlock($blockClicked, BlockFactory::get(Block::AIR), true, true);
						$player->getLevelNonNull()->broadcastLevelSoundEvent($blockClicked->add(0.5, 0.5, 0.5), $blockClicked->getBucketFillSound());
						if($player->isSurvival()){
							if($stack->getCount() === 0){
								$player->getInventory()->setItemInHand($ev->getItem());
							}else{
								$player->getInventory()->setItemInHand($stack);
								$player->getInventory()->addItem($ev->getItem());
							}
						}else{
							$player->getInventory()->addItem($ev->getItem());
						}

						return true;
					}else{
						$player->getInventory()->sendContents($player);
					}
				}
			}
		}elseif($resultBlock instanceof Liquid and $blockReplace->canBeReplaced()){
			$ev = new PlayerBucketEmptyEvent($player, $blockReplace, $face, $this, ItemFactory::get(Item::BUCKET));
			$ev->call();
			if(!$ev->isCancelled()){
				$player->getLevelNonNull()->setBlock($blockReplace, $resultBlock->getFlowingForm(), true, true);
				$player->getLevelNonNull()->broadcastLevelSoundEvent($blockReplace->add(0.5, 0.5, 0.5), $resultBlock->getBucketEmptySound());

				if($player->isSurvival()){
					$player->getInventory()->setItemInHand($ev->getItem());
				}
				return true;
			}else{
				$player->getInventory()->sendContents($player);
			}
		}

		return false;
	}

	public function getResidue(){
		return ItemFactory::get(Item::BUCKET, 0, 1);
	}

	public function getAdditionalEffects() : array{
		return [];
	}

	public function canBeConsumed() : bool{
		return $this->meta === 1; //Milk
	}

	public function onConsume(Living $consumer){
		$consumer->removeAllEffects();
	}
}
