<?php

namespace Gao\C5Bundle\Service\Paging;

use Gao\C5Bundle\Service\Paging\Pagination;

/**
 * PaginateExtension class.
 *
 * Class for use paging as Twig extension.
 */
class PaginateExtension extends \Twig_Extension
{

    /**
     * Twig Environment.
     */
    private $twigEnvironment;

    /**
     * Default Template.
     */
    private $defaultTemplate;

    /**
     * Constructor.
     *
     * @param mixed $defaultTemplate Default Template.
     */
    public function __construct($defaultTemplate)
    {
        $this->defaultTemplate = $defaultTemplate;
    }

    /**
     * Get Twig extension name.
     *
     * @return string Twig extension name.
     */
    public function getName()
    {
        return 'my_custom_paginate_extension';
    }

    /**
     * Get Twig Functions.
     *
     * @return Twig_SimpleFunction Twig Functions.
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('paginate', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Set Twig Environment.
     * @param \Twig_Environment $environment Twig Environment.
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twigEnvironment = $environment;
    }

    /**
     * Render the pagination.
     *
     * @param Pagination $pagination The Pagination object.
     * @param string $fragment The fragment.
     * @param type $template Twig template.
     * @return mixed Twig response.
     */
    public function render(Pagination $pagination, $sort, $fragment = null, $template = null)
    {
        if ($template == null) {
            $template = $this->defaultTemplate;
        }

        return $this->twigEnvironment->render($template, array(
                    'pagination' => $pagination,
                    'sort' => $sort,
                    'fragment' => $fragment
        ));
    }
}
