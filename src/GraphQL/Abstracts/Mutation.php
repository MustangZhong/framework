<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-18 17:51
 */
namespace Notadd\Foundation\GraphQL\Abstracts;

use Notadd\Foundation\GraphQL\Traits\ShouldValidate;

/**
 * Class Mutation.
 */
abstract class Mutation extends Field
{
    use ShouldValidate;
}
