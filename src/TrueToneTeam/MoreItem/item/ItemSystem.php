<?php

namespace TrueToneTeam\MoreItem\item;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};
use pocketmine\block\Block;
use pocketmine\item\{
	Item, ItemBlock, ItemFactory, 
	enchantment\Enchantment, enchantment\EnchantmentInstance,
	SpawnEgg, Bucket, Sword, Shovel, Pickaxe, Axe, Hoe
};
use pocketmine\network\mcpe\convert\ItemTranslator;

use TrueToneTeam\MoreItem\MoreItem;
use TrueToneTeam\MoreItem\block\{BlockSystem};
use TrueToneTeam\MoreItem\item\{ItemIds};
use TrueToneTeam\MoreItem\item\utils\{TieredTool};

use ReflectionProperty;
use const pocketmine\RESOURCE_PATH;

class ItemSystem implements Listener{
	
	private $plugin;
	
	public function __construct(VanillaSystem $plugin){
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}
	
	public static function init(){
		self::initItemMapping();
		
		self::register(new Bucket(ItemIds::BUCKET, 11, "Powder Snow Bucket"), true); /* 325:11 */
		self::register(new Bucket(ItemIds::BUCKET, 12, "Axolotl Bucket"), true); /* 325:12 */
		
		self::register(new Item(ItemIds::LODESTONECOMPASS, 0, "Lodestone Campass"), true); /* 741 */
		self::register(new Item(ItemIds::NETHERITE_INGOT, 0, "Netherite Ingot"), true); /* 742 */
		self::register(new Sword(ItemIds::NETHERITE_SWORD, 0, "Netherite Sowrd", TieredTool::TIER_NETHERITE), true); /* 743 */
		self::register(new Shovel(ItemIds::NETHERITE_SHOVEL, 0, "Netherite Shovel", TieredTool::TIER_NETHERITE), true); /* 744 */
		self::register(new Pickaxe(ItemIds::NETHERITE_PICKAXE, 0, "Netherite Pickaxe", TieredTool::TIER_NETHERITE), true); /* 745 */
		self::register(new Axe(ItemIds::NETHERITE_AXE, 0, "Netherite Axe", TieredTool::TIER_NETHERITE), true); /* 746 */
		self::register(new Hoe(ItemIds::NETHERITE_HOE, 0, "Netherite Hoe", TieredTool::TIER_NETHERITE), true); /* 747 */
		self::register(new NetheriteHelmet(), true); /* 748 */
		self::register(new NetheriteChestplate(), true); /* 749 */
		self::register(new NetheriteLeggings(), true); /* 750 */
		self::register(new NetheriteBoots(), true); /* 751 */
		self::register(new Item(ItemIds::NETHERITE_SCRAP, 0, "Netherite Scrap"), true); /* 752 */
		
		self::register(new Item(ItemIds::RECORD_PIGSTEP, 0, "Music Disc"), true); /* 759 */
		
		self::register(new Item(ItemIds::SOUL_CAMPFIRE, 0, "Soul Campfire"), true); /* 801 */
		
		/* 비공식 패치 */
		self::register(new Item(ItemIds::GLOW_FRAME, 0, "Glow Frame"), true); /* 810 */
		self::register(new Item(ItemIds::GLOW_INK_SAC, 0, "Glow Ink Sac"), true); /* 813 */
		self::register(new Item(ItemIds::SPYGLASS, 0, "Spyglass"), true); /* 812 */
		self::register(new Item(ItemIds::AMETHYST_SHARD, 0, "Ametjyst Shard"), true); /* 813 */
		self::register(new GlowBerries(ItemIds::SOUL_CAMPFIRE, 0, "Glow Berries"), true); /* 814 */
		self::register(new Item(ItemIds::COPPER_INGOT, 0, "Copper Ingot"), true); /* 815 */
		self::register(new Item(ItemIds::RAW_IRON, 0, "Raw Iron"), true); /* 816 */
		self::register(new Item(ItemIds::RAW_GOLD, 0, "Raw Gold"), true); /* 817 */
		self::register(new Item(ItemIds::RAW_COPPER, 0, "Raw Copper"), true); /* 818 */
		/* 비공식 패치 */
		
		Item::initCreativeItems();
	}
	
	public static function initItemMapping(){
		self::addComplexItemString("minecraft:powder_snow_bucket", "minecraft:bucket", 11);
		self::addComplexItemString("minecraft:axolotl_bucket", "minecraft:bucket", 12);
		
		self::addComplexItemString("minecraft:goat_spawn_egg", "minecraft:spawn_egg", 128);
		self::addComplexItemString("minecraft:glow_squid_spawn_egg", "minecraft:spawn_egg", 129);
		self::addComplexItemString("minecraft:axolotl_spawn_egg", "minecraft:spawn_egg", 130);
		
		self::addComplexItemInt("minecraft:glow_frame", 810, 0);
		self::addComplexItemInt("minecraft:glow_ink_sac", 811, 0);
		self::addComplexItemInt("minecraft:spyglass", 812, 0);
		self::addComplexItemInt("minecraft:amethyst_shard", 813, 0);
		self::addComplexItemInt("minecraft:glow_berries", 814, 0);
		self::addComplexItemInt("minecraft:copper_ingot", 815, 0);
		self::addComplexItemInt("minecraft:raw_iron", 816, 0);
		self::addComplexItemInt("minecraft:raw_gold", 817, 0);
		self::addComplexItemInt("minecraft:raw_copper", 818, 0);
	}
	
	public static function register(Item $item, bool $override = false){
		ItemFactory::registerItem($item, $override);
	}
	
	public static function addComplexItemString(string $newId, string $oldId, int $meta) : void{
		$runtimeIds = json_decode(file_get_contents(RESOURCE_PATH . "/vanilla/required_item_list.json"), true);
		$legacyStringToIntMap = json_decode(file_get_contents(RESOURCE_PATH . "/vanilla/item_id_map.json"), true);
		$id = $legacyStringToIntMap[$oldId];
		$netId = $runtimeIds[$newId]["runtime_id"];
		
		self::setComplexItem($netId, $id, $meta);
	}
	
	public static function addComplexItemInt(string $newId, int $oldId, int $meta) : void{
		$runtimeIds = json_decode(file_get_contents(RESOURCE_PATH . "/vanilla/required_item_list.json"), true);
		$netId = $runtimeIds[$newId]["runtime_id"];
		
		self::setComplexItem($netId, $oldId, $meta);
	}
	
	public static function setComplexItem($netId, $id, $meta) : void{
		/** complexCoreToNetMapping */
		$property = new ReflectionProperty(ItemTranslator::class, "complexCoreToNetMapping");
		$property->setAccessible(true);
		$value = $property->getValue(ItemTranslator::getInstance());
		$value[$id][$meta] = $netId;
		$property->setValue(ItemTranslator::getInstance(), $value);
		
		/** complexNetToCoreMapping */
		$property = new ReflectionProperty(ItemTranslator::class, "complexNetToCoreMapping");
		$property->setAccessible(true);
		$value = $property->getValue(ItemTranslator::getInstance());
		$value[$netId] = [$id, $meta];
		$property->setValue(ItemTranslator::getInstance(), $value);
	}
}
?>