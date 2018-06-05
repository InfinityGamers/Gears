## Gears
[![Chat](https://img.shields.io/badge/chat-on%20discord-7289da.svg)](https://discord.gg/uctHyRD)<br>
Tired of same old boring kits? try this!<br/><br/>

### How to use:

Every kit has it's own ability, which you can do by doing certain things like right clicking<br/>
an item in hand or hitting another player, these are called "modes".<br/>
Each kit can also be configured to a custom cooldown, items, effects, commands, etc...<br/>
All kits that come with this plugin require a special configurable item to activate the ability, except for iceman.<br/><br/>

### Special Kits List:
+ Acrobat:
  + Mode: left/right click
  + Custom Cooldown: yes
  + Custom Deactivate Time: no 
  + Description: Jump high in the air, being able to escape from enemies. Takes no fall damage.
+ Bartender:
  + Mode: damage player
  + Custom Cooldown: yes
  + Custom Deactivate Time: yes
  + Description: Gives target nausea effect to custom amount of seconds.
+ Berserker:
  + Mode: right click
  + Custom Cooldown: yes
  + Custom Deactivate Time: yes
  + Description: Grows player 2 times their own size and the damage is doubled.
+ Iceman:
  + Mode: move
  + Custom Cooldown: no
  + Custom Deactivate Time: no
  + Description: Creates a 3x1x3 ice path when player walks on water.
+ Magneto:
  + Mode: right click
  + Custom Cooldown: yes
  + Custom Deactivate Time: no
  + Description: Pulls all players within 20 blocks and launches them into the air causing fall damage.
+ Scorpio:
  + Mode: right click
  + Custom Cooldown: yes
  + Custom Deactivate Time: yes
  + Description: Launches special item and the player that picks it up will be 
  teleported to the player who launched it. The item will disappear after a custom amount of seconds.
+ Spider:
  + Mode: right click
  + Custom Cooldown: yes
  + Custom Deactivate Time: yes
  + Description: Spawn a 10x2x10 cob web trap in the block the player is looking at. 
  All players in it will get a poison effect with a custom amount of seconds. While the ability is active the
  player can also climb walls and glide like a spider. 
  The cob web trap will slowly disappear after a custom amount of seconds and all blocks will return to normality.
+ Swapper:
  + Mode: right click
  + Custom Cooldown: yes
  + Custom Deactivate Time: no
  + Description: Point at a player with the special item and you
+ Thor:
  + Mode: move
  + Custom Cooldown: no
  + Custom Deactivate Time: no
  + Description: Spawn a 10x1x10 fire layer on top of a 10x1x10 netherrack layer 
  in the block the player is looking at. 
  Lighting will summon and all players in it will be set on fire for a custom amount of seconds. 
  Both layers will slowly disappear after a custom amount of seconds and all blocks will return to normality.
<br/><br/>

### Permissions

All kit permissions are set to `gear.<the kits name all lowercase>`
<br/><br/>

### Commands:

+ `/gear <kit name>`
+ aliases: gears
+ If you just type `/gear` it will show all kits available to the sender.
+ To unload a kit: `/gear reset`
<br/><br/>

### Configuration

Example:
```yaml
ability_item: 288:0:1:&bAcrobat &a| Click with this to use ability
cooldown: 10s
deactivate_time: -1s
helmet: "298:0:1:default"
chest: 299:0:1:default:protection:1
legs: "300:0:1:default"
items:
- 268:0:1:default:sharpness:1
commands:
- tell @player &5You equipped the @kit kit.
effects:
- strength:1:30
```

### Format:
+ Items:
  + ```id:damage:amount:display name:enchanments...```
  
  + example (Diamond sword with sharpness & unbreaking): ```276:0:1:default:sharpness:4:unbreaking:3``` 
  + other: if display name is set to `default` the item display name will be set to it's default minecraft name.
  + The enchantments are optional.
+ Commands:
  + It's exactly like any console command, but the player name is replace with `@player` and the kit name with `@kit`
+ Effects:
  + `effect name:amplifier:duration in seconds`
  + example (Strength 1 with 30 second duration): `strength:1:30`
+ Cooldown & Deactivate Time:
  + `integer(s) followed by the letter(s) for each time frame.`
    + Year = `y`
    + Week = `w`
    + Day = `d`
    + Month = `mo`
    + Hour = `h`
    + Minute = `m`
    + Second = `s`
  + example (1 minute and 30 seconds): `1m30s`
  + example (1 week and 3 days): `1w3d`
  + example (1 year and 3 months): `1y3mo`
  + Cooldown: time it takes to be able to use kit again.
  + Deactivate Time: time it takes for the ability and it's effect **while using it** to completely deactivate.
<br/><br/>

### Create Your Own Kits:

First create a class which extends the `InfinityGamers\Gears\Kit\Kit` class:
```php
namespace mykitsplugin;
use InfinityGamers\Gears\Kit\Kit;
class MyCustomKit extends Kit{
}
```

in the class constructor call the parent constructor with your kit configuration:
```php
namespace mykitsplugin;
use pocketmine\item\Item;
use InfinityGamers\Gears\Kit\Kit;
class RandomTeleportKit extends Kit{
        public function __construct(){
            $kitName = "RandomTeleportKit";
            $specialItem = Item::get(345, 0, 1); //compass
            $items = [
                Item::get(276, 0, 1) // diamond sword
            ]; // generic items
            $clickMode = Kit::ALL_CLICK_MODE; // check all click modes in the Kit class
            $coolDown = 30; // in seconds
            $deactivateTime = -1; // in seconds, not needed in this case
            
            parent::__construct($kitName, $specialItem, $items, $clickMode, $coolDown, $deactivateTime);
        }
}
```
Now add the `onUseSpecialItem` function:
```php
namespace mykitsplugin;
use pocketmine\item\Item;
use InfinityGamers\Gears\Kit\Kit;
class RandomTeleportKit extends Kit{
        public function __construct(){
            $kitName = "RandomTeleportKit";
            $specialItem = Item::get(345, 0, 1); //compass
            $items = [
                Item::get(276, 0, 1) // diamond sword
            ]; // generic items
            $clickMode = Kit::ALL_CLICK_MODE; // check all click modes in the Kit class
            $coolDown = 30; // in seconds
            $deactivateTime = -1; // in seconds, not needed in this case
            
            parent::__construct($kitName, $specialItem, $items, $clickMode, $coolDown, $deactivateTime);
        }
        
        public function onUseSpecialItem(array $data){      
        }
}
```

The `$data` argument is different for each mode, check the plugin kits for examples.
Now make sure the string tag named `kit_name` from the special item is equal to your kits name, 
check the cooldown, then you can make things work your way.<br/>
You can also use `$this->getGearsInstance()` to get the instance of the plugin's base.
```php
namespace mykitsplugin;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use InfinityGamers\Gears\Kit\Kit;
class RandomTeleportKit extends Kit{
        public function __construct(){
                $kitName = "RandomTeleportKit";
                $specialItem = Item::get(345, 0, 1); //compass
                $items = [
                    Item::get(276, 0, 1) // diamond sword
                ]; // generic items
                $clickMode = Kit::ALL_CLICK_MODE; // check all click modes in the Kit class
                $coolDown = 30; // in seconds
                $deactivateTime = -1; // in seconds, not needed in this case

                parent::__construct($kitName, $specialItem, $items, $clickMode, $coolDown, $deactivateTime);
        }

        public function onUseSpecialItem(array $data){
                $player = $data['Player'];
                $item = $data['Item'];

                if(($player instanceof Player) and ($item instanceof Item)){
                        if(!$item->hasCustomBlockData()) return false;

                        /** @var CompoundTag $data */
                        $data = $item->getCustomBlockData();

                        if(!$data->hasTag("kit_name")) return false;
                        if(strtolower($data->getString("kit_name")) === "randomteleportkit"){
                                if($this->checkCoolDown($player)){
                                    $randomX = mt_rand(0, 1000);
                                    $randomY = mt_rand(0, 256);
                                    $randomZ = mt_rand(0, 1000);
    
                                    $v3 = new Vector3($randomX, $randomY, $randomZ);
    
                                    $safeSpawn = $player->level->getSafeSpawn($v3);
    
                                    $player->teleport($safeSpawn);
                                }
                        }
                }

                return true;
        }
}
```
Finally register the kit to the kit manager from your plugin, make sure to have the server instance.

```php
$randomTeleportKit = new RandomTeleportKit();
$plugin = $server->getPluginManager()->getPlugin('Gears');
if($plugin !== null){
    $plugin->getKitManager()->registerKit($randomTeleportKit);
}
```

You can also optionally add the following functions to your custom kit: 
+ `onLoad()` called when player loads the kit. 
+ `onUnload()` called when player unloads the kit. 
+ `onDeath()` called when player dies.
+ `onQuit()` called when player quits the server.