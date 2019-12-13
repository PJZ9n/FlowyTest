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
    use pocketmine\event\player\PlayerChatEvent;
    use function flowy\listen;
    use pocketmine\plugin\PluginBase;
    use pocketmine\event\block\BlockBreakEvent;
    
    class FlowyTest extends PluginBase
    {
        
        public function onEnable()
        {
            Flowy::run($this, Closure::fromCallable(function () {
                /** @var BlockBreakEvent $event */
                $event = yield listen(BlockBreakEvent::class);//多分ここで待機
                $player = $event->getPlayer();
                
                $block = $event->getBlock();//保持できるかテスト
                
                $player->sendMessage(
                    "ブロックが破壊されました。次から選んでください。\n" .
                    "1: 再設置\n" .
                    "2: キック\n" .
                    "3: ブロックの情報を表示"
                );
                
                /** @var PlayerChatEvent $event */
                $event = yield listen(PlayerChatEvent::class)->filter(function (/** @var PlayerChatEvent $ev */ $ev) use ($player) {
                    return $ev->getPlayer() === $player;//さっきと同じプレイヤー(オブジェクト)だったら?
                });//多分ここで待機
                
                switch ($event->getMessage()) {
                    case "1":
                        $block->getLevel()->setBlock($block, $block);//ちゃんと保持できるはず
                        $player->sendMessage("ブロックを設置しました！");
                        break;
                    case "2":
                        $player->kick("死んでください");
                        break;
                    case "3":
                        $player->sendMessage($block->__toString());//こっちも
                        break;
                    default:
                        $player->sendMessage("デフォルト！");
                        break;
                }
                
                //ここでフローが終了。
            }));
        }
        
    }