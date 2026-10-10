<?php

namespace App\Filament\Resources\Motors\Pages;

use App\Filament\Resources\Motors\Widgets\MotorStats;
use App\Filament\Resources\Motors\MotorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMotors extends ListRecords
{
    protected static string $resource = MotorResource::class;

     protected function getHeaderWidgets(): array
    {
        return [
            MotorStats::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
