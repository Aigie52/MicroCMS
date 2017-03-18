<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 18/03/2017
 * Time: 13:15
 */

namespace MicroCMS\Tests;


use Silex\Application;
use Silex\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AppTest extends WebTestCase
{
    /**
     * Basic, application-wide functional test inspired by Symfony best practices.
     * Simply checks that all application URLs load successfully.
     * During test execution, this method is called for each URL returned by the provideUrls method.
     *
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessfull($url)
    {
        $client = $this->createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * Creates the application.
     *
     * @return HttpKernelInterface
     */
    public function createApplication()
    {
        $app = new Application();

        require __DIR__.'/../../app/config/dev.php';
        require __DIR__.'/../../app/app.php';
        require __DIR__.'/../../app/routes.php';

        // Generate raw exceptions instead of HTML pages if errors occur
        unset($app['exception_handler']);
        // Simulate sessins for testing
        $app['session.test'] = true;
        // Enable anonymous access to admin zone
        $app['security.access_rules'] = array();

        return $app;
    }

    /**
     * Provides all valid application URLs
     * @return array
     */
    public function provideUrls()
    {
        return array(
            array('/'),
            array('/article/1'),
            array('/login'),
            array('/admin'),
            array('/admin/article/add'),
            array('/admin/article/1/edit'),
            array('/admin/comment/1/edit'),
            array('/admin/user/add'),
            array('/admin/user/1/edit'),
            array('/api/articles'),
            array('/api/article/1'),
        );
    }
}
