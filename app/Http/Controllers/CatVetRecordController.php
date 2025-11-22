<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\CatVetRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;

class CatVetRecordController extends Controller
{
    public function store(Request $request, Cat $cat): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $data = $request->validate([
            'visit_date' => ['required', 'date'],
            'clinic_name' => ['nullable', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'document' => ['nullable', 'file', 'max:4096'],
        ]);

        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('vet-documents', 'public');
        }

        $data['amount'] = $data['amount'] ?? 0;

        $cat->vetRecords()->create($data);

        $this->logActivity('vet.created', $cat, 'Visite vétérinaire ajoutée.');

        return back()->with('status', 'Visite vétérinaire enregistrée.');
    }

    public function update(Request $request, Cat $cat, CatVetRecord $vetRecord): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);
        abort_unless($vetRecord->cat_id === $cat->id, 404);

        $data = $request->validate([
            'visit_date' => ['required', 'date'],
            'clinic_name' => ['nullable', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'document' => ['nullable', 'file', 'max:4096'],
        ]);

        if ($request->hasFile('document')) {
            if ($vetRecord->document_path && Storage::disk('public')->exists($vetRecord->document_path)) {
                Storage::disk('public')->delete($vetRecord->document_path);
            }
            $data['document_path'] = $request->file('document')->store('vet-documents', 'public');
        }

        $data['amount'] = $data['amount'] ?? 0;

        $vetRecord->update($data);

        $this->logActivity('vet.updated', $cat, 'Visite vétérinaire mise à jour.');

        return back()->with('status', 'Visite mise à jour.');
    }

    public function destroy(Cat $cat, CatVetRecord $vetRecord): RedirectResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);
        abort_unless($vetRecord->cat_id === $cat->id, 404);

        if ($vetRecord->document_path && Storage::disk('public')->exists($vetRecord->document_path)) {
            Storage::disk('public')->delete($vetRecord->document_path);
        }

        $vetRecord->delete();

        $this->logActivity('vet.deleted', $cat, 'Visite vétérinaire supprimée.');

        return back()->with('status', 'Visite supprimée.');
    }

    public function export(Cat $cat): StreamedResponse
    {
        $this->authorizeRoles(['admin', 'benevole']);

        $filename = 'chat-' . $cat->id . '-visites-veterinaires.csv';

        return response()->streamDownload(function () use ($cat) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Chat', 'Date', 'Motif', 'Clinique', 'Montant (€)', 'Notes']);

            $cat->vetRecords()
                ->orderByDesc('visit_date')
                ->each(function (CatVetRecord $record) use ($handle, $cat) {
                    fputcsv($handle, [
                        $cat->name,
                        optional($record->visit_date)->format('Y-m-d'),
                        $record->reason,
                        $record->clinic_name,
                        number_format($record->amount, 2, '.', ''),
                        $record->notes,
                    ]);
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
