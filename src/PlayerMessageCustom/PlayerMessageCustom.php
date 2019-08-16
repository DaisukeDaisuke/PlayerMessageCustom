<?php

namespace PlayerMessageCustom;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

//use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketSendEvent;

use pocketmine\utils\Config;
class PlayerMessageCustom extends PluginBase implements Listener{
	public $edb = [];

	const NO_OUTPUT = 0;
	const CHAT = 1;
	const TIP = 2;
	const POPUP = 3;

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("config.yml");
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML,[
			"Join_Message_destination" => 1,
			"Quit_Message_destination" => 1,
			"console_output" =>  "yes",
			"JoinMessages" => [
				"{%0} がゲームに参加しました",
			],
			"QuitMessages" => [
				"{%0} がゲームを退出しました"
			],
		]);
		$this->Join_Message_destination = $this->config->get("Join_Message_destination");
		$this->Quit_Message_destination = $this->config->get("Quit_Message_destination");
		$this->console_output = $this->config->get("console_output");
		$this->JoinMessages = $this->config->get("JoinMessages");
		$this->QuitMessages = $this->config->get("QuitMessages");
		unset($this->config);
		//$this->fakehp_hp = $this->config->get("fakehp_hp");
		//$this->login_edb = $this->config->get("login_edb");
		//unset($this->config);
	}

	public function join(PlayerJoinEvent $event){
		if($this->Join_Message_destination === -1){
			return;
		}
		$message = str_replace("#PLAYER",$event->getPlayer()->getName(),$this->JoinMessages[mt_rand(0,count($this->JoinMessages)-1)]);
		switch($this->Join_Message_destination){
			case self::NO_OUTPUT:
				$event->setJoinMessage("");
			break;
			case self::CHAT:
				$event->setJoinMessage($message);
				$this->info($message);
			break;
			case self::TIP:
				$event->setJoinMessage("");
				$this->getServer()->broadcastTip($message);
				$this->info($message);
			break;
			case self::POPUP:
				$event->setJoinMessage("");
				$this->getServer()->broadcastPopup($message);
				$this->info($message);
			break;
		}
		//$this->getLogger()->info($message);
	}

	public function Quit(PlayerQuitEvent $event){
		if($this->Quit_Message_destination == -1){
			return;
		}
		$message = str_replace("#PLAYER",$event->getPlayer()->getName(),$this->QuitMessages[mt_rand(0,count($this->QuitMessages)-1)]);
		switch($this->Quit_Message_destination){
			case self::NO_OUTPUT:
				$event->setQuitMessage("");
			break;
			case self::CHAT:
				$event->setQuitMessage($message);
				$this->info($message);
			break;
			case self::TIP:
				$event->setQuitMessage("");
				$this->getServer()->broadcastTip($message);
				$this->info($message);
			break;
			case self::POPUP:
				$event->setQuitMessage("");
				$this->getServer()->broadcastPopup($message);
				$this->info($message);
			break;
		}
	}
	public function info($message){
		if($this->console_output === true){
			$this->getLogger()->info($message);
		}
	}
}
