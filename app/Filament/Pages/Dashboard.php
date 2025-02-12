<?php

namespace App\Filament\Pages;
use Filament\Panel;
use App\Models\Project;
use App\Models\Company;


class Dashboard extends \Filament\Pages\Dashboard
{
  public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->widgets([]);
}
}