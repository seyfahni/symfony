<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Niklas Seyfarth <niklas@seyfarth.de>
 */
class ArrayToJsonTransformer implements DataTransformerInterface
{
    /**
     * @var int
     */
    private $json_encode_options;

    /**
     * @var int
     */
    private $json_decode_options;

    /**
     * @var int
     */
    private $depth;

    /**
     * @param int $json_encode_options options to pass to json_encode
     * @param int $json_decode_options options to pass to json_decode
     * @param int $depth               depth argument to pass to json_encode and json_decode
     */
    public function __construct($json_encode_options = 0, $json_decode_options = 0, $depth = 512)
    {
        $this->json_encode_options = $json_encode_options;
        $this->json_decode_options = $json_decode_options;
        $this->depth = $depth;
    }

    public function transform($data)
    {
        $result = json_encode($data, $this->json_encode_options, $this->depth);
        $jsonError = json_last_error();
        if (\JSON_ERROR_NONE !== $jsonError) {
            throw new TransformationFailedException('Failed JSON conversion.', $jsonError);
        }

        return $result;
    }

    public function reverseTransform($json)
    {
        $result = json_decode($json, true, $this->depth, $this->json_decode_options);
        $jsonError = json_last_error();
        if (\JSON_ERROR_NONE !== $jsonError) {
            throw new TransformationFailedException('String is invalid JSON.', $jsonError);
        }

        return $result;
    }
}
