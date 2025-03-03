<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use App\Models\Company;

class CompanyList extends BaseWidget
{
    protected static ?int $sort = 2;

    public function table(Tables\Table $table): Tables\Table
    {
        echo phpinfo();die;

        return $table
            ->query(Company::query()) 
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('contact_name')
                    ->label('Contact Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('contact_phone')
                    ->label('Contact Phone')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('contact_email')
                    ->label('Contact Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('notes')
                    ->label('Notes')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()
                    ->url(fn ($record) => route('filament.admin.resources.companies.edit', $record->getKey()))
                    ->icon('heroicon-m-pencil'),

                DeleteAction::make()
                    ->icon('heroicon-m-trash'),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('➕ Add New Company')
                    ->url(fn () => route('filament.admin.resources.companies.create'))
                    ->button(),
            ])->defaultPaginationPageOption(5) //  
            ->paginated(true);
            
    }
}
