<?php

namespace App\Services;

use UniFi_API\Client;

class UniFiService
{
    protected $client;
    protected $blockedSites = ['default', 's5mpyj5u', 'wk6c46ff'];

    public function __construct()
    {
        $siteId = session('unifi_site_id', config('unifi.site_id'));

        $this->client = new Client(
            config('unifi.user'),
            config('unifi.password'),
            config('unifi.url'),
            $siteId,
            config('unifi.version'),
            config('unifi.debug')
        );
    }

    public function login()
    {
        return $this->client->login();
    }

    public function getActiveSite()
    {
        $this->login();
        $sites = $this->client->list_sites();
        $siteId = session('unifi_site_id', config('unifi.site_id'));
        
        return collect($sites)->firstWhere('name', $siteId);
    }

    public function list_sites($user = null)
    {
        $this->login();
        $allSites = $this->client->list_sites();
        
        // Apply Global Filter (Blocked Sites)
        $sites = array_filter($allSites, function($site) {
            return !in_array($site->name, $this->blockedSites);
        });

        if ($user && !$user->isAdmin()) {
            $allowed = $user->allowedSites();
            $sites = array_filter($sites, function($site) use ($allowed) {
                return in_array($site->name, $allowed);
            });
        }
        
        return array_values($sites);
    }

    public function setSite($siteId)
    {
        session(['unifi_site_id' => $siteId]);
    }

    public function getWlans($user = null)
    {
        $this->login();
        $wlans = $this->client->list_wlanconf();

        if ($user && !$user->isAdmin()) {
            $restricted = ['VISITANTES', 'COLABORADORES', 'CAN'];
            $wlans = array_values(array_filter($wlans, function($wlan) use ($restricted) {
                return !in_array(strtoupper($wlan->name), $restricted);
            }));
        }

        return $wlans;
    }

    public function updateWlanName($wlan_id, $new_name)
    {
        $this->login();
        return $this->client->set_wlansettings_base($wlan_id, ['name' => $new_name]);
    }

    public function getDevices()
    {
        $this->login();
        return $this->client->list_devices();
    }

    public function getClients()
    {
        $this->login();
        return $this->client->list_clients();
    }

    public function getKnownUsers()
    {
        $this->login();
        return $this->client->list_users();
    }

    public function getGlobalStats($user = null)
    {
        $this->login();
        $sites = $this->list_sites($user);
        
        $originalSite = session('unifi_site_id', config('unifi.site_id'));
        
        $stats = [
            'total_sites' => count($sites),
            'total_devices' => 0,
            'total_clients' => 0,
            'sites_breakdown' => []
        ];

        foreach ($sites as $site) {
            // Temporarily switch to the site to fetch stats
            $this->client->set_site($site->name);
            
            $devices = $this->client->list_devices();
            $clients = $this->client->list_clients();
            
            $deviceCount = count($devices ?? []);
            $clientCount = count($clients ?? []);
            
            $stats['total_devices'] += $deviceCount;
            $stats['total_clients'] += $clientCount;
            
            $stats['sites_breakdown'][] = [
                'name' => $site->name,
                'desc' => $site->desc,
                'devices' => $deviceCount,
                'clients' => $clientCount
            ];
        }

        // Restore the original site
        $this->client->set_site($originalSite);

        return $stats;
    }

    protected function getUserIdByMac($mac)
    {
        $this->login();
        $users = $this->client->list_users();
        $user = collect($users)->firstWhere('mac', strtolower($mac));
        return $user ? $user->_id : null;
    }

    public function addMacToFilter($wlan_id, $mac, $name = null)
    {
        $this->login();
        
        $wlans = $this->client->list_wlanconf($wlan_id);
        if (!$wlans) return false;
        
        $wlan = $wlans[0];
        $mac_filter_list = $wlan->mac_filter_list ?? [];
        if (!in_array(strtolower($mac), array_map('strtolower', $mac_filter_list))) {
            $mac_filter_list[] = strtolower($mac);
        }
        
        // Update name on the controller if provided
        if ($name) {
            $userId = $this->getUserIdByMac($mac);
            if ($userId) {
                $this->client->set_sta_name($userId, $name);
            }
        }
        
        return $this->client->set_wlan_mac_filter($wlan_id, $wlan->mac_filter_policy ?? 'allow', true, $mac_filter_list);
    }

    public function removeMacFromFilter($wlan_id, $mac)
    {
        $this->login();
        
        $wlans = $this->client->list_wlanconf($wlan_id);
        if (!$wlans) return false;
        
        $wlan = $wlans[0];
        $mac_filter_list = $wlan->mac_filter_list ?? [];
        
        $mac_filter_list = array_filter($mac_filter_list, function($m) use ($mac) {
            return strtolower($m) !== strtolower($mac);
        });

        // Clear name on the controller
        $userId = $this->getUserIdByMac($mac);
        if ($userId) {
            $this->client->set_sta_name($userId, '');
        }
        
        return $this->client->set_wlan_mac_filter($wlan_id, $wlan->mac_filter_policy ?? 'allow', true, array_values($mac_filter_list));
    }
}
