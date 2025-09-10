<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Support\Str;

class EditTeamProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Editar Unidade';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->label('Prefixo')
                    ->unique()
                    ->disabled()
                    ->dehydrated()
                    ->validationMessages([
                        'unique' => 'Esse registro já existe na base de dados'
                    ]),
            ]);
    }
}
