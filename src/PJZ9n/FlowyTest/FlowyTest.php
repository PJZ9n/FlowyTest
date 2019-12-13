<?php
    
    /**
     *  Copyright (c) 2019 PJZ9n.
     *
     * This program is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 3 of the License, or
     * (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License
     * along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */
    
    declare(strict_types=1);
    
    namespace PJZ9n\FlowyTest;
    
    require_once(dirname(__FILE__) . "/../../../vendor/autoload.php");
    
    use Closure;
    use flowy\Flowy;
    use function flowy\listen;
    use pocketmine\plugin\PluginBase;
    use pocketmine\event\block\BlockBreakEvent;
    
    class FlowyTest extends PluginBase
    {
        
        public function onEnable()
        {
            Flowy::run($this, Closure::fromCallable(function () {
                /** @var BlockBreakEvent $event */
                $event = yield listen(BlockBreakEvent::class);
                $player = $event->getPlayer();
                $player->sendMessage("block break hahaha");
            }));
        }
        
    }