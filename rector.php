<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Rector\ArrayDimFetch\SessionVariableToSessionFacadeRector;
use RectorLaravel\Rector\Assign\CallOnAppArrayAccessToStandaloneAssignRector;
use RectorLaravel\Rector\BooleanNot\AvoidNegatedCollectionContainsOrDoesntContainRector;
use RectorLaravel\Rector\Cast\DatabaseExpressionCastsToMethodCallRector;
use RectorLaravel\Rector\Class_\AddExtendsAnnotationToModelFactoriesRector;
use RectorLaravel\Rector\Class_\LivewireComponentComputedMethodToComputedAttributeRector;
use RectorLaravel\Rector\Class_\LivewireComponentQueryStringToUrlAttributeRector;
use RectorLaravel\Rector\Class_\ModelCastsPropertyToCastsMethodRector;
use RectorLaravel\Rector\Class_\PropertyDeferToDeferrableProviderToRector;
use RectorLaravel\Rector\Class_\RemoveModelPropertyFromFactoriesRector;
use RectorLaravel\Rector\Class_\ReplaceExpectsMethodsInTestsRector;
use RectorLaravel\Rector\Class_\UnifyModelDatesWithCastsRector;
use RectorLaravel\Rector\ClassMethod\AddGenericReturnTypeToRelationsRector;
use RectorLaravel\Rector\Coalesce\ApplyDefaultInsteadOfNullCoalesceRector;
use RectorLaravel\Rector\Empty_\EmptyToBlankAndFilledFuncRector;
use RectorLaravel\Rector\Expr\AppEnvironmentComparisonToParameterRector;
use RectorLaravel\Rector\Expr\SubStrToStartsWithOrEndsWithStaticMethodCallRector\SubStrToStartsWithOrEndsWithStaticMethodCallRector;
use RectorLaravel\Rector\FuncCall\FactoryFuncCallToStaticCallRector;
use RectorLaravel\Rector\FuncCall\NotFilledBlankFuncCallToBlankFilledFuncCallRector;
use RectorLaravel\Rector\FuncCall\NowFuncWithStartOfDayMethodCallToTodayFuncRector;
use RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector;
use RectorLaravel\Rector\FuncCall\RemoveRedundantValueCallsRector;
use RectorLaravel\Rector\FuncCall\RemoveRedundantWithCallsRector;
use RectorLaravel\Rector\FuncCall\SleepFuncToSleepStaticCallRector;
use RectorLaravel\Rector\FuncCall\ThrowIfAndThrowUnlessExceptionsToUseClassStringRector;
use RectorLaravel\Rector\FuncCall\TypeHintTappableCallRector;
use RectorLaravel\Rector\MethodCall\AssertSeeToAssertSeeHtmlRector;
use RectorLaravel\Rector\MethodCall\AssertStatusToAssertMethodRector;
use RectorLaravel\Rector\MethodCall\AvoidNegatedCollectionFilterOrRejectRector;
use RectorLaravel\Rector\MethodCall\DatabaseExpressionToStringToMethodCallRector;
use RectorLaravel\Rector\MethodCall\EloquentOrderByToLatestOrOldestRector;
use RectorLaravel\Rector\MethodCall\EloquentWhereRelationTypeHintingParameterRector;
use RectorLaravel\Rector\MethodCall\EloquentWhereTypeHintClosureParameterRector;
use RectorLaravel\Rector\MethodCall\FactoryApplyingStatesRector;
use RectorLaravel\Rector\MethodCall\RedirectBackToBackHelperRector;
use RectorLaravel\Rector\MethodCall\RedirectRouteToToRouteHelperRector;
use RectorLaravel\Rector\MethodCall\ReplaceWithoutJobsEventsAndNotificationsWithFacadeFakeRector;
use RectorLaravel\Rector\MethodCall\ResponseHelperCallToJsonResponseRector;
use RectorLaravel\Rector\MethodCall\ReverseConditionableMethodCallRector;
use RectorLaravel\Rector\MethodCall\UnaliasCollectionMethodsRector;
use RectorLaravel\Rector\MethodCall\ValidationRuleArrayStringValueToArrayRector;
use RectorLaravel\Rector\MethodCall\WhereToWhereLikeRector;
use RectorLaravel\Rector\PropertyFetch\OptionalToNullsafeOperatorRector;
use RectorLaravel\Rector\PropertyFetch\ReplaceFakerInstanceWithHelperRector;
use RectorLaravel\Rector\StaticCall\CarbonSetTestNowToTravelToRector;
use RectorLaravel\Rector\StaticCall\DispatchToHelperFunctionsRector;
use RectorLaravel\Rector\StaticCall\EloquentMagicMethodToQueryBuilderRector;
use RectorLaravel\Rector\StaticCall\Redirect301ToPermanentRedirectRector;
use RectorLaravel\Rector\StaticCall\ReplaceAssertTimesSendWithAssertSentTimesRector;
use RectorLaravel\Rector\StaticCall\RequestStaticValidateToInjectRector;
use RectorLaravel\Rector\StaticCall\RouteActionCallableRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/config',
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        privatization: true,
        naming: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
    )
    ->withTypeCoverageLevel(100)
    ->withSets([
        RectorLaravel\Set\LaravelLevelSetList::UP_TO_LARAVEL_110,
        RectorLaravel\Set\LaravelSetList::LARAVEL_CODE_QUALITY,
        RectorLaravel\Set\LaravelSetList::LARAVEL_IF_HELPERS,
        RectorLaravel\Set\LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        RectorLaravel\Set\LaravelSetList::LARAVEL_COLLECTION,
        RectorLaravel\Set\LaravelSetList::LARAVEL_ARRAY_STR_FUNCTION_TO_STATIC_CALL,
        RectorLaravel\Set\LaravelSetList::LARAVEL_ARRAYACCESS_TO_METHOD_CALL,
        RectorLaravel\Set\LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
    ])
    ->withRules(rules: [
        AddExtendsAnnotationToModelFactoriesRector::class,
        AddGenericReturnTypeToRelationsRector::class,
        AppEnvironmentComparisonToParameterRector::class,
        ApplyDefaultInsteadOfNullCoalesceRector::class,
        AssertSeeToAssertSeeHtmlRector::class,
        AssertStatusToAssertMethodRector::class,
        AvoidNegatedCollectionFilterOrRejectRector::class,
        AvoidNegatedCollectionContainsOrDoesntContainRector::class,
        CallOnAppArrayAccessToStandaloneAssignRector::class,
        CarbonSetTestNowToTravelToRector::class,
        DatabaseExpressionCastsToMethodCallRector::class,
        DatabaseExpressionToStringToMethodCallRector::class,
        DispatchToHelperFunctionsRector::class,
        EloquentMagicMethodToQueryBuilderRector::class,
        EloquentOrderByToLatestOrOldestRector::class,
        EloquentWhereRelationTypeHintingParameterRector::class,
        EloquentWhereTypeHintClosureParameterRector::class,
        EmptyToBlankAndFilledFuncRector::class,
        FactoryApplyingStatesRector::class,
        FactoryFuncCallToStaticCallRector::class,
        LivewireComponentComputedMethodToComputedAttributeRector::class,
        LivewireComponentQueryStringToUrlAttributeRector::class,
        ModelCastsPropertyToCastsMethodRector::class,
        NotFilledBlankFuncCallToBlankFilledFuncCallRector::class,
        NowFuncWithStartOfDayMethodCallToTodayFuncRector::class,
        OptionalToNullsafeOperatorRector::class,
        PropertyDeferToDeferrableProviderToRector::class,
        Redirect301ToPermanentRedirectRector::class,
        RedirectBackToBackHelperRector::class,
        RedirectRouteToToRouteHelperRector::class,
        RemoveDumpDataDeadCodeRector::class,
        RemoveModelPropertyFromFactoriesRector::class,
        RemoveRedundantValueCallsRector::class,
        RemoveRedundantWithCallsRector::class,
        ReplaceAssertTimesSendWithAssertSentTimesRector::class,
        ReplaceExpectsMethodsInTestsRector::class,
        ReplaceFakerInstanceWithHelperRector::class,
        ReplaceWithoutJobsEventsAndNotificationsWithFacadeFakeRector::class,
        RequestStaticValidateToInjectRector::class,
        ResponseHelperCallToJsonResponseRector::class,
        ReverseConditionableMethodCallRector::class,
        RouteActionCallableRector::class,
        SessionVariableToSessionFacadeRector::class,
        SleepFuncToSleepStaticCallRector::class,
        SubStrToStartsWithOrEndsWithStaticMethodCallRector::class,
        ThrowIfAndThrowUnlessExceptionsToUseClassStringRector::class,
        TypeHintTappableCallRector::class,
        UnaliasCollectionMethodsRector::class,
        UnifyModelDatesWithCastsRector::class,
        ValidationRuleArrayStringValueToArrayRector::class,
        WhereToWhereLikeRector::class,
    ]);
