<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Str;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Cadastrar Unidade';
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

    protected function handleRegistration(array $data): Team
    {
        $team = Team::create($data);
        $team->users()->attach(auth()->user());
        return $team;
    }
}
