<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\Company;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Carbon\Carbon;




class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
         
        return $form
    ->schema([
        Select::make('company_id')
            ->label('Company')
            ->options(Company::all()->pluck('name', 'id'))
            ->searchable()
            ->required(),

        TextInput::make('project_name')
            ->required()
            ->maxLength(255),

        Select::make('project_type')
            ->label('Project Type')
            ->options([
                'one time' => 'One Time',
                'recurring' => 'Recurring',
            ])
            ->placeholder('Choose one') 
            ->reactive() // Makes it dynamically update fields based on selection
            ->required(),

        DatePicker::make('created_date')
            ->required()
            ->default(now()),

        TextInput::make('videos_allotted')
            ->required()
            ->numeric(),

        TextInput::make('videos_completed')
            ->numeric()
            ->default(0),

        Textarea::make('project_notes')
            ->maxLength(65535),

        Select::make('status')
            ->options([
                'chillin' => 'Chillin',
                'contacted' => 'Contacted to Come in',
                'in_editing' => 'In Editing',
                'awaiting_review' => 'Awaiting Review',
                'posting_content' => 'Posting Content',
            ])
            ->required(),

        // Show only for One-time projects
        DatePicker::make('due_date')
            ->visible(fn ($get) => $get('project_type') === 'one time')
            ->nullable(),

        Repeater::make('video_topics')
            ->schema([
                TextInput::make('topic')->required(),
            ])
            ->columns(1)
            ->createItemButtonLabel('Add Topic')
            ->visible(fn ($get) => $get('project_type') === 'one time'),

        // Show only for Recurring projects
        TextInput::make('months_between_shoots')
            ->numeric()
            ->nullable()
            ->visible(fn ($get) => $get('project_type') === 'recurring'),

        DatePicker::make('last_shoot_date')
            ->nullable()
            ->visible(fn ($get) => $get('project_type') === 'recurring'),

        // Display calculated Next Shoot Date
        DatePicker::make('next_shoot_date')
            ->label('Next Shoot Date')
            ->disabled()
            ->reactive()
            ->default(fn ($get) => 
                ($get('project_type') === 'recurring' && $get('last_shoot_date') && $get('months_between_shoots')) 
                ? \Carbon\Carbon::parse($get('last_shoot_date'))->addMonths($get('months_between_shoots'))->format('Y-m-d') 
                : null
            )
            ->visible(fn ($get) => $get('project_type') === 'recurring'),
    ]);
    }

    public static function table(Table $table): Table
    {
       

        return $table
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
    
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'view' => Pages\SingleRecords::route('/{record}'),
        ];
    }
}
