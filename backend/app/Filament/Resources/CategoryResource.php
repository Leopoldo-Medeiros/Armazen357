<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Categorias';
    protected static ?string $modelLabel = 'Categoria';
    protected static ?string $pluralModelLabel = 'Categorias';
    protected static ?string $navigationGroup = 'Catálogo';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informações')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) =>
                            $set('slug', Str::slug($state ?? ''))
                        ),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Textarea::make('description')
                        ->label('Descrição')
                        ->columnSpanFull()
                        ->rows(3),
                ]),

            Section::make('Imagem e Visibilidade')
                ->columns(2)
                ->schema([
                    FileUpload::make('image')
                        ->label('Imagem')
                        ->image()
                        ->directory('categories')
                        ->columnSpanFull(),

                    Toggle::make('is_active')
                        ->label('Ativa')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=C&background=b45309&color=fff'),

                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('products_count')
                    ->label('Produtos')
                    ->counts('products')
                    ->badge()
                    ->color('warning'),

                IconColumn::make('is_active')
                    ->label('Ativa')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Criada em')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Ativas')
                    ->falseLabel('Inativas'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
