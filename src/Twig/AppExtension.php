<?php
/**
 * Created by PhpStorm.
 * User: yanni
 * Date: 8/1/2018
 * Time: 9:33 PM
 */

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price', ['priceFilter'])
        ];
    }

    public function priceFilter($value){
        return '$'.number_format($value,2);
    }

    public function getGlobals(){
        return [
            'locale' => $this->locale
        ];
    }
}