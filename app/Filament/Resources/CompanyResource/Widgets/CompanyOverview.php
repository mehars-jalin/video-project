<?php

namespace App\Filament\Resources\CompanyResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use  App\Filament\Resources\CompanyResource;

class CompanyOverview extends BaseWidget
{
    public static function getWidgets(): array
    {
        return [
            CompanyResource\Widgets\CompanyOverview::class,
        ];
    }
}
