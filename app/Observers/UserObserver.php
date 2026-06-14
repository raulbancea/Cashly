<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ExpenseCategory;

class UserObserver
{

    public function created(User $user)
    {

        $defaults = [
            ['name' => 'Transport',             'color' => '#3b82f6'],
            ['name' => 'Masă / Restaurant',     'color' => '#f97316'],
            ['name' => 'Software / Abonamente', 'color' => '#8b5cf6'],
            ['name' => 'Marketing',             'color' => '#ec4899'],
            ['name' => 'Birou / Papetărie',     'color' => '#06b6d4'],
            ['name' => 'Utilități',             'color' => '#f59e0b'],
            ['name' => 'Salarii',               'color' => '#10b981'],
            ['name' => 'Altele',                'color' => '#6b7280'],
        ];

        foreach ($defaults as $category) {
            ExpenseCategory::create([
                'user_id' => $user->id,
                'name'    => $category['name'],
                'color'   => $category['color'],
            ]);
        }
    }
}
