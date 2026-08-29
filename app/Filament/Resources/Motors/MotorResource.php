<?php

namespace App\Filament\Resources\Motors;

use App\Filament\Resources\Motors\Pages\CreateMotor;
use App\Filament\Resources\Motors\Pages\EditMotor;
use App\Filament\Resources\Motors\Pages\ListMotors;
use App\Filament\Resources\Motors\Schemas\MotorForm;
use App\Filament\Resources\Motors\Tables\MotorsTable;
use App\Models\Motor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Text;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;



class MotorResource extends Resource
{
    protected static ?string $model = Motor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
         return $schema
            ->components([

                        TextInput::make('nomor_polisi')
                            ->label('No. Polisi')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20),

                        TextInput::make('motor')
                            ->label('Merk')
                            ->required()
                            ->maxLength(100),

                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'Tersedia' => 'Tersedia',
                                'Disewa'   => 'Disewa',
                            ])
                            ->default('Tersedia')
                            ->native(false),

                        FileUpload::make('foto')
                            ->label('Foto Motor')
                            ->image()
                            ->disk('public')
                            ->directory('motors')
                            ->visibility('public')
                            ->imageEditor()
                            ->imagePreviewHeight('150')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular(),

                TextColumn::make('nomor_polisi')
                    ->label('No. Polisi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('motor')
                    ->label('Motor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tersedia' => 'success',
                        'Disewa'   => 'warning',
                        'Dibooking'   => 'warning',
                        'Servis'   => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Tersedia' => 'Tersedia',
                        'Disewa'   => 'Disewa',
                        'Servis'   => 'Servis',
                    ]),

                Filter::make('Tersedia')
                    ->label('Hanya Tersedia')
                    ->query(fn (Builder $query): Builder => $query->where('Status', 'Tersedia')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMotors::route('/'),
            'create' => CreateMotor::route('/create'),
            'edit' => EditMotor::route('/{record}/edit'),
        ];
    }
}
