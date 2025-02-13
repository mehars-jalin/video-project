<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\Filter;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;


class ProjectList extends BaseWidget
{
    protected static ?int $sort = 2;

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
        ->query(Project::query()->take(5)) 
        ->columns([
            TextColumn::make('company.name')
                ->label('Company')
                ->sortable()
                ->searchable(),
    
            TextColumn::make('project_name')
                ->sortable()
                ->searchable(),
    
            TextColumn::make('project_type')
                ->label('Project Type')
                ->sortable()
                ->badge(),
    
            TextColumn::make('created_date')
                ->label('Created Date')
                ->date()
                ->sortable(),
    
            TextColumn::make('videos_allotted')
                ->label('Videos Allotted')
                ->sortable(),
    
            TextColumn::make('videos_completed')
                ->label('Videos Completed')
                ->sortable(),
    
            TextColumn::make('status')
                ->sortable()
                ->badge()
                ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),
    
            TextColumn::make('due_date')
                ->label('Due Date')
                ->date()
                ->sortable()
                ->visible(fn ($record) => $record?->project_type === 'one time'),
    
            TextColumn::make('months_between_shoots')
                ->label('Months Between Shoots')
                ->sortable()
                ->visible(fn ($record) => $record?->project_type === 'recurring'),
    
            TextColumn::make('last_shoot_date')
                ->label('Last Shoot Date')
                ->date()
                ->sortable()
                ->visible(fn ($record) => $record?->project_type === 'recurring'),
    
            

            TextColumn::make('next_shoot_date')
                ->label('Next Shoot Date')
                
                ->formatStateUsing(function ($record) {
                    if (!$record || $record->project_type !== 'recurring' || !$record->last_shoot_date || !$record->months_between_shoots) {
                        return 'N/A';
                    }

                    $nextShootDate = Carbon::parse($record->last_shoot_date)
                        ->addMonths($record->months_between_shoots);
                    $today = Carbon::today();

                    $color = 'green'; // Default color
                    $status = '';

                    if ($nextShootDate->lt($today)) {
                        $color = 'red'; // Overdue
                        $status = " (Overdue)";
                    } elseif ($nextShootDate->diffInDays($today) <= 30) {
                        $color = 'orange'; // Upcoming
                        $status = " (Upcoming)";
                    }

                    return "<span style='color: {$color}; font-weight: bold;'>{$nextShootDate->format('Y-m-d')}{$status}</span>";
                })
                ->html() // Enable HTML rendering
                ->sortable(),
                // ->visible(fn ($record) => $record?->project_type === 'one time'),
                 

        // **Fix: Add an action column with a label**
        Tables\Columns\TextColumn::make('actions')
        ->label('Actions')
        ->formatStateUsing(fn() => ' ')
        ->sortable(false)
        ->html(),
                
        ]
        )
        ->filters([
             
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->url(fn ($record) => route('filament.admin.resources.projects.view', $record))
                    ->icon('heroicon-m-eye') // ✅ Corrected icon
                    ->openUrlInNewTab(),
            
                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->url(fn ($record) => route('filament.admin.resources.projects.edit', $record))
                    ->icon('heroicon-m-pencil'), // ✅ Corrected icon
            
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-m-trash'), // ✅ Corrected delete icon
            ])
            ->headerActions([
                Action::make('create')
                    ->label('➕ Add New Project')
                    ->url(fn () => route('filament.admin.resources.projects.create')) // Redirect to create page
                    ->button(),
            ])
            ->defaultPaginationPageOption(5) // ✅ Show only 5 records per page
        ->paginated(true);
    }
}
