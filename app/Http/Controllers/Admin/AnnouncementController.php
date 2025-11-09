<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = $this->getAnnouncements();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'required|date',
            'type' => 'required|in:info,success,warning,error',
        ]);

        $announcements = $this->getAnnouncements();
        $newId = empty($announcements) ? 1 : max(array_column($announcements, 'id')) + 1;
        
        $announcements[] = [
            'id' => $newId,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'date' => $validated['date'],
            'type' => $validated['type'],
        ];

        $this->saveAnnouncements($announcements);

        return redirect()->route('admin.announcements.index')->with('success', 'Ankündigung wurde erstellt.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'required|date',
            'type' => 'required|in:info,success,warning,error',
        ]);

        $announcements = $this->getAnnouncements();
        foreach ($announcements as &$ann) {
            if ($ann['id'] == $id) {
                $ann['title'] = $validated['title'];
                $ann['content'] = $validated['content'];
                $ann['date'] = $validated['date'];
                $ann['type'] = $validated['type'];
                break;
            }
        }

        $this->saveAnnouncements($announcements);

        return redirect()->route('admin.announcements.index')->with('success', 'Ankündigung wurde aktualisiert.');
    }

    public function destroy($id)
    {
        $announcements = $this->getAnnouncements();
        $announcements = array_filter($announcements, fn($a) => $a['id'] != $id);
        $announcements = array_values($announcements);
        
        $this->saveAnnouncements($announcements);

        return redirect()->route('admin.announcements.index')->with('success', 'Ankündigung wurde gelöscht.');
    }

    private function getAnnouncements()
    {
        if (!Storage::exists('announcements.json')) {
            return [];
        }
        $content = Storage::get('announcements.json');
        return json_decode($content, true) ?? [];
    }

    private function saveAnnouncements(array $announcements)
    {
        Storage::put('announcements.json', json_encode($announcements, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

