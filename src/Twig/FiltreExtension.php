<?php

namespace App\Twig;


use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;


class FiltreExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('deviseFr', [$this, 'deviseFr']),
            new TwigFilter('civilite', [$this, 'civilite']),
            new TwigFilter('collection', [$this, 'collection']),
        ];
    }

    public function deviseFr(float $prix): string
    {
        return number_format($prix, 2, ",", " ") . " €";
    }

    public function civilite(string $civilite): string
    {
        if ($civilite === 'F') {
            return '<i class="bi bi-gender-female text-danger fs-5"></i>';
        }
        return '<i class="bi bi-gender-male text-primary fs-5"></i>';
    }

    public function collection(string $collection): string
    {
        if ($collection === 'F') {
            return 'Femme';
        }
        return 'Homme';
    }
}
