<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Filament\Resources\BlogPostResource\RelationManagers;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\URL;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Publication date')
                    ->required()
                    ->default(now())
                    ->minDate(now()),
                Forms\Components\RichEditor::make('description')
                    ->label('Content')
                    ->required()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            Tables\Columns\TextColumn::make('title')
                ->searchable(),
            Tables\Columns\TextColumn::make('published_at')
                ->dateTime()
                ->sortable()
                ->label('Publication date'),
            Tables\Columns\BooleanColumn::make('is_published')
                ->label('Published'),
        ];

        $filters = [
            Tables\Filters\Filter::make('Not published')
                ->query(fn (Builder $query) => $query->where('published_at', '>', now())),
            Tables\Filters\Filter::make('Published')
                ->query(fn (Builder $query) => $query->where('published_at', '<=', now())),
        ];

        if (auth()->user()->is_admin) {
            $columns = [
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable(),
                ...$columns,
            ];

            $filters[] = Tables\Filters\Filter::make('From Api')
                ->query(fn (Builder $query) => $query->fromApi());
        }

        return $table
            ->columns($columns)
            ->pushActions([
                Tables\Actions\LinkAction::make('Read')
                    ->url(fn ($record) => $record->is_published ? route('blog-post', ['id' => $record]) : URL::signedRoute('blog-post', ['id' => $record])),
            ])
            ->filters($filters);
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->is_admin) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()->where('owner_id', auth()->id());
    }
}
