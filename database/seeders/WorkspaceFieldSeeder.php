<?php

namespace Database\Seeders;

use App\Models\WorkspaceField;
use Illuminate\Database\Seeder;

class WorkspaceFieldSeeder extends Seeder
{
    public function run(): void
    {
        WorkspaceField::updateOrCreate(
            ['form' => 'coffee_chat', 'key' => 'follow_up_priority'],
            [
                'label' => 'Follow-up priority',
                'type' => 'select',
                'required' => false,
                'in_analytics' => true,
                'position' => 10,
                'placeholder' => 'Select priority',
                'options' => [
                    ['value' => 'high', 'label' => 'High'],
                    ['value' => 'medium', 'label' => 'Medium'],
                    ['value' => 'low', 'label' => 'Low'],
                ],
            ]
        );

        WorkspaceField::updateOrCreate(
            ['form' => 'coffee_chat', 'key' => 'discussion_focus'],
            [
                'label' => 'Discussion focus',
                'type' => 'multiselect',
                'required' => false,
                'in_analytics' => true,
                'position' => 11,
                'help_text' => 'Select all that applied',
                'options' => [
                    ['value' => 'role_insights', 'label' => 'Role insights'],
                    ['value' => 'company_culture', 'label' => 'Company culture'],
                    ['value' => 'referral', 'label' => 'Referral ask'],
                    ['value' => 'personal_story', 'label' => 'Personal story'],
                ],
            ]
        );

        WorkspaceField::updateOrCreate(
            ['form' => 'coffee_chat', 'key' => 'referral_promised'],
            [
                'label' => 'Referral promised',
                'type' => 'boolean',
                'required' => false,
                'in_analytics' => true,
                'position' => 12,
                'placeholder' => 'Referral promised',
            ]
        );
    }
}
