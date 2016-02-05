<?php

declare(strict_types=1);

namespace mcordingley\Regression\RegressionAlgorithms;

use InvalidArgumentException;
use mcordingley\LinearAlgebra\Matrix;
use mcordingley\Regression\CoefficientSet;
use mcordingley\Regression\DataBag;

final class LinearLeastSquares implements RegressionAlgorithm
{
    public function regress(DataBag $data): CoefficientSet
    {
        $design = new Matrix($data->getIndependents());
        $observed = (new Matrix([$data->getDependents()]))->transpose();

        if ($design->columns >= $design->rows) {
            throw new InvalidArgumentException('Not enough observations to perform regression. You need to have more observations than explanatory variables.');
        }

        $designTranspose = $design->transpose();

        $prediction = $designTranspose
                             ->multiply($design)
                             ->inverse()
                             ->multiply($designTranspose->multiply($observed));

        return new CoefficientSet($prediction->transpose()->toArray()[0]);
    }
}
