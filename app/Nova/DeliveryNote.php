<?php

namespace App\Nova;

use App\Nova\Actions\Post\DeliveryNote\ProcessDeliveryNote;
use App\Nova\Lenses\Post\DeliveryNote\ProcessedDeliveryNotes;
use App\Nova\Lenses\Post\DeliveryNote\UnprocessedDeliveryNotes;
use App\Nova\Parts\Post\SharedFields\AdditionalDetails;
use App\Nova\Parts\Post\SharedFields\DeliveryDetails;
use App\Nova\Parts\Post\SharedFields\FinancialDetails;
use App\Nova\Parts\Post\SharedFields\OrderHeader;
use App\Traits\ResourcePolicies;
use Illuminate\Http\Request;
use IslandServices\PendingOrderInfo\PendingOrderInfo;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Query\Search\SearchableRelation;
use Laravel\Nova\Tabs\Tab;

class DeliveryNote extends Resource
{
    use ResourcePolicies;

    public static string $policyKey = 'delivery_note';
    /**
     * The model the resource corresponds to.
     * @var class-string<\App\Models\Post\DeliveryNote>
     */

    public static $model = \App\Models\Post\DeliveryNote::class;
    /**
     * The single value that should be used to represent the resource when being displayed.
     * @var string
     */
    public static $title = 'delivery_note_number';
    /**
     * The columns that should be searched.
     * @var array
     */
    public static $search = [
        'delivery_note_number',
    ];

    public static function searchableColumns(): array
    {
        return [
            'delivery_note_number',
            new SearchableRelation('customer', 'client'),
            new SearchableRelation('customer', 'account_number'),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [

            ... (new OrderHeader())('delivery_note', \App\Models\Post\DeliveryNote::class),

            Tab::group('Information', [
                Tab::make("Delivery Details", (new DeliveryDetails)("delivery_note")),
                Tab::make("Financial Details", new FinancialDetails()),
                Tab::make("Additional Details", (new AdditionalDetails)("delivery_note"))

            ]),

            HasMany::make('Products', 'deliveryNoteProducts', DeliveryNoteProduct::class),

        ];
    }

    /**
     * Get the cards available for the resource.
     * @return array<int, Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [
            Metrics\DeliveryNote\TotalDeliveryNotes::make()->defaultRange('TODAY')->refreshWhenActionsRun(),
            Metrics\DeliveryNote\NewDeliveryNotes::make()->defaultRange('TODAY')->refreshWhenActionsRun(),
            Metrics\DeliveryNote\ProcessedDeliveryNotes::make()->defaultRange('TODAY')->refreshWhenActionsRun(),
            PendingOrderInfo::make(),
        ];
    }

    /**
     * Get the filters available for the resource.
     * @return array<int, Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     * @return array<int, Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [
            new ProcessedDeliveryNotes(),
            new UnprocessedDeliveryNotes()
        ];
    }

    /**
     * Get the actions available for the resource.
     * @return array<int, Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [
            new ProcessDeliveryNote(),
        ];
    }

    //Dont include clients with account closed
    public static function relatableCustomers(NovaRequest $request, $query)
    {
        return $query->where('account_closed', false);
    }

    public function authorizedToUpdate(Request $request): bool
    {
        if($request->user()->cannot('update ' . self::$policyKey)) {
            return false;
        }
        return !self::model()->status;
    }

    public function authorizedToDelete(Request $request): bool
    {
        if($request->user()->cannot('delete ' . self::$policyKey)) {
            return false;
        }
        return !self::model()->status;
    }

    public function authorizedToReplicate(Request $request): bool
    {
        return !self::model()->status;
    }
}
