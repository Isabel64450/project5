<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\CartExtensionRuntime;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension implements GlobalsInterface
{

 public function __construct(private RequestStack $requestStack)
    {
    }

     public function getGlobals(): array
    {
        $session = $this->requestStack->getSession();

        if (!$session) {
            return ['cartCount' => 0];
        }

        $cart = $session->get('cart', []);
        $count = array_sum($cart);

        return [
            'cartCount' => $count,
        ];
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [CartExtensionRuntime::class, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [CartExtensionRuntime::class, 'doSomething']),
        ];
    }
}
