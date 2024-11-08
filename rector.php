<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;
use RectorLaravel\Rector\Class_\AnonymousMigrationsRector;
use Rector\Arguments\Rector\ClassMethod\ArgumentAdderRector;
use RectorLaravel\Rector\Namespace_\FactoryDefinitionRector;
use Rector\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector;
use RectorLaravel\Rector\Class_\UnifyModelDatesWithCastsRector;
use RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector;
use RectorLaravel\Rector\MethodCall\RedirectBackToBackHelperRector;
use Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector;
use RectorLaravel\Rector\FuncCall\FactoryFuncCallToStaticCallRector;
use RectorLaravel\Rector\FuncCall\HelperFuncCallToFacadeClassRector;
use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use RectorLaravel\Rector\FuncCall\ArgumentFuncCallToMethodCallRector;
use RectorLaravel\Rector\PropertyFetch\OptionalToNullsafeOperatorRector;
use RectorLaravel\Rector\StaticCall\RequestStaticValidateToInjectRector;
use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;
use Rector\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector;
use Rector\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return static function (RectorConfig $rectorConfig): void {
    // Configs
    $rectorConfig->sets([
        LaravelSetList::LARAVEL_110,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_STATIC_TO_INJECTION,
        LevelSetList::UP_TO_PHP_83,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
    ]);
    $rectorConfig->phpVersion(PhpVersion::PHP_83);
    $rectorConfig->parallel(600, 4);
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);
    $rectorConfig->cacheDirectory(__DIR__.'/.rector');
    $rectorConfig->cacheClass(FileCacheStorage::class);

    // Rules
    $rectorConfig->rule(AnonymousMigrationsRector::class);
    $rectorConfig->rule(FactoryDefinitionRector::class);
    $rectorConfig->rule(FactoryFuncCallToStaticCallRector::class);
    $rectorConfig->rule(RedirectBackToBackHelperRector::class);
    $rectorConfig->rule(UnifyModelDatesWithCastsRector::class);
    $rectorConfig->rule(RemoveAlwaysElseRector::class);
    $rectorConfig->rule(RemoveDeadReturnRector::class);
    $rectorConfig->rule(SimplifyUselessVariableRector::class);
    $rectorConfig->rule(RemoveDumpDataDeadCodeRector::class);
    $rectorConfig->rule(AddVoidReturnTypeWhereNoReturnRector::class);

    $rectorConfig->ruleWithConfiguration(OptionalToNullsafeOperatorRector::class, [
        OptionalToNullsafeOperatorRector::EXCLUDE_METHODS => ['present'],
    ]);

    $rectorConfig->skip([
        ArgumentAdderRector::class,
        ArgumentFuncCallToMethodCallRector::class,
        CompleteDynamicPropertiesRector::class,
        HelperFuncCallToFacadeClassRector::class,
        IssetOnPropertyObjectToPropertyExistsRector::class,
        StaticCallToMethodCallRector::class,
        NullToStrictStringFuncCallArgRector::class,
        SimplifyEmptyCheckOnEmptyArrayRector::class,
        AddOverrideAttributeToOverriddenMethodsRector::class,
        AddTypeToConstRector::class,
        DisallowedEmptyRuleFixerRector::class,

        RequestStaticValidateToInjectRector::class => [__DIR__.'/app/Logging/Loki/Formatter.php'],
        AddParamBasedOnParentClassMethodRector::class => [__DIR__.'/app/Logging/Loki/Formatter.php'],
    ]);

    $rectorConfig->paths([
        __DIR__.'/app/**',
        __DIR__.'/config/**',
        __DIR__.'/tests/**',
        __DIR__.'/database/factories/**',
        __DIR__.'/database/seeders/**',
    ]);
};
