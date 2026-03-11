<?php

namespace App\Http\Controllers;

use App\Services\UniFiService;
use Illuminate\Http\Request;

class UniFiController extends Controller
{
    protected $unifi;

    public function __construct(UniFiService $unifi)
    {
        $this->unifi = $unifi;
    }

    public function dashboard()
    {
        try {
            $stats = $this->unifi->getGlobalStats(auth()->user());
            return view('dashboard', compact('stats'));
        } catch (\Exception $e) {
            return view('dashboard')->with('error', 'Could not connect to UniFi: ' . $e->getMessage());
        }
    }

    public function networks()
    {
        try {
            $sites = $this->unifi->list_sites(auth()->user());
            $wlans = $this->unifi->getWlans();
            $devices = $this->unifi->getDevices();
            $clients = $this->unifi->getClients();

            return view('unifi.networks', compact('wlans', 'devices', 'clients', 'sites'));
        } catch (\Exception $e) {
            return view('unifi.wifi')->with('error', 'Could not connect to UniFi: ' . $e->getMessage());
        }
    }

    public function indexWifi()
    {
        $wlans = $this->unifi->getWlans(auth()->user());
        $activeSite = $this->unifi->getActiveSite();
        return view('unifi.wifi', compact('wlans', 'activeSite'));
    }

    public function updateWifi(\App\Http\Requests\UniFi\MacFilterRequest $request)
    {
        // Note: Using MacFilterRequest here because it has wlan_id and name, 
        // which matches what updateWifi needed before. But updateWifi is mostly 
        // deprecated by the user's request to remove renaming. 
        // I will keep it functional for the backend just in case.

        // Verify the WLAN belongs to the current site
        $wlans = $this->unifi->getWlans();
        $wlan = collect($wlans)->firstWhere('_id', $request->wlan_id);
        
        if (!$wlan) {
            return back()->with('error', 'Erro: Esta rede WiFi não pertence ao site selecionado ou não existe.');
        }

        $this->unifi->updateWlanName($request->wlan_id, $request->name);

        return back()->with('success', 'Nome da rede WiFi atualizado com sucesso.');
    }

    public function indexDevices()
    {
        $clients = $this->unifi->getClients();
        $wlans = $this->unifi->getWlans();
        return view('unifi.devices', compact('clients', 'wlans'));
    }

    public function addMacFilter(\App\Http\Requests\UniFi\MacFilterRequest $request)
    {
        $this->unifi->addMacToFilter($request->wlan_id, $request->mac, $request->name);

        return back()->with('success', 'MAC address added to filter with name.');
    }

    public function selectSite(\App\Http\Requests\UniFi\SelectSiteRequest $request)
    {
        $this->unifi->setSite($request->site_id);

        return redirect()->route('unifi.networks')->with('success', 'Site switched successfully.');
    }

    public function indexMacFilters($wlan_id)
    {
        $wlans = $this->unifi->getWlans();
        // Find the specific WLAN
        $wlan = collect($wlans)->firstWhere('_id', $wlan_id);
        
        if (!$wlan) {
            return redirect()->route('unifi.wifi')->with('error', 'WLAN not found.');
        }

        // Fetch known users to get names for the MAC list
        $knownUsers = $this->unifi->getKnownUsers();
        $macNames = collect($knownUsers)->mapWithKeys(function($user) {
            return [$user->mac => $user->name ?? $user->hostname ?? null];
        });

        return view('unifi.mac-filters', compact('wlan', 'macNames'));
    }

    public function removeMacFilter(\App\Http\Requests\UniFi\MacFilterRequest $request)
    {
        $this->unifi->removeMacFromFilter($request->wlan_id, $request->mac);

        return back()->with('success', 'MAC address removed from filter.');
    }
}
