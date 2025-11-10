<?php

function changeDateFormate($date, $date_format)
{
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);
}

function limits($factor = 1)
{
    $limits = [10 * $factor, 25 * $factor, 50 * $factor, 100 * $factor];

    $limitUrls = [];

    foreach ($limits as $limit) {
        $item = [];
        $item['url'] = add_query_params(['limit' => $limit]);
        $item['label'] = $limit;
        $item['active'] = currentLimit() == $limit;
        $limitUrls[] = $item;
    }
    return $limitUrls;
}

function default_limit($factor = 1)
{
    return 10 * $factor;
}

function currentLimit($factor = 1)
{
    if ($limit = request()->get('limit'))
        return  $limit;
    else
        return default_limit($factor);
}

// function getCriterias()
// {
//   $criterias = [];
//   if (!request()->get('search'))
//     remove_query_params(['search']);
//   else
//     $criterias[] = 'Search: ' . request()->get('search');
//   if ($page = request()->get('page'))
//     $criterias[] = 'Page: ' . $page;
//   if ($limit = request()->get('limit'))
//     $criterias[] = 'Per Page: ' . $limit;
//   else
//     $limit = 10;

//   return $criterias;
// }

function remove_query_params(array $params = [])
{
    $url = url()->current(); // get the base URL - everything to the left of the "?"
    $query = request()->query(); // get the query parameters (what follows the "?")

    foreach ($params as $param) {
        unset($query[$param]); // loop through the array of parameters we wish to remove and unset the parameter from the query array
    }

    return $query ? $url . '?' . http_build_query($query) : $url; // rebuild the URL with the remaining parameters, don't append the "?" if there aren't any query parameters left
}

function add_query_params(array $params = [])
{
    $query = array_merge(
        request()->query(),
        $params
    ); // merge the existing query parameters with the ones we want to add

    return url()->current() . '?' . http_build_query($query); // rebuild the URL with the new parameters array
}

/**
 * Create singleton object through single facade.
 *
 * @param  string  $className
 * @return object
 */
function getSingletonInstance($className)
{
    static $instance = [];

    if (array_key_exists($className, $instance)) {
        return $instance[$className];
    }

    return $instance[$className] = app($className);
}

/**
 * Format and convert price with currency symbol.
 *
 * @param  float  $price
 * @return string
 */
function currency($amount = 0, $targetCurrencyCode = 'USD',  $exchangeRate = 1.0)
{
    if (is_null($amount)) {
        $amount = 0;
    }

    return formatPrice(convertPrice($amount, $targetCurrencyCode, $exchangeRate), $targetCurrencyCode);
}

function getFeesRate()
{
    return getFeesPercent() / 100;
}
function getFeesPercent()
{
    return 0;
}

/**
 * Format and convert price with currency symbol.
 *
 * @param  float  $price
 * @param  string (optional)  $currencyCode
 * @return string
 */
function formatPrice($price, $currencyCode = 'USD')
{
    if (is_null($price)) {
        $price = 0;
    }

    $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

    return $formatter->formatCurrency($price, $currencyCode);
}

/**
 * Converts price.
 *
 * @param  float  $amount
 * @param  string  $targetCurrencyCode
 * @return string
 */
function convertPrice($amount, $targetCurrencyCode = null, $exchangeRate = 1.0)
{

    if (!$targetCurrencyCode) {
        return $amount;
    }


    if (
        '' === $exchangeRate
        || null === $exchangeRate
    ) {
        return $amount;
    }

    $result = (float) $amount * (float) $exchangeRate;

    return $result;
}

/**
 * Returns base channel's currency model.
 *
 * @return \Webkul\Core\Contracts\Currency
 */
function getBaseCurrency()
{
    static $currency;

    if ($currency) {
        return $currency;
    }

    $baseCurrency = 'USD';

    return $currency = $baseCurrency;
}

if (!function_exists('cart')) {
    /**
     * Cart helper.
     *
     * @return \App\Helpers\Cart
     */
    function cart()
    {
        return app()->make('cart');
    }
}
