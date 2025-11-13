<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Tl\Methods;

use Tak\Liveproto\Errors\Security;

trait Utilities {
	public function getDhConfig() : object {
		$version = is_null($this->dhConfig) === false ? $this->dhConfig->version : 0;
		$getDhConfig = $this->messages->getDhConfig(version : $version,random_length : 0);
		if($getDhConfig instanceof \Tak\Liveproto\Tl\Types\Messages\DhConfig):
			$getDhConfig->p = strval(gmp_import($getDhConfig->p));
			Security::checkGoodPrime($getDhConfig->p,$getDhConfig->g);
			$this->dhConfig = $getDhConfig;
		elseif(is_null($this->dhConfig)):
			throw new \Exception('dh config not modified !');
		endif;
		return $this->dhConfig;
	}
	public function getMediaConnections(int $dc_id,bool $pfs = false,? int $count = null) : array {
		if(is_null($count) || $count <= 0):
			$count = $this->settings->getMinConnections();
		endif;
		$media = $this->mediaSocket($dc_id,$pfs);
		$new = min($count - count($media),$this->settings->getMaxConnections());
		if(count($media) < $this->settings->getMaxConnections()):
			if($new === 0) $new++;
		endif;
		if($new > 0):
			for($i = 0;$i < $new;$i++):
				$this->connections []= $pfs ? $this->getTemp(dc_id : $dc_id,expires_in : 24 * 60 * 60) : $this->switchDC(dc_id : $dc_id,media : true,renew : true);
			endfor;
		endif;
		$media = $this->mediaSocket($dc_id,$pfs);
		shuffle($media);
		return array_slice($media,- $count);
	}
	private function mediaSocket(int $dc_id,bool $pfs) : array {
		return array_filter($this->connections,fn(object $client) : bool => $client->load->dc === $dc_id and boolval($pfs === false || $client->load->expires_at > time()));
	}
}

?>