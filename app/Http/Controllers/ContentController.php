<?php

namespace App\Http\Controllers;

use App\Models\BhudevSamagriCard;
use App\Models\PanchangEntry;
use App\Models\Rashifal;
use App\Services\AstroApiService;
use App\Services\MuhuratService;
use App\Services\PoojaSamagriService;
use App\Services\VedicAstroEngine;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    protected AstroApiService $astroService;

    public function __construct(AstroApiService $astroService)
    {
        $this->astroService = $astroService;
    }

    /**
     * Today's Daily Rashifal for all 12 signs (Current day only).
     */
    public function rashifal(Request $request)
    {
        // Strictly locked to the current date (Asia/Kolkata timezone)
        $today = now('Asia/Kolkata')->startOfDay();
        $date = $today->toDateString();

        $items = Rashifal::whereDate('date', $date)
            ->where('status', 'published')
            ->orderBy('id')
            ->get()
            ->keyBy('sign');

        // Self-healing check: if today's records are missing, generate & persist immediately
        if ($items->count() < 12) {
            $this->astroService->syncDays($today, 1);
            $items = Rashifal::whereDate('date', $date)
                ->where('status', 'published')
                ->orderBy('id')
                ->get()
                ->keyBy('sign');
        }

        return view('pages.rashifal', compact('date', 'items'));
    }

    /**
     * Today's Nitya Panchang (Current day only, location-selectable).
     */
    public function panchang(Request $request)
    {
        // Strictly locked to the current date (Asia/Kolkata timezone)
        $today = now('Asia/Kolkata')->startOfDay();
        $date = $today->toDateString();

        $location = trim((string) $request->query('location', ''));
        $searchLocation = $location ?: VedicAstroEngine::DEFAULT_LOCATION;

        $entry = PanchangEntry::whereDate('date', $date)
            ->where('status', 'published')
            ->where(function ($q) use ($searchLocation) {
                $q->where('location', $searchLocation)
                  ->orWhere('location', 'like', '%' . explode(',', $searchLocation)[0] . '%');
            })
            ->first();

        // Self-healing check: if today's entry is missing, generate & persist immediately
        if (!$entry) {
            $this->astroService->syncDays($today, 1, $searchLocation);
            $entry = PanchangEntry::whereDate('date', $date)
                ->where('status', 'published')
                ->where(function ($q) use ($searchLocation) {
                    $q->where('location', $searchLocation)
                      ->orWhere('location', 'like', '%' . explode(',', $searchLocation)[0] . '%');
                })
                ->first();
        }

        return view('pages.panchang', compact('date', 'location', 'entry'));
    }

    /**
     * Pooja Samagri Checklists (Interactive, printable, and shareable).
     */
    public function samagri(Request $request, ?string $slug = null)
    {
        $all = PoojaSamagriService::all();

        if ($slug) {
            abort_unless(isset($all[$slug]), 404, 'Samagri checklist not found.');
            $selected = $all[$slug];
            return view('pages.samagri-detail', compact('slug', 'selected', 'all'));
        }

        return view('pages.samagri-index', compact('all'));
    }

    /**
     * Shubh Muhurat Guide (Vivah, Griha Pravesh, Vahan, Namkaran).
     */
    public function muhurat(Request $request)
    {
        $muhurats = MuhuratService::all();
        return view('pages.muhurat', compact('muhurats'));
    }

    /**
     * Show custom generated Pooja Samagri Patrika (Public Devotee/Yajman view with 1-click Download, Print & WhatsApp).
     */
    public function showSamagriCard(Request $request, BhudevSamagriCard $card)
    {
        $card->load(['profile.user']);
        return view('pages.samagri-card-show', compact('card'));
    }
}
