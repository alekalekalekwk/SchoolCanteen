<?php

namespace App\Livewire\Admin;

use App\Models\TimeSlot;
use App\Models\ScheduleSetting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class ScheduleManagement extends Component
{
    public $editingId = null;
    public $type;
    public $start_time;
    public $end_time;
    public $sort_order;

    public function toggleOverride()
    {
        $setting = ScheduleSetting::first();
        $setting->update(['override_active' => !$setting->override_active]);
    }

    public function edit($id)
    {
        $slot = TimeSlot::findOrFail($id);
        $this->editingId = $slot->id;
        $this->type = $slot->type;
        $this->start_time = $slot->start_time;
        $this->end_time = $slot->end_time;
        $this->sort_order = $slot->sort_order;
    }

    public function save()
    {
        $this->validate([
            'type' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'sort_order' => 'required|integer',
        ]);

        $overlap = TimeSlot::where('type', $this->type)
            ->when($this->editingId, fn($q) => $q->where('id', '!=', $this->editingId))
            ->where(function ($q) {
                $q->where('start_time', '<', $this->end_time)
                  ->where('end_time', '>', $this->start_time);
            })
            ->exists();

        if ($overlap) {
            $this->addError('start_time', 'Slot waktu bertabrakan dengan jadwal yang sudah ada.');
            return;
        }

        TimeSlot::updateOrCreate(['id' => $this->editingId], [
            'type' => $this->type,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'sort_order' => $this->sort_order,
        ]);

        $this->reset(['editingId', 'type', 'start_time', 'end_time', 'sort_order']);
    }

    public function delete($id)
    {
        TimeSlot::destroy($id);
    }

    public function cancelEdit()
    {
        $this->reset(['editingId', 'type', 'start_time', 'end_time', 'sort_order']);
    }

    public function render()
    {
        return view('livewire.admin.schedule-management', [
            'setting' => ScheduleSetting::first(),
            'senin' => TimeSlot::where('type', 'senin')->orderBy('sort_order')->get(),
            'reguler' => TimeSlot::where('type', 'reguler')->orderBy('sort_order')->get(),
            'override' => TimeSlot::where('type', 'override')->orderBy('sort_order')->get(),
        ]);
    }
}
