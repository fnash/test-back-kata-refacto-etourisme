<?php

namespace Evaneos\Template\Renderer;

class RendererFactory
{
    const FORMATS = [
        RendererFactory::FORMAT_HTML,
        RendererFactory::FORMAT_TEXT,
    ];

    // Formats list
    const FORMAT_HTML = 'html';
    const FORMAT_TEXT = 'text';

    private static $cachedInstances = [];

    /**
     * @param string $entityName
     * @param string $format
     *
     * @return RendererInterface
     */
    public static function get($entityFqcn, $format)
    {
        $fqcn = sprintf('%s\\%s%sRenderer', __NAMESPACE__, (new \ReflectionClass($entityFqcn))->getShortName(), ucfirst(strtolower($format)));

        if (array_key_exists($fqcn, static::$cachedInstances)) {
            return static::$cachedInstances[$fqcn];
        }

        if (!class_exists($fqcn)) {
            throw new RendererNotFoundException(sprintf('%s is missing. May be you should implement it?', $fqcn));
        }

        if (! (new \ReflectionClass($fqcn))->implementsInterface(RendererInterface::class)){
            throw new \LogicException(sprintf('%s must implement %s', $fqcn, RendererInterface::class));
        }

        static::$cachedInstances[$fqcn] = new $fqcn();

        return static::$cachedInstances[$fqcn];
    }
}
