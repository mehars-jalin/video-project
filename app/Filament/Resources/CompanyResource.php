<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
 
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('name')
                ->label('Name')
                ->required(),
            TextInput::make('contact_name')
                ->label('Contact Name')
                ->required(),
            TextInput::make('contact_phone')
                ->label('Contact Phone')
                ->required(),
            TextInput::make('contact_email')
                ->label('Contact Email')
                ->email()
                ->required(),
            Textarea::make('notes')
                ->label('Notes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
            TextColumn::make('action')
            ->label('Action')
            ->searchable()
            ->sortable(),
        ])
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
