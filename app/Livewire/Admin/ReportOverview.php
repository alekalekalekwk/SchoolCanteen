<?php

namespace App\Livewire\Admin;

use App\Models\CreditReport;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('components.layouts.app')]
class ReportOverview extends Component
{
    use AuthorizesRequests;

    public function render()
    {
        $reports = CreditReport::with(['reportedUser', 'reporter', 'order'])
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.admin.report-overview', compact('reports'));
    }

    public function cancelReport(int $reportId)
    {
        $report = CreditReport::findOrFail($reportId);
        $this->authorize('update', $report);

        if ($report->status === 'aktif') {
            $report->reportedUser->increment('credit_score', $report->score_deduction);
            $report->status = 'dibatalkan';
            $report->save();
            session()->flash('success', 'Laporan dibatalkan, skor peminjam dikembalikan.');
        }
    }
}
