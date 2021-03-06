<?php

namespace AppBundle\Base;

use AppBundle\Traits\ServiceTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class BaseTwigExtension extends \Twig_Extension implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ServiceTrait;
}
