<?php
namespace Rephole\ViewControl\Vendor\Illuminate\View\Engines;

use Illuminate\View\Engines\EngineInterface;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use InvalidArgumentException;
use Rephole\ViewControl\Vendor\Wa72\HtmlPageDom\HtmlPageCrawler;

class ControlEngine extends PhpEngine
{

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * ControlEngine constructor.
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array   $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        $name = $this->getName($path);

        if (! $this->getFilesystem()->exists($this->getBladePath($path))) {
            throw new InvalidArgumentException("Blade [$name] not found.");
        }

        $view = $this->getBladeEngine()->get($this->getBladePath($path), $data);

        $data['document'] = new HtmlPageCrawler($view);
        $this->evaluatePath($path, $data);

        return $data['document']->saveHTML();
    }

    /**
     * @return Factory
     */
    protected function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return \Illuminate\View\FileViewFinder
     */
    protected function getFinder()
    {
        return $this->getFactory()->getFinder();
    }

    /**
     * @return \Illuminate\Filesystem\Filesystem
     */
    protected function getFilesystem()
    {
        return $this->getFinder()->getFilesystem();
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getName($path)
    {
        return preg_replace('/\.cs\.php$/', '', basename($path));
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getBladePath($path)
    {
        return preg_replace('/.cs.php$/', '.blade.php', $path);
    }

    /**
     * @return EngineInterface
     */
    protected function getBladeEngine()
    {
        return $this->getFactory()->getEngineResolver()->resolve('blade');
    }
    
}
