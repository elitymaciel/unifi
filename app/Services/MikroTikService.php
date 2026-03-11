<?php

namespace App\Services;

/**
 * Lightweight MikroTik RouterOS API Client
 * Based on the official MikroTik RouterOS PHP API class.
 */
class MikroTikService
{
    protected $host;
    protected $user;
    protected $pass;
    protected $port;
    protected $socket;
    protected $connected = false;

    public function __construct(\App\Models\MikroTik $mikrotik = null)
    {
        if ($mikrotik) {
            $this->host = $mikrotik->host;
            $this->user = $mikrotik->username;
            $this->pass = $mikrotik->password;
            $this->port = $mikrotik->port;
        } else {
            $this->host = config('mikrotik.host');
            $this->user = config('mikrotik.user');
            $this->pass = config('mikrotik.password');
            $this->port = config('mikrotik.port', 8728);
        }
    }

    public function connect()
    {
        $this->socket = @fsockopen($this->host, $this->port, $errno, $errstr, 5);
        if (!$this->socket) return false;

        $this->connected = true;
        
        if (!$this->login()) {
            $this->disconnect();
            return false;
        }

        return true;
    }

    protected function login()
    {
        $response = $this->comm('/login');
        if (isset($response['!done'][0]['ret'])) {
            $hash = md5(chr(0) . $this->pass . pack('H*', $response['!done'][0]['ret']));
            $response = $this->comm('/login', [
                'name' => $this->user,
                'response' => '00' . $hash
            ]);
        }
        return (isset($response['!done']));
    }

    public function comm($command, $params = [])
    {
        if (!$this->connected && $command !== '/login') {
            if (!$this->connect()) return false;
        }

        $this->writeWord($command);
        foreach ($params as $key => $value) {
            $this->writeWord('=' . $key . '=' . $value);
        }
        $this->writeWord('');

        return $this->readResponse();
    }

    protected function writeWord($word)
    {
        $length = strlen($word);
        if ($length < 0x80) {
            fwrite($this->socket, chr($length));
        } elseif ($length < 0x4000) {
            fwrite($this->socket, chr(($length >> 8) | 0x80) . chr($length & 0xFF));
        } elseif ($length < 0x200000) {
            fwrite($this->socket, chr(($length >> 16) | 0xC0) . chr(($length >> 8) & 0xFF) . chr($length & 0xFF));
        }
        fwrite($this->socket, $word);
    }

    protected function readResponse()
    {
        $response = [];
        $status = null;
        while (true) {
            $byte = ord(fread($this->socket, 1));
            $length = 0;
            if ($byte & 0x80) {
                if (($byte & 0xC0) == 0x80) {
                    $length = (($byte & 0x3F) << 8) + ord(fread($this->socket, 1));
                } elseif (($byte & 0xE0) == 0xC0) {
                    $length = (($byte & 0x1F) << 16) + (ord(fread($this->socket, 1)) << 8) + ord(fread($this->socket, 1));
                }
            } else {
                $length = $byte;
            }

            if ($length > 0) {
                $word = fread($this->socket, $length);
                if (strpos($word, '!') === 0) {
                    $status = $word;
                } else {
                    $parts = explode('=', substr($word, 1), 2);
                    if (count($parts) == 2) {
                        $response[$status][] = [$parts[0] => $parts[1]];
                    }
                }
            } else {
                if ($status == '!done') break;
            }
        }
        return $response;
    }

    public function createHotspotUser($name, $password, $profile = 'default', $comment = '')
    {
        return $this->comm('/ip/hotspot/user/add', [
            'name' => $name,
            'password' => $password,
            'profile' => $profile,
            'comment' => $comment
        ]);
    }

    public function listHotspotUsers()
    {
        $response = $this->comm('/ip/hotspot/user/print');
        return $response['!re'] ?? [];
    }

    public function disconnect()
    {
        if ($this->socket) fclose($this->socket);
        $this->connected = false;
    }
}
