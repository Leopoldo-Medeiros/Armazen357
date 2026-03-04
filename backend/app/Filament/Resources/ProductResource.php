<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Produtos';
    protected static ?string $modelLabel = 'Produto';
    protected static ?string $pluralModelLabel = 'Produtos';
    protected static ?string $navigationGroup = 'Catálogo';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Informações Gerais')
                ->columns(2)
                ->schema([
                    Select::make('category_id')
                        ->label('Categoria')
                        ->options(Category::query()->where('is_active', true)->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    TextInput::make('sku')
                        ->label('SKU')
                        ->unique(ignoreRecord: true)
                        ->maxLength(50)
                        ->placeholder('Gerado automaticamente'),

                    TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) =>
                            $set('slug', Str::slug($state ?? ''))
                        ),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Descrição')
                        ->columnSpanFull()
                        ->rows(4),
                ]),

            Section::make('Características do Café')
                ->columns(3)
                ->schema([
                    TextInput::make('origin')
                        ->label('Origem')
                        ->maxLength(255)
                        ->placeholder('Ex: Minas Gerais, Colômbia'),

                    Select::make('roast_level')
                        ->label('Torra')
                        ->options(Product::roastLevelLabels())
                        ->required()
                        ->default('medium'),

                    Select::make('grind_type')
                        ->label('Moagem')
                        ->options(Product::grindTypeLabels())
                        ->required()
                        ->default('whole_bean'),
                ]),

            Section::make('Preços')
                ->columns(3)
                ->schema([
                    TextInput::make('price_b2c')
                        ->label('Preço Varejo (B2C)')
                        ->numeric()
                        ->required()
                        ->prefix('R$')
                        ->minValue(0),

                    TextInput::make('price_b2b')
                        ->label('Preço Atacado (B2B)')
                        ->numeric()
                        ->required()
                        ->prefix('R$')
                        ->minValue(0),

                    TextInput::make('min_wholesale_qty')
                        ->label('Qtd. mínima atacado')
                        ->numeric()
                        ->required()
                        ->default(10)
                        ->minValue(1)
                        ->suffix('un.'),
                ]),

            Section::make('Estoque')
                ->columns(2)
                ->schema([
                    TextInput::make('stock_qty')
                        ->label('Estoque atual')
                        ->numeric()
                        ->required()
                        ->default(0)
                        ->minValue(0)
                        ->suffix('un.'),

                    Toggle::make('is_active')
                        ->label('Produto ativo')
                        ->default(true),
                ]),

            Section::make('Imagens')
                ->schema([
                    FileUpload::make('images')
                        ->label('Fotos do produto')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->directory('products')
                        ->maxFiles(8),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('')
                    ->getStateUsing(fn (Product $record) => $record->images[0] ?? null)
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=P&background=b45309&color=fff')
                    ->square(),

                TextColumn::make('name')
                    ->label('Produto')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Product $record) => $record->category->name ?? '—'),

                TextColumn::make('origin')
                    ->label('Origem')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('roast_level')
                    ->label('Torra')
                    ->formatStateUsing(fn (string $state) => Product::roastLevelLabels()[$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'light'  => 'warning',
                        'medium' => 'success',
                        'dark'   => 'danger',
                        default  => 'gray',
                    }),

                TextColumn::make('price_b2c')
                    ->label('Varejo')
                    ->money('BRL')
                    ->sortable(),

                TextColumn::make('price_b2b')
                    ->label('Atacado')
                    ->money('BRL')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('stock_qty')
                    ->label('Estoque')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state) => $state === 0 ? 'danger' : ($state < 10 ? 'warning' : 'success')),

                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name'),

                SelectFilter::make('roast_level')
                    ->label('Torra')
                    ->options(Product::roastLevelLabels()),

                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Ativos')
                    ->falseLabel('Inativos'),
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
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
