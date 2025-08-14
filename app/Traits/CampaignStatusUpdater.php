<?php

namespace App\Traits;

use App\Models\TaskVolunteerProfile;

trait CampaignStatusUpdater
{
    public function updateStatus($type)
    {
        if ($type === 'individual' && !in_array($this->acceptance_status_id, [2])) {
        return;
        }

        if ($type === 'individual') {
            $donated = $this->donations()->sum('amount');
        } elseif ($type === 'association') {
            $donated = $this->donationAssociationCampaigns()->sum('amount');
        } else {
            return;
        }

        if ($donated == $this->amount_required) {
            $this->campaign_status_id = 3;
        } elseif (!is_null($this->compaigns_end_time) && now()->gt($this->compaigns_end_time)) {
            $this->campaign_status_id = 2;
        } else {
            $this->campaign_status_id = 1;
        }

        if($type === 'association' && in_array($this->campaign_status_id , [2,3])){
            $tasks = $this->volunteerTasks()->pluck('id');
            TaskVolunteerProfile::with('status')
                                ->whereIn('volunteer_task_id' , $tasks)
                                ->update([
                                   'status_id' => 2
                                ]);
            }

        $this->load('campaignStatus');

        $this->save();
    }
}
