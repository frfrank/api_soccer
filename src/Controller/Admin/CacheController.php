<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class CacheController extends AbstractController
{
    /**
     * @Route("/admin/cache/clear", name="admin_cache_clear")
     * @param KernelInterface $kernel
     * @return RedirectResponse
     * @throws \Exception
     */
    public function clearCache (KernelInterface $kernel)
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'cache:clear',
            '--env' => 'prod', // not working, just clear the current env
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $this->addFlash('success', 'Cache cleared.');

        return $this->redirect('/admin');
    }


    /**
     * @Route("/admin/cache/remove/{env}", name="admin_cache_remove")
     * @param string $env
     * @param KernelInterface $kernel
     * @param ParameterBagInterface $params
     * @return RedirectResponse
     */
    public function removeCache ($env = 'dev', KernelInterface $kernel, ParameterBagInterface $params)
    {
        $cacheDir = dirname($kernel->getCacheDir()) . DIRECTORY_SEPARATOR . $env;

        if(file_exists($cacheDir) and is_dir($cacheDir))
        {
            $fs = new Filesystem();
            $fs->remove($cacheDir);

            $this->addFlash('success', 'Cache removed.');
        }

        return $this->redirect('/admin');
    }


}
