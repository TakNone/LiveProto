<?php

declare(strict_types = 1);

namespace Tak\Liveproto\Network\Proxy;

final class Uri {
	public readonly string $scheme;
	public readonly ? string $user;
	public readonly ? string $pass;
	public readonly string $host;
	public readonly int $port;
	public readonly string $path;
	public readonly ? string $query;
	public readonly ? string $fragment;
	public int $domain {
		get => intval($this->ipVersion === 6 ? AF_INET6 : AF_INET);
	}
	public int $protocol {
		get => match($this->scheme){
			'udp' => SOL_UDP,
			default => SOL_TCP
		};
	}
	public int $type {
		get => match($this->scheme){
			'udp' => SOCK_DGRAM,
			default => SOCK_STREAM
		};
	}
	public int $ipVersion {
		get {
			$ip = trim($this->host,'[]');
			return match(true){
				boolval(filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)) => 4,
				boolval(filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)) => 6,
				default => 0
			};
		}
	}
	public string $address {
		get => trim($this->host,'[]');
	}

	public function __construct(string $uri){
		$parts = parse_url($uri);
		if($parts === false || isset($parts['host']) === false){
			throw new \InvalidArgumentException('Invalid URI : '.$uri);
		}
		$this->scheme = $parts['scheme'] ?? 'tcp';
		$this->user = $parts['user'] ?? null;
		$this->pass = $parts['pass'] ?? null;
		$this->host = $parts['host'];
		$this->port = $parts['port'] ?? $this->getDefaultPort($this->scheme);
		$this->path = $parts['path'] ?? '/';
		$this->query = $parts['query'] ?? null;
		$this->fragment = $parts['fragment'] ?? null;
	}
	private function getDefaultPort(string $scheme) : int {
		return match($scheme){
			'http' , 'ws' => 80,
			'https' , 'wss' => 443,
			'ftp' => 21,
			'ssh' => 22,
			default => 0
		};
	}
}

?>